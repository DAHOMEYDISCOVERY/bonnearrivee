<?php

namespace App\Jobs;

use App\Support\ContentLayer;
use App\Support\PageSnapshot;
use App\Support\SearchIndex;
use App\Support\TranslationStore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Traduit UNE page juste après son enregistrement au back-office, pour que la
 * version anglaise suive sans attendre la synchro de nuit (trad:sync).
 *
 * Lancé via dispatchAfterResponse() : s'exécute après l'envoi de la réponse,
 * dans le même processus — aucun worker de file d'attente requis. La sauvegarde
 * reste instantanée pour l'éditeur ; la traduction se fait juste après.
 *
 * Ne traduit que les segments nouveaux ou modifiés (les autres gardent leur
 * traduction). Sans clé DeepL, ne fait rien. Toute erreur est journalisée et
 * n'affecte jamais l'enregistrement.
 */
class TranslatePageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $path,
        public string $locale = 'en',
    ) {}

    public function handle(): void
    {
        try {
            if ((string) config('services.deepl.key') === '') {
                return; // DeepL non configuré : la synchro de nuit s'en chargera
            }

            $path = ContentLayer::normalisePath($this->path);
            $html = PageSnapshot::html($path);
            if ($html === null) {
                return;
            }

            $existing = TranslationStore::get($this->locale, $path);
            $page = [];
            foreach (ContentLayer::extract($html, true) as $item) {
                if (! in_array($item['type'], ['text', 'rich', 'head'], true)) {
                    continue;
                }
                $fr = trim((string) ($item['value'] ?? ''));
                if ($fr === '') {
                    continue;
                }
                $en = $existing[$item['key']]['value'] ?? null;
                $page[$item['key']] = [
                    'type' => $item['type'],
                    'group' => (string) ($item['group'] ?? ''),
                    'fr' => $fr,
                    'en' => is_string($en) ? $en : null,
                ];
            }
            if ($page === []) {
                return;
            }

            $dir = storage_path('app/translations');
            if (! is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $tmp = $dir.'/_page-'.md5($this->locale.':'.$path).'.json';
            file_put_contents($tmp, json_encode([$path => $page], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            // Traduction DeepL (seuls les segments vides) puis import dans le magasin.
            Artisan::call('trad:translate', ['file' => $tmp, '--target' => 'EN-GB']);
            Artisan::call('trad:import', ['file' => $tmp, '--locale' => $this->locale]);
            @unlink($tmp);

            // Caches dépendants de cette page.
            Cache::forget('pagetextmap:'.$this->locale.':'.$path);
            SearchIndex::refresh($path);
        } catch (\Throwable $e) {
            Log::warning('Traduction à l\'enregistrement échouée', [
                'path' => $this->path,
                'locale' => $this->locale,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
