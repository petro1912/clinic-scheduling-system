<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'price',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
