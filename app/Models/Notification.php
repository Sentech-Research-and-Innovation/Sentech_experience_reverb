<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }
    protected $casts = [
        'model_ids' => 'json'
    ];

    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class);
    }
}
