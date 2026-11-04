<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\User;
use App\Support\PageContentStore;
use App\Support\SitePages;
use Filament\Pages\Page;

/**
 * « Textes et images » : la liste de toutes les pages du site, chacune
 * modifiable (textes, images, liens, titre Google) sans toucher au code.
 */
class PageContentIndex extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Textes et images';
    protected static ?string $title = 'Textes et images des pages';
    protected static ?int $navigationSort = 0;
    protected static ?string $slug = 'textes-et-images';
    protected static string $view = 'filament.admin.pages.page-content-index';

    public static function canAccess(): bool
    {
        $u = auth()->user();

        return $u instanceof User && $u->isEditor();
    }

    /** @return array<string, list<array{path:string,title:string,group:string,edits:int,url:string}>> */
    public function getGroups(): array
    {
        $groups = [];
        foreach (SitePages::all() as $page) {
            $page['edits'] = PageContentStore::count($page['path']);
            $page['url'] = PageContentEditor::getUrl(['chemin' => $page['path']]);
            $groups[$page['group']][] = $page;
        }

        return $groups;
    }
}
