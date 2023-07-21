<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class PermisionsResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permissiongroup = [];

        foreach ($this->resource as $perm) {

            $parts = explode('-', $perm->name);
            $groupName = $parts[0];
            $label = $parts[1];

            $permissiongroup[] = [

                'id' => $perm->id,
                'name' => $perm->name,
                'label' => ucwords($label),
                'groupName' =>   ucwords($groupName)
            ];
        }

        return $permissiongroup;
    }
}
