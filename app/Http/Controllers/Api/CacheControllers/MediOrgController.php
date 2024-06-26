<?php

namespace App\Http\Controllers\Api\CacheControllers;

use App\Http\Controllers\BaseControllers\BaseApiCacheController;
use Illuminate\Http\Request;
use App\Models\HIS\MediOrg;
use App\Http\Requests\MediOrg\CreateMediOrgRequest;
use App\Http\Requests\MediOrg\UpdateMediOrgRequest;
use App\Events\Cache\DeleteCache;

class MediOrgController extends BaseApiCacheController
{
    public function __construct(Request $request){
        parent::__construct($request); // Gọi constructor của BaseController
        $this->medi_org = new MediOrg();
    }
    public function medi_org($id = null)
    {
        if ($id == null) {
            $name = $this->medi_org_name;
            $param = [];
        } else {
            if (!is_numeric($id)) {
                return return_id_error($id);
            }
            $data = $this->medi_org->find($id);
            if ($data == null) {
                return return_not_record($id);
            }
            $name = $this->medi_org_name . '_' . $id;
            $param = [];
        }
        $data = get_cache_full($this->medi_org, $param, $name, $id, $this->time);
        $count = $data->count();
        $param_return = [
            'start' => null,
            'limit' => null,
            'count' => $count
        ];
        return return_data_success($param_return, $data);
    }

    public function medi_org_create(CreateMediOrgRequest $request)
    {
        $data = $this->medi_org::create([
            'create_time' => now()->format('Ymdhis'),
            'modify_time' => now()->format('Ymdhis'),
            'creator' => get_loginname_with_token($request->bearerToken(), $this->time),
            'modifier' => get_loginname_with_token($request->bearerToken(), $this->time),
            'app_creator' => $this->app_creator,
            'app_modifier' => $this->app_modifier,
            'medi_org_code' => $request->medi_org_code,
            'medi_org_name' => $request->medi_org_name,
            'province_code' => $request->province_code,
            'province_name' => $request->province_name,
            'district_code' => $request->district_code,
            'district_name' => $request->district_name,
            'commune_code' => $request->commune_code,
            'commune_name' => $request->commune_name,
            'address' => $request->address,
            'rank_code' => $request->rank_code,
            'level_code' => $request->level_code,
        ]);
        // Gọi event để xóa cache
        event(new DeleteCache($this->medi_org_name));
        return return_data_create_success($data);
    }

    public function medi_org_update(UpdateMediOrgRequest $request, $id)
    {
        if (!is_numeric($id)) {
            return return_id_error($id);
        }
        $data = $this->medi_org->find($id);
        if ($data == null) {
            return return_not_record($id);
        }
        $data_update = [
            'modify_time' => now()->format('Ymdhis'),
            'modifier' => get_loginname_with_token($request->bearerToken(), $this->time),
            'app_modifier' => $this->app_modifier,
            'medi_org_code' => $request->medi_org_code,
            'medi_org_name' => $request->medi_org_name,
            'province_code' => $request->province_code,
            'province_name' => $request->province_name,
            'district_code' => $request->district_code,
            'district_name' => $request->district_name,
            'commune_code' => $request->commune_code,
            'commune_name' => $request->commune_name,
            'address' => $request->address,
            'rank_code' => $request->rank_code,
            'level_code' => $request->level_code,
        ];
        $data->fill($data_update);
        $data->save();
        // Gọi event để xóa cache
        event(new DeleteCache($this->medi_org_name));
        return return_data_update_success($data);
    }

    public function medi_org_delete(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return return_id_error($id);
        }
        $data = $this->medi_org->find($id);
        if ($data == null) {
            return return_not_record($id);
        }
        try {
            $data->delete();
            // Gọi event để xóa cache
            event(new DeleteCache($this->medi_org_name));
            return return_data_delete_success();
        } catch (\Exception $e) {
            return return_data_delete_fail();
        }
    }
}
