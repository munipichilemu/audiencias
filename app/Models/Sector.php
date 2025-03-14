<?php

namespace App\Models;

use App\SectorType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static inRandomOrder()
 * @method static find(int $int)
 */
class Sector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'name',
    ];

    protected $casts = [
        'type' => SectorType::class,
    ];

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }
}
