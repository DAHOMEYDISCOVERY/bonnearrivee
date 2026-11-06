<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Une campagne publicitaire partenaire affichée dans le carrousel de la page
 * « Liens utiles ». Administrée depuis le back-office (AdCampaignResource).
 */
final class AdCampaign extends Model
{
    protected $fillable = [
        'title', 'advertiser', 'placement', 'image_path', 'image_alt', 'description',
        'button_label', 'url', 'starts_at', 'ends_at', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Campagnes réellement diffusables : actives et dans leur fenêtre de dates.
     * Une date de début/fin vide signifie « pas de borne » de ce côté.
     */
    public function scopeVisible(Builder $query): Builder
    {
        $today = now()->startOfDay();

        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $today))
            ->where(fn (Builder $q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $today))
            ->orderBy('sort_order')
            ->orderByDesc('id');
    }

    /** URL publique de l'image (disque public lié via storage:link). */
    public function getImageUrlAttribute(): string
    {
        $path = (string) $this->image_path;
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.ltrim($path, '/');
    }
}
