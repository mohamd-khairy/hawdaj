<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $inPermission = true;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'name', 'region_id',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
