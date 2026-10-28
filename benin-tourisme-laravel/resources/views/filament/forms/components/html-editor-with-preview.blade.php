@php
    $statePath = $getStatePath();
    $rows = $getRows();
    $stylesheets = $getPreviewStylesheets();
    $bodyClass = $getPreviewBodyClass();
    $origin = url('/');
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            html: @js($getState() ?? ''),
            editMode: false,
            previewHtml: '',
            layout: 'split',
            _timer: null,
            _origin: @js($origin),
            _stylesheets: @js($stylesheets),
            _bodyClass: @js($bodyClass),

            init() {
                this.schedulePreview();
            },

            onCodeInput(event) {
                this.html = event.target.value;
                this.schedulePreview();
            },

            setHtmlAndSync(value) {
                this.html = value;
                this.$refs.codeArea.value = value;
                this.$refs.codeArea.dispatchEvent(new Event('input', { bubbles: true }));
                this.schedulePreview();
            },

            schedulePreview() {
                clearTimeout(this._timer);
                this._timer = setTimeout(() => this.rebuildPreview(), 350);
            },

            rebuildPreview() {
                this.previewHtml = this.buildPreviewHtml(this.html);
                this.$nextTick(() => this.applyEditMode(this.editMode));
            },

            buildPreviewHtml(body) {
                const links = this._stylesheets
                    .map((href) => `<link rel='stylesheet' href='${this._origin}${href}' />`)
                    .join('');
                return `<!doctype html><html lang='fr'><head>
                    <meta charset='utf-8'>
                    <base href='${this._origin}/'>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet'>
                    ${links}
                    <style>
                        body { margin: 0; padding: 0; background: #fff; min-height: 100vh; }
                        [contenteditable='true'] { outline: 2px dashed rgba(234,88,12,.35); outline-offset: 2px; }
                        [contenteditable='true']:focus-within { outline-color: rgba(234,88,12,.9); }
                    </style>
                    </head><body class='${this._bodyClass}'>
                    <main role='main' class='content-type-page basic_page' id='main-content'>${body}</main>
                    </body></html>`;
            },

            applyEditMode(on) {
                try {
                    const doc = this.$refs.preview.contentDocument;
                    if (!doc || !doc.body) return;
                    doc.designMode = on ? 'on' : 'off';
                    doc.body.contentEditable = on ? 'true' : 'false';
                    doc.body.style.cursor = on ? 'text' : '';
                } catch (e) {}
            },

            toggleEdit() {
                this.editMode = !this.editMode;
                this.applyEditMode(this.editMode);
            },

            pullVisualToHtml() {
                try {
                    const doc = this.$refs.preview.contentDocument;
                    if (!doc) return;
                    const main = doc.getElementById('main-content');
                    const inner = main ? main.innerHTML : doc.body.innerHTML;
                    this.setHtmlAndSync(inner);
                } catch (e) {}
            },

            cycleLayout() {
                this.layout = this.layout === 'split' ? 'preview' : this.layout === 'preview' ? 'code' : 'split';
            },
        }"
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
            @media (min-width: 1024px) {
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
        </style>

        <div class="ba-html-editor__toolbar">
            <button type="button" @click="cycleLayout()" class="ba-html-editor__btn">
                <span x-text="layout === 'split' ? '↔ Vue cote-a-cote' : layout === 'preview' ? '◉ Visuel plein ecran' : '▭ HTML plein ecran'"></span>
            </button>
            <button type="button" @click="toggleEdit()" :class="editMode ? 'ba-html-editor__btn ba-html-editor__btn--active' : 'ba-html-editor__btn'">
                <span x-text="editMode ? '● Edition visuelle active' : '○ Edition visuelle'"></span>
            </button>
            <button type="button" @click="pullVisualToHtml()" x-show="editMode" class="ba-html-editor__btn">
                Appliquer visuel → HTML
            </button>
            <div class="ba-html-editor__sep"></div>
            <button type="button" @click="rebuildPreview()" class="ba-html-editor__btn">
                ↻ Actualiser visuel
            </button>
        </div>

        <div :class="'ba-html-editor__panels ba-html-editor__panels--' + layout">
            <div class="ba-html-editor__pane ba-html-editor__codepane">
                <div class="ba-html-editor__label">HTML brut</div>
                <textarea
                    x-ref="codeArea"
                    @input="onCodeInput($event)"
                    wire:model.live.debounce.400ms="{{ $statePath }}"
                    rows="{{ $rows }}"
                    spellcheck="false"
                    class="ba-html-editor__codearea"
                >{{ $getState() }}</textarea>
                <div class="ba-html-editor__hint">
                    Classes CSS, structures, SVG et directives Blade sont préservés. Modifier ici met à jour le visuel après 350 ms.
                </div>
            </div>

            <div class="ba-html-editor__pane ba-html-editor__previewpane">
                <div class="ba-html-editor__label">Aperçu visuel</div>
                <iframe
                    x-ref="preview"
                    :srcdoc="previewHtml"
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
