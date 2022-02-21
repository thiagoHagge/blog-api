<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'front_pages';
    protected $primaryKey = 'pg_id';
    public $timestamps = false;
    // protected $keyType = 'string';
    protected $fillable = ['pg_name', 'pg_link', 'pg_parent', 'pg_order', 'pg_content'];
}
