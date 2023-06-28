<?php


namespace App\Models\System;


class DataStorage
{

    public static function data($key, $relationship, $parent)
    {
        $response = [];
        if (!is_null($parent)) {
            $userRelationship = [
                'relationships' => [
                    $key => $parent->$relationship,
                ]
            ];
            foreach ($userRelationship['relationships'] as $key => $relationship) {
                $response[$key] = false;
                if ($key !== 'user') {
                    if (!is_null($relationship)) {
                        $response[$key] = self::relationshipData($key, $relationship);
                    }
                }
            }
        }

        return $response;
    }

    public static function dataByID($id, $model)
    {
        $record = $model::find($id);
        if (is_null($record)) {
            return RequestEncrypt::decrypt($record);
        }

        return $record;
    }
    public static function dataByColumn($column,$value, $model)
    {
        $record = $model::where($column,$value)->latest()->first();
        if (!is_null($record)) {
            $record= RequestEncrypt::decrypt($record->toArray());
        }

        return $record;
    }

    public static function relationshipData($key, $object)
    {
        $response = [];
        if (!is_null($object)) {
            return $response[$key] = RequestEncrypt::decrypt($object->toArray());
        } else {
            return $response[$key] = null;
        }


    }

    public static function dataHasMany($key, $relationship, $parent)
    {

        $response = [];
        if (!is_null($parent)) {
            $userRelationship = [
                $key => $parent->$relationship,
            ];
            if (count($userRelationship[$key])) {
                foreach ($userRelationship[$key] as $k => $value) {
                    $response[$key][$k] = self::relationshipData($key, $value);
                }
            }

        }
        return $response;
    }

    public static function childRelationship($relationship, $mainKey, $childKey)
    {
        $response = [];
        if (count($relationship)) {
            foreach ($relationship as $key => $childRelationship) {
                $response[$mainKey][$key] = null;
                if (!is_null($childRelationship->$childKey)) {
                    $response[$mainKey][$key] = self::relationshipData($key, $childRelationship);
                }

            }
        }

        return $response;

    }
}
