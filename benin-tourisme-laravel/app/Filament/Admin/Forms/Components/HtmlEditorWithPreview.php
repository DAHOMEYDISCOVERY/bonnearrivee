<?php

declare(strict_types=1);

namespace App\Filament\Admin\Forms\Components;

use Filament\Forms\Components\Field;

/**
 * Editeur HTML a deux volets, cote a cote :
 *  - a gauche : textarea monospace pour le HTML brut (source de verite)
 *  - a droite : iframe de preview rendue avec les CSS reels du site
 *
 * Mode 'edition visuelle' : contentEditable sur l'iframe. Les modifications
 * visuelles sont renvoyees vers la textarea d'un clic ('Appliquer visuel ->
 * HTML'). La synchronisation HTML -> visuel est automatique, debouncee a 400ms.
 */
class HtmlEditorWithPreview extends Field
{
    protected string $view = 'filament.forms.components.html-editor-with-preview';

    protected int $rows = 28;

    /** Chemins CSS absolus a injecter dans l'iframe de preview, dans l'ordre. */
    protected array $previewStylesheets = [
        '/assets/site/css/main_chunk_1.f40787f89c.css',
        '/assets/site/css/main_chunk_2.5d356b7f03.css',
        '/assets/site/css/main_chunk_3.d0cacfd5ce.css',
        '/assets/site/css/wicon.6c843834ea.css',
        '/assets/benin-carte.css',
        '/assets/benin-overrides.min.css',
    ];

    protected string $previewBodyClass = 'page prod gabarit-basic_page has-custom-content has-raw-content';

    public function rows(int $rows): static
    {
        $this->rows = $rows;
        return $this;
    }

    public function previewStylesheets(array $urls): static
    {
        $this->previewStylesheets = $urls;
        return $this;
    }

    public function previewBodyClass(string $class): static
    {
        $this->previewBodyClass = $class;
        return $this;
    }

    public function getRows(): int { return $this->rows; }
    public function getPreviewStylesheets(): array { return $this->previewStylesheets; }
    public function getPreviewBodyClass(): string { return $this->previewBodyClass; }
}
