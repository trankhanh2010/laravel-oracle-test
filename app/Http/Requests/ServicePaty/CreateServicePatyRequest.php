<?php

namespace App\Http\Requests\ServicePaty;

use App\Models\HIS\ServiceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
class CreateServicePatyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $ration_time_id_check = ['AN'];
    protected $ration_time_id_check_string;
    protected $ration_time_id_check_id;

    protected $instr_num_by_type_from_check = ['KH'];
    protected $instr_num_by_type_from_check_string;
    protected $instr_num_by_type_from_check_id;

    protected $instr_num_by_type_to_check = ['KH'];
    protected $instr_num_by_type_to_check_string;
    protected $instr_num_by_type_to_check_id;
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
        $this->ration_time_id_check_id = ServiceType::select('id')->whereIn('service_type_code', $this->ration_time_id_check)->pluck('id')->implode(',');
        $this->ration_time_id_check_string = implode(", ", $this->ration_time_id_check);

        $this->instr_num_by_type_from_check_id = ServiceType::select('id')->whereIn('service_type_code', $this->instr_num_by_type_from_check)->pluck('id')->implode(',');
        $this->instr_num_by_type_from_check_string = implode(", ", $this->instr_num_by_type_from_check);

        $this->instr_num_by_type_to_check_id = ServiceType::select('id')->whereIn('service_type_code', $this->instr_num_by_type_to_check)->pluck('id')->implode(',');
        $this->instr_num_by_type_to_check_string = implode(", ", $this->instr_num_by_type_to_check);
        return [
            'service_type_id' =>                'required|integer|exists:App\Models\HIS\ServiceType,id', 
            'service_id' =>                     'required|integer|exists:App\Models\HIS\Service,id', 
            'branch_ids' =>                     'required|string', 
            'patient_type_ids' =>               'required|string', 
            'patient_classify_id' =>            'nullable|integer|exists:App\Models\HIS\PatientClassify,id', 
            'price' =>                          'required|numeric|regex:/^\d{1,15}(\.\d{1,4})?$/|min:0',
            'vat_ratio' =>                      'required|numeric|regex:/^\d{1,15}(\.\d{1,4})?$/|min:0|max:1',

            'overtime_price' =>                 'nullable|numeric|regex:/^\d{1,15}(\.\d{1,4})?$/|min:0|lte:price',
            'actual_price' =>                   'nullable|numeric|regex:/^\d{1,15}(\.\d{1,4})?$/|min:0',
            'priority' =>                       'nullable|numeric',
            'ration_time_id' =>                 'nullable|integer|exists:App\Models\HIS\RationTime,id|prohibited_unless:service_type_id,'.$this->ration_time_id_check_id,
            'intruction_number_from' =>         'nullable|integer|min:0',
            'intruction_number_to' =>           'nullable|integer|min:0',
            'instr_num_by_type_from' =>         'nullable|integer|min:0|prohibited_unless:service_type_id,'.$this->instr_num_by_type_from_check_id,
            'instr_num_by_type_to' =>           'nullable|integer|min:0|prohibited_unless:service_type_id,'.$this->instr_num_by_type_to_check_id,

            'from_time' =>                      'nullable|integer|regex:/^\d{14}$/',
            'to_time' =>                        'nullable|integer|regex:/^\d{14}$/|gte:from_time',
            'treatment_from_time' =>            'nullable|integer|regex:/^\d{14}$/',
            'treatment_to_time' =>              'nullable|integer|regex:/^\d{14}$/|gte:treatment_from_time',
            'day_from' =>                       'nullable|integer|in:1,2,3,4,5,6,7',
            'day_to' =>                         'nullable|integer|in:1,2,3,4,5,6,7',
            'hour_from' =>                      [
                                                    'nullable',
                                                    'string',
                                                    'max:4',
                                                    'regex:/^(0[0-9]|1[0-9]|2[0-3])(00|15|30|45)$/'
                                                ],
            'hour_to' =>                        [
                                                    'nullable',
                                                    'string',
                                                    'max:4',
                                                    'regex:/^(0[0-9]|1[0-9]|2[0-3])(00|15|30|45)$/'
                                                ],

        ];
    }

    public function messages()
    {
        return [
            'service_type_id.required'      => config('keywords')['service_paty']['service_type_id'].config('keywords')['error']['required'],
            'service_type_id.integer'       => config('keywords')['service_paty']['service_type_id'].config('keywords')['error']['integer'],
            'service_type_id.exists'        => config('keywords')['service_paty']['service_type_id'].config('keywords')['error']['exists'],  
            
            'service_id.required'      => config('keywords')['service_paty']['service_id'].config('keywords')['error']['required'],
            'service_id.integer'       => config('keywords')['service_paty']['service_id'].config('keywords')['error']['integer'],
            'service_id.exists'        => config('keywords')['service_paty']['service_id'].config('keywords')['error']['exists'], 

            'branch_ids.required'       => config('keywords')['service_paty']['branch_ids'].config('keywords')['error']['required'],
            'branch_ids.string'         => config('keywords')['service_paty']['branch_ids'].config('keywords')['error']['string'],

            'patient_type_ids.required'     => config('keywords')['service_paty']['patient_type_id'].config('keywords')['error']['required'],
            'patient_type_ids.string'       => config('keywords')['service_paty']['patient_type_id'].config('keywords')['error']['string'],

            'patient_classify_id.required'      => config('keywords')['service_paty']['patient_classify_id'].config('keywords')['error']['required'],
            'patient_classify_id.integer'       => config('keywords')['service_paty']['patient_classify_id'].config('keywords')['error']['integer'],
            'patient_classify_id.exists'        => config('keywords')['service_paty']['patient_classify_id'].config('keywords')['error']['exists'], 

            'price.required'         => config('keywords')['service_paty']['price'].config('keywords')['error']['required'],
            'price.numeric'          => config('keywords')['service_paty']['price'].config('keywords')['error']['numeric'],
            'price.regex'            => config('keywords')['service_paty']['price'].config('keywords')['error']['regex_19_4'],
            'price.min'              => config('keywords')['service_paty']['price'].config('keywords')['error']['integer_min'],

            'vat_ratio.required'         => config('keywords')['service_paty']['vat_ratio'].config('keywords')['error']['required'],
            'vat_ratio.numeric'          => config('keywords')['service_paty']['vat_ratio'].config('keywords')['error']['numeric'],
            'vat_ratio.regex'            => config('keywords')['service_paty']['vat_ratio'].config('keywords')['error']['regex_19_4'],
            'vat_ratio.min'              => config('keywords')['service_paty']['vat_ratio'].config('keywords')['error']['integer_min'],
            'vat_ratio.max'              => config('keywords')['service_paty']['vat_ratio'].config('keywords')['error']['integer_max'],


            'overtime_price.required'         => config('keywords')['service_paty']['overtime_price'].config('keywords')['error']['required'],
            'overtime_price.numeric'          => config('keywords')['service_paty']['overtime_price'].config('keywords')['error']['numeric'],
            'overtime_price.regex'            => config('keywords')['service_paty']['overtime_price'].config('keywords')['error']['regex_19_4'],
            'overtime_price.min'              => config('keywords')['service_paty']['overtime_price'].config('keywords')['error']['integer_min'],
            'overtime_price.lte'              => config('keywords')['service_paty']['overtime_price'].config('keywords')['error']['lte'],

            'actual_price.required'         => config('keywords')['service_paty']['actual_price'].config('keywords')['error']['required'],
            'actual_price.numeric'          => config('keywords')['service_paty']['actual_price'].config('keywords')['error']['numeric'],
            'actual_price.regex'            => config('keywords')['service_paty']['actual_price'].config('keywords')['error']['regex_19_4'],
            'actual_price.min'              => config('keywords')['service_paty']['actual_price'].config('keywords')['error']['integer_min'],

            'priority.numeric'          => config('keywords')['service_paty']['priority'].config('keywords')['error']['numeric'],

            'ration_time_id.integer'            => config('keywords')['service_paty']['ration_time_id'].config('keywords')['error']['integer'],
            'ration_time_id.exists'             => config('keywords')['service_paty']['ration_time_id'].config('keywords')['error']['exists'], 
            'ration_time_id.prohibited_unless'  => config('keywords')['service_paty']['ration_time_id'].config('keywords')['error']['prohibited_unless_service_type'].$this->ration_time_id_check_string,

            'intruction_number_from.integer'        => config('keywords')['service_paty']['intruction_number_from'].config('keywords')['error']['integer'],
            'intruction_number_from.min'            => config('keywords')['service_paty']['intruction_number_from'].config('keywords')['error']['integer_min'], 

            'intruction_number_to.integer'      => config('keywords')['service_paty']['intruction_number_to'].config('keywords')['error']['integer'],
            'intruction_number_to.min'          => config('keywords')['service_paty']['intruction_number_to'].config('keywords')['error']['integer_min'], 

            'instr_num_by_type_from.integer'            => config('keywords')['service_paty']['instr_num_by_type_from'].config('keywords')['error']['integer'],
            'instr_num_by_type_from.min'                => config('keywords')['service_paty']['instr_num_by_type_from'].config('keywords')['error']['integer_min'], 
            'instr_num_by_type_from.prohibited_unless'  => config('keywords')['service_paty']['instr_num_by_type_from'].config('keywords')['error']['prohibited_unless_service_type'].$this->instr_num_by_type_from_check_string,

            'instr_num_by_type_to.integer'            => config('keywords')['service_paty']['instr_num_by_type_to'].config('keywords')['error']['integer'],
            'instr_num_by_type_to.min'                => config('keywords')['service_paty']['instr_num_by_type_to'].config('keywords')['error']['integer_min'], 
            'instr_num_by_type_to.prohibited_unless'  => config('keywords')['service_paty']['instr_num_by_type_to'].config('keywords')['error']['prohibited_unless_service_type'].$this->instr_num_by_type_to_check_string,


            'from_time.integer'            => config('keywords')['service_paty']['from_time'].config('keywords')['error']['integer'],
            'from_time.regex'              => config('keywords')['service_paty']['from_time'].config('keywords')['error']['regex_ymdhis'],

            'to_time.integer'            => config('keywords')['service_paty']['to_time'].config('keywords')['error']['integer'],
            'to_time.regex'              => config('keywords')['service_paty']['to_time'].config('keywords')['error']['regex_ymdhis'],
            'to_time.gte'                => config('keywords')['service_paty']['to_time'].config('keywords')['error']['gte'],

            'treatment_from_time.integer'            => config('keywords')['service_paty']['treatment_from_time'].config('keywords')['error']['integer'],
            'treatment_from_time.regex'              => config('keywords')['service_paty']['treatment_from_time'].config('keywords')['error']['regex_ymdhis'],

            'treatment_to_time.integer'            => config('keywords')['service_paty']['treatment_to_time'].config('keywords')['error']['integer'],
            'treatment_to_time.regex'              => config('keywords')['service_paty']['treatment_to_time'].config('keywords')['error']['regex_ymdhis'],
            'treatment_to_time.gte'                => config('keywords')['service_paty']['treatment_to_time'].config('keywords')['error']['gte'],

            'day_from.integer'             => config('keywords')['service_paty']['day_from'].config('keywords')['error']['integer'],
            'day_from.in'                  => config('keywords')['service_paty']['day_from'].config('keywords')['error']['in'],

            'day_to.integer'             => config('keywords')['service_paty']['day_to'].config('keywords')['error']['integer'],
            'day_to.in'                  => config('keywords')['service_paty']['day_to'].config('keywords')['error']['in'],

            'hour_from.string'                  => config('keywords')['service_paty']['hour_from'].config('keywords')['error']['string'],
            'hour_from.max'                     => config('keywords')['service_paty']['hour_from'].config('keywords')['error']['string_max'],
            'hour_from.regex'                   => config('keywords')['service_paty']['hour_from'].config('keywords')['error']['regex_hhmm'],

            'hour_to.string'                  => config('keywords')['service_paty']['hour_to'].config('keywords')['error']['string'],
            'hour_to.max'                     => config('keywords')['service_paty']['hour_to'].config('keywords')['error']['string_max'],
            'hour_to.regex'                   => config('keywords')['service_paty']['hour_to'].config('keywords')['error']['regex_hhmm'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('branch_ids')) {
            $this->merge([
                'branch_ids_list' => explode(',', $this->branch_ids),
            ]);
        }
        if ($this->has('patient_type_ids')) {
            $this->merge([
                'patient_type_ids_list' => explode(',', $this->patient_type_ids),
            ]);
        }
    }
     
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('branch_ids_list') && ($this->branch_ids_list[0] != null)) {
                foreach ($this->branch_ids_list as $id) {
                    if (!is_numeric($id) || !\App\Models\HIS\Branch::find($id)) {
                        $validator->errors()->add('branch_ids', 'Chi nhánh với id = ' . $id . ' trong danh sách chi nhánh không tồn tại!');
                    }
                }
            }
            if ($this->has('patient_type_ids_list') && ($this->patient_type_ids_list[0] != null)) {
                foreach ($this->patient_type_ids_list as $id) {
                    if (!is_numeric($id) || !\App\Models\HIS\PatientType::find($id)) {
                        $validator->errors()->add('patient_type_ids', 'Đối tượng thanh toán với id = ' . $id . ' trong danh sách đối tượng thanh toán không tồn tại!');
                    }
                }
            }
        });
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