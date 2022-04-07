<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'front_contact';
    protected $fillable = [
        'phone', 
        'email', 
        'whatsapp', 
        'whatsappMessage', 
        'facebook', 
        'instagram',
        'spotify',
        'youtube'
    ];
    public $timestamps = false;
}
