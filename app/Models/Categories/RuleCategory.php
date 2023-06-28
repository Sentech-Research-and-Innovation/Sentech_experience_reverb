<?php


namespace App\Models\Categories;


use App\Models\Rules\SystemRule;
use Illuminate\Database\Eloquent\Model;

class RuleCategory extends Model
{

    public function subCategoriesList(){
            return self::HasMany(RuleSubCategory::class,'cat_id','id');
    }
    public function rules(){
        return self::HasMany(SystemRule::class,'cat_id','id');
    }
}
