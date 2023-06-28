<?php


namespace App\Http\Controllers\Admin;


use App\Models\Categories\RuleCategory;
use App\Models\Categories\RuleSubCategory;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\System\DataValidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriesController extends Model
{

    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function index()
    {
        $data['categories'] = RuleCategory::all();
        return Inertia::render('Admin/Categories/Index', compact('data'));
    }

    public function action(Request $request)
    {


        if ($request['action'] === 'create' || $request['action'] === 'edit') {

            $validateArr = [
                'rule_category_name' => $request['rule_category_name']
            ];

            $this->errorBag = (new DataValidation)->required($validateArr);

            if (count($this->errorBag)) {
                $this->errorBag['rule_category_name'] = 'Category name can not be empty';
                return ActionResponse::error('Category name can not be empty', $this->errorBag);
            }


            if ($request['action'] === 'create') {
                $validateCat = RuleCategory::where('rule_category_name', $request['rule_category_name'])->latest()->first();
                if (!is_null($validateCat)) {
                    $this->errorBag['rule_category_name'] = 'You can not have duplicate entries.';
                    return ActionResponse::error('You can not have duplicate entries.', $this->errorBag);
                }
                $saveCat = CRUD::create($request->all(), config('system_config.models.rule_category'), 'rule_categories');
            }
            if ($request['action'] === 'edit') {
                $saveCat = CRUD::update($request->all(), config('system_config.models.rule_category'), 'rule_categories', 'id', $request['id']);
            }

            if ($saveCat['success']) {
                $data['form'] = $saveCat['data'];
                $data['form']->subCategoriesList;
                return ActionResponse::success('', $data);
            } else {
                return $saveCat;
            }

        }
        if ($request['action'] === '_create_sub_category_existing') {
            $validateArr = [
                'sub_category_name' => $request['sub_category_name']
            ];

            $this->errorBag = (new DataValidation)->required($validateArr);

            if (count($this->errorBag)) {
                $this->errorBag['sub_category_name'] = 'Sub category name can not be empty';
                return ActionResponse::error('Sub category name can not be empty', $this->errorBag);
            }

            $validateSubCat = RuleSubCategory::where('cat_id', $request['cat_id'])->where('sub_category_name', $request['sub_category_name'])->latest()->first();
            if (is_null($validateSubCat)) {

                $save = CRUD::create($request->all(), config('system_config.models.rule_sub_category'), 'rule_sub_categories');
                if ($save['success']) {

                    CRUD::update(['sub_categories' => 1], config('system_config.models.rule_category'), 'rule_categories', 'id', $request['cat_id']);
                    $data['form'] = DataStorage::dataByID($request['cat_id'], config('system_config.models.rule_category'));
                    $data['form']->subCategoriesList;
                    return ActionResponse::success('', $data);
                } else {
                    return $save;
                }

            }
            $this->errorBag['sub_category_name'] = 'You can not have duplicate entries.';
            return ActionResponse::error('You can not have duplicate entries.', $this->errorBag);
        }
        if ($request['action'] === 'delete_sub_cat') {
            $deleteArr = [
                'id' => $request['id']
            ];

            (new CRUD)->delete($deleteArr, 'rule_sub_categories');
            $data['form'] = DataStorage::dataByID($request['cat_id'], config('system_config.models.rule_category'));
            $data['form']->subCategoriesList;
            return ActionResponse::success('', $data);
        }
        if ($request['action'] === '_category_edit') {
            $data['form'] = DataStorage::dataByID($request['id'], config('system_config.models.rule_category'));
            $data['form']->subCategoriesList;
            return ActionResponse::success('', $data);

        }
    }
}
