<?php
/**
 * Created by PhpStorm.
 * User: fire
 * Date: 2017/12/26
 * Time: 18:51
 */

namespace app\reception\controller;
use think\Controller;
use think\Session;
use think\Db;

class Center extends Controller
{
    public function center(){
        return $this->fetch();
    }


    public function getmy(){
        session_start();
        $nowlogin = Session::get('nowlogin');
        $where =[
            'f_user_id' => $nowlogin
        ];
        $res =  Db::table('f_user')->where($where)->find();
        return $res;
    }

    public function updatamyname(){
        if(input('?post.nowname') && input('?post.id')){
            $uname=input('param.nowname');//接收
            $id=input('param.id');//接收
            $phone1 = strip_tags($uname);
            $name = addslashes($phone1);
            if(!isset($name{8})){
                $name =[
                    'f_user_name' => $name
                ];
                $where= [
                    'f_user_phone' => $id
                ];
                $res = Db::table('f_user')->where($where)->update($name);
                echo $res;
            }else{
                echo 5;
            }

        }
    }

    public function updatamypaw(){
        $uppwdmsg = config('msg')['uppwd']; //调用配置文件
        if(input('?post.id') && input('?post.paw') && input('?post.pwd') && input('?post.pass')){
            $id=input('param.id');//接收
            $paw=input('param.paw');//接收
            $pwd=input('param.pwd');//接收
            $pass=input('param.pass');//接收
            $paw = strip_tags($paw);
            $paw = addslashes($paw);
            $pwd = strip_tags($pwd);
            $pwd = addslashes($pwd);
            $pass = strip_tags($pass);
            $pass = addslashes($pass);
            if(strlen($paw)<6){
                return json(['code'=>10001,'msg'=>$uppwdmsg['uppwd_error'],'data'=>[],'url' => []]);
            }elseif(strlen($paw)>20){
                return json(['code'=>10002,'msg'=>$uppwdmsg['uppwd_error1'],'data'=>[],'url' => []]);
            }elseif($paw!=$pass){
                return json(['code'=>10003,'msg'=>$uppwdmsg['uppwd_error2'],'data'=>[],'url' => []]);
            }else{
                $where = [
                    'f_user_phone' => $id,
                    'f_user_pwd'=>md5($pwd)
                ];
                $res =  Db::table('f_user')->where($where)->find();
                if($res){
                    $where =[
                        'f_user_phone' => $id
                    ];
                    $uppwd = [
                        'f_user_pwd'=>md5($pass)
                    ];
                    $res = Db::table('f_user')->where($where)->update($uppwd);
                    if($res){
                        Session::delete('nowlogin');
                        return json(['code'=>10000,'msg'=>$uppwdmsg['uppwd_success'],'data'=>[],'url' => url('reception/Index/index')]);
                    }else{
                        return json(['code'=>10005,'msg'=>$uppwdmsg['uppwd_error4'],'data'=>[],'url' => []]);
                    }
                }else{
                    return json(['code'=>10004,'msg'=>$uppwdmsg['uppwd_error3'],'data'=>[],'url' => []]);
                }
            }

        }
    }

    public function travels(){
        $search=Session::set("travelSearch",'');
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->paginate(3);
        $this->assign("list",$list);
        return $this->fetch();
    }
}