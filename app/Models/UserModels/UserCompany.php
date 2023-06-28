<?php


namespace App\Models\UserModels;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserCompany extends Model
{

    public function role(){
        return $this->hasOne(UserRole::class, 'user_id', 'user_id');
    }

    public function owner(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
