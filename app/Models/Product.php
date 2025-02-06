<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'sku',
        'stock_quantity',
        'is_active',
        'category_id',
        'brand_id',
        'image',
        'additional_images',
        'video_url',
        'is_featured',
        'is_visible',
        'weight',
        'dimensions',
        'shipping_class',
        'available_from',
        'available_until',
    ];

}
