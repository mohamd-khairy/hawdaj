<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swalef extends Model
{
    use HasFactory;

    protected $guarded;

    const TYPES = [
        'شعر' => 'text',
        'مثل عربي' => 'text',
        'قصه' => 'textarea',
        'ملف' => 'file'
    ];

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
}
