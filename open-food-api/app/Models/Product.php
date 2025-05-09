<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'code',
        'url',
        'creator',
        'created_t',
        'created_datetime',
        'last_modified_t',
        'last_modified_datetime',
        'product_name',
        'generic_name',
        'quantity',
        'packaging',
        'brands',
        'categories',
        'origins',
        'manufacturing_places',
        'labels',
        'image_url',
        'image_small_url',
        'serving_size',
        'no_nutriments',
        'nutrition_grade_fr',
        'main_category',
        'main_category_fr',
        'energy_100g',
        'fat_100g',
        'saturated_fat_100g',
        'sodium_100g',
    ];

    protected $dates = ['imported_t'];
}
