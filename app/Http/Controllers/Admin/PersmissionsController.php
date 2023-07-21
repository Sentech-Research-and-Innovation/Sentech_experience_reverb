<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermisionsResources;


class PersmissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return response()->json(new PermisionsResources($permissions));
    }
}
