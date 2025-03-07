<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'content',
        'title',
        'slug',
        'category',
        'image',
    ];
}
