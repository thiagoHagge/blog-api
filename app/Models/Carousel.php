<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    protected $table = 'front_carousel';
    protected $primaryKey = 'crsl_id';
    public $timestamps = false;
    // protected $keyType = 'string';
    protected $fillable = ['crsl_title', 'crsl_subtitle', 'crsl_image'];
}
