<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
    ];

    /**
     * Events in this category
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the count of events in this category
     */
    public function getEventsCountAttribute()
    {
        return $this->events()->count();
    }
}