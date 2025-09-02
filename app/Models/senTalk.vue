<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SenTalk extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'pdf_path',
        'number_views',
        'number_downloads',
        'number_likes',
    ];
}
