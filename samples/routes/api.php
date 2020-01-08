<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/test', function (Request $request) {
//     $client = new Client();
//     // return $client->get(url('api/elorest/Models/Quest'));
//     return $client->post(url('api/invitation'), ['form_params' => [
//         'email' => 'test1@redtech.co.id',
//         'user_id' => '1'
//     ]]);
// });

\Webcore\Elorest\Elorest::routes([
    'middleware' => ['auth:api', 'throttle:60,1'],
    // 'only' => ['post', 'put', 'patch', 'delete'],
    // 'except' => ['get']
]);

Route::post('/forgotPassword', function (Request $request) {
    $user = \App\Models\User::where('email', $request->email)->first();

    $token = md5(time());
    $check = false;
    // $user->forgot_token = md5(time());
    // $user->save();
    if($user) {
        $check = $user->update([
            'forgot_token' => $token
        ]);
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "User not found"
        ], 410))
            ->header('Content-Type', 'application/json');
    }

    if($check) {
        $data['url'] = 'http://localhost/resetPassword?token='.$token;

        \Mail::send('reset_mail', $data, function($message) use ($request) {
            // $message->to('dandisy.test1@gmail.com', 'Dandi Setiyawan')
            $message->to($request->email)
                ->subject('Reset Password - Clove Community Website');

            $message->from('clovecommunity@gmail.com','Clove Community');
        });

        if (\Mail::failures()) {
            // return response()->Fail('Sorry! Please try again latter');
            return response()->json([
                'status' => false,
                'message' => 'Sorry! Please try again latter'
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Yes, Your reset mail has been sent!'
            ]);
        }
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "Failed to process Forgot Password"
        ], 200))
            ->header('Content-Type', 'application/json');
    }
});

Route::post('/resetPassword', function (Request $request) {
    $user = \App\Models\User::where('forgot_token', $request->token)->first();

    $response = null;
    if($user) {
        $response = $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "User not found"
        ], 410))
            ->header('Content-Type', 'application/json');
    }

    if($response) {
        return response(json_encode([
            "status" => true,
            "message" => "Password has been reset",
            "data" => $response
        ], 210))
            ->header('Content-Type', 'application/json');
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "Failed to reset Password"
        ], 200))
            ->header('Content-Type', 'application/json');
    }
});

Route::middleware('auth:api')->put('/updatePassword', function (Request $request) {
    $user = $request->user();
    $user = \App\Models\User::find($user->id);

    $response = null;
    if($user) {
        if(strpos($user->email, 'google.')) { // added by dandisy, handling logged in user with google socialite
            return response(json_encode([
                "status" => false,
                "message" => "Failed to update Password"
            ], 200))
                ->header('Content-Type', 'application/json');
        } else {
            $response = $user->update([
                'password' => \Illuminate\Support\Facades\Hash::make($request->password)
            ]);
        }
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "User not found"
        ], 410))
            ->header('Content-Type', 'application/json');
    }

    if($response) {
        return response(json_encode([
            "status" => true,
            "message" => "Password has been updated",
            "data" => $response
        ], 210))
            ->header('Content-Type', 'application/json');
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "Failed to update Password"
        ], 200))
            ->header('Content-Type', 'application/json');
    }
});

Route::middleware('auth:api')->post('/send-notif', function (Request $request) {
    $user = $request->user();
    $user = \App\Models\User::select('name', 'email', 'full_name')->with('profile')->find($user->id);

    $users = [];
    $notif = null;
    $data = null;

    if($request->model_type == 'App\Models\Discussion') {
        $comments = \App\Models\Comment::with('user')
            ->where('model_type', 'App\Models\Discussion')
            ->where('model_id', $request->model_id)
            ->groupBy('created_by')
            ->get();

        foreach($comments as $comment) {
            if(!empty($comment->user->notif_token)) {
                array_push($users, $comment->user->notif_token);
            }
        }

        $notif = [
            "title" => "Clove Community",
            "body" => $user->name . ' has been commented'
        ];

        $data = [
            "message" => $request->data['message'],
            "created_by" => $user
        ];
    } else if($request->model_type == 'App\Models\Moment') {
        $comments = \App\Models\Comment::with('user')
            ->where('model_type', 'App\Models\Moment')
            ->where('model_id', $request->model_id)
            ->groupBy('created_by')
            ->get();

        foreach($comments as $comment) {
            if(!empty($comment->user->notif_token)) {
                array_push($users, $comment->user->notif_token);
            }
        }

        $notif = [
            "title" => "Clove Community",
            "body" => $user->name . ' has been commented'
        ];

        $data = [
            "message" => $request->data['message'],
            "created_by" => $user
        ];
    } else {
        $users = $request->notif_token;
        $notif = $request->notif;
        $data = $request->data;
    }

    if($users) {
        $fcm = new \App\Services\FCMService($users);

        return $fcm->sendNotif($notif, $data);
    } else {
        return json_encode('no user as receiver the notification');
    }
});

Route::middleware('auth:api')->post('/notif-token', function (Request $request) {
    $user = $request->user();
    $user = \App\Models\User::find($user->id);

    $data = null;
    if($user) {
        $data = $user->update([
            'notif_token' => $request->notif_token,
        ]);
    } else {
        return response(json_encode([
            "status" => false,
            "message" => "User not found",
            "data" => $request->all()
        ], 410))
            ->header('Content-Type', 'application/json');
    }
    // $user->notif_token = $request->notif_token;
    // $user->save();

    return response(json_encode([
        "status" => true,
        "message" => "User has been updated",
        "data" => $data
    ], 210))
        ->header('Content-Type', 'application/json');
});

Route::post('/register', function (Request $request) {
    // TODO : tambahkan point untuk user by reference_code yang berhasil mengajak org lain register
    if($request->reference_code) {
        $user = \App\Models\User::where('reference_code', $request->reference_code)->first();

        if($user) {
            // TODO : di sini koding http client untuk post history quest invite friend
            $client = new Client(); //GuzzleHttp\Client
            // $result = $client->post(url('api/histories'), [
            $client->post(url('api/histories'), [
                'form_params' => [
                    'model_type' => 'App\Models\Quest',
                    'model_id' => 3, // TODO : create config/setting for id of Invite Friend Quest
                    'user_id' => $user->id
                ]
            ]);

            // return response(json_encode([
            //     "status" => true,
            //     "message" => "Invite Friend Quest has been submit",
            //     "data" => $result
            // ], 200))
            //     ->header('Content-Type', 'application/json');
        } else {
            return response(json_encode([
                "status" => false,
                "message" => "User not found"
            ], 410))
                ->header('Content-Type', 'application/json');
        }
    }

    return User::create([
        'name' => $request->name,
        'email' => $request->email,
        'full_name' => $request->full_name,
        'reference_code' => substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6),
        'password' => Hash::make($request->password),
    ]);
});

// Route::middleware('auth:api')->post('invitation', function(Request $request) {
Route::middleware('auth:api')->post('invitation', function(Request $request) {
    $user = $request->user();

    $baseUrl = 'http://localhost';

    if(empty($user->id)) {
        return response(json_encode([
            "status" => false,
            "message" => "User not found"
        ], 410))
            ->header('Content-Type', 'application/json');
    }

    $momentInvitation = null;
    if($request->moment_id) {
        $momentInvitation = \App\Models\MomentInvitation::create([
            'moment_id' => $request->moment_id,
            'email' => $request->email,
            'created_by' => $user->id
        ]);
    }

    if($momentInvitation) {
        $data = array('url' => url($baseUrl.'challange/make-moment/details/'.$request->moment_id));
    } else {
        $data = array('url' => url($baseUrl.'welcome/'.$user->reference_code));
    }

    \Mail::send('invitation_mail', $data, function($message) use ($request) {
        // $message->to('dandisy.test1@gmail.com', 'Dandi Setiyawan')
        $message->to($request->email)
            ->subject('From Clove Community Website');

        $message->from('clovecommunity@gmail.com','Clove Community');
    });

    if (\Mail::failures()) {
        // return response()->Fail('Sorry! Please try again latter');
        return response()->json([
            'status' => false,
            'message' => 'Sorry! Please try again latter'
        ]);
    } else {
        return response()->json([
            'status' => true,
            'message' => 'Yes, Your mail has been sent!'
        ]);
    }
});

Route::middleware('auth:api')->post('upload', function(Request $request) {
    $user = $request->user();

    $dir = str_replace('./','',env('SAVE_PATH')).$user->id;

    if (!realpath('..'.DIRECTORY_SEPARATOR.$dir)) {
        mkdir('..'.DIRECTORY_SEPARATOR.$dir, 0777, true);
    }

    $dir = str_replace('/',DIRECTORY_SEPARATOR,$dir);
    $name = $user->id.'_'.$request->type.'_'.time().'.'.$request->extention;
    $path = $dir.DIRECTORY_SEPARATOR.$name;
    // $path = $dir.$name;
    $destinationPath = '..'.DIRECTORY_SEPARATOR.$dir;

    if($request->hasFile('file')) {
        if (realpath('..'.DIRECTORY_SEPARATOR.$path)) {
            return response(json_encode([
                "status" => false,
                "message" => "file already exist"
            ], 200))
                ->header('Content-Type', 'application/json');
        }

        $file = $request->file('file');
        $file->move($destinationPath, $name);

        if (realpath('..'.DIRECTORY_SEPARATOR.$path)) {
            return response([
                "status" => true,
                "message" => "file saved successfully",
                // "data" => str_replace(DIRECTORY_SEPARATOR,'/',str_replace('public'.DIRECTORY_SEPARATOR,'',$path))
                "data" => url('/').str_replace('storage/app/public','/storage',str_replace(DIRECTORY_SEPARATOR,'/',$path))
            ], 200)
                ->header('Content-Type', 'application/json');
        }
    } else {
        if($request->file) {
            $data = base64_decode($request->file);
            file_put_contents(str_replace('public'.DIRECTORY_SEPARATOR,'',$path),$data);
        }

        if (realpath('..'.DIRECTORY_SEPARATOR.$path)) {
            return response([
                "status" => true,
                "message" => "file saved successfully",
                // "data" => str_replace(DIRECTORY_SEPARATOR,'/',str_replace('public'.DIRECTORY_SEPARATOR,'',$path))
                "data" => url('/').str_replace('storage/app/public','/storage',str_replace(DIRECTORY_SEPARATOR,'/',$path))
            ], 200)
                ->header('Content-Type', 'application/json');
        }
    }

    return response(json_encode([
        "status" => false,
        "message" => "data input not valid"
    ], 200))
        ->header('Content-Type', 'application/json');
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
