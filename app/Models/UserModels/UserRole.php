<?php


namespace App\Models\UserModels;



use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{

    public function owner(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
