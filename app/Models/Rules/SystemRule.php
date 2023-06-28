<?php


namespace App\Models\Rules;


use App\Models\Categories\RuleCategory;
use Illuminate\Database\Eloquent\Model;

class SystemRule extends Model
{
    public function ruleAnswer()
    {
        return self::HasOne(SystemRuleAnswer::class, 'id', 'rule_answer_type_id');
    }

    public static function buildRulesForm()
    {
        $data = [];
        if (count(RuleCategory::all())) {
            foreach (RuleCategory::all() as $key => $category) {
                $data[$key]['category'] = $category->toArray();
                $data[$key]['category']['rules'] = false;
                if ($category->sub_categories !== 0) {
                    if (count($category->subCategoriesList)) {
                        foreach ($category->subCategoriesList as $sc => $item) {
                            $data[$key]['category']['sub_category'][$sc] = $item;
                            $data[$key]['category']['sub_category'][$sc]['rules'] = $item->rules;
//                            $data[$key]['category']['sub_category'][$sc]['living_expenses'] = false;
                            if (count($item->rules)) {
                                foreach ($item->rules as $ir => $rule) {
                                    if ($rule->rule_type === 'living-expenses') {
                                        $livingExpense[] = $rule;
//                                        $data[$key]['category']['sub_category'][$sc]['living_expenses'] = $livingExpense;
                                        $data['living_expenses'] = $livingExpense;
                                        $data['living_expenses']['category'] = $item;

                                    }
                                    if ($rule->rule_type === 'dropdown') {
                                        if (!is_null($rule->ruleAnswer)) {
                                            $data[$key]['category']['sub_category'][$sc]['rules'][$ir]['dropdown_items'] =explode(',', $rule->ruleAnswer->rule_answer_type_name);
                                        }

                                    }

                                }
                            }
                        }
                    }

                } else {
                    $data[$key]['category']['sub_category'] = false;
                    $data[$key]['category']['rules'] = $category->rules;
                    if (count($category->rules)) {
                        foreach ($category->rules as $ir => $rule) {
                            if ($rule->rule_type === 'dropdown') {
                                if (!is_null($rule->ruleAnswer)) {
                                    $data[$key]['category']['rules'][$ir]['dropdown_items'] =explode(',', $rule->ruleAnswer->rule_answer_type_name);
                                }

                            }

                        }
                    }


                }

            }
        }
//        dd($livingExpense);
//        dd($data[6]['category']['sub_category'][17]);
        return $data;
    }
}
