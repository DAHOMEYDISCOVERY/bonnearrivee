<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestoOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'nom_client', 'amount_xof',
        'kkiapay_transaction_id', 'status',
        'paid_at', 'access_expires_at', 'kkiapay_payload',
    ];

    protected $casts = [
        'paid_at'           => 'datetime',
        'access_expires_at' => 'datetime',
        'kkiapay_payload'   => 'array',
    ];

    public function accessTokens(): HasMany
    {
        return $this->hasMany(RestoAccessToken::class, 'order_id');
    }

    public function markPaid(array $payload = []): void
    {
        $this->status = 'paid';
        $this->paid_at = now();
        $this->access_expires_at = now()->addMonths(6);
        if ($payload) {
            $this->kkiapay_payload = $payload;
        }
        $this->save();
    }
}
