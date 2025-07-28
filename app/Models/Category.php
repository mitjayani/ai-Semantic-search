<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_category',
        'sub_category',
        'service',
        'keywords',
        'embedding'
    ];

    protected $casts = [
        'embedding' => 'array', // to handle JSON vector
    ];
}
