<?php


namespace App\Models\Company;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyStaff extends Model
{

    protected $table ='company_staff';

    public function staff(){
        return self::hasOne(User::class,'id','user_id');

    }

    public function role(){
        return self::hasOne(User::class,'id','user_id');

    }

}
