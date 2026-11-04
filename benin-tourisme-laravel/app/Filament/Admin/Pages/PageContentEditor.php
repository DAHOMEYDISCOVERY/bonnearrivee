<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\User;
use App\Support\ContentLayer;
use App\Support\PageContentStore;
use App\Support\PageSnapshot;
use App\Support\SitePages;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Modifier les textes, images et liens d'une page, sans toucher au code.
 *
 * Le formulaire est construit à partir de la page telle que le code la
 * produit ; enregistrer publie aussitôt (voir ApplyContentLayer). Chaque
 * élément peut être remis à son état d'origine, ou toute la page d'un coup.
 */
class PageContentEditor extends Page
{
    // Envoi de fichiers : fourni par Filament (InteractsWithForms → WithFileUploads).

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'textes-et-images/modifier';
    protected static string $view = 'filament.admin.pages.page-content-editor';

    private const UPLOAD_DIR = 'uploads/pages';

    #[Url(as: 'chemin')]
    public string $chemin = '/';

    /** @var list<array<string, mixed>> éléments de la page (sans positions) */
    public array $items = [];

    /** @var array<int, string> valeur courante de chaque élément, par rang */
    public array $values = [];

    /** @var array<int, string> texte alternatif des images, par rang */
    public array $alts = [];

    /** @var array<int, TemporaryUploadedFile|null> nouvelles photos, par rang */
    public array $uploads = [];

    /** @var array<int, bool> images remises à l'origine avant enregistrement */
    public array $restored = [];

    public int $orphans = 0;

    public ?string $failure = null;

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return $u instanceof User && $u->isEditor();
    }

    public function getTitle(): string | Htmlable
    {
        return 'Textes et images — '.$this->pageTitle();
    }

    public function mount(): void
    {
        $this->chemin = ContentLayer::normalisePath($this->chemin);
        abort_unless(SitePages::exists($this->chemin), 404);
        $this->load();
    }

    public function pageTitle(): string
    {
        foreach (SitePages::all() as $page) {
            if ($page['path'] === $this->chemin) {
                return $page['title'];
            }
        }

        return $this->chemin;
    }

    private function load(): void
    {
        $this->failure = null;
        $html = PageSnapshot::html($this->chemin);
        if ($html === null) {
            $this->items = [];
            $this->failure = 'La page n’a pas pu être lue. Vérifiez qu’elle s’affiche sur le site, puis rechargez cet écran.';

            return;
        }

        $edits = PageContentStore::edits($this->chemin);
        $found = [];
        $this->items = $this->values = $this->alts = $this->uploads = $this->restored = [];

        foreach (ContentLayer::extract($html) as $i => $item) {
            $edit = $edits[$item['key']] ?? null;
            $found[$item['key']] = true;
            $this->items[$i] = [
                'key' => $item['key'],
                'type' => $item['type'],
                'tag' => $item['tag'] ?? null,
                'group' => $item['group'],
                'label' => $item['label'] ?? null,
                'original' => $item['value'],
                'originalAlt' => $item['alt'] ?? null,
                'edited' => $edit !== null,
            ];
            $this->values[$i] = is_array($edit) && isset($edit['value']) ? (string) $edit['value'] : $item['value'];
            if ($item['type'] === 'image') {
                $this->alts[$i] = is_array($edit) && array_key_exists('alt', $edit) && $edit['alt'] !== null ? (string) $edit['alt'] : (string) ($item['alt'] ?? '');
            }
        }
        $this->items = array_values($this->items);
        $this->values = array_values($this->values);
        $this->alts = $this->alts === [] ? [] : $this->alts;
        $this->orphans = count(array_diff_key($edits, $found));
    }

    /** @return array<string, list<int>> rubrique => rangs des éléments */
    public function getGroups(): array
    {
        $groups = [];
        foreach ($this->items as $i => $item) {
            $groups[$item['group']][] = $i;
        }

        return $groups;
    }

    public function restore(int $i): void
    {
        if (! isset($this->items[$i])) {
            return;
        }
        $item = $this->items[$i];
        $this->values[$i] = $item['original'];
        if ($item['type'] === 'image') {
            $this->alts[$i] = (string) ($item['originalAlt'] ?? '');
            unset($this->uploads[$i]);
            $this->restored[$i] = true;
        }
        $this->dispatch('ba-contenu-restaure', index: $i, value: $item['original']);
    }

    public function save(): void
    {
        $this->validate([
            'uploads.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,avif', 'max:8192'],
            'values.*' => ['nullable', 'string', 'max:20000'],
            'alts.*' => ['nullable', 'string', 'max:500'],
        ], [
            'uploads.*.image' => 'Ce fichier n’est pas une image.',
            'uploads.*.mimes' => 'Formats acceptés : JPG, PNG, WebP, GIF, AVIF.',
            'uploads.*.max' => 'Image trop lourde (8 Mo au maximum).',
        ]);

        $previous = PageContentStore::edits($this->chemin);
        $edits = $previous;

        foreach ($this->items as $i => $item) {
            $key = $item['key'];
            $original = (string) $item['original'];
            $value = (string) ($this->values[$i] ?? $original);

            switch ($item['type']) {
                case 'image':
                    $current = is_array($previous[$key] ?? null) ? $previous[$key] : [];
                    $src = ! empty($this->restored[$i]) ? $original : (string) ($current['value'] ?? $original);
                    $upload = $this->uploads[$i] ?? null;
                    if ($upload instanceof TemporaryUploadedFile) {
                        $src = $this->storeImage($upload);
                    }
                    $alt = trim((string) ($this->alts[$i] ?? ''));
                    $edit = [];
                    if ($src !== $original) {
                        $edit['value'] = $src;
                    }
                    if ($alt !== trim((string) ($item['originalAlt'] ?? ''))) {
                        $edit['alt'] = $alt;
                    }
                    $this->put($edits, $key, $edit === [] ? null : ['type' => 'image', 'original' => $original] + $edit);
                    break;

                case 'rich':
                    $clean = ContentLayer::sanitiseRich($value);
                    $same = self::sameHtml($clean, $original) || ContentLayer::plain(strip_tags($clean)) === '';
                    $this->put($edits, $key, $same ? null : ['type' => 'rich', 'value' => $clean, 'original' => $original]);
                    break;

                case 'link':
                    $value = trim($value);
                    $ok = $value !== '' && $value !== $original && ContentLayer::isSafeUrl($value);
                    $this->put($edits, $key, $ok ? ['type' => 'link', 'value' => $value, 'original' => $original] : null);
                    break;

                default: // text, head
                    $value = trim($value);
                    $same = $value === '' || ContentLayer::plain($value) === ContentLayer::plain($original);
                    $this->put($edits, $key, $same ? null : ['type' => $item['type'], 'value' => $value, 'original' => $original]);
            }
        }

        PageContentStore::save($this->chemin, $edits);
        $this->load();

        Notification::make()
            ->title('Modifications publiées')
            ->body(count($edits) === 0 ? 'La page affiche son contenu d’origine.' : count($edits).' élément(s) modifié(s) sur cette page.')
            ->success()
            ->send();
    }

    public function restoreAll(): void
    {
        PageContentStore::save($this->chemin, []);

        Notification::make()->title('Page remise à l’origine')->body('Toutes les modifications de cette page ont été retirées.')->success()->send();

        // Recharge l'écran : les paragraphes mis en forme sont gérés par le
        // navigateur et ne se remettraient pas à jour d'eux-mêmes.
        $this->redirect(static::getUrl(['chemin' => $this->chemin]));
    }

    public function dropOrphans(): void
    {
        $edits = PageContentStore::edits($this->chemin);
        $keys = array_column($this->items, 'key');
        PageContentStore::save($this->chemin, array_intersect_key($edits, array_flip($keys)));
        $this->load();

        Notification::make()->title('Anciennes modifications retirées')->success()->send();
    }

    public function previewUrl(int $i): ?string
    {
        $upload = $this->uploads[$i] ?? null;
        if ($upload instanceof TemporaryUploadedFile) {
            try {
                return $upload->temporaryUrl();
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    /** @param array<string, mixed>|null $edit */
    private function put(array &$edits, string $key, ?array $edit): void
    {
        if ($edit === null) {
            unset($edits[$key]);
        } else {
            $edits[$key] = $edit;
        }
    }

    private function storeImage(TemporaryUploadedFile $file): string
    {
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'image';
        $name = Str::limit($base, 60, '').'-'.Str::lower(Str::random(6)).'.'.strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $file->storeAs(self::UPLOAD_DIR, $name, 'site_images');

        return '/assets/img/'.self::UPLOAD_DIR.'/'.$name;
    }

    private static function sameHtml(string $a, string $b): bool
    {
        $norm = static function (string $html): string {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $html = str_replace(["\u{00A0}", "'"], [' ', '"'], $html);
            $html = (string) preg_replace('/\s+/u', ' ', $html);
            $html = (string) preg_replace('/\s*(<\/?[a-z]+)\b/i', '$1', $html);

            return trim((string) preg_replace('/<br\s*\/?>/i', '<br>', $html));
        };

        return $norm($a) === $norm($b);
    }
}
