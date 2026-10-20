<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'slug', 'sous_titre', 'categorie', 'ville', 'quartier',
        'adresse', 'telephone', 'horaires', 'budget_min', 'budget_max',
        'specialite', 'pourquoi_y_aller', 'conseil_dahomey',
        'gps_lat', 'gps_lng', 'photos', 'coup_de_coeur', 'published', 'sort_order',
    ];

    protected $casts = [
        'pourquoi_y_aller' => 'array',
        'photos'           => 'array',
        'coup_de_coeur'    => 'boolean',
        'published'        => 'boolean',
        'gps_lat'          => 'decimal:7',
        'gps_lng'          => 'decimal:7',
    ];

    public const CATEGORIES = [
        'gastronomique' => 'Gastronomique',
        'maquis'        => 'Maquis & cuisine locale',
        'cafe_bar'      => 'Café / bar',
        'fast_food'     => 'Fast-food',
        'international' => 'International',
    ];

    protected static function booted(): void
    {
        static::saving(function (Restaurant $r) {
            if (empty($r->slug)) {
                $r->slug = Str::slug($r->nom . '-' . $r->ville);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function getBudgetLabelAttribute(): string
    {
        if ($this->budget_min && $this->budget_max) {
            return number_format($this->budget_min, 0, ',', ' ') . ' – ' . number_format($this->budget_max, 0, ',', ' ') . ' FCFA';
        }
        if ($this->budget_min) {
            return 'À partir de ' . number_format($this->budget_min, 0, ',', ' ') . ' FCFA';
        }
        return 'Prix non communiqué';
    }
}
