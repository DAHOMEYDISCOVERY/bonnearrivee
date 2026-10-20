<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RestoAccessToken extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'token', 'email', 'expires_at', 'last_seen_at', 'hits'];

    protected $casts = [
        'expires_at'   => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(RestoOrder::class, 'order_id');
    }

    /**
     * Genere un nouveau token unique lie a une commande payee.
     * Duree de vie : 30 jours renouvelables tant que l'access_expires_at
     * de la commande parent n'est pas depasse.
     */
    public static function issueFor(RestoOrder $order, int $days = 30): self
    {
        return self::create([
            'order_id'   => $order->id,
            'token'      => Str::random(48),
            'email'      => $order->email,
            'expires_at' => now()->addDays($days),
        ]);
    }

    public function isValid(): bool
    {
        return $this->expires_at?->isFuture()
            && $this->order?->status === 'paid'
            && $this->order?->access_expires_at?->isFuture();
    }

    public function renew(int $days = 30): void
    {
        if ($this->order?->access_expires_at?->isFuture()) {
            $this->expires_at = min(
                now()->addDays($days),
                $this->order->access_expires_at,
            );
            $this->save();
        }
    }

    public function markSeen(): void
    {
        $this->hits++;
        $this->last_seen_at = now();
        $this->save();
    }
}
