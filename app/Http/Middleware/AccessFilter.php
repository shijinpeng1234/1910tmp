<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class AccessFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_uri = $_SERVER['REQUEST_URI'];//获取当前url
        $url_hash = substr(md5($request_uri),5,10);
        $expire = 10;  //n 秒后重试
        $time_last= 60; //时间段
        $key = 'access_total_'.$url_hash;
        $total = Redis::get($key); //获取访问次数
        if($total > 10){
            $response = [
                'errno' => 50016,
                'msg'   => "请求过于频繁，请 {$expire} 秒后重试"
            ];
            Redis::expire($key,$expire);  //设置key过期时间
//            die(json_encode($response,JSON_UNESCAPED_UNICODE));
            return response()->json($response);
        }else{
            Redis::incr($key);
            Redis::expire($key,$time_last);
        }
        return $next($request);
    }
}
