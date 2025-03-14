<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hearing extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'requested_at' => 'datetime',
        'attachment' => 'array', // Convierte automáticamente JSON a array y viceversa
    ];

    protected $fillable = [
        'requested_at',
        'beneficiary_id',
        'request_type_id',
        'details',
        'hearing_date',
        'hearing_time',
        'did_assist',
        'notes',
        'attachment',
        'status',
        'solution_steps',
    ];

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }
}
