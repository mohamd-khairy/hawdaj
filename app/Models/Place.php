<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded;

    protected $casts = [
        // 'seasons' => 'array',
        'key_words' => 'array',
        'categories' => 'array',
        'lat' => 'float',
        'long' => 'float',
        'related_places' => 'array',
        'near_stores' => 'array',
        'featured' => 'boolean',
        'visited' => 'boolean',
    ];

    public $appends = [
        'rate',
        'review',
        'type'
    ];

    public function getTypeAttribute()
    {
        return 'place';
    }

    public function getSeasonsAttribute()
    {
        return $this->attributes['seasons'] ? (!is_array($this->attributes['seasons']) ? explode(',', $this->attributes['seasons']) : $this->attributes['seasons']) : [];
    }

    public function getCategoriesAttribute()
    {
        return $this->attributes['categories'] ? json_decode($this->attributes['categories'], true) : [];
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function galleries()
    {
        return $this->hasMany(Gallery::class, "parent_id")->where('type', '=', 'places');
    }
    public function ratings()
    {
        return $this->hasMany(Rate::class, 'parent_id')->where('type', '=', 'places')->orderBy('id', 'desc');
    }
    public function ceo()
    {
        return $this->hasOne(Ceo::class, "parent_id")->where('type', '=', 'places');
    }
    public function getRateAttribute()
    {
        return $this->ratings->count() ? round($this->ratings->sum('rate') / $this->ratings->count(), 1) : 0;
    }
    public function getReviewAttribute()
    {
        return  $this->ratings->count() ? $this->ratings->count() : 0;
    }
    public function price()
    {
        return $this->belongsTo(Price::class);
    }
    public function getLatAttribute()
    {
        return $this->attributes['lat'] ? round($this->attributes['lat'], 10) : 0;
    }

    public function getLongAttribute()
    {
        return $this->attributes['long'] ? round($this->attributes['long'], 10) : 0;
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image']) {
            if (file_exists($this->attributes['image'])) {
                return $this->attributes['image'];
            } elseif (file_exists('storage/' . $this->attributes['image'])) {
                return 'storage/' . $this->attributes['image'];
            } else {
                return 'front_assets/imgs/zad1.jpg';
            }
        }
        return null;
    }

    public function getDescriptionAttribute()
    {
        if (request()->segment(2) != 'place-details') {
            return $this->attributes['description'] ? mb_convert_encoding(substr(strip_tags($this->attributes['description']), 0, 200), 'UTF-8', 'UTF-8') : "";
        }
        return strip_tags($this->attributes['description']);
    }
}
