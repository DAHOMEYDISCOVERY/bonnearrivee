<x-filament-panels::page>
    <style>
        .ba-ci-intro { border-radius: 12px; background: #fff; padding: 16px; font-size: .9rem; line-height: 1.55; color: #374151; box-shadow: 0 1px 2px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.06); }
        .ba-ci-intro a { color: #00a05d; font-weight: 600; }
        .ba-ci-search { width: 100%; max-width: 420px; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 12px; font-size: .95rem; }
        .ba-ci-group h2 { margin: 24px 0 8px; font-size: 1rem; font-weight: 700; color: #111827; }
        .ba-ci-list { border-radius: 12px; background: #fff; border: 1px solid rgba(0,0,0,.06); overflow: hidden; }
        .ba-ci-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 16px; border-top: 1px solid #f1f1f1; text-decoration: none; color: inherit; }
        .ba-ci-row:first-child { border-top: 0; }
        .ba-ci-row:hover { background: #f6fbf8; }
        .ba-ci-title { font-weight: 600; color: #111827; }
        .ba-ci-path { font-size: .8rem; color: #6b7280; word-break: break-all; }
        .ba-ci-badge { flex: 0 0 auto; font-size: .75rem; font-weight: 700; padding: 3px 9px; border-radius: 999px; background: #e7f6ee; color: #007a47; }
        .ba-ci-go { flex: 0 0 auto; font-size: .85rem; font-weight: 600; color: #00a05d; }
    </style>

    <div class="ba-ci-intro">
        Choisissez une page pour modifier ses <strong>textes</strong>, ses <strong>photos</strong>, ses <strong>liens</strong>
        et son <strong>titre Google</strong>. « Enregistrer et publier » met le site à jour immédiatement ; chaque modification
        peut être annulée. Le menu et le pied de page se modifient dans
        <a href="{{ \App\Filament\Admin\Pages\SiteLayoutPage::getUrl() }}">En-tête et pied de page</a>, les restaurants dans
        <a href="{{ \App\Filament\Admin\Resources\RestaurantResource::getUrl('index') }}">Restaurants</a>.
    </div>

    <div x-data="{ q: '' }" class="space-y-2">
        <input type="search" class="ba-ci-search" placeholder="Rechercher une page…" x-model="q" aria-label="Rechercher une page">

        @foreach ($this->getGroups() as $group => $pages)
            <section class="ba-ci-group" x-show="!q || $el.textContent.toLowerCase().includes(q.toLowerCase())">
                <h2>{{ $group }} <span class="text-sm font-normal text-gray-500">({{ count($pages) }})</span></h2>
                <div class="ba-ci-list">
                    @foreach ($pages as $page)
                        <a class="ba-ci-row" href="{{ $page['url'] }}" wire:navigate
                           x-show="!q || $el.textContent.toLowerCase().includes(q.toLowerCase())">
                            <span>
                                <span class="ba-ci-title">{{ $page['title'] }}</span><br>
                                <span class="ba-ci-path">{{ $page['path'] }}</span>
                            </span>
                            @if ($page['edits'] > 0)
                                <span class="ba-ci-badge">{{ $page['edits'] }} modif.</span>
                            @endif
                            <span class="ba-ci-go">Modifier →</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-filament-panels::page>
