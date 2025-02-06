<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'category_id',
        'parent_id',
        'sort_order',
        'is_active',
        'is_new',
        'is_top',
        'is_feature'
    ];
}
