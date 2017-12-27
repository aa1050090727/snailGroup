<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/22
 * Time: 15:24
 */
namespace app\backstage\controller;

use think\Controller;
use \think\Db;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }


    public function login(){
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
                return json(['code'=>10004,'msg'=>$loginmsg['login_error4'],'data'=>[],'url' => []]);
            }else{

                $phone = [
                    'b_user_account' =>   $name
                ];
                $where = [
                    'b_user_account' =>   $name,
                    'b_user_pwd' =>   md5($password)
                ];
                $my =[
                    'b_user_account' =>   $name,
                    'b_user_status' => '使用'
                ];
                $res  = Db::table('b_user')->where($phone)->find();
                if(!empty($res)){
                    $res  = Db::table('b_user')->where($my)->find();
                    if(!empty($res)){
                        $result  = Db::table('b_user')->where($where)->find();
                        if(!empty($result)){
                            return json(['code'=>10000,'msg'=>$loginmsg['login_success'],'data'=>[],'url' =>  url('backstage/Index/index')]);
                        }else{
                            return json(['code'=>10001,'msg'=>$loginmsg['login_error2'],'data'=>[],'url' => []]);
                        }
                    }else{
                        return json(['code'=>10005,'msg'=>$loginmsg['login_error3'],'data'=>[],'url' => []]);
                    }

                }else{
                    return json(['code'=>10002,'msg'=>$loginmsg['login_error1'],'data'=>[],'url' => []]);
                }
            }

        }else{
            return json(['code'=>10003,'msg'=>$loginmsg['login_error'],'data'=>[],'url' => []]);
        }
    }
}