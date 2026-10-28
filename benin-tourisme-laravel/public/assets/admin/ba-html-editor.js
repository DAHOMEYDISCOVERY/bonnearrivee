/**
 * Composant Alpine 'baHtmlEditor' : editeur HTML split-view avec apercu
 * visuel iframe modifiable. Enregistre une seule fois par chargement de
 * page, et fonctionne apres les re-renders Livewire.
 *
 * Charge via AdminPanelProvider::renderHook('panels::body.start'). Place
 * en fichier statique parce que l'inliner dans le Blade field le fait
 * echapper par le wrapper de champ Filament/Livewire -> le JS s'affiche
 * en texte au lieu d'etre execute.
 */
(function () {
    'use strict';

    if (window.__baHtmlEditorRegistered) return;
    window.__baHtmlEditorRegistered = true;

    function define() {
        if (!window.Alpine || !window.Alpine.data) {
            return setTimeout(define, 30);
        }

        window.Alpine.data('baHtmlEditor', function (cfg) {
            return {
                html: (cfg && cfg.initialHtml) || '',
                editMode: false,
                previewHtml: '',
                layout: 'split',
                _timer: null,
                _origin: (cfg && cfg.origin) || window.location.origin,
                _stylesheets: (cfg && cfg.stylesheets) || [],
                _bodyClass: (cfg && cfg.bodyClass) || 'page prod',

                init: function () {
                    this.rebuildPreview();
                },

                onCodeInput: function (event) {
                    this.html = event.target.value;
                    this.schedulePreview();
                },

                setHtmlAndSync: function (value) {
                    this.html = value;
                    if (this.$refs.codeArea) {
                        this.$refs.codeArea.value = value;
                        this.$refs.codeArea.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    this.schedulePreview();
                },

                schedulePreview: function () {
                    var self = this;
                    clearTimeout(this._timer);
                    this._timer = setTimeout(function () { self.rebuildPreview(); }, 350);
                },

                rebuildPreview: function () {
                    this.previewHtml = this.buildPreviewHtml(this.html);
                    var self = this;
                    this.$nextTick(function () { self.applyEditMode(self.editMode); });
                },

                stripBladeArtefacts: function (html) {
                    if (!html) return html;
                    // Les commentaires Blade {{-- ... --}} sont normalement supprimes
                    // au compile, mais ici on injecte le HTML brut dans srcdoc : pas
                    // de passe Blade. On les enleve manuellement pour que l'apercu
                    // ne les montre pas en texte.
                    html = html.replace(/\{\{--[\s\S]*?--\}\}/g, '');
                    // Pareil pour les commentaires PHP qui auraient pu glisser.
                    html = html.replace(/\{\{!!\s*|\s*!!\}\}/g, '');
                    return html;
                },

                buildPreviewHtml: function (body) {
                    body = this.stripBladeArtefacts(body);
                    var o = this._origin;
                    var links = this._stylesheets.map(function (href) {
                        return '<link rel="stylesheet" href="' + o + href + '" />';
                    }).join('');
                    var at = String.fromCharCode(64);
                    var gf = 'https://fonts.googleapis.com/css2'
                        + '?family=Playfair+Display:ital,wght' + at + '0,400;0,500;0,700;1,400;1,500'
                        + '&family=Inter:wght' + at + '300;400;500;600;700'
                        + '&display=swap';
                    var emptyNotice = '<p style="padding:2rem;color:#999;font-family:sans-serif;">'
                        + 'Apercu vide - ajoute du HTML a gauche.</p>';
                    return '<!doctype html><html lang="fr"><head>'
                        + '<meta charset="utf-8">'
                        + '<base href="' + o + '/">'
                        + '<link rel="preconnect" href="https://fonts.googleapis.com">'
                        + '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'
                        + '<link rel="stylesheet" href="' + gf + '">'
                        + links
                        + '<style>'
                        + 'body{margin:0;padding:0;background:#fff;min-height:100vh;}'
                        + '[contenteditable="true"]{outline:2px dashed rgba(234,88,12,.35);outline-offset:2px;}'
                        + '[contenteditable="true"]:focus-within{outline-color:rgba(234,88,12,.9);}'
                        + '</style>'
                        + '</head><body class="' + this._bodyClass + '">'
                        + '<main role="main" class="content-type-page basic_page" id="main-content">'
                        + (body || emptyNotice)
                        + '</main>'
                        + '</body></html>';
                },

                applyEditMode: function (on) {
                    try {
                        var doc = this.$refs.preview.contentDocument;
                        if (!doc || !doc.body) return;
                        doc.designMode = on ? 'on' : 'off';
                        doc.body.contentEditable = on ? 'true' : 'false';
                        doc.body.style.cursor = on ? 'text' : '';
                    } catch (e) {}
                },

                toggleEdit: function () {
                    this.editMode = !this.editMode;
                    this.applyEditMode(this.editMode);
                },

                pullVisualToHtml: function () {
                    try {
                        var doc = this.$refs.preview.contentDocument;
                        if (!doc) return;
                        var main = doc.getElementById('main-content');
                        var inner = main ? main.innerHTML : doc.body.innerHTML;
                        this.setHtmlAndSync(inner);
                    } catch (e) {}
                },

                cycleLayout: function () {
                    this.layout = this.layout === 'split' ? 'preview'
                        : this.layout === 'preview' ? 'code' : 'split';
                },
            };
        });
    }

    if (window.Alpine && window.Alpine.data) {
        define();
    } else {
        document.addEventListener('alpine:init', define);
        // Fallback : au cas ou alpine:init est deja passe mais Alpine pas
        // encore global, on retente dans 100ms.
        setTimeout(function () {
            if (window.Alpine && window.Alpine.data && !window.__baHtmlEditorDefined) {
                window.__baHtmlEditorDefined = true;
                define();
            }
        }, 100);
    }

    // Marque la definition reussie pour le fallback ci-dessus.
    var _origDefine = define;
    define = function () {
        _origDefine();
        window.__baHtmlEditorDefined = true;
    };
})();
