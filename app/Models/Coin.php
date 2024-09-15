<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coin extends Model
{
    use HasUuids;
    protected $fillable = [
        'id',
        'coin_id',
        'name',
        'symbol',
        'image',
        'current_price',
        'price_change_percentage_24h',
        'market_cap'
    ];
    public $incrementing = false;
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function bootIfNotBooted(): void
    {
        parent::bootIfNotBooted();
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
