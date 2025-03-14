<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laragear\Rut\HasRut;
use Illuminate\database\Eloquent\Factories\HasFactory;

/**
 * @method static inRandomOrder()
 */
class Beneficiary extends Model
{
    use SoftDeletes, HasRut, HasFactory;

    protected $fillable = [
        'name',
        'rut',
        'phone',
        'email',
        'sector_id',
        'city',
        'notes',
    ];

    protected $appends = [
        'rut'
    ];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class);
    }


}
