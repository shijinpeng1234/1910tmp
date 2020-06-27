<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\UsersModel;
use Illuminate\Http\Request;
use App\Model\TokenModel;
use TheSeer\Tokenizer\Token;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    //注册
    public function reg(Request $request)
    {
        $user_name = $request->post('user_name');
        $user_email = $request->post('user_email');
        $password = $request->post('password');
        $pwd = $request->post('pwd');
        $len = strlen($password);
        if(empty($user_name)){
            $response = [
                'errno' => 50001,
                'msg' => '用户名不能为空'
            ];
            return $response;
        }
        $u =UsersModel::where(['user_name'=>$user_name])->first();
        if($u){
            $response = [
                'errno' => 50007,
                'msg' => '用户名已存在'
            ];
            return $response;
        }
        if(empty($user_email)){
            $response = [
                'errno' => 50002,
                'msg' => '邮箱不能为空'
            ];
            return $response;
        }
        if(empty($password)){
            $response = [
                'errno' => 50003,
                'msg' => '密码不能为空'
            ];
            return $response;
        }
        if(strlen($password) < 6){
            $response = [
                'errno' => 50004,
                'msg' => '密码长度必须大于等于6'
            ];
            return $response;
        }
        if(empty($pwd)){
            $response = [
                'errno' => 50005,
                'msg' => '确认密码不能为空'
            ];
            return $response;
        }

        if($password != $pwd){
            $response = [
                'errno' => 50006,
                'msg' => '两次密码不一致'
            ];
            return $response;
        }

        $usersModel = new UsersModel();
        $usersModel->user_name = $user_name;
        $usersModel->password = password_hash($password,PASSWORD_BCRYPT);
        $usersModel->email= $user_email;
        $usersModel->reg_time= time();
        $res=$usersModel->save();
        if($res){
            $response= [
                'errno' => 0,
                'msg' => "注册成功"
            ];
        }else{
            $response = [
              'errno'=> 50008,
              'msg' => "注册失败"
            ];
        }
        return $response;
    }
    //登录
    public function login(Request $request)
    {
        $user_name = $request->post("user_name");
        $password = $request->post("password");
        if(empty($user_name)){
            $response = [
                'errno' => 50001,
                'msg' => '用户名不能为空'
            ];
            return $response;
        }
        if(empty($password)){
            $response = [
                'errno' => 50003,
                'msg' => '密码不能为空'
            ];
            return $response;
        }
        $res = UsersModel::where(['user_name'=>$user_name])->first();
        if(!$res){
            $response = [
                'errno' => 50009,
                'msg' => '用户不存在'
            ];
            return $response;
        }else{
            $res2 = password_verify($password,$res->password);
            if($res2){
                UsersModel::where('user_name','=',$res['user_name'])->update(['last_login'=>time()]);
                //生成token
                $str = $res->user_id . $res->user_name . time();
                $token = substr(md5($str),10,16) . substr(md5($str),0,10);
                //数据库保存token
//                $data = [
//                    'uid'    => $res->user_id,
//                    'token'  => $token,
//                    'expire' => time()+7200
//                ];
//                TokenModel::insert($data);
                //讲token保存到redis种
                $key = $token;
                Redis::set($key,$res->user_id);
                //设置token过期时间
                Redis::expire($token,50);
                $response = [
                  'errno' => 0,
                  'msg'   => '登录成功',
                  'token' => $token
                ];
            }else{
                $response = [
                    'errno' => 50010,
                    'msg'   => '密码错误'
                ];
            }
            return $response;
        }
    }
    //个人中心
    public function center()
    {
        $token = $_GET['token'];
        //检查token是否有效
//        $res = TokenModel::where(['token'=>$token])->first();
        $uid = Redis::get($token);
        if($uid){
            $user_info = UsersModel::find($uid);
            echo $user_info->user_name . "欢迎来到个人中心";
        }else{
            echo "请登录";
        }
    }
}
