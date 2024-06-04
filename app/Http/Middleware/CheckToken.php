<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ACS\Token;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token_header = $request->bearerToken();
        if (!$token_header) {
            return response()->json(['message' => 'Thiếu token'], 401);
        }

        $token = get_token_header($request, $token_header);
        if(!$token){
            Cache::forget('token_'.$token_header);
            return response()->json(['message' => 'Token không hợp lệ'], 401);
        }
        // dd(date("Y-m-d H:i:s", $token->expire_time));
        $date = Carbon::createFromTimestamp($token->expire_time);
        // Kiểm tra xem ngày hiện tại có lớn hơn ngày hết hạn hay không
        $is_expire = Carbon::now()->greaterThan($date);
        // Nếu token không hợp lệ, trả về lỗi 401 Unauthorized
        if (!$token || (!$token->is_active) || ($token->is_delete) || ($is_expire)) {
            return response()->json(['message' => 'Token không hợp lệ'], 401);
        }
        // Nếu token hợp lệ, cho phép tiếp tục xử lý request
        return $next($request);
    }
}
