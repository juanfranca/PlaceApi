<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'city', 'state'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($place) {
            $place->slug = Str::slug($place->name);
        });

        static::updating(function ($place) {
            if ($place->isDirty('name')) {
                $place->slug = Str::slug($place->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
