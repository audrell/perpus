<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Book extends Model
{
    // mempelajari bahwa fillable sangat penting agar data bisa masuk lewat seeder/controller
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'author',
        'description',
        'stock',
        'cover_path'
    ];

    // mempelajari cara otomatisasi slug agar url buku lebih rapi
    protected static function booted()
    {
        static::creating(function ($book) {
            $book->slug = \Illuminate\Support\Str::slug($book->title);
        });
    }

    // mempelajari relasi ke kategori (satu buku punya satu kategori)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

