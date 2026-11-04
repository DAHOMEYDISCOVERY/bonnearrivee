<x-filament-panels::page>
    <style>
        .ba-ce-bar { position: sticky; top: 64px; z-index: 20; display: flex; flex-wrap: wrap; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; background: #fff; border: 1px solid rgba(0,0,0,.08); box-shadow: 0 4px 16px rgba(0,0,0,.06); }
        .ba-ce-bar .grow { flex: 1 1 220px; }
        .ba-ce-search { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 9px 12px; font-size: .95rem; }
        .ba-ce-btn { display: inline-flex; align-items: center; gap: 6px; border-radius: 8px; padding: 9px 14px; font-size: .9rem; font-weight: 600; border: 1px solid #d1d5db; background: #fff; color: #111827; cursor: pointer; text-decoration: none; }
        .ba-ce-btn--primary { background: #00a05d; border-color: #00a05d; color: #fff; }
        .ba-ce-btn--primary:hover { background: #008a50; }
        .ba-ce-btn--danger { color: #b42318; border-color: #f3c3bd; }
        .ba-ce-note { border-radius: 12px; padding: 14px 16px; font-size: .9rem; line-height: 1.55; background: #fff; border: 1px solid rgba(0,0,0,.06); color: #374151; }
        .ba-ce-note--warn { background: #fff7e0; border-color: #f5d98b; color: #6b4e00; }
        .ba-ce-group { border-radius: 12px; background: #fff; border: 1px solid rgba(0,0,0,.06); overflow: hidden; }
        .ba-ce-group > h2 { margin: 0; padding: 12px 16px; font-size: .95rem; font-weight: 700; background: #f7f7f4; border-bottom: 1px solid #eee; color: #111827; }
        .ba-ce-item { padding: 14px 16px; border-top: 1px solid #f1f1f1; }
        .ba-ce-item:first-of-type { border-top: 0; }
        .ba-ce-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 6px; }
        .ba-ce-kind { font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #6b7280; }
        .ba-ce-kind b { color: #00a05d; }
        .ba-ce-edited { font-size: .72rem; font-weight: 700; color: #007a47; background: #e7f6ee; border-radius: 999px; padding: 2px 8px; }
        .ba-ce-reset { font-size: .78rem; color: #6b7280; background: none; border: 0; cursor: pointer; text-decoration: underline; }
        .ba-ce-input, .ba-ce-area { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 9px 11px; font-size: .95rem; line-height: 1.5; background: #fff; color: #111827; }
        .ba-ce-area { min-height: 44px; resize: vertical; }
        .ba-ce-big { font-size: 1.15rem; font-weight: 700; }
        .ba-ce-rich { min-height: 44px; border: 1px solid #d1d5db; border-radius: 0 0 8px 8px; padding: 9px 11px; font-size: .95rem; line-height: 1.55; background: #fff; color: #111827; outline: none; }
        .ba-ce-rich:focus, .ba-ce-input:focus, .ba-ce-area:focus { border-color: #00a05d; box-shadow: 0 0 0 3px rgba(0,160,93,.15); }
        .ba-ce-rich a { color: #00a05d; text-decoration: underline; }
        .ba-ce-tools { display: flex; gap: 4px; padding: 4px; border: 1px solid #d1d5db; border-bottom: 0; border-radius: 8px 8px 0 0; background: #f9fafb; }
        .ba-ce-tools button { min-width: 32px; height: 28px; border-radius: 6px; border: 0; background: transparent; cursor: pointer; font-size: .85rem; color: #374151; }
        .ba-ce-tools button:hover { background: #e5e7eb; }
        .ba-ce-img { display: grid; grid-template-columns: 160px 1fr; gap: 14px; align-items: start; }
        .ba-ce-img img { width: 160px; height: 110px; object-fit: cover; border-radius: 8px; background: #f3f4f6; border: 1px solid #e5e7eb; }
        .ba-ce-img .fields { display: grid; gap: 8px; }
        .ba-ce-hint { font-size: .78rem; color: #6b7280; }
        .ba-ce-err { font-size: .8rem; color: #b42318; }
        @media (max-width: 640px) { .ba-ce-img { grid-template-columns: 1fr; } .ba-ce-img img { width: 100%; height: 160px; } .ba-ce-bar { top: 56px; } }
    </style>

    <div class="ba-ce-note">
        <a href="{{ \App\Filament\Admin\Pages\PageContentIndex::getUrl() }}" wire:navigate>← Toutes les pages</a>
        · Page <a href="{{ url($chemin) }}" target="_blank" rel="noopener"><strong>{{ $chemin }}</strong> ↗</a>
        <br>Modifiez les champs voulus puis cliquez sur <strong>Enregistrer et publier</strong>. La mise en page (couleurs, carrousels,
        disposition) ne change pas : seuls les textes, les photos et les liens sont remplacés.
    </div>

    @if ($failure)
        <div class="ba-ce-note ba-ce-note--warn">{{ $failure }}</div>
    @endif

    @if ($orphans > 0)
        <div class="ba-ce-note ba-ce-note--warn">
            {{ $orphans }} modification(s) enregistrée(s) ne correspondent plus au contenu actuel de la page (le texte d’origine a changé dans le code) : elles ne s’affichent plus.
            <button type="button" class="ba-ce-reset" wire:click="dropOrphans" wire:confirm="Retirer ces anciennes modifications ?">Les retirer</button>
        </div>
    @endif

    @if (count($items) > 0)
        <form wire:submit="save" x-data="{ q: '' }" class="space-y-4">
            <div class="ba-ce-bar">
                <div class="grow"><input type="search" class="ba-ce-search" placeholder="Rechercher un texte dans la page…" x-model="q" aria-label="Rechercher un texte"></div>
                <button type="button" class="ba-ce-btn ba-ce-btn--danger" wire:click="restoreAll" wire:confirm="Retirer toutes les modifications de cette page et revenir au contenu d’origine ?">Tout remettre à l’origine</button>
                <a class="ba-ce-btn" href="{{ url($chemin) }}" target="_blank" rel="noopener">Voir la page ↗</a>
                <button type="submit" class="ba-ce-btn ba-ce-btn--primary" wire:loading.attr="disabled" wire:target="save,uploads">
                    <span wire:loading.remove wire:target="save">Enregistrer et publier</span>
                    <span wire:loading wire:target="save">Publication…</span>
                </button>
            </div>

            @foreach ($this->getGroups() as $group => $indexes)
                <section class="ba-ce-group" wire:key="g-{{ md5($group.$indexes[0]) }}"
                         x-show="!q || $el.textContent.toLowerCase().includes(q.toLowerCase()) || [...$el.querySelectorAll('textarea,input')].some(f => (f.value||'').toLowerCase().includes(q.toLowerCase()))">
                    <h2>{{ $group }}</h2>

                    @foreach ($indexes as $i)
                        @php($item = $items[$i])
                        <div class="ba-ce-item" wire:key="it-{{ $item['key'] }}"
                             x-show="!q || $el.textContent.toLowerCase().includes(q.toLowerCase()) || [...$el.querySelectorAll('textarea,input')].some(f => (f.value||'').toLowerCase().includes(q.toLowerCase()))">
                            <div class="ba-ce-head">
                                <span class="ba-ce-kind">
                                    @switch($item['type'])
                                        @case('head') <b>{{ $item['label'] }}</b> @break
                                        @case('image') <b>Photo</b> @break
                                        @case('link') <b>Lien</b> — « {{ $item['label'] }} » @break
                                        @default
                                            @if (in_array($item['tag'], ['h1','h2','h3','h4','h5','h6'], true)) <b>Titre</b>
                                            @elseif ($item['type'] === 'rich') <b>Paragraphe</b>
                                            @else <b>Texte</b>
                                            @endif
                                    @endswitch
                                </span>
                                <span>
                                    @if ($item['edited']) <span class="ba-ce-edited">Modifié</span> @endif
                                    <button type="button" class="ba-ce-reset" wire:click="restore({{ $i }})">Remettre l’original</button>
                                </span>
                            </div>

                            @if ($item['type'] === 'image')
                                <div class="ba-ce-img">
                                    <img src="{{ $this->previewUrl($i) ?? ($restored[$i] ?? false ? $item['original'] : ($values[$i] ?? $item['original'])) }}" alt="">
                                    <div class="fields">
                                        <label class="ba-ce-hint">Remplacer la photo (JPG, PNG, WebP — 8 Mo max.)</label>
                                        <input type="file" accept="image/*" wire:model="uploads.{{ $i }}">
                                        <div wire:loading wire:target="uploads.{{ $i }}" class="ba-ce-hint">Envoi de la photo…</div>
                                        @error('uploads.'.$i) <div class="ba-ce-err">{{ $message }}</div> @enderror
                                        <label class="ba-ce-hint">Description de la photo (lue par les malvoyants et Google)</label>
                                        <input type="text" class="ba-ce-input" wire:model="alts.{{ $i }}" maxlength="500">
                                    </div>
                                </div>
                            @elseif ($item['type'] === 'rich')
                                <div wire:ignore x-data="{
                                        sync() { $wire.set('values.{{ $i }}', this.$refs.ed.innerHTML, false) },
                                        cmd(c) { document.execCommand(c, false, null); this.sync() },
                                        lien() { const u = prompt('Adresse du lien (laisser vide pour retirer le lien) :', ''); if (u === null) return; u.trim() === '' ? document.execCommand('unlink') : document.execCommand('createLink', false, u.trim()); this.sync() }
                                     }"
                                     x-on:ba-contenu-restaure.window="if ($event.detail.index === {{ $i }}) { $refs.ed.innerHTML = $event.detail.value }">
                                    <div class="ba-ce-tools">
                                        <button type="button" title="Gras" @mousedown.prevent="cmd('bold')"><b>G</b></button>
                                        <button type="button" title="Italique" @mousedown.prevent="cmd('italic')"><i>I</i></button>
                                        <button type="button" title="Lien" @mousedown.prevent="lien()">🔗</button>
                                        <button type="button" title="Retirer la mise en forme" @mousedown.prevent="cmd('removeFormat')">✕</button>
                                    </div>
                                    <div x-ref="ed" class="ba-ce-rich {{ in_array($item['tag'], ['h1','h2','h3'], true) ? 'ba-ce-big' : '' }}" contenteditable="true"
                                         @input.debounce.300ms="sync()" @blur="sync()">{!! \App\Support\ContentLayer::sanitiseRich($values[$i] ?? '') !!}</div>
                                </div>
                            @elseif ($item['type'] === 'link')
                                <input type="text" class="ba-ce-input" wire:model="values.{{ $i }}" inputmode="url" placeholder="https://… ou /page">
                                @if (($values[$i] ?? '') !== $item['original'])
                                    <div class="ba-ce-hint">Adresse d’origine : {{ $item['original'] }}</div>
                                @endif
                            @else
                                <textarea class="ba-ce-area {{ in_array($item['tag'], ['h1','h2','h3'], true) ? 'ba-ce-big' : '' }}"
                                          rows="{{ max(1, min(8, (int) ceil(mb_strlen($values[$i] ?? '') / 90))) }}"
                                          wire:model="values.{{ $i }}" maxlength="20000"></textarea>
                                @if ($item['edited'])
                                    <div class="ba-ce-hint">Texte d’origine : {{ \Illuminate\Support\Str::limit($item['original'], 220) }}</div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </section>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" class="ba-ce-btn ba-ce-btn--primary" wire:loading.attr="disabled" wire:target="save,uploads">Enregistrer et publier</button>
            </div>
        </form>
    @elseif (! $failure)
        <div class="ba-ce-note">Cette page ne contient pas de texte ni d’image modifiable ici.</div>
    @endif
</x-filament-panels::page>
