<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static inRandomOrder()
 */
class RequestType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'color'
    ];

    protected $casts = [
        'color' => 'array',
    ];
}
