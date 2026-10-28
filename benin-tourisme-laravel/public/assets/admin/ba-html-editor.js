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
                _syncTimer: null,
                _origin: (cfg && cfg.origin) || window.location.origin,
                _stylesheets: (cfg && cfg.stylesheets) || [],
                _bodyClass: (cfg && cfg.bodyClass) || 'page prod',
                _statePath: (cfg && cfg.statePath) || null,

                // Images detectees dans le HTML, pour le panel 'Images de la page'.
                images: [],
                uploading: false,
                uploadError: '',

                init: function () {
                    this.rebuildPreview();
                    this.detectImages();
                    // Push initial vers Livewire pour s'assurer que la state est aligne
                    // sur la valeur extraite de la vue Blade au premier chargement.
                    this.pushToLivewire();
                },

                /**
                 * Pousse 'this.html' vers le state Livewire via $wire.set, avec debounce.
                 * Appele a chaque modification (frappe, upload image, pull visuel). Garantit
                 * qu au Save, Livewire a la bonne valeur, independamment du wire:model
                 * sur la textarea (qu on pouvait pas faire fonctionner de facon fiable
                 * avec Alpine + modifs programmatiques).
                 */
                pushToLivewire: function () {
                    var self = this;
                    clearTimeout(this._syncTimer);
                    this._syncTimer = setTimeout(function () {
                        if (self._statePath && self.$wire && typeof self.$wire.set === 'function') {
                            try {
                                self.$wire.set(self._statePath, self.html, false);
                            } catch (e) {
                                console.warn('[baHtmlEditor] $wire.set failed:', e);
                            }
                        }
                    }, 300);
                },

                onCodeInput: function (event) {
                    this.html = event.target.value;
                    this.schedulePreview();
                    this.detectImages();
                    this.pushToLivewire();
                },

                setHtmlAndSync: function (value) {
                    this.html = value;
                    if (this.$refs.codeArea) {
                        var proto = window.HTMLTextAreaElement && window.HTMLTextAreaElement.prototype;
                        var setter = proto && Object.getOwnPropertyDescriptor(proto, 'value');
                        if (setter && setter.set) {
                            setter.set.call(this.$refs.codeArea, value);
                        } else {
                            this.$refs.codeArea.value = value;
                        }
                        this.$refs.codeArea.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    this.schedulePreview();
                    this.detectImages();
                    this.pushToLivewire();
                },

                /** Parse les <img src=".."> du HTML pour alimenter le panel. */
                detectImages: function () {
                    var seen = {};
                    var out = [];
                    var re = /<img\b[^>]*\bsrc\s*=\s*["']([^"']+)["'][^>]*>/gi;
                    var m;
                    while ((m = re.exec(this.html)) !== null) {
                        var url = m[1];
                        if (seen[url]) continue;
                        seen[url] = true;
                        var isUpload = url.indexOf('/assets/img/uploads/') === 0;
                        out.push({ url: url, isUpload: isUpload });
                    }
                    this.images = out;
                },

                /**
                 * Remplace toutes les occurrences d'une URL d'image dans le HTML par
                 * la nouvelle URL (y compris dans srcset si meme url). L'ancien
                 * fichier serveur est supprime si c'etait un upload.
                 */
                replaceImage: function (oldUrl) {
                    var self = this;
                    var input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/jpeg,image/png,image/webp,image/gif';
                    input.onchange = function (e) {
                        var file = e.target.files && e.target.files[0];
                        if (!file) return;
                        self.uploading = true;
                        self.uploadError = '';
                        var fd = new FormData();
                        fd.append('file', file);
                        fd.append('old_url', oldUrl);
                        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                        var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
                        fetch('/admin/media/replace', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                            body: fd,
                            credentials: 'same-origin',
                        })
                            .then(function (resp) {
                                if (!resp.ok) return resp.json().then(function (j) { throw new Error(j.error || j.message || ('HTTP ' + resp.status)); });
                                return resp.json();
                            })
                            .then(function (data) {
                                // Remplace toutes les occurrences de l'URL (src + srcset)
                                var escaped = oldUrl.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                                var next = self.html.replace(new RegExp(escaped, 'g'), data.url);
                                self.setHtmlAndSync(next);
                                self.uploading = false;
                            })
                            .catch(function (err) {
                                self.uploadError = err.message || 'Erreur';
                                self.uploading = false;
                                console.error('[baHtmlEditor] replace failed:', err);
                            });
                    };
                    input.click();
                },

                /** Supprime le tag <img> correspondant du HTML + le fichier serveur si upload. */
                removeImage: function (url) {
                    var self = this;
                    if (!confirm('Supprimer cette image de la page ?')) return;
                    // Enleve TOUS les <img> dont le src correspond.
                    var escaped = url.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    var re = new RegExp('<img\\b[^>]*\\bsrc\\s*=\\s*["\']' + escaped + '["\'][^>]*>', 'gi');
                    var next = this.html.replace(re, '');
                    this.setHtmlAndSync(next);

                    // Supprime le fichier serveur si c'etait un upload.
                    if (url.indexOf('/assets/img/uploads/') === 0) {
                        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                        var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
                        var fd = new FormData();
                        fd.append('url', url);
                        fetch('/admin/media/destroy', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                            body: fd,
                            credentials: 'same-origin',
                        }).catch(function () {});
                    }
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
                    // L iframe ne passe pas par Blade : les directives s afficheraient
                    // en texte brut. On les nettoie pour que l apercu reste propre.
                    // Commentaires {{-- ... --}}
                    html = html.replace(/\{\{--[\s\S]*?--\}\}/g, '');
                    // Blocs @php(...) et @php...@endphp
                    html = html.replace(/@php\s*\([^)]*\)/g, '');
                    html = html.replace(/@php[\s\S]*?@endphp/g, '');
                    // Blocs conditionnels : on garde le texte interne, on vire les tags
                    html = html.replace(/@if\s*\([^)]*\)/g, '');
                    html = html.replace(/@elseif\s*\([^)]*\)/g, '');
                    html = html.replace(/@(else|endif|endforeach|endfor|endwhile|endswitch|break|continue)\b/g, '');
                    html = html.replace(/@(foreach|for|while|switch|case)\s*\([^)]*\)/g, '');
                    // Expressions {!! ... !!} : on evalue partiellement pour les cas simples
                    // (variable simple du site). Si le texte est non-ascii/complexe, on vide.
                    html = html.replace(/\{!!\s*[\s\S]*?\s*!!\}/g, '');
                    // Expressions {{ ... }} : affichees telles quelles donneraient du texte
                    // bizarre. On vide (dans la vraie page, elles sont rendues par Blade).
                    html = html.replace(/\{\{\s*[\s\S]*?\s*\}\}/g, '');
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

                /**
                 * Ouvre le file picker pour que l'utilisateur selectionne une image.
                 * Le fichier est uploade vers /admin/media/upload, et le tag <img>
                 * est insere a la position du curseur dans la textarea.
                 */
                triggerImageUpload: function () {
                    var self = this;
                    var input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/jpeg,image/png,image/webp,image/gif';
                    input.onchange = function (e) {
                        var file = e.target.files && e.target.files[0];
                        if (file) self.uploadImage(file);
                    };
                    input.click();
                },

                uploadImage: function (file) {
                    var self = this;
                    this.uploading = true;
                    this.uploadError = '';
                    var formData = new FormData();
                    formData.append('file', file);

                    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

                    fetch('/admin/media/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: formData,
                        credentials: 'same-origin',
                    })
                        .then(function (resp) {
                            if (!resp.ok) {
                                return resp.json().then(function (j) {
                                    throw new Error(j.error || j.message || ('HTTP ' + resp.status));
                                }).catch(function () {
                                    throw new Error('Upload a echoue (HTTP ' + resp.status + ')');
                                });
                            }
                            return resp.json();
                        })
                        .then(function (data) {
                            self.insertImageTag(data.url, data.alt || 'image');
                            self.uploading = false;
                        })
                        .catch(function (err) {
                            self.uploadError = err.message || 'Erreur d upload';
                            self.uploading = false;
                            console.error('[baHtmlEditor] upload failed:', err);
                        });
                },

                insertImageTag: function (url, alt) {
                    var ta = this.$refs.codeArea;
                    if (!ta) return;
                    var tag = '<img src="' + url + '" alt="' + (alt || '').replace(/"/g, '&quot;')
                        + '" loading="lazy" decoding="async" />';
                    var start = ta.selectionStart || 0;
                    var end = ta.selectionEnd || 0;
                    var before = this.html.substring(0, start);
                    var after = this.html.substring(end);
                    var next = before + tag + after;
                    this.setHtmlAndSync(next);
                    // Replace la selection apres le tag insere.
                    this.$nextTick(function () {
                        ta.focus();
                        var pos = start + tag.length;
                        ta.setSelectionRange(pos, pos);
                    });
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
