<?php


namespace App\Models\System;


//use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CRUD
{
    protected  $rawQuery;

    public  function __construct()
    {
        $this->rawQuery =null;

    }

    public static function createUpdate($request, $model, $table, $search_by, $search_value)
    {


        if (class_exists($model)) {


            if ($search_by === 'id') {
                $record = $model::find($search_value);
            } else {
                $record = $model::where($search_by, $search_value)->first();
            }
            if (is_null($record)) {
                $record = new $model();
            }

            if (isset($request['id'])) {
                unset($request['id']);
            }
            if (count($request)) {
                foreach ($request as $key => $value) {
                    if ($key === 'password') {
                        $value = Hash::make($value);
                    }

                    if (Schema::hasColumn($table, $key)) {
                        $record->$key = $value;
                    }


                }

                if ($record->save()) {
                    return ActionResponse::success('Record successfully created', $record);
                }
            }
            return ActionResponse::error('An error occurred, please try again or contact system administrator', []);
        } else {
            return ActionResponse::error('Could not find class ' . $model . ' please make sure the class exists', []);
        }

    }

    public static function create($request, $model, $table): array
    {
        if (class_exists($model)) {
            $record = new $model();
            if (count($request)) {
                foreach ($request as $key => $value) {
                    if ($key === 'password') {
                        $value = Hash::make($value);
                    }
                    if (Schema::hasColumn($table, $key)) {
                        $record->$key = $value;
                    }

                }
            }

            if ($record->save()) {
                return ActionResponse::success('Record successfully created', $record);
            }
            return ActionResponse::error('An error occurred, please try again or contact system administrator', []);
        } else {
            return ActionResponse::error('Could not find class ' . $model . ' please make sure the class exists', []);
        }
    }

    public static function update($request, $model, $table, $search_by, $search_value): array
    {
        if (class_exists($model)) {


            if ($search_by === 'id') {
                $record = $model::find($search_value);
            } else {
                $record = $model::where($search_by, $search_value)->first();
            }

            if (!is_null($record)) {
                if (count($request)) {
                    foreach ($request as $key => $value) {
                        if ($key === 'password') {
                            $value = Hash::make($value);
                        }

                        if (Schema::hasColumn($table, $key)) {
                            $record->$key = $value;
                        }

                    }
                }
                if ($record->save()) {
                    return ActionResponse::success('Record successfully created', $record);
                }
            }
            return ActionResponse::error('An error occurred, please try again or contact system administrator', []);
        } else {
            return ActionResponse::error('Could not find class ' . $model . ' please make sure the class exists', []);
        }
    }

    public function delete($values,$table)
    {
            if(is_array($values)){
                $this->rawQuery ='DELETE FROM '.$table. ' WHERE ';
                foreach ($values as $key => $value){

                    $this->rawQuery .= $key.'='."$value".' AND ';
                }
                $this->rawQuery = substr($this->rawQuery, 0, -4);
                $delete=DB::select($this->rawQuery);
                return ActionResponse::success('Record successfully deleted', $delete);
            }


        return ActionResponse::success('Record successfully created', []);

    }

    public static function validate($column_name, $model, $value): array
    {
        if (class_exists($model)) {
            $record = $model::where($column_name, $value)->first();
            if (is_null($record)) {
                return ActionResponse::error('Record does not exist', $record);
            } else {
                return ActionResponse::success('Record exists', $record);
            }
        }
        return ActionResponse::error('Could not find class ' . $model . ' please make sure the class exists', []);
    }

    public static function validateMany($column_name, $model, $id): array
    {
        if (class_exists($model)) {
            $record = $model::where($column_name, $id)->get();
            if (is_null($record)) {
                return ActionResponse::error('Record does not exist', $record);
            } else {
                return ActionResponse::success('Record exists', $record);
            }
        }
    }

    public static function generateEmail($id, $country): string
    {
        if (!is_null($id)) {
            return $id . '@' . $country . '.user';
        }
        return null;
    }

    public static function getID($email): string
    {
        $id = explode('@', $email);
        $id = $id[0];
        return $id;
    }

    public static function getCountry($email): string
    {
        $country = explode('.', $email);
        $country = explode('@', $country[0]);
        $country = $country[1];
        return $country;
    }
}
