<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Models\ACS\Token;
use App\Models\ACS\User;
use App\Models\HIS\UserRoom;
use Illuminate\Support\Facades\Request;

function create_slug($string)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', ' ', $string);
        $string = strtolower($string);
        return $string;
    }


if (!function_exists('get_user_with_loginname')) {
    function get_user_with_loginname($loginname)
    {
        $user = Cache::remember('user_' . $loginname, now()->addMinutes(1440), function () use ($loginname) {
            return User::select()->where("loginname", $loginname)->first();
        });
        return $user;
    }
}

if (!function_exists('get_token_header')) {
    function get_token_header($request, $token_header)
    {
        $token = Cache::remember('token_' . $token_header, now()->addMinutes(1440), function () use ($token_header) {
            return Token::where("token_code", $token_header)->first();
        });
        return $token;
    }
}

if (!function_exists('get_cache')) {
    function get_cache($model, $name, $id = null, $time)
    {
        if (!$id) {
            $data = Cache::remember($name, $time, function () use ($model) {
                return $model->all();
            });
            return $data;
        } else {
            if (!is_numeric($id)) {
                return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
            }
            $data = Cache::remember($name . '_id_' . $id, $time, function () use ($model, $id) {
                return $model->find($id);
            });
            // Xóa Cache nếu không có dữ liệu
            if (!$data) Cache::forget($name . '_id_' . $id);
            return $data;
        }
    }
}
    function get_cache($model, $name, $id = null, $time, $start, $limit)
    {
        if (!$id) {
            $data = Cache::remember($name, $time, function () use ($model, $start, $limit) {
                $data = $model;
                $count = $data->count();
                $data = $data    
                    ->skip($start)
                    ->take($limit)
                    ->get();
            return ['data' => $data, 'count' => $count];
            });
            return $data;
        } else {
            if (!is_numeric($id)) {
                return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
            }
            $data = Cache::remember($name . '_id_' . $id, $time, function () use ($model, $id) {
                return $model->find($id);
            });
            // Xóa Cache nếu không có dữ liệu
            if (!$data) Cache::forget($name . '_id_' . $id);
            return $data;
        }
    }


if (!function_exists('get_cache_full')) {
    // function get_cache_full($model, $relation_ship, $name, $id = null, $time)
    // {
    //     if (!$id) {
    //         $data = Cache::remember($name, $time, function () use ($model, $relation_ship) {
    //             return $model::with($relation_ship)->get();
    //         });
    //         return $data;
    //     } else {
    //         if($id == 'deleted'){
    //             $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id) {
    //                 return $model::withDeleted()->with($relation_ship)->get();
    //             });
    //         }
    //         else{
    //             $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id) {
    //                 return $model::where('id', $id)->with($relation_ship)->first();
    //             });
    //         }
    //         return $data;
    //     }
    // }
    function get_cache_full($model, $relation_ship, $name, $id = null, $time, $start, $limit)
{
    if (!$id) {
        $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $start, $limit) {
            $data = $model::with($relation_ship);
            $count = $data->count();
            $data = $data    
                ->skip($start)
                ->take($limit)
                ->get();
        return ['data' => $data, 'count' => $count];
        });
        return $data;
    } else {
        if($id == 'deleted'){
            $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id) {
                return $model::withDeleted()->with($relation_ship)->get();
            });
        }
        else{
            $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id) {
                return $model::where('id', $id)->with($relation_ship)->first();
            });
        }
        return $data;
    }
}
}



if (!function_exists('get_cache_full_select')) {
    // function get_cache_full_select($model, $relation_ship, $select, $name, $id = null, $time)
    // {
    //     if (!$id) {
    //         $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $select) {
    //             return $model::select($select)->with($relation_ship)->get();
    //         });
    //         return $data;
    //     } else {
    //         $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id, $select) {
    //             return $model::select($select)->where('id', $id)->with($relation_ship)->get();
    //         });
    //         return $data;
    //     }
    // }
    function get_cache_full_select($model, $relation_ship, $select, $name, $id = null, $time, $start, $limit)
    {
        if (!$id) {
            $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $select, $start, $limit) {
                $data=  $model::select($select)->with($relation_ship);
                
                $count = $data->count();
                $data = $data    
                    ->skip($start)
                    ->take($limit)
                    ->get();
            return ['data' => $data, 'count' => $count];
            });
        } else {
            $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id, $select) {
                return $model::select($select)->where('id', $id)->with($relation_ship)->get();
            });
        }
        return $data;
    }
}

if (!function_exists('get_cache_full_select_paginate')) {
    function get_cache_full_select_paginate($model, $relation_ship, $per_page, $select, $name, $id = null, $time)
    {
        if (!$id) {
            $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $select, $per_page) {
                return $model->with($relation_ship)->paginate($per_page);
            });
            return $data;
        } else {
            $data = Cache::remember($name, $time, function () use ($model, $relation_ship, $id, $select) {
                return $model::select($select)->where('id', $id)->with($relation_ship)->get();
            });
            return $data;
        }
    }
}

if (!function_exists('update_cache')) {
    function update_cache($name, $data_update, $time)
    {
        $data = Cache::remember($name, $time, function () use ($data_update) {
            return $data_update;
        });
        return $data;
    }
}

// if (!function_exists('cache_model_construct')) {
//     function cache_model_construct($name, $model, $time)
//     {
//         $data = Cache::remember($name.'_construct', $time, function () use ($model) {
//             return $model::lazy()->all();
//         });
//         return $data;
//     }
// }

if (!function_exists('get_cache_1_1')) {
    function get_cache_1_1($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . '_' . $id, $time, function () use ($model, $relationship_name, $id) {
            return $model->find($id)->$relationship_name()->get();
        });
        return $data;
    }
}

if (!function_exists('get_cache_1_n')) {
    function get_cache_1_n($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . '_' . $id, $time, function () use ($model, $relationship_name, $id) {
            $relationship_name = $relationship_name . 's';
            return $model->find($id)->$relationship_name()->get();
        });
        return $data;
    }
}

if (!function_exists('get_cache_1_n_with_ids')) {
    function get_cache_1_n_with_ids($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . 's_' . $id, $time, function () use ($model, $relationship_name, $id) {
            $relationship_name = $relationship_name . 's';
            return $model->find($id)->$relationship_name();
        });
        return $data;
    }
}

if (!function_exists('get_cache_1_1_n_with_ids')) {
    function get_cache_1_1_n_with_ids($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . '_' . $id, $time, function () use ($model, $relationship_name, $id) {
            $parts = explode(".", $relationship_name);
            $a0 = $parts[0];
            $a1 = $parts[1] . 's';
            return $model::with($a0)->find($id)->$a0->$a1();
        });
        return $data;
    }
}

if (!function_exists('get_cache_1_1_1')) {
    function get_cache_1_1_1($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . '_' . $id, $time, function () use ($model, $relationship_name, $id) {
            $parts = explode(".", $relationship_name);
            $a0 = $parts[0];
            $a1 = $parts[1];
            return $model::with($relationship_name)->find($id)->$a0->$a1;
        });
        return $data;
    }
}


if (!function_exists('get_cache_1_1_1_1')) {
    function get_cache_1_1_1_1($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . '_' . $id, $time, function () use ($model, $relationship_name, $id) {
            $parts = explode(".", $relationship_name);
            $a0 = $parts[0];
            $a1 = $parts[1];
            $a2 = $parts[2];
            return $model::with($relationship_name)->find($id)->$a0->$a1->$a2;
        });
        return $data;
    }
}

if (!function_exists('get_cache_1_1_1_1_1')) {
    function get_cache_1_1_1_1_1($model, $relationship_name, $name, $id = null, $time)
    {
        if (!is_numeric($id)) {
            return response()->json(['error' => 'Id không hợp lệ'], 400)->original;
        }
        $data = Cache::remember($name . '_get_' . $relationship_name . '_' . $id, $time, function () use ($model, $relationship_name, $id) {
            $parts = explode(".", $relationship_name);
            $a0 = $parts[0];
            $a1 = $parts[1];
            $a2 = $parts[2];
            $a3 = $parts[3];
            return $model::with($relationship_name)->find($id)->$a0->$a1->$a2->$a3;
        });
        return $data;
    }
}

if (!function_exists('get_cache_by_code')) {
    function get_cache_by_code($model, $name, $param, $type_name, $type, $time)
    {
        if ($type == null) {
            $data = Cache::remember($name . '_by_' . $type_name . '_' . $type, $time, function () use ($model, $param, $type_name, $type) {
                return $model::with($param)->get();
            });
        } else {
            $data = Cache::remember($name . '_by_' . $type_name . '_' . $type, $time, function () use ($model, $param, $type_name, $type) {
                return $model::with($param)->where($type_name, 'LIKE', $type . '%')->get();
            });
        }
        return $data;
    }
}

if (!function_exists('view_service_req')) {
    function view_service_req($execute_room_id, $token, $time)
    {
        $loginname = Cache::remember('token_' . $token . '_loginname', $time, function () use ($token) {
            return Token::select()->where('token_code', '=', $token)->value('login_name');
        });
        $user = get_user_with_loginname($loginname);
        if($user->checkSuperAdmin()){
            return true;
        }
        $check = Cache::remember('loginname_check_execute_room_id_' . $execute_room_id, $time, function () use ($loginname, $execute_room_id, $time) {
            $user_room = new UserRoom();
            return UserRoom::with('room.execute_room')
                ->whereHas('room.execute_room', function ($query) use ($execute_room_id) {
                    $query->where('id', $execute_room_id);
                })->exists();
        });
    }
}


if (!function_exists('get_loginname_with_token')) {
    function get_loginname_with_token($token, $time)
    {
        $loginname = Cache::remember('token_' . $token . '_loginname', $time, function () use ($token) {
            return Token::select()->where('token_code', '=', $token)->value('login_name');
        });
        return $loginname;
    }
}

if (!function_exists('return_id_error')) {
    function return_id_error($id)
    {
        return response()->json([
            'success'   => false,
            'message'   => 'Id = '.$id.' không hợp lệ!',
        ], 422);
    }
}

if (!function_exists('return_not_record')) {
    function return_not_record($id)
    {
        return response()->json([
            'success'   => false,
            'message'   => 'Không tìm thấy bản ghi với id = '.$id.'!',
        ], 422);
    }
}

if (!function_exists('return_data_success')) {
    function return_data_success($param_return, $data_return)
    {
        if($param_return == null){
            return response()->json([
                'success' => true,
                'data' => $data_return,
            ], 200)->original;
        }else{
            return response()->json([
                'success' => true,
                'param' => $param_return,
                'data' => $data_return,
            ], 200);
        }
    }
}

if (!function_exists('return_data_create_success')) {
    function return_data_create_success($data_return)
    {
        return response()->json([
            'success' => true,
            'data' => $data_return,
        ], 201);

    }
}

if (!function_exists('return_data_update_success')) {
    function return_data_update_success($data_return)
    {
        return response()->json([
            'success' => true,
            'data' => $data_return,
        ], 200);

    }
}

if (!function_exists('return_data_delete_success')) {
    function return_data_delete_success()
    {
        return response()->json([
            'success' => true,
            'message' => 'Xóa bản ghi thành công!'
        ], 200);

    }
}

if (!function_exists('return_data_delete_fail')) {
    function return_data_delete_fail()
    {
        return response()->json([
            'success' => false,
            'message' => 'Không thể xóa. Dữ liệu đã được sử dụng!'
        ], 400);

    }
}

if (!function_exists('return_data_fail_transaction')) {
    function return_data_fail_transaction()
    {
        return response()->json([
            'success' => false,
        ], 500);
    }
}
