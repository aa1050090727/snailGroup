<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/28
 * Time: 0:25
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;
use think\Session;

class Staff extends Controller
{
    public function staff(){
        return $this->fetch();
    }
    //获取员工信息
    public function getStaff(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;

        $resArr = Db::table("b_user")->field("b_user_id,b_user_account,b_user_name,b_user_role_id,b_user_status,b_user_head,b_role_name")->alias("a")->join("b_role b","a.b_user_role_id=b.b_role_id")->where('b_user_id|b_user_account|b_user_name|b_role_name|b_user_status','like','%'.$keyword.'%')->limit("".$start.",10")->select();

        $resCount = Db::table("b_user")->field("b_user_id,b_user_account,b_user_name,b_user_role_id,b_user_status,b_user_head,b_role_name")->alias("a")->join("b_role b","a.b_user_role_id=b.b_role_id")->where('b_user_id|b_user_account|b_user_name|b_role_name|b_user_status','like','%'.$keyword.'%')->count();
//        var_dump($resArr);exit;
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
//        foreach($resArrJson as $key=>$value){
//            if($value['statu'] == 1){
//                $value['statu'] = '使用';
//            }
//        }/
        return json_decode($resArrJson);
    }
    //删除员工
    public function delStaff(){
        $delmsg = config('msg')['delmsg']; //调用配置文件
        $userId = isset($_GET["userId"])?$_GET["userId"]:"";
//        var_dump($userId);
        $delRes = Db::table('b_user')->delete($userId);
//        var_dump($res);

        if($delRes !== 0){
            return json(['code'=>10000,'msg'=>$delmsg["del_success"],'data'=>'','url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$delmsg["del_fail"],'data'=>'','url'=>'']);
        }
    }
    //修改员工状态
    public function upStaffStatus(){
        $upmsg = config('msg')['upmsg']; //调用配置文件
        $status = isset($_GET["status"])?$_GET["status"]:"";
        $staffId = isset($_GET["staffId"])?$_GET["staffId"]:"";
//        var_dump($status,$staffId);
        $backstageUser = Session::get("backstageUser");
//        var_dump($backstageUser["b_user_role_id"]);exit;
        if($staffId != $backstageUser["b_user_id"]){
            $res = Db::table('b_user')->where('b_user_id', $staffId)->update(['b_user_status' => $status]);
            if($res !== 0){
                return json(['code'=>10000,'msg'=>$upmsg["up_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10001,'msg'=>$upmsg["up_fail"],'data'=>'','url'=>'']);
            }
        }else{
            return json(['code'=>10002,'msg'=>$upmsg["up_error"],'data'=>'','url'=>'']);
        }

    }
    //重置密码
    public function upStaffPsw(){
        $upmsg = config('msg')['upmsg']; //调用配置文件
        $staffId = isset($_GET["staffId"])?$_GET["staffId"]:"";
        $res = Db::table('b_user')->where('b_user_id', $staffId)->update(['b_user_pwd' => md5("666666")]);
        if($res !== 0){
            return json(['code'=>10000,'msg'=>$upmsg["up_success"],'data'=>'','url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$upmsg["up_fail"],'data'=>'','url'=>'']);
        }
    }
    //修改员工信息
    public function upStaff(){
        $upmsg = config('msg')['upmsg']; //调用配置文件
        $staffArr = isset($_GET["staffArr"])?$_GET["staffArr"]:"";
        //var_dump($staffArr);
        $res = Db::table('b_user')->where('b_user_id', $staffArr['id'])->update(['b_user_name' => $staffArr['name'],'b_user_role_id' => $staffArr['role']]);
        if($res !== 0){
            $roleRes = Db::table('b_role')->field('b_role_name')->where('b_role_id',$staffArr['role'])->select();
            return json(['code'=>10000,'msg'=>$upmsg["up_success"],'data'=>$roleRes,'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$upmsg["up_fail"],'data'=>'','url'=>'']);
        }
    }
    //添加员工
    public function addStaff(){
        $addmsg = config('msg')['addmsg']; //调用配置文件
        $staffArr = isset($_GET["staffArr"])?$_GET["staffArr"]:"";
//        var_dump($staffArr);
        $count = Db::table('b_user')->where('b_user_account',$staffArr['staffAccount'])->count();
        if($count === 0){
            $data = [
                'b_user_account' => $staffArr['staffAccount'],
                'b_user_pwd' => md5('666666'),
                'b_user_name' => $staffArr['staffName'],
                'b_user_role_id' => $staffArr['role'],
                'b_user_status' => '使用',
                'b_user_head' => '__STATIC__/image/qq.png'
            ];
            $res = Db::table('b_user')->insert($data);
            if($res === 1){
                return json(['code'=>10000,'msg'=>$addmsg["add_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10001,'msg'=>$addmsg["add_fail"],'data'=>'','url'=>'']);
            }
        }else{
            return json(['code'=>10002,'msg'=>$addmsg["add_error"],'data'=>'','url'=>'']);
        }
    }
}