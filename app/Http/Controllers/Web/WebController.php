<?php


namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\System\RouteInformation;
use App\Models\System\SFTPConn;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class  WebController extends  Controller
{

    public  function  index(){
        return Inertia::render('Web/Index');
    }

    public  function  test($id){

        $parameters = [
                'id',
                'name'
            ];
        dd(json_encode($parameters));
        dd('Hello');
    }
    public  function terms(){
        return Inertia::render('User/Terms');
    }
    public  function privacy(){
        return Inertia::render('User/Privacy');
    }
}
