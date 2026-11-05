<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motorcycle extends Model
{
    protected $fillable = [
        'name',
        'transmission',
        'engine_cc',
        'daily_rate',
        'is_available',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // Find the uploaded picture using the motorcycle ID
    public function getImageUrlAttribute()
    {
        $directory = public_path('images');

        $images = glob($directory . '/' . $this->id . '.*');

        if (!$images) {
            return null;
        }

        return asset('images/' . basename($images[0]));
    }
}