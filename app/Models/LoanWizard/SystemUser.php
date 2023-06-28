<?php


namespace App\Models\LoanWizard;


use App\Models\Address\Address;
use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model
{

    public function personalDetails(){
        return self::HasOne(PersonalDetail::class,'user_id','id');
    }
    public function address(){
        return $this->hasOne(Address::class, 'user_id');
    }


}

