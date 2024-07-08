<?php

namespace App\Http\Controllers\Api\CacheControllers;

use App\Http\Controllers\BaseControllers\BaseApiCacheController;
use App\Models\HIS\BHYTWhitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BhytWhitelistController extends BaseApiCacheController
{
    public function __construct(Request $request)
    {
        parent::__construct($request); // Gọi constructor của BaseController
        $this->bhyt_whitelist = new BHYTWhitelist();
        $this->order_by_join = [];
        // Kiểm tra tên trường trong bảng
        if ($this->order_by != null) {
            foreach ($this->order_by as $key => $item) {
                if (!in_array($key, $this->order_by_join)) {
                    if (!$this->bhyt_whitelist->getConnection()->getSchemaBuilder()->hasColumn($this->bhyt_whitelist->getTable(), $key)) {
                        unset($this->order_by_request[camelCaseFromUnderscore($key)]);
                        unset($this->order_by[$key]);
                    }
                }
            }
            $this->order_by_tring = arrayToCustomString($this->order_by);
        }

    }
    public function bhyt_whitelist($id = null)
    {
        $keyword = mb_strtolower($this->keyword, 'UTF-8');
        if ($keyword != null) {
            $data = $this->bhyt_whitelist
                ->where(DB::connection('oracle_his')->raw('lower(bhyt_whitelist_code)'), 'like', '%' . $keyword . '%');
            $count = $data->count();
            if ($this->order_by != null) {
                foreach ($this->order_by as $key => $item) {
                    $data->orderBy($key, $item);
                }
            }
            $data = $data
                ->skip($this->start)
                ->take($this->limit)
                ->get();
        } else {
            if ($id == null) {
                $data = get_cache($this->bhyt_whitelist, $this->bhyt_whitelist_name . '_start_' . $this->start . '_limit_' . $this->limit. $this->order_by_tring, null, $this->time, $this->start, $this->limit, $this->order_by);
            } else {
                if (!is_numeric($id)) {
                    return return_id_error($id);
                }
                $data = $this->bhyt_whitelist->find($id);
                if ($data == null) {
                    return return_not_record($id);
                }
                $data = get_cache($this->bhyt_whitelist, $this->bhyt_whitelist_name, $id, $this->time, $this->start, $this->limit, $this->order_by);
            }
        }
        $param_return = [
            'start' => $this->start,
            'limit' => $this->limit,
            'count' => $count ?? $data['count'],
            'keyword' => $this->keyword,
            'order_by' => $this->order_by_request
        ];
        return return_data_success($param_return, $data ?? $data['data']);
    }
}