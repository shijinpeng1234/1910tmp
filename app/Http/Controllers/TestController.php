<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\UsersModel;

class TestController extends Controller
{
    public function hello()
    {
        echo __METHOD__;
        echo '<br>';
        echo date('Y-m-d H:i:s');
    }
    public function info()
    {
        phpinfo();
    }
    //注册页面
    public function reg()
    {
        return view('reg.reg');
    }
    //注册
    public function regdo(Request $request)
    {
        $user_name = $request->post('user_name');
        $user_email = $request->post('user_email');
        $password = $request->post('password');
        $pwd = $request->post('pwd');
        if(empty($user_name)){
            die('用户名不能为空');
        }
        if(empty($user_email)){
            die('邮箱不能为空');
        }
        if(empty($password)){
            die('密码不能为空');
        }
        if(empty($pwd)){
            die('确认密码不能为空');
        }
        if(strlen($password) < 6){
            die('密码长度必须大于6');
        }
        if($password != $pwd){
            die('两次密码不一致');
        }
        $u =UsersModel::where(['user_name'=>$user_name])->first();
        if($u){
            die('用户名已存在');
        }
        $usersModel = new UsersModel();
        $usersModel->user_name = $user_name;
        $usersModel->password = password_hash($password,PASSWORD_BCRYPT);
        $usersModel->email= $user_email;
        $usersModel->reg_time= time();
        $res=$usersModel->save();
        if($res){
            echo "注册成功";
        }else{
            echo "注册失败";
        }
    }
}
