<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOfZad extends Model
{
    use HasFactory;

    protected $guarded;

    public $inPermission = true;

    protected $dates = [
        'deleted_at',
    ];

    public static function allParents()
    {
        return CategoryOfZad::whereNull('parent_id')->get();
    }

    public function childes()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
