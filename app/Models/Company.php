<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['company_name', 'contact_person_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'company_id');
    }

    public function activity()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
