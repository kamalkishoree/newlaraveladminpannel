<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
       protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'parent_id',
        'sort_order',
        'is_active',
        'is_new',
        'is_top',
        'is_feature'
    ];
}
