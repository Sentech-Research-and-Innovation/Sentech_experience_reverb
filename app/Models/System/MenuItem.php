<?php


namespace App\Models\System;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    protected $fillable = ['menu_id', 'title', 'url', 'target', 'icon_class', 'parent_id', 'active'];

    public static function pages()
    {
        $routes = self::routes();
        $routesArray = [];
        foreach ($routes as $key => $value) {
            $routesArray[$key] = Route::get('/{' . $value['Parent'] . '}', 'App\Http\Controllers\Web\PublicController@page')->name($value['Parent'])->defaults($value['Parent'], $value['Parent']);
        }
        $subRoutes = self::buildSubRoutes();
        return array_merge($subRoutes, $routesArray);
    }

    public static function inertia()
    {
        $routes = self::menu();
        $routesArray = [];
        if (count($routes)) {
            foreach ($routes as $key => $value) {
                $controller = $value['Parent']['controller'];
                $function = $value['Parent']['function'];


//                Admin Get
                if ($value['Parent']['web'] == 0 && $value['Parent']['method'] == 1) {
                    if ($value['Parent']['type'] == 2) {
                        Route::get('/' . $value['Parent']['url'] . '', 'App\Http\Controllers\Admin\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }
                }

//                Admin Post
                if ($value['Parent']['web'] == 0 && $value['Parent']['method'] == 0) {
                    if ($value['Parent']['type'] == 7) {
                        Route::post('/' . $value['Parent']['url'] . '', 'App\Http\Controllers\Admin\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }
                }

                if ($value['Parent']['web'] == 1 && $value['Parent']['method'] == 1) {
                    if ($value['Parent']['type'] == 1) {
                        $routesArray[$key] = Route::get($value['Parent']['url'], function () use ($value) {
                            return Inertia::render($value['Parent']['component']);
                        })->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])->name($value['Parent']['name']);
                    }

                    if ($value['Parent']['type'] == 2) {

                        Route::get('/' . $value['Parent']['url'] . '', 'App\Http\Controllers\Web\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }

                    if ($value['Parent']['type'] == 3) {
                        $controller = $value['Parent']['controller'];
                        $function = $value['Parent']['function'];
                        $parameters = json_decode($value['Parent']['parameters']);
                        $parameters = (array)$parameters;
                        $paramString = '';
                        if (!is_null($parameters)) {
                            if (count($parameters)) {
                                foreach ($parameters as $k => $parameter) {
                                    $paramString .= '{' . $parameter . '}/';
                                }
                            }
                        }
                        $paramString = substr($paramString, 0, -1);
                        Route::get('/' . $value['Parent']['url'] . '/' . $paramString, 'App\Http\Controllers\Web\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }
                    if ($value['Parent']['type'] == 4) {
                        $controller = $value['Parent']['controller'];
                        $function = $value['Parent']['function'];
                        Route::get('/' . $value['Parent']['url'] . '', 'App\Http\Controllers\Auth\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }


                }
                if (count($value['Child'])) {
                    foreach ($value['Child'] as $c => $child) {
                        if ($child['web'] == 1 && $child['method']===1) {
                            if ($child['type'] == 1) {
                                $routesArray[$key] = Route::get($child['url'], function () use ($child) {
                                    return Inertia::render($child['component']);
                                })->middleware([$child['middleware_type'], $child['middleware_name']])->name($child['name']);
                            }
                            if ($child['type'] == 2) {
                                $controller = $child['controller'];
                                $function = $child['function'];
                                Route::get('/' . $child['url'] . '', 'App\Http\Controllers\Web\\' . $controller . '@' . $function)
                                    ->middleware([$child['middleware_type'], $child['middleware_name']])
                                    ->name($child['name'])
                                    ->defaults($child['name'], $child['name']);
                            }
                            if ($child['type'] == 3) {
                                $controller = $child['controller'];
                                $function = $child['function'];
                                $parameters = json_decode($child['parameters']);
                                $parameters = (array)$parameters;
                                $paramString = '';
                                if (!is_null($parameters)) {
                                    if (count($parameters)) {
                                        foreach ($parameters as $k => $parameter) {
                                            $paramString .= '{' . $parameter . '}/';
                                        }
                                    }
                                }
                                $paramString = substr($paramString, 0, -1);

                                Route::get('/' . $child['url'] . '/' . $paramString, 'App\Http\Controllers\Web\\' . $controller . '@' . $function)
                                    ->middleware([$child['middleware_type'], $child['middleware_name']])
                                    ->name($child['name'])
                                    ->defaults($child['name'], $child['name']);
                            }
                        }
                    }
                }

                if ($value['Parent']['web'] == 1 && $value['Parent']['method'] == 0) {
                    if ($value['Parent']['type'] == 5) {
                        Route::post('/' . $value['Parent']['url'] . '', 'App\Http\Controllers\Auth\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }
                    if ($value['Parent']['type'] == 6) {
                        Route::post('/' . $value['Parent']['url'] . '', 'App\Http\Controllers\Web\\' . $controller . '@' . $function)
                            ->middleware([$value['Parent']['middleware_type'], $value['Parent']['middleware_name']])
                            ->name($value['Parent']['name'])
                            ->defaults($value['Parent']['name'], $value['Parent']['name']);
                    }



                }

            }
        }
        return $routesArray;
    }

    public static function buildSubRoutes()
    {
        $routes = self::routes();
        $routesArray = [];
        foreach ($routes as $key => $value) {
            if (isset($routes[$key]['Child']))
                if (count($routes[$key]['Child']) > 0) {
                    foreach ($routes[$key]['Child'] as $k => $child) {
                        if ($child != '')
                            $routesArray[$k] = Route::get('/' . $child . '', 'App\Http\Controllers\Web\PublicController@page')->name($child)->defaults($child, $child);
                    }

                }
        }

        return $routesArray;
    }

    public static function routes()
    {

        $routes = [];
        $menu = self::menu();

        if (count($menu) > 0) {
            foreach ($menu as $key => $value) {
                $routes[$key]['Parent'] = $value['Parent']['url'];
                if (count($menu[$key]['Child']) > 0) {
                    foreach ($menu[$key]['Child'] as $k => $child) {
                        $routes[$key]['Child'][$k] = $child['url'];
                    }
                }
            }
        }
        return $routes;
    }

    public static function menu()
    {
        $data = [];
        $parentMenuItems = MenuItem::where('parent_id', 0)->where('menu_id', 1)->get();
        if (!is_null($parentMenuItems)) {
            foreach ($parentMenuItems as $key => $value) {
                $data[$value->id]['Parent'] = $value->toArray();
                $data[$value->id]['Child'] = [];
                $child[$value->id] = MenuItem::where('menu_id', 1)->where('parent_id', $value->id)->get();
                if (count($child[$value->id])) {
                    $data[$value->id]['Child'] = $child[$value->id]->toArray();
                }
            }
        }
        if (count($data) > 0) {
            foreach ($data as $key => $value) {

                unset($data[$key]['Parent']['id']);
                unset($data[$key]['Parent']['menu_id']);
                unset($data[$key]['Parent']['parent_id']);
                unset($data[$key]['Parent']['created_at']);
                unset($data[$key]['Parent']['updated_at']);
                $data[$key]['Parent']['name'] = strtolower(str_replace(' ', '', $value['Parent']['title']));
//                $data[$key]['Parent']['component'] =strtolower(str_replace(' ','',$value['Parent']['component']));
                if (count($data[$key]['Child']) > 0) {
                    foreach ($data[$key]['Child'] as $child => $c) {
                        $data[$key]['Child'][$child]['name'] = strtolower(str_replace(' ', '', $c['title']));
//                        $data[$key]['Child'][$child]['component'] =strtolower(str_replace(' ','',$c['title']));
                        unset($data[$key]['Child'][$child]['id']);
                        unset($data[$key]['Child'][$child]['menu_id']);
                        unset($data[$key]['Child'][$child]['parent_id']);
                        unset($data[$key]['Child'][$child]['created_at']);
                        unset($data[$key]['Child'][$child]['updated_at']);
                    }
                }
            }
        }
//        dd($data);
        $data = array_values($data);

        return $data;
    }

    public function countSubItems($parent_item_id)
    {
        return DB::table('menu_items')->where('parent_id', $parent_item_id)->count();
    }
}
