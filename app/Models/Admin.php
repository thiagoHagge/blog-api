<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'auth_admin';
    protected $primaryKey = 'usr_id';
    public $timestamps = false;
    protected $fillable = ['usr_name', 'usr_pass','usr_token'];

}
