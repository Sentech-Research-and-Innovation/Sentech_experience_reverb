<?php


namespace App\Models\Categories;


use App\Models\Rules\SystemRule;
use Illuminate\Database\Eloquent\Model;

class RuleSubCategory extends  Model
{

    public function rules(){
        return self::HasMany(SystemRule::class,'sub_cat_id','id');
    }
}
