<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/27
 * Time: 18:54
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;

class User extends Controller
{
    public function user(){
        return $this->fetch();
    }
    //获取用户信息
    public function getUser(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;

        $resArr = Db::table("f_user")->field("f_user_id,f_user_phone,f_user_name,f_user_money,f_user_sex,f_user_sell,f_user_states")->where('f_user_id|f_user_phone|f_user_name|f_user_money|f_user_sex|f_user_sell|f_user_states','like','%'.$keyword.'%')->limit("".$start.",10")->select();

        $resCount = Db::table("f_user")->field("f_user_id,f_user_phone,f_user_name,f_user_money,f_user_sex,f_user_sell,f_user_states")->where('f_user_id|f_user_phone|f_user_name|f_user_money|f_user_sex|f_user_sell|f_user_states','like','%'.$keyword.'%')->limit("".$start.",10")->count();

        //var_dump($resArr);exit;

        foreach($resArr as $key=>&$value){
            if($value['f_user_sell']==1){
                $value['f_user_sell'] = "是";
            }else{
                $value['f_user_sell'] = "否";
            }
        }
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';

        return json_decode($resArrJson);
    }
    //重置密码
    public function resetPsw(){
        $upMsg = config('msg')['upmsg']; //调用配置文件
        $userId = $_GET["userId"]?$_GET["userId"]:"";
        $res = Db::table('f_user')->where('f_user_id', $userId)->update(['f_user_pwd' => md5("666666")]);
        if($res !== 0){
            return json(['code'=>10000,'msg'=>$upMsg["up_success"],'data'=>'','url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$upMsg["up_fail"],'data'=>'','url'=>'']);
        }
    }
    //修改用户状态
    public function upUserState(){
        $upMsg = config('msg')['upmsg']; //调用配置文件
        $userId = isset($_GET["userId"])?$_GET["userId"]:"";
        $status = isset($_GET["status"])?$_GET["status"]:"";
        //var_dump($userId,$status);
        $res = Db::table('f_user')->where('f_user_id', $userId)->update(['f_user_states' => $status]);
        if($res !== 0){
            return json(['code'=>10000,'msg'=>$upMsg["up_success"],'data'=>'','url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$upMsg["up_fail"],'data'=>'','url'=>'']);
        }
//        $backstageUser = Session::get("backstageUser");
////        var_dump($backstageUser["b_user_role_id"]);exit;
//        if($staffId != $backstageUser["b_user_id"]){
//            $res = Db::table('b_user')->where('b_user_id', $staffId)->update(['b_user_status' => $status]);
//            if($res !== 0){
//                return json(['code'=>10000,'msg'=>$upmsg["up_success"],'data'=>'','url'=>'']);
//            }else{
//                return json(['code'=>10001,'msg'=>$upmsg["up_fail"],'data'=>'','url'=>'']);
//            }
//        }else{
//            return json(['code'=>10002,'msg'=>$upmsg["up_error"],'data'=>'','url'=>'']);
//        }

    }
}