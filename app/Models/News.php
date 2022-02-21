<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'front_news';
    protected $primaryKey = 'news_id';
    protected $fillable = ['news_slug', 'news_title', 'news_content', 'news_image'];
    const CREATED_AT = 'news_creation';
    const UPDATED_AT = 'news_updated';
}
