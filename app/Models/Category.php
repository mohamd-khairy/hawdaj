<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name', 'notes', 'icon', 'parent_id',
    ];

    public static function allParents()
    {
        return Category::whereNull('parent_id')->get();
    }

    public function childes()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getIconAttribute()
    {
        if ($this->attributes['icon']) {
            if (file_exists($this->attributes['icon'])) {
                return $this->attributes['icon'];
            } elseif (file_exists('storage/' . $this->attributes['icon'])) {
                return 'storage/' . $this->attributes['icon'];
            } else {
                return 'front_assets/imgs/zad1.jpg';
            }
        }
        return null;
    }
}
