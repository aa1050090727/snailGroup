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

        $resArr = Db::table("f_seller")->field("f_seller_id,f_user_phone,f_seller_idnumber,f_seller_stats,f_province_name,f_city_name,f_district_name,f_seller_site,f_seller_time,f_user_sell")->alias("a")->join("f_user b","a.f_seller_uid=b.f_user_id")->join("f_province p","a.f_seller_pid=p.f_province_id")->join("f_city c","a.f_seller_cid=c.f_city_id")->join("f_district d","a.f_seller_did=d.f_district_id")->where('f_seller_id|f_user_phone|f_seller_idnumber|f_seller_stats|f_province_name|f_city_name|f_district_name|f_seller_site|f_seller_time','like','%'.$keyword.'%')->limit("".$start.",10")->select();

        $resCount = Db::table("f_seller")->field("f_seller_id,f_user_phone,f_seller_idnumber,f_seller_stats,f_province_name,f_city_name,f_district_name,f_seller_site,f_seller_time,f_user_sell")->alias("a")->join("f_user b","a.f_seller_uid=b.f_user_id")->join("f_province p","a.f_seller_pid=p.f_province_id")->join("f_city c","a.f_seller_cid=c.f_city_id")->join("f_district d","a.f_seller_did=d.f_district_id")->where('f_seller_id|f_user_phone|f_seller_idnumber|f_seller_stats|f_province_name|f_city_name|f_district_name|f_seller_site|f_seller_time','like','%'.$keyword.'%')->limit("".$start.",10")->count();


        foreach($resArr as $key=>&$value){
            if($value['f_seller_stats']==1){
                $value['f_seller_stats'] = "景点";
            }else{
                $value['f_seller_stats'] = "酒店";
            }
        }
        foreach($resArr as $key=>&$value){
            if($value['f_user_sell']==2){
                $value['f_user_sell'] = "待审核";
            }elseif($value['f_user_sell']==3){
                $value['f_user_sell'] = "审核未通过";
            }elseif($value['f_user_sell']==4){
                $value['f_user_sell'] = "商家被锁定";
            }elseif($value['f_user_sell']==5){
                $value['f_user_sell'] = "成为卖家";
            }
        }
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';

        return json_decode($resArrJson);
    }

    //商家审核
    public function upSellerStats(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $userPhone = $_GET["userPhone"]?$_GET["userPhone"]:"";
        $stats = $_GET["stats"]?$_GET["stats"]:"";

        $res = Db::table("f_user")->where("f_user_phone",$userPhone)->update(["f_user_sell"=>$stats]);
        if($res !== 0){
            return json(['code'=>10000,'msg'=>$previewMsg["examine_success"],'data'=>'','url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$previewMsg["examine_fail"],'data'=>'','url'=>'']);
        }
    }
}