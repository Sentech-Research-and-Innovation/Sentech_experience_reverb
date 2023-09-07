<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordCloud extends Model
{
    protected $fillable = ['text'];

    protected $table = "word_cloud";
}
