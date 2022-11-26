<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
