<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoteBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'environment',
        'response_data',
        'status',
        'user_id',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];
}
