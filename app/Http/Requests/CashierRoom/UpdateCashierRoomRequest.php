<?php

namespace App\Http\Requests\CashierRoom;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class UpdateCashierRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cashier_room_name' =>              'required|string|max:100',
            'area_id' =>                        [
                                                    'nullable',
                                                    'integer',
                                                    Rule::exists('App\Models\HIS\Area', 'id')
                                                    ->where(function ($query) {
                                                        $query = $query
                                                        ->where(DB::connection('oracle_his')->raw("is_active"), 1);
                                                    }),
                                                ],
            'room_type_id'  =>                  [
                                                    'required',
                                                    'integer',
                                                    Rule::exists('App\Models\HIS\RoomType', 'id')
                                                    ->where(function ($query) {
                                                        $query = $query
                                                        ->where(DB::connection('oracle_his')->raw("is_active"), 1);
                                                    }),
                                                ],
            'einvoice_room_code' =>             'nullable|string|max:10',
            'einvoice_room_name' =>             'nullable|string|max:100',
            'is_active' =>                      'required|integer|in:0,1'

        ];
    }
    public function messages()
    {
        return [
            'cashier_room_name.required'    => config('keywords')['cashier_room']['cashier_room_name'].config('keywords')['error']['required'],
            'cashier_room_name.string'      => config('keywords')['cashier_room']['cashier_room_name'].config('keywords')['error']['string'],
            'cashier_room_name.max'         => config('keywords')['cashier_room']['cashier_room_name'].config('keywords')['error']['string_max'],

            'area_id.integer'     => config('keywords')['cashier_room']['area_id'].config('keywords')['error']['integer'],
            'area_id.exists'      => config('keywords')['cashier_room']['area_id'].config('keywords')['error']['exists'], 

            'room_type_id.required'    => config('keywords')['cashier_room']['room_type_id'].config('keywords')['error']['required'],            
            'room_type_id.integer'     => config('keywords')['cashier_room']['room_type_id'].config('keywords')['error']['integer'],
            'room_type_id.exists'      => config('keywords')['cashier_room']['room_type_id'].config('keywords')['error']['exists'],  

            'einvoice_room_code.string'      => config('keywords')['cashier_room']['einvoice_room_code'].config('keywords')['error']['string'],
            'einvoice_room_code.max'         => config('keywords')['cashier_room']['einvoice_room_code'].config('keywords')['error']['string_max'],

            'einvoice_room_name.string'      => config('keywords')['cashier_room']['einvoice_room_name'].config('keywords')['error']['string'],
            'einvoice_room_name.max'         => config('keywords')['cashier_room']['einvoice_room_name'].config('keywords')['error']['string_max'],

            'is_active.required'    => config('keywords')['all']['is_active'].config('keywords')['error']['required'],            
            'is_active.integer'     => config('keywords')['all']['is_active'].config('keywords')['error']['integer'], 
            'is_active.in'          => config('keywords')['all']['is_active'].config('keywords')['error']['in'], 
        ];
    }



    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Dữ liệu không hợp lệ!',
            'data'      => $validator->errors()
        ], 422));
    }
}
