<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CdcDocument extends Model
{
    protected $fillable = [
        'cdc_number',
        'environment',
        'response_data',
        'status',
        'user_id',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];
}

