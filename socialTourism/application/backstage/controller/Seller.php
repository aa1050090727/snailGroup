<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2018/1/14
 * Time: 22:20
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;

class Seller extends Controller
{
    public function seller(){
        return $this->fetch();
    }
    //获取商家信息
    public function getSeller(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;

        $resArr = Db::table("f_seller")->field("f_seller_id,f_user_phone,f_seller_idnumber,f_seller_stats,f_province_name,f_city_name,f_district_name,f_seller_site,f_seller_time")->alias("a")->join("f_user b","a.f_seller_uid=b.f_user_id")->join("f_province p","a.f_seller_pid=p.f_province_id")->join("f_city c","a.f_seller_cid=c.f_city_id")->join("f_district d","a.f_seller_did=d.f_district_id")->where('f_seller_id|f_user_phone|f_seller_idnumber|f_seller_stats|f_province_name|f_city_name|f_district_name|f_seller_site|f_seller_time','like','%'.$keyword.'%')->limit("".$start.",10")->select();

        $resCount = Db::table("f_seller")->field("f_seller_id,f_user_phone,f_seller_idnumber,f_seller_stats,f_province_name,f_city_name,f_district_name,f_seller_site,f_seller_time")->alias("a")->join("f_user b","a.f_seller_uid=b.f_user_id")->join("f_province p","a.f_seller_pid=p.f_province_id")->join("f_city c","a.f_seller_cid=c.f_city_id")->join("f_district d","a.f_seller_did=d.f_district_id")->where('f_seller_id|f_user_phone|f_seller_idnumber|f_seller_stats|f_province_name|f_city_name|f_district_name|f_seller_site|f_seller_time','like','%'.$keyword.'%')->limit("".$start.",10")->count();


//        foreach($resArr as $key=>&$value){
//            if($value['f_user_sell']==1){
//                $value['f_user_sell'] = "是";
//            }else{
//                $value['f_user_sell'] = "否";
//            }
//        }
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';

        return json_decode($resArrJson);
    }
}