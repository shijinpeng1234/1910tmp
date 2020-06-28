<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class CheckPri
{
    /**
     * Handle an incoming request.
     *
     * 鉴权中间件
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
        //验证token是否有效
        $uid = Redis::get($token);
        if(!$uid){
         $response = [
             'errno' => 50015,
             'msg'   => '鉴权失败'
         ];
         echo json_encode($response);die;
        }
        return $next($request);
    }
}
