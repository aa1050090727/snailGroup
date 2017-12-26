<?php
namespace app\reception\controller;

use \think\Controller;
use \think\Db;
use \think\Request;
use think\Cache;
use think\Session;


class Login extends Controller
{
    //进入登录页面
    public function login(){
        return $this->fetch();
    }

    //进入注册页面
    public function register(){
        return $this->fetch();
    }

    //验证是否注册过
    public function verify(){
        $verifymsg = config('msg')['verify']; //调用配置文件
        if(input('?post.uname')){//判断是否有值
            $uname=input('param.uname');//接收
            $phone1 = strip_tags($uname);
            $name = addslashes($phone1);
            $phone = [
                'f_user_phone' =>   $name
            ];
            $res  = Db::table('f_user')->where($phone)->find();//数据库查询

            if(!empty($res)){
                return json(['code'=>10001,'msg'=>$verifymsg['verify_error'],'data'=>[],'url' => [] ]);
            }else{
                session_start();
                Session::set('phone',$uname);
                return json(['code'=>10000,'msg'=>$verifymsg['verify_success'],'data'=>[],'url'=>url('reception/Login/registeryan')]);
            }
        }else{
            return json(['code'=>10002,'msg'=>$verifymsg['verify_error1'],'data'=>[],'url' => []]);
        }
    }

    //验证账号是否存在
    public function verifyphone(){
        $verifymsg = config('msg')['verifyphone']; //调用配置文件
        if(input('?post.cellphone')){//判断是否有值
            $cellphone=input('param.cellphone');//接收
            $phone1 = strip_tags($cellphone);
            $name = addslashes($phone1);
            $phone = [
                'f_user_phone' =>   $name
            ];
            $res  = Db::table('f_user')->where($phone)->find();//数据库查询
            if(!empty($res)){
                return json(['code'=>10001,'msg'=>$verifymsg['verifyphone_error'],'data'=>[],'url' => []]);
            }else{
                return json(['code'=>10000,'msg'=>$verifymsg['verifyphone_success'],'data'=>[],'url' => []]);
            }
        }
    }


    //验证验证码输入是否正确
    public function verifycode(){
        $verifymsg = config('msg')['verifycode']; //调用配置文件
        if(input('?post.code')){//判断是否有值
            $cellphone=input('param.code');//接收
            $phone1 = strip_tags($cellphone);
            $name = addslashes($phone1);
            if(!captcha_check($name)){
                return json(['code'=>10000,'msg'=>$verifymsg['verifycode_success'],'data'=>[],'url' => []]);
            }else{
                return json(['code'=>10001,'msg'=>$verifymsg['verifycode_error'],'data'=>[],'url' => []]);
            }
        }
    }

    //填写信息页面
    public function registeryan(){
        return $this->fetch();
    }

    //random() 函数返回随机整数。
    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = '1234567890';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    //获取短信验证码
    public function getno(){
        $phone = Session::get('phone');
        $arr = $this->random(4,0);
        Session::set('cellcode',$arr);
        return json(['code'=>$arr,'phone'=>$phone]);
    }

    //数据写入数据库
    public function setmy(){
        $registermsg = config('msg')['register']; //调用配置文件
        if(input('?post.name') && input('?post.pwd') && input('?post.password') && input('?post.code') && input('?post.cellcode')) {//判断是否有值
            $name = input('param.name');//接收
            $pwd = input('param.pwd');
            $password = input('param.password');
            $code = input('param.code');
            $cellcode = input('param.cellcode');
            $notecode = Session::get('cellcode');
            $name1 = addslashes($name);
            $f_user_name = strip_tags($name1);
            $pwd1 = addslashes($pwd);
            $pwd2 = strip_tags($pwd1);
            $password1 = addslashes($password);
            $password2 = strip_tags($password1);
            $code1 = addslashes($code);
            $code2 = strip_tags($code1);
            if($cellcode!=$notecode){
                return json(['code'=>10006,'msg'=>$registermsg['register_error5'],'data'=>[],'url' => []]);
            }elseif(strlen($f_user_name)>8){
                return json(['code'=>10001,'msg'=>$registermsg['register_error'],'data'=>[],'url' => []]);
            }elseif(strlen($pwd2)<6){
                return json(['code'=>10002,'msg'=>$registermsg['register_error1'],'data'=>[],'url' => []]);
            }elseif(strlen($pwd2)>20){
                return json(['code'=>10003,'msg'=>$registermsg['register_error2'],'data'=>[],'url' => []]);
            }elseif($pwd2!=$password2){
                return json(['code'=>10004,'msg'=>$registermsg['register_error3'],'data'=>[],'url' => []]);
            }elseif(!captcha_check($code2)){
                return json(['code'=>10005,'msg'=>$registermsg['register_error4'],'data'=>[],'url' => []]);
            }else{
                $phone = Session::get('phone');
                $where = [
                    'f_user_phone'=>$phone,
                ];
                $res = Db::table('f_user')->where($where)->find();
                if(!empty($res)){
                    $data = [
                        'f_user_id'=>'',
                        'f_user_phone'=>$phone,
                        'f_user_pwd' =>md5($pwd2),
                        'f_user_name'=>$f_user_name,
                        'f_user_img'=>'__STATIC__/image/0.png',
                        'f_user_money'=>50
                    ];
                    Db::table('f_user')->insert($data);
                    return json(['code'=>10000,'msg'=>$registermsg['register_success'],'data'=>[],'url' => url('reception/Index/index')]);
                }else{
                    return json(['code'=>10007,'msg'=>$registermsg['register_error6'],'data'=>[],'url' => []]);
                }
            }
        }
    }

    //账号登录方法
    public function logincode(){
        $loginmsg = config('msg')['login']; //调用配置文件
        if(input('?post.uname')  && input('?post.pwd') &&  input('?post.code')){
            $uname=input('param.uname');
            $pwd=input('param.pwd');
            $code=input('param.code');
            $phone1 = strip_tags($uname);
            $name = addslashes($phone1);
            $psw = strip_tags($pwd);
            $password = addslashes($psw);

            if(!captcha_check($code)){
                $phone = [
                    'f_user_phone' =>   $name
                ];
                $where = [
                    'f_user_phone' =>   $name,
                    'f_user_pwd' =>   md5($password)
                ];
                $res  = Db::table('f_user')->where($phone)->find();
                if(!empty($res)){
                    $result  = Db::table('f_user')->where($where)->find();
                    if(!empty($result)){
                        return json(['code'=>10000,'msg'=>$loginmsg['login_success'],'data'=>[],'url' =>  url('reception/Index/index')]);
                    }else{
                        return json(['code'=>10001,'msg'=>$loginmsg['login_error2'],'data'=>[],'url' => []]);
                    }
                }else{
                    return json(['code'=>10002,'msg'=>$loginmsg['login_error1'],'data'=>[],'url' => []]);
                }
            }else{
                return json(['code'=>10004,'msg'=>$loginmsg['login_error2'],'data'=>[],'url' => []]);
            }

        }else{
            return json(['code'=>10003,'msg'=>$loginmsg['login_error'],'data'=>[],'url' => []]);
        }


    }


}
