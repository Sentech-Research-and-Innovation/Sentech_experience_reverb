<?php

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'contact_person_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person_id');
    }
}
