<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Upload d'images depuis l'editeur HTML du back-office.
 *
 * Les fichiers sont stockes directement dans public/assets/img/uploads/YYYY/MM/
 * — pas de disk 'public' avec symlink, pour que les URLs correspondent au
 * reste du site qui sert ses images depuis /assets/img/*.
 */
final class MediaController extends Controller
{
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    private const MAX_SIZE_KB = 8192; // 8 MB

    public function upload(Request $request): JsonResponse
    {
        if (($deny = $this->guard($request)) !== null) return $deny;

        $file = $this->validateFile($request);
        $saved = $this->moveToUploads($file);
        return response()->json($saved);
    }

    /**
     * Remplace une image existante : upload le nouveau fichier a la place de
     * l'ancienne URL. Si l'ancienne pointe sur un fichier uploade (prefixe
     * /assets/img/uploads/), elle est supprimee du disque. Les fichiers
     * livres avec le site (images originales) sont preserves.
     */
    public function replace(Request $request): JsonResponse
    {
        if (($deny = $this->guard($request)) !== null) return $deny;

        $oldUrl = (string) $request->input('old_url', '');
        $file   = $this->validateFile($request);
        $saved  = $this->moveToUploads($file);

        if ($oldUrl !== '' && $this->isSafeToDelete($oldUrl)) {
            $absolute = public_path(ltrim($oldUrl, '/'));
            if (is_file($absolute)) {
                @unlink($absolute);
            }
        }

        return response()->json($saved + ['old_url' => $oldUrl, 'deleted' => $this->isSafeToDelete($oldUrl)]);
    }

    /** Supprime une image uploadee (fichier + rien d'autre a toucher). */
    public function destroy(Request $request): JsonResponse
    {
        if (($deny = $this->guard($request)) !== null) return $deny;

        $url = (string) $request->input('url', '');
        if ($url === '' || !$this->isSafeToDelete($url)) {
            return response()->json(['error' => 'URL non supprimable'], 422);
        }
        $absolute = public_path(ltrim($url, '/'));
        if (is_file($absolute)) {
            @unlink($absolute);
        }
        return response()->json(['url' => $url, 'deleted' => true]);
    }

    /**
     * On n'autorise la suppression que pour les URLs qui pointent dans
     * /assets/img/uploads/* — jamais pour les images originales du site.
     */
    private function isSafeToDelete(string $url): bool
    {
        return str_starts_with($url, '/assets/img/uploads/')
            && !str_contains($url, '..');
    }

    private function guard(Request $request): ?JsonResponse
    {
        $user = $request->user();
        if (!($user instanceof User) || !$user->isEditor()) {
            return response()->json(['error' => 'Non autorise'], 403);
        }
        return null;
    }

    private function validateFile(Request $request): \Illuminate\Http\UploadedFile
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:' . self::MAX_SIZE_KB],
        ]);
        $file = $data['file'];
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
            abort(response()->json(['error' => 'Format non autorise'], 422));
        }
        return $file;
    }

    /**
     * @return array{url:string, name:string, size:int, alt:string}
     */
    private function moveToUploads(\Illuminate\Http\UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $slug = Str::slug(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME));
        $slug = $slug !== '' ? substr($slug, 0, 40) : 'image';
        $name = $slug . '-' . Str::random(8) . '.' . $ext;

        $yearMonth = date('Y') . '/' . date('m');
        $relativeDir = "assets/img/uploads/{$yearMonth}";
        $absoluteDir = public_path($relativeDir);
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0775, true);
        }

        $file->move($absoluteDir, $name);
        return [
            'url'  => '/' . $relativeDir . '/' . $name,
            'name' => $name,
            'size' => (int) (filesize($absoluteDir . DIRECTORY_SEPARATOR . $name) ?: 0),
            'alt'  => $slug,
        ];
    }
}
