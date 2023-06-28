<?php

namespace App\Models\LoanWizard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDetail extends Model
{
    use HasFactory;


    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

}
