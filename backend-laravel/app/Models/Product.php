<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'stock', 'image'
    ];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        $image = $this->images()->first();
        if (!$image) return null;
        
        // Force localhost:8000 if APP_URL is not set correctly for local dev
        $path = 'storage/' . $image->image_path;
        return 'http://localhost:8000/' . $path;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
