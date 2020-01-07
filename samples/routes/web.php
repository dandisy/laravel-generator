<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fcm-test', function () {
    return view('fcm_test');
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => 3,
        'redirect_uri' => 'http://localhost/gamify/public/auth/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect(url('oauth/authorize?'.$query));
});

Route::get('auth/callback', function(Request $request) {
    echo $request->code;
});

// Route::get('/callback', function (Request $request) {
//     $http = new GuzzleHttp\Client;

//     $response = $http->post('http://your-app.com/oauth/token', [
//         'form_params' => [
//             'grant_type' => 'authorization_code',
//             'client_id' => 'client-id',
//             'client_secret' => 'client-secret',
//             'redirect_uri' => 'http://example.com/callback',
//             'code' => $request->code,
//         ],
//     ]);

//     return json_decode((string) $response->getBody(), true);
// });

Route::get('api-docs', function() {
    // return view('api_docs');
    return view('api_docs.index');
});

Route::get('/dx-datagrid-test', function() {
    return view('dx_datagrid_server_process');

    // try {
    //     file_get_contents('test.php');
    // } catch(Exception $e) {
    //     dd($e);
    // }
    // $data = '{
    //     "5":{"name":"John","email":"JD@stackoverflow.com"},
    //     "6":{"name":"Ken","email":"Ken@stackoverflow.com"}
    //   }';
    // $object = (array)json_decode($data);
    // $collection = \App\User::hydrate($object);

    // dd($collection);
});

Route::get('dxDatagrid/{model}', function($model, Request $request) {
    $res['info'] = [];
    $res['info']['request'] = $request->all();

    $modelNS = '\App\Models\\'.$model;
    $data = new $modelNS();

    if($request->filter) {
        $filter = json_decode($request->filter);

        // $res['info']['filter'] = $filter;

        if($filter[1] == 'and' || $filter[1] == 'or') {
            if($filter[1] == 'and') {
                if($filter[2][1] == 'or') {
                    if($filter[0][1] == 'and') {
                        foreach($filter[0] as $k0 => $f0) {
                            if($k0 % 2 == 0) {
                                if($f0[1] == 'contains') {
                                    $f0[1] = 'like';
                                    $f0[2] = '%'.$f0[2].'%';
                                }

                                $data = $data->where($f0[0], $f0[1], $f0[2]);
                            }
                        }
                    } else {
                        if($filter[0][1] == 'contains') {
                            $filter[0][1] = 'like';
                            $filter[0][2] = '%'.$filter[0][2].'%';
                        }

                        $data = $data->where($filter[0][0], $filter[0][1], $filter[0][2]);
                    }

                    $data = $data->where(function($query) use ($filter) {
                        foreach($filter[2] as $k2 => $f2) {
                            if($k2 % 2 == 0) {
                                if($f2[1] == 'contains') {
                                    $f2[1] = 'like';
                                    $f2[2] = '%'.$f2[2].'%';
                                }

                                $query = $query->orWhere($f2[0], $f2[1], $f2[2]);
                            }
                        }
                    });
                } else {
                    foreach($filter as $k1 => $f1) {
                        if($k1 % 2 == 0) {
                            if($f1[1] == 'contains') {
                                $f1[1] = 'like';
                                $f1[2] = '%'.$f1[2].'%';
                            }

                            $data = $data->where($f1[0], $f1[1], $f1[2]);
                        }
                    }
                }
            }
            if($filter[1] == 'or') {
                foreach($filter as $k1 => $f1) {
                    if($k1 % 2 == 0) {
                        if($f1[1] == 'contains') {
                            $f1[1] = 'like';
                            $f1[2] = '%'.$f1[2].'%';
                        }

                        $data = $data->orWhere($f1[0], $f1[1], $f1[2]);
                    }
                }
            }
        } else {
            if($filter[1] == 'contains') {
                $filter[1] = 'like';
                $filter[2] = '%'.$filter[2].'%';
            }

            $data = $data->where($filter[0], $filter[1], $filter[2]);
        }
    }

    // TODO : cek logic untuk yang custom summary
    if($request->totalSummary) {
        $res['summary'] = [];
        $summaries = json_decode($request->totalSummary);

        foreach($summaries as $summary) {
            $cmd = $summary->summaryType;
            array_push($res['summary'], $data->$cmd($summary->selector));
        }
    }

    if($request->requireTotalCount == 'true') {
        $res['totalCount'] = $data->count();
    }

    if($request->group) {
        $resData = [];
        $groups = json_decode($request->group);

        // TODO : cek lagi apakah group selalu array count = 1 atau bisa lebih dari 1 sehingga butuh foreach
        $gData0 = $data->groupBy($groups[0]->selector);

        if($request->requireGroupCount == 'true') {
            $res['groupCount'] = count($gData0->get()->toArray());
        }

        if($groups[0]->desc) {
            $gData0 = $gData0->orderBy($groups[0]->selector, 'desc');
        } else {
            $gData0 = $gData0->orderBy($groups[0]->selector);
        }

        $res['info']['sql'] = $gData0->toSql();

        // group inner data
        $gData0 = $gData0->get();
        foreach($gData0 as $key => $item) {
            $resData[$key] = [];
            $resData[$key]['key'] = $item->{$groups[0]->selector};

            $inGroupData = $data->where($groups[0]->selector, $item->{$groups[0]->selector});

            if($request->sort) {
                $sorts = json_decode($request->sort);

                // data yang di group by kemudian di sort, array sort-nya bisa lebih dari 1 sehingga butuh foreach
                foreach($sorts as $sort) {
                    if(isset($sort->isExpanded)) {
                        // TODO : terjadi ketika kolom sevagai group by di sort
                        if($sort->isExpanded) {
                            //
                        }
                    }

                    if($sort->desc) {
                        $inGroupData = $inGroupData->orderBy($sort->selector, 'desc');
                    } else {
                        $inGroupData = $inGroupData->orderBy($sort->selector);
                    }
                }
            }

            if($groups[0]->isExpanded == 'true') {
                $resData[$key]['items'] = $inGroupData->get();
            } else {
                $resData[$key]['items'] = null;
            }

            $resData[$key]['count'] =  $inGroupData->count();

            // TODO : cek hasilnya apakah sudah OK, dan cek logic untuk yang custom summary
            if($request->groupSummary) {
                $resData[$key]['summary'] = [];
                $gSummaries = json_decode($request->groupSummary);

                foreach($gSummaries as $gSummary) {
                    $gCmd = $gSummary->summaryType;
                    array_push($resData[$key]['summary'], $inGroupData->$gCmd($gSummary->selector));
                }
            }
        }

        // group header data
        $resData = collect($resData);
        if($request->skip) {
            $resData = $resData->slice($request->skip);
        }
        $res['data'] = $resData->take($request->take);
    } else {
        if($request->sort) {
            $sorts = json_decode($request->sort);

            // TODO : cek apakah sort pada data yang tidak di grouping selalu array count = 1 atau bisa lebih dari 1 sehingga butuh foreach
            foreach($sorts as $sort) {
                if(isset($sort->isExpanded)) {
                    // TODO : terjadi ketika kolom sevagai group by di sort
                    if($sort->isExpanded) {
                        //
                    }
                }

                if($sort->desc) {
                    $data = $data->orderBy($sort->selector, 'desc');
                } else {
                    $data = $data->orderBy($sort->selector);
                }
            }
        }

        if($request->skip) {
            $data = $data->skip($request->skip);
        }
        if($request->take) {
            $data = $data->take($request->take);
        }

        $res['info']['sql'] = $data->toSql();

        $res['data'] = $data->get();
    }

    return $res;

    // return response(json_encode($res, 200))
    //     ->header('Content-Type', 'application/json');
});


Auth::routes();


Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/check-env', function(Request $request) {
        dd(env($request->constant));
    });

    Route::get('log-files', function() {
        if (!file_exists(storage_path('logs'))) {
            return [];
        }

        $logFiles = \File::allFiles(storage_path('logs'));

        // Sort files by modified time DESC
        usort($logFiles, function ($a, $b) {
            return -1 * strcmp($a->getMTime(), $b->getMTime());
        });

        return view('log_files', compact('logFiles'));
    })->name('log-files.index');

    Route::get('log-files/{fileName}', function($fileName) {
        if (file_exists(storage_path('logs/'.$fileName))) {
            $path = storage_path('logs/'.$fileName);

            return response()->file($path, ['content-type' => 'text/plain']);
        }

        return 'Invalid file name.';
    })->name('log-files.show');

    Route::get('log-files/{fileName}/download', function($fileName) {
        if (file_exists(storage_path('logs/'.$fileName))) {
            $path = storage_path('logs/'.$fileName);
            $downloadFileName = env('APP_ENV').'.'.$fileName;

            return response()->download($path, $downloadFileName);
        }

        return 'Invalid file name.';
    })->name('log-files.download');

    Route::get('/artisan', function(Request $request) {
        $cmd = $request->cmd;
        $params = $request->all();
        unset($params['cmd']);
        Artisan::call($cmd, $params ? : []);
        return Artisan::output();
    });

    Route::get('pdf', function(Request $request){
        $data = ['some data from eloquent query'];

        $pdf = PDF::loadView('print', $data);
        return $pdf->download('print.pdf');
    });
});

Route::get('/img/{path}', function(Filesystem $filesystem, $path) {
    $server = ServerFactory::create([
        'response' => new LaravelResponseFactory(app('request')),
        'source' => $filesystem->getDriver(),
        'cache' => $filesystem->getDriver(),
        'cache_path_prefix' => '.cache',
        'base_url' => 'img',
    ]);

    return $server->getImageResponse($path, request()->all());
})->where('path', '.*');


Route::middleware(['can:admin'])->group(function () {
    Route::resource('users', 'UserController');
    // Route::post('importUser', 'UserController@import');

    Route::resource('roles', 'RoleController');
    // Route::post('importRole', 'RoleController@import');

    Route::resource('permissions', 'PermissionController');
    // Route::post('importPermission', 'PermissionController@import');

    Route::resource('settings', 'SettingController');
    // Route::post('importSetting', 'SettingController@import');

    Route::resource('profiles', 'ProfileController');
    // Route::post('importProfile', 'ProfileController@import');

    Route::get('/home', 'HomeController@index')->name('home');
});
