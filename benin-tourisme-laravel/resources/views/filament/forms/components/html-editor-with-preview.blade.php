@php
    $statePath   = $getStatePath();
    $rows        = $getRows();
    $stylesheets = $getPreviewStylesheets();
    $bodyClass   = $getPreviewBodyClass();
    $origin      = url('/');
    $state       = (string) ($getState() ?? '');
@endphp

{{-- La definition JS de 'baHtmlEditor' est chargee via AdminPanelProvider
     renderHook('panels::body.start'). L'inliner ici fait que Livewire/Filament
     l'echappe comme texte dans le DOM du field wrapper. --}}
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="baHtmlEditor({
            initialHtml: @js($state),
            origin: @js($origin),
            stylesheets: @js($stylesheets),
            bodyClass: @js($bodyClass),
            statePath: @js($statePath),
        })"
        class="ba-html-editor"
    >
        <style>
            .ba-html-editor { display: flex; flex-direction: column; gap: .5rem; }
            .ba-html-editor__toolbar {
                display: flex; flex-wrap: wrap; align-items: center; gap: .5rem;
                padding: .5rem .75rem; background: rgb(249 250 251); border: 1px solid rgb(229 231 235);
                border-radius: .5rem; font-size: .8125rem;
            }
            .dark .ba-html-editor__toolbar { background: rgb(31 41 55); border-color: rgb(55 65 81); color: rgb(229 231 235); }
            .ba-html-editor__btn {
                display: inline-flex; align-items: center; gap: .25rem;
                padding: .25rem .6rem; border-radius: .375rem; font-weight: 500;
                background: rgb(255 255 255); border: 1px solid rgb(209 213 219); color: rgb(55 65 81);
                cursor: pointer; transition: background .15s;
            }
            .ba-html-editor__btn:hover { background: rgb(243 244 246); }
            .ba-html-editor__btn--active { background: rgb(234 88 12); color: #fff; border-color: rgb(234 88 12); }
            .dark .ba-html-editor__btn { background: rgb(55 65 81); border-color: rgb(75 85 99); color: rgb(229 231 235); }
            .dark .ba-html-editor__btn:hover { background: rgb(75 85 99); }
            .ba-html-editor__sep { flex: 1; }
            .ba-html-editor__panels { display: grid; gap: 1rem; grid-template-columns: 1fr; }
            @@media (min-width: 1024px) {
                .ba-html-editor__panels--split { grid-template-columns: 1fr 1fr; }
            }
            .ba-html-editor__panels--preview .ba-html-editor__codepane { display: none; }
            .ba-html-editor__panels--code .ba-html-editor__previewpane { display: none; }
            .ba-html-editor__pane { display: flex; flex-direction: column; min-height: 0; }
            .ba-html-editor__label {
                font-size: .75rem; font-weight: 600; color: rgb(107 114 128);
                letter-spacing: .05em; text-transform: uppercase; margin-bottom: .25rem;
            }
            .ba-html-editor__codearea {
                width: 100%; resize: vertical; padding: .75rem; border-radius: .5rem;
                border: 1px solid rgb(209 213 219); background: rgb(255 255 255); color: rgb(17 24 39);
                font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
                font-size: 13px; line-height: 1.55; white-space: pre; overflow: auto; tab-size: 2;
            }
            .dark .ba-html-editor__codearea { background: rgb(17 24 39); color: rgb(229 231 235); border-color: rgb(55 65 81); }
            .ba-html-editor__codearea:focus { outline: 2px solid rgb(234 88 12); outline-offset: -2px; }
            .ba-html-editor__preview {
                width: 100%; border: 1px solid rgb(209 213 219); border-radius: .5rem;
                background: rgb(255 255 255); min-height: 32rem;
            }
            .dark .ba-html-editor__preview { border-color: rgb(55 65 81); }
            .ba-html-editor__hint {
                font-size: .75rem; color: rgb(107 114 128); margin-top: .25rem;
            }
            .ba-html-editor__btn--danger {
                background: rgb(254 242 242); border-color: rgb(252 165 165); color: rgb(185 28 28);
            }
            .ba-html-editor__btn--danger:hover { background: rgb(254 226 226); }
            .ba-html-editor__images {
                padding: .75rem; background: rgb(249 250 251);
                border: 1px solid rgb(229 231 235); border-radius: .5rem;
            }
            .dark .ba-html-editor__images { background: rgb(31 41 55); border-color: rgb(55 65 81); }
            .ba-html-editor__thumbs {
                display: grid; gap: .75rem; margin-top: .5rem;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
            .ba-html-editor__thumb {
                display: flex; flex-direction: column; gap: .35rem;
                padding: .5rem; background: #fff;
                border: 1px solid rgb(229 231 235); border-radius: .375rem;
            }
            .dark .ba-html-editor__thumb { background: rgb(17 24 39); border-color: rgb(55 65 81); }
            .ba-html-editor__thumb img {
                width: 100%; height: 110px; object-fit: cover;
                border-radius: .25rem; background: rgb(243 244 246);
            }
            .ba-html-editor__thumb-meta {
                display: flex; align-items: center; gap: .35rem; font-size: .7rem;
                color: rgb(107 114 128);
            }
            .ba-html-editor__thumb-meta code {
                font-size: .65rem; overflow: hidden; text-overflow: ellipsis;
                white-space: nowrap; flex: 1; min-width: 0;
            }
            .ba-html-editor__badge {
                padding: 1px 6px; border-radius: 999px;
                background: rgb(254 215 170); color: rgb(154 52 18); font-weight: 600;
                font-size: .65rem;
            }
            .ba-html-editor__thumb-actions {
                display: flex; gap: .35rem;
            }
            .ba-html-editor__thumb-actions .ba-html-editor__btn {
                flex: 1; justify-content: center; font-size: .7rem; padding: .2rem .4rem;
            }
            .ba-html-editor__error {
                margin-top: .5rem; padding: .35rem .5rem; border-radius: .25rem;
                background: rgb(254 226 226); color: rgb(185 28 28); font-size: .75rem;
            }
            [x-cloak] { display: none !important; }
        </style>

        <div class="ba-html-editor__toolbar">
            <button type="button" x-on:click="cycleLayout()" class="ba-html-editor__btn">
                <span x-text="layout === 'split' ? '↔ Vue côte-à-côte' : layout === 'preview' ? '◉ Visuel plein écran' : '▭ HTML plein écran'"></span>
            </button>
            <button type="button" x-on:click="toggleEdit()" x-bind:class="editMode ? 'ba-html-editor__btn ba-html-editor__btn--active' : 'ba-html-editor__btn'">
                <span x-text="editMode ? '● Édition visuelle active' : '○ Édition visuelle'"></span>
            </button>
            <button type="button" x-on:click="pullVisualToHtml()" x-show="editMode" class="ba-html-editor__btn">
                Appliquer visuel → HTML
            </button>
            <button type="button" x-on:click="triggerImageUpload()" class="ba-html-editor__btn">
                <span x-text="uploading ? '⏳ Upload…' : '📎 Insérer image'"></span>
            </button>
            <div class="ba-html-editor__sep"></div>
            <button type="button" x-on:click="rebuildPreview()" class="ba-html-editor__btn">
                ↻ Actualiser visuel
            </button>
        </div>

        {{-- Panel 'Images de la page' : liste les <img> detectees dans le HTML
             avec un bouton Remplacer et un bouton Retirer sur chacune. --}}
        <div class="ba-html-editor__images" x-show="images.length > 0" x-cloak>
            <div class="ba-html-editor__label">Images de la page (<span x-text="images.length"></span>)</div>
            <div class="ba-html-editor__thumbs">
                <template x-for="img in images" x-bind:key="img.url">
                    <div class="ba-html-editor__thumb">
                        <img x-bind:src="img.url" x-bind:alt="img.url" />
                        <div class="ba-html-editor__thumb-meta">
                            <code x-text="img.url.length > 42 ? '…' + img.url.slice(-40) : img.url"></code>
                            <span x-show="img.isUpload" class="ba-html-editor__badge">upload</span>
                        </div>
                        <div class="ba-html-editor__thumb-actions">
                            <button type="button" class="ba-html-editor__btn" x-on:click="replaceImage(img.url)">
                                ↻ Remplacer
                            </button>
                            <button type="button" class="ba-html-editor__btn ba-html-editor__btn--danger" x-on:click="removeImage(img.url)">
                                × Retirer
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="ba-html-editor__error" x-show="uploadError" x-text="uploadError"></div>
        </div>

        <div x-bind:class="'ba-html-editor__panels ba-html-editor__panels--' + layout">
            <div class="ba-html-editor__pane ba-html-editor__codepane">
                <div class="ba-html-editor__label">HTML brut</div>
                <textarea
                    x-ref="codeArea"
                    x-on:input="onCodeInput($event)"
                    wire:model.live.debounce.400ms="{{ $statePath }}"
                    rows="{{ $rows }}"
                    spellcheck="false"
                    class="ba-html-editor__codearea"
                >{{ $state }}</textarea>
                <div class="ba-html-editor__hint">
                    Classes CSS, structures, SVG et directives Blade sont préservés. Modifier ici met à jour le visuel après 350 ms.
                </div>
            </div>

            <div class="ba-html-editor__pane ba-html-editor__previewpane">
                <div class="ba-html-editor__label">Aperçu visuel</div>
                <iframe
                    x-ref="preview"
                    x-bind:srcdoc="previewHtml"
                    class="ba-html-editor__preview"
                    sandbox="allow-same-origin allow-scripts"
                    style="height: 36rem;"
                ></iframe>
                <div class="ba-html-editor__hint">
                    Active « Édition visuelle » puis clique dans l'aperçu pour modifier directement. Clique « Appliquer visuel → HTML » pour renvoyer tes changements dans le code source.
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
