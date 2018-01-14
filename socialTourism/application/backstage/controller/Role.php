<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/28
 * Time: 0:19
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;
use think\Exception;

class Role extends Controller
{
    public function role(){
        return $this->fetch();
    }
    //获取角色信息
    public function getRole(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $start = ($page - 1)*$limit;

        $resArr = Db::table("b_role")->limit("".$start.",10")->select();
        $resCount = Db::table("b_role")->count();
         //var_dump($resArr);
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }
    public function getRoleName(){
        $resArr = Db::table("b_role")->field("b_role_id,b_role_name")->select();
        return $resArr;
    }
    //删除角色
    public function delRole(){
        $delMsg = config('msg')['delmsg']; //调用配置文件
        $roleId = $_GET["roleId"]?$_GET["roleId"]:"";
        try {
            Db::table('b_role')->where('b_role_id', $roleId)->delete();
            return json(['code'=>10000,'msg'=>$delMsg["del_success"],'data'=>'','url'=>'']);
        }catch(Exception $e){
            return json(['code'=>10001,'msg'=>$delMsg["del_fail"],'data'=>'','url'=>'']);
        }
    }
    //根据ID获取角色信息
    public function getIdRole(){
        $openMsg = config('msg')['open_msg']; //调用配置文件
        $roleId = $_GET["roleId"]?$_GET["roleId"]:"";
        $resArr = Db::table("b_role")->where("b_role_id",$roleId)->select();
        if($resArr != null){
            return json(['code'=>10000,'msg'=>$openMsg["open_success"],'data'=>$resArr[0],'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$openMsg["open_fail"],'data'=>'','url'=>'']);
        }
    }
    //修改角色信息
    public function upRole(){
        $roleMsg = config('msg')['role_msg']; //调用配置文件
        $roleArr = $_GET["roleArr"] ? $_GET["roleArr"] : "";
        $roleName = $_GET["roleName"] ? $_GET["roleName"] : "";

        //var_dump($roleArr, $roleName);
        if($roleArr["name"] === $roleName){
            $res = Db::table('b_role')->where('b_role_id', $roleArr["id"])->update(['b_role_des' => $roleArr["desc"]]);
            if($res !== 0){
                return json(['code'=>10000,'msg'=>$roleMsg["up_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10003,'msg'=>$roleMsg["up_fail"],'data'=>'','url'=>'']);
            }
        }else{
            if($roleArr["id"] !== "1"){
                $count = Db::table('b_role')->where('b_role_name', $roleArr['name'])->count();
                if($count === 0){
                    $res = Db::table('b_role')->where('b_role_id', $roleArr["id"])->update(['b_role_name' => $roleArr['name'],'b_role_des' => $roleArr["desc"]]);
                    if($res !== 0){
                        return json(['code'=>10000,'msg'=>$roleMsg["up_success"],'data'=>'','url'=>'']);
                    }else{
                        return json(['code'=>10003,'msg'=>$roleMsg["up_fail"],'data'=>'','url'=>'']);
                    }
                }else{
                    return json(['code' => 10002, 'msg' => $roleMsg["role_fail"], 'data' => '', 'url' => '']);
                }
            }else{
                return json(['code'=>10001,'msg'=>$roleMsg["role_error"],'data'=>'','url'=>'']);
            }
        }
    }
    //添加角色
    public function addRole(){
        $roleMsg = config('msg')['role_msg']; //调用配置文件
        $addRoleArr= isset($_GET["addRoleArr"])?$_GET["addRoleArr"]:"";
//        var_dump($staffArr);
        $count = Db::table('b_role')->where('b_role_name',$addRoleArr['name'])->count();
        if($count === 0){
            $data = [
                'b_role_name' => $addRoleArr['name'],
                'b_role_des' => $addRoleArr['desc']
            ];
            $res = Db::table('b_role')->insert($data);
            if($res === 1){
                return json(['code'=>10000,'msg'=>$roleMsg["add_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10001,'msg'=>$roleMsg["add_fail"],'data'=>'','url'=>'']);
            }
        }else{
            return json(['code'=>10002,'msg'=>$roleMsg["role_fail"],'data'=>'','url'=>'']);
        }
    }
}