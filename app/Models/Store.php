<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded;

    protected $casts = [
        'categories' => 'array',
        'related_stores' => 'array',
        'near_places' => 'array',
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
        return 'store';
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function category()
    {
        return $this->belongsTo(CategoryOfStore::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, "parent_id")->where('type', '=', 'stores');
    }

    public function ceo()
    {
        return $this->hasOne(Ceo::class, "parent_id")->where('type', '=', 'stores');
    }
    public function ratings()
    {
        return $this->hasMany(Rate::class, 'parent_id')->where('type', '=', 'stores')->orderBy('id', 'desc');
    }
    public function getRateAttribute()
    {
        return $this->ratings()->count() ? round($this->ratings()->sum('rate') / $this->ratings()->count(), 1) : 0;
    }
    public function getReviewAttribute()
    {
        return $this->ratings()->count() ? $this->ratings()->count() : 0;
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

    public function getLatAttribute()
    {
        return $this->attributes['lat'] ? round($this->attributes['lat'], 10) : 0;
    }

    public function getLongAttribute()
    {
        return $this->attributes['long'] ? round($this->attributes['long'], 10) : 0;
    }
}
