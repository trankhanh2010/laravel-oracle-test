<?php

namespace App\Http\Controllers\Api\CacheControllers;

use Illuminate\Http\Request;
use App\Models\HIS\Service;
use App\Events\Cache\DeleteCache;
use App\Http\Controllers\BaseControllers\BaseApiCacheController;
use App\Http\Requests\Service\CreateServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Models\HIS\ServiceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ServiceController extends BaseApiCacheController
{
    public function __construct(Request $request){
        parent::__construct($request); // Gọi constructor của BaseController
        $this->service = new Service();
        $this->service_type = new ServiceType();
    }

    
    public function service($id)
    {
        if (!is_numeric($id)) {
            return return_id_error($id);
        }
        $data = $this->service->find($id);
        if ($data == null) {
            return return_not_record($id);
        }

        $data = get_cache($this->service, $this->service_name, $id, $this->time);
        $data1 = get_cache_1_1($this->service, "service_type", $this->service_name, $id, $this->time);
        $data2 = get_cache_1_1($this->service, "parent", $this->service_name, $id, $this->time);
        $data3 = get_cache_1_1($this->service, "service_unit", $this->service_name, $id, $this->time);
        $data4 = get_cache_1_1($this->service, "hein_service_type", $this->service_name, $id, $this->time);
        $data5 = get_cache_1_1($this->service, "bill_patient_type", $this->service_name, $id, $this->time);
        $data6 = get_cache_1_1($this->service, "pttt_group", $this->service_name, $id, $this->time);
        $data7 = get_cache_1_1($this->service, "pttt_method", $this->service_name, $id, $this->time);
        $data8 = get_cache_1_1($this->service, "icd_cm", $this->service_name, $id, $this->time);
        $data9 = get_cache_1_1($this->service, "revenue_department", $this->service_name, $id, $this->time);
        $data10 = get_cache_1_1($this->service, "package", $this->service_name, $id, $this->time);
        $data11 = get_cache_1_1($this->service, "exe_service_module", $this->service_name, $id, $this->time);
        $data12 = get_cache_1_1($this->service, "gender", $this->service_name, $id, $this->time);
        $data13 = get_cache_1_1($this->service, "ration_group", $this->service_name, $id, $this->time);
        $data14 = get_cache_1_1($this->service, "diim_type", $this->service_name, $id, $this->time);
        $data15 = get_cache_1_1($this->service, "fuex_type", $this->service_name, $id, $this->time);
        $data16 = get_cache_1_1($this->service, "test_type", $this->service_name, $id, $this->time);
        $data17 = get_cache_1_1($this->service, "other_pay_source", $this->service_name, $id, $this->time);
        $data18 = get_cache_1_n_with_ids($this->service, "body_part", $this->service_name, $id, $this->time);
        $data19 = get_cache_1_1($this->service, "film_size", $this->service_name, $id, $this->time);
        $data20 = get_cache_1_n_with_ids($this->service, "applied_patient_type", $this->service_name, $id, $this->time);
        $data21 = get_cache_1_1($this->service, "default_patient_type", $this->service_name, $id, $this->time);
        $data22 = get_cache_1_n_with_ids($this->service, "applied_patient_classify", $this->service_name, $id, $this->time);
        $data23 = get_cache_1_n_with_ids($this->service, "min_proc_time_except_paty", $this->service_name, $id, $this->time);
        $data24 = get_cache_1_n_with_ids($this->service, "max_proc_time_except_paty", $this->service_name, $id, $this->time);
        $data25 = get_cache_1_n_with_ids($this->service, "total_time_except_paty", $this->service_name, $id, $this->time);
        $count = $data->count();
        $param_return = [
            'start' => null,
            'limit' => null,
            'count' => $count
        ];
        $param_data = [
            'service' => $data,
            'service_type' => $data1,
            'parent' => $data2,
            'service_unit' => $data3,
            'hein_service_type' => $data4,
            'bill_patient_type' => $data5,
            'pttt_group' => $data6,
            'pttt_method' => $data7,
            'icd_cm' => $data8,
            'revenue_department' => $data9,
            'package' => $data10,
            'exe_service_module' => $data11,
            'gender' => $data12,
            'ration_group' => $data13,
            'diim_type' => $data14,
            'fuex_type' => $data15,
            'test_type' => $data16,
            'other_pay_source' => $data17,
            'body_parts' => $data18,
            'film_size' => $data19,
            'applied_patient_types' => $data20,
            'default_patient_type' => $data21,
            'applied_patient_classifys' => $data22,
            'min_proc_time_except_patys' => $data23,
            'max_proc_time_except_patys' => $data24,
            'total_time_except_patys' => $data25
        ];
        return return_data_success($param_return, $param_data);
    }


    // public function service_by_code($type_id)
    // {
    //     $param = [];
    //     $data = get_cache_by_code($this->service, $this->service_name, $param, 'service_code', $type_id, $this->time);
    //     return response()->json(['data' => $data], 200);
    // }
    public function service_by_service_type($id)
    {
        if (!is_numeric($id)) {
            return return_id_error($id);
        }
        $data = $this->service_type->find($id);
        if ($data == null) {
            return return_not_record($id);
        }
        $param =[];
        $data = get_cache_by_code($this->service, $this->service_name, $param, 'service_type_id', $id, $this->time);

        $count = $data->count();
        $param_return = [
            'start' => null,
            'limit' => null,
            'count' => $count
        ];
        return return_data_success($param_return, $data);
    }
    

    public function service_create(CreateServiceRequest $request)
    {
        $data = $this->service::create([
            'create_time' => now()->format('Ymdhis'),
            'modify_time' => now()->format('Ymdhis'),
            'creator' => get_loginname_with_token($request->bearerToken(), $this->time),
            'modifier' => get_loginname_with_token($request->bearerToken(), $this->time),
            'app_creator' => $this->app_creator,
            'app_modifier' => $this->app_modifier,
            'service_type_id' => $request->service_type_id,
            'service_code' => $request->service_code,
            'service_name' => $request->service_name,
            'service_unit_id' => $request->service_unit_id,
            'speciality_code' => $request->speciality_code,
            'hein_service_type_id' => $request->hein_service_type_id,

            'hein_service_bhyt_code' => $request->hein_service_bhyt_code,
            'hein_service_bhyt_name' => $request->hein_service_bhyt_name,
            'hein_order' => $request->hein_order,
            'parent_id' => $request->parent_id,
            'package_id' => $request->package_id,
            'package_price' => $request->package_price,

            'bill_option' => $request->bill_option,
            'bill_patient_type_id' => $request->bill_patient_type_id,
            'pttt_method_id' => $request->pttt_method_id,
            'is_not_change_bill_paty' => $request->is_not_change_bill_paty,
            'applied_patient_classify_ids' => $request->applied_patient_classify_ids,
            'applied_patient_type_ids' => $request->applied_patient_type_ids,

            'testing_technique' => $request->testing_technique,
            'default_patient_type_id' => $request->default_patient_type_id,
            'pttt_group_id' => $request->pttt_group_id,
            'hein_limit_price_old' => $request->hein_limit_price_old,
            'icd_cm_id' => $request->icd_cm_id,
            'hein_limit_price_in_time' => $request->hein_limit_price_in_time,

            'hein_limit_price' => $request->hein_limit_price,
            'cogs' => $request->cogs,
            'ration_symbol' => $request->ration_symbol,
            'ration_group_id' => $request->ration_group_id,
            'num_order' => $request->num_order,
            'pacs_type_code'=> $request->pacs_type_code,

            'diim_type_id' => $request->diim_type_id,
            'fuex_type_id' => $request->fuex_type_id,
            'test_type_id' => $request->test_type_id,
            'sample_type_code' => $request->sample_type_code,
            'max_expend' => $request->max_expend,
            'number_of_film' => $request->number_of_film,

            'film_size_id' => $request->film_size_id,
            'min_process_time' => $request->min_process_time,
            'min_proc_time_except_paty_ids' => $request->min_proc_time_except_paty_ids,
            'estimate_duration' => $request->estimate_duration,
            'max_process_time' => $request->max_process_time,
            'max_proc_time_except_paty_ids' => $request->max_proc_time_except_paty_ids,

            'age_from' => $request->age_from,
            'age_to' => $request->age_to,
            'max_total_process_time' => $request->max_total_process_time,
            'total_time_except_paty_ids' => $request->total_time_except_paty_ids,
            'gender_id' => $request->gender_id,
            'min_duration' => $request->min_duration,

            'max_amount' => $request->max_amount,
            'body_part_ids' => $request->body_part_ids,
            'capacity' => $request->capacity,
            'warning_sampling_time' => $request->warning_sampling_time,
            'exe_service_module_id' => $request->exe_service_module_id,
            'suim_index_id' => $request->suim_index_id,

            'is_kidney' => $request->is_kidney,
            'is_antibiotic_resistance' => $request->is_antibiotic_resistance,
            'is_disallowance_no_execute' => $request->is_disallowance_no_execute,
            'is_multi_request' => $request->is_multi_request,
            'is_split_service_req' => $request->is_split_service_req,
            'is_out_parent_fee' => $request->is_out_parent_fee,

            'is_allow_expend' => $request->is_allow_expend,
            'is_auto_expend' => $request->is_auto_expend,
            'is_out_of_drg' => $request->is_out_of_drg,
            'is_out_of_management' => $request->is_out_of_management,
            'is_other_source_paid' => $request->is_other_source_paid,
            'is_enable_assign_price' => $request->is_enable_assign_price,

            'is_not_show_tracking' => $request->is_not_show_tracking,
            'must_be_consulted' => $request->must_be_consulted,
            'is_block_department_tran' => $request->is_block_department_tran,
            'allow_simultaneity' => $request->allow_simultaneity,
            'is_not_required_complete' => $request->is_not_required_complete,
            'do_not_use_bhyt' => $request->do_not_use_bhyt,

            'allow_send_pacs' => $request->allow_send_pacs,
            'other_pay_source_id' => $request->other_pay_source_id,
            'attach_assign_print_type_code' => $request->attach_assign_print_type_code,
            'description' => $request->description,
            'notice' => $request->notice,
            'tax_rate_type' => $request->tax_rate_type,

            'process_code' => $request->process_code,
        ]);
        // Gọi event để xóa cache
        event(new DeleteCache($this->service_name));
        return return_data_create_success($data);
    }

    public function service_update(UpdateServiceRequest $request, $id)
    {
        if (!is_numeric($id)) {
            return return_id_error($id);
        }
        $data = $this->service->find($id);
        if ($data == null) {
            return return_not_record($id);
        }
        $data_update = [
            'modify_time' => now()->format('Ymdhis'),
            'modifier' => get_loginname_with_token($request->bearerToken(), $this->time),
            'app_modifier' => $this->app_modifier,
            'service_code' => $request->service_code,
            'service_name' => $request->service_name,
            'service_unit_id' => $request->service_unit_id,
            'speciality_code' => $request->speciality_code,
            'hein_service_type_id' => $request->hein_service_type_id,

            'hein_service_bhyt_code' => $request->hein_service_bhyt_code,
            'hein_service_bhyt_name' => $request->hein_service_bhyt_name,
            'hein_order' => $request->hein_order,
            'parent_id' => $request->parent_id,
            'package_id' => $request->package_id,
            'package_price' => $request->package_price,

            'bill_option' => $request->bill_option,
            'bill_patient_type_id' => $request->bill_patient_type_id,
            'pttt_method_id' => $request->pttt_method_id,
            'is_not_change_bill_paty' => $request->is_not_change_bill_paty,
            'applied_patient_classify_ids' => $request->applied_patient_classify_ids,
            'applied_patient_type_ids' => $request->applied_patient_type_ids,

            'testing_technique' => $request->testing_technique,
            'default_patient_type_id' => $request->default_patient_type_id,
            'pttt_group_id' => $request->pttt_group_id,
            'hein_limit_price_old' => $request->hein_limit_price_old,
            'icd_cm_id' => $request->icd_cm_id,
            'hein_limit_price_in_time' => $request->hein_limit_price_in_time,

            'hein_limit_price' => $request->hein_limit_price,
            'cogs' => $request->cogs,
            'ration_symbol' => $request->ration_symbol,
            'ration_group_id' => $request->ration_group_id,
            'num_order' => $request->num_order,
            'pacs_type_code'=> $request->pacs_type_code,

            'diim_type_id' => $request->diim_type_id,
            'fuex_type_id' => $request->fuex_type_id,
            'test_type_id' => $request->test_type_id,
            'sample_type_code' => $request->sample_type_code,
            'max_expend' => $request->max_expend,
            'number_of_film' => $request->number_of_film,

            'film_size_id' => $request->film_size_id,
            'min_process_time' => $request->min_process_time,
            'min_proc_time_except_paty_ids' => $request->min_proc_time_except_paty_ids,
            'estimate_duration' => $request->estimate_duration,
            'max_process_time' => $request->max_process_time,
            'max_proc_time_except_paty_ids' => $request->max_proc_time_except_paty_ids,

            'age_from' => $request->age_from,
            'age_to' => $request->age_to,
            'max_total_process_time' => $request->max_total_process_time,
            'total_time_except_paty_ids' => $request->total_time_except_paty_ids,
            'gender_id' => $request->gender_id,
            'min_duration' => $request->min_duration,

            'max_amount' => $request->max_amount,
            'body_part_ids' => $request->body_part_ids,
            'capacity' => $request->capacity,
            'warning_sampling_time' => $request->warning_sampling_time,
            'exe_service_module_id' => $request->exe_service_module_id,
            'suim_index_id' => $request->suim_index_id,

            'is_kidney' => $request->is_kidney,
            'is_antibiotic_resistance' => $request->is_antibiotic_resistance,
            'is_disallowance_no_execute' => $request->is_disallowance_no_execute,
            'is_multi_request' => $request->is_multi_request,
            'is_split_service_req' => $request->is_split_service_req,
            'is_out_parent_fee' => $request->is_out_parent_fee,

            'is_allow_expend' => $request->is_allow_expend,
            'is_auto_expend' => $request->is_auto_expend,
            'is_out_of_drg' => $request->is_out_of_drg,
            'is_out_of_management' => $request->is_out_of_management,
            'is_other_source_paid' => $request->is_other_source_paid,
            'is_enable_assign_price' => $request->is_enable_assign_price,

            'is_not_show_tracking' => $request->is_not_show_tracking,
            'must_be_consulted' => $request->must_be_consulted,
            'is_block_department_tran' => $request->is_block_department_tran,
            'allow_simultaneity' => $request->allow_simultaneity,
            'is_not_required_complete' => $request->is_not_required_complete,
            'do_not_use_bhyt' => $request->do_not_use_bhyt,

            'allow_send_pacs' => $request->allow_send_pacs,
            'other_pay_source_id' => $request->other_pay_source_id,
            'attach_assign_print_type_code' => $request->attach_assign_print_type_code,
            'description' => $request->description,
            'notice' => $request->notice,
            'tax_rate_type' => $request->tax_rate_type,

            'process_code' => $request->process_code,
        ];
        $data->fill($data_update);
        $data->save();
        // Gọi event để xóa cache
        event(new DeleteCache($this->service_name));
        return return_data_update_success($data);
    }

    public function service_delete(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return return_id_error($id);
        }
        $data = $this->service->find($id);
        if ($data == null) {
            return return_not_record($id);
        }
        try {
            $data->delete();
            // Gọi event để xóa cache
            event(new DeleteCache($this->service_name));
            return return_data_delete_success();
        } catch (\Exception $e) {
            return return_data_delete_fail();
        }
    }
}
