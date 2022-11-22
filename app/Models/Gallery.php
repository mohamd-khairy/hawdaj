<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $guarded;

    //    public function place() {
    //        return $this->belongsTo(Place::class);
    //    }

    public function getFileAttribute()
    {
        if ($this->attributes['file']) {

            if (file_exists($this->attributes['file'])) {

                return $this->attributes['file'];

            } elseif (file_exists('storage/' . $this->attributes['file'])) {
//                dd('storage/' . $this->attributes['file']);
                return 'storage/' . $this->attributes['file'];
            } else {
                return 'front_assets/imgs/zad1.jpg';
            }
        }
        return null;
    }
}
