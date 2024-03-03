<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BehaviorNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'is_positive'
    ];

    protected $casts = [
        'is_positive' => 'boolean',
    ];
}
