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

class Role extends Controller
{
    public function role(){
        return $this->fetch();
    }
    public function getRole(){
        $page = $_GET["page"];
        $limit = $_GET["limit"];
//        var_dump($page);
//        var_dump($limit);
        $resArr = Db::table("b_role")->limit("0,10")->select();
        $resCount = Db::table("b_role")->count();
         //var_dump($resArr);
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }

}