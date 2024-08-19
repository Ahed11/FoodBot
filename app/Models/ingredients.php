<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ingredients extends Model
{
    use HasFactory;

    protected $fillable = [
        "dish_id",
        "name"
    ];

    public function Dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }
}
