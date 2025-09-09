<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SenTalk extends Model
{
    use HasFactory;

    // Tell Laravel the exact table name
    protected $table = 'sentalks';

    protected $fillable = [
        'pdf_path',
    'thumbnail_path',
        'title',
        'creator',
        'number_views',
        'number_likes',
        'number_downloads',
        'created_date',
        'created_time',
    ];
}
