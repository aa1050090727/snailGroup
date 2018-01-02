<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 15:19
 */

namespace app\backstage\controller;

use think\Controller;
use think\Db;
class Science extends Controller
{
    public function science(){
        return $this->fetch();
    }
    public function getScience(){
        $page = $_GET["page"];
        $limit = $_GET["limit"];
        $start = ($page -1)*$limit;
//        var_dump($page);
//        var_dump($limit);
        $resArr = Db::table("f_science")->limit($start,10)->select();
        $resCount = Db::table("f_science")->count();
        //var_dump($resArr);
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }

}