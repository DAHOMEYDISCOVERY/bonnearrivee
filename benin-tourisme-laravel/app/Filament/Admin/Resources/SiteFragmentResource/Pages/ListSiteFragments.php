<?php

namespace App\Filament\Admin\Resources\SiteFragmentResource\Pages;

use App\Filament\Admin\Resources\SiteFragmentResource;
use App\Models\SiteFragment;
use Filament\Resources\Pages\ListRecords;

class ListSiteFragments extends ListRecords
{
    protected static string $resource = SiteFragmentResource::class;

    /**
     * A l'ouverture, on s'assure que header + footer sont presents en base
     * (seed depuis le HTML des partials actuels s'ils manquent). Idempotent.
     */
    public function mount(): void
    {
        parent::mount();
        SiteFragment::seedFromPartials();
    }
}
