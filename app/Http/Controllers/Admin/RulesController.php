<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Categories\RuleCategory;
use App\Models\Rules\SystemRule;
use App\Models\Rules\SystemRuleAnswer;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RulesController extends Controller
{
    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function index()
    {
        $data=[];

        $data['rules'] = SystemRule::all();
        $data['categories'] = RuleCategory::all();
        if (count($data['categories'])) {
            foreach ($data['categories'] as $key => $value) {
                $data['categories'][$key]->subCategoriesList;
            }
        }
        return Inertia::render('Admin/Rules/Index', compact('data'));
    }

    public function action(Request $request)
    {

//        dd($request->all());
//        return ['test'];

        if($request['action']==='_rule_edit'){
            $data['form'] = DataStorage::dataByID($request['id'], config('system_config.models.system_rule'));
            $data['form']->rule_answer_type_name  =null;
            if(!is_null($data['form'])){
                if(!is_null($data['form']->ruleAnswer)){
                    $data['form']->rule_answer_type_name  = $data['form']->ruleAnswer->rule_answer_type_name;
                }
            }
            return ActionResponse::success('', $data);
        }
        if ($request['action'] === 'create' || $request['action'] === 'edit') {
            if ($request['action'] === 'edit') {
//                $rule_answer_type_id = null;
                $systemRuleArr = [
                    'rule_answer_type_id' => null,
                    'rule_name' => $request['rule_name'],
                    'cat_id' => $request['cat_id'],
                    'sub_cat_id' => $request['sub_cat_id'],
                    'rule_type' => $request['rule_type'],

                ];

                $save = CRUD::update($systemRuleArr, config('system_config.models.system_rule'), 'system_rules', 'id', $request['id']);
            }
            if ($request['action'] === 'create') {
                $validateRule = SystemRule::where('rule_name', $request['rule_name'])
                    ->where('cat_id', $request['cat_id'])
                    ->where('sub_cat_id', $request['sub_cat_id'])
                    ->where('rule_type', $request['rule_type'])->latest()->first();
                if (!is_null($validateRule)) {
                    $this->errorBag['rule_name'] = 'You can not have duplicate entries.';
                    return ActionResponse::error('You can not have duplicate entries.', $this->errorBag);
                }
                $save = CRUD::create($request->all(), config('system_config.models.system_rule'), 'system_rules');

            }

            if ($save['success']) {
                if ($request['rule_type'] === 'dropdown') {
                    $rule_answer_type_id = 0;
                    $validateRule = SystemRuleAnswer::where('rule_answer_type_name', $request['rule_answer_type_name'])->latest()->first();
                    if (is_null($validateRule)) {
                        $ansArr = [
                            'rule_answer_type_name' => $request['rule_answer_type_name']
                        ];
                        $create = CRUD::create($ansArr, config('system_config.models.system_rule_answer'), 'system_rule_answers');
                        if ($create['success']) {
                            $rule_answer_type_id = $create['data']->id;
                        }
                    } else {
                        $rule_answer_type_id = $validateRule->id;
                    }
                    $systemRuleArr = [
                        'rule_answer_type_id' => $rule_answer_type_id
                    ];
                    CRUD::update($systemRuleArr, config('system_config.models.system_rule'), 'system_rules', 'id', $save['data']->id);

                }
//                Enable,Disable,Refer Rule
                $data=[
                    'form'=>$save['data']
                ];
                $data['rules'] = SystemRule::all();
                $data['categories'] = RuleCategory::all();
                if (count($data['categories'])) {
                    foreach ($data['categories'] as $key => $value) {
                        $data['categories'][$key]->subCategoriesList;
                    }
                }
                return ActionResponse::success('Rules successfully created',$data);
            }

        }

        dd($request->all());
    }

}
