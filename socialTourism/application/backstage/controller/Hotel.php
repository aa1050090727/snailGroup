<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2018/1/8
 * Time: 17:28
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;

class Hotel extends Controller
{
    public function hotel(){
        return $this->fetch();
    }
    //获取酒店信息
    public function getHotel(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;
//        var_dump($page);
//        var_dump($limit);
        $resArr = Db::table("f_hotel")->field("f_hotel_id,f_hotel_name,f_hotel_state,f_hotel_states,f_hotel_num,f_hotel_price,f_hotel_time,f_user_phone")->alias("a")->join("f_seller b","a.f_hotel_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->where('f_hotel_id|f_hotel_name|f_hotel_state|f_hotel_states|f_hotel_num|f_hotel_price|f_user_phone|f_hotel_time','like','%'.$keyword.'%')->limit($start,10)->select();
        $resCount = Db::table("f_hotel")->field("f_hotel_id,f_hotel_name,f_hotel_state,f_hotel_states,f_hotel_num,f_hotel_price,f_hotel_time,f_user_phone")->alias("a")->join("f_seller b","a.f_hotel_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->where('f_hotel_id|f_hotel_name|f_hotel_state|f_hotel_states|f_hotel_num|f_hotel_price|f_user_phone|f_hotel_time','like','%'.$keyword.'%')->count();

        foreach($resArr as $key=>&$value){
            if($value['f_hotel_state']==1){
                $value['f_hotel_state'] = "审核通过";
            }
            elseif($value['f_hotel_state']==2){
                $value['f_hotel_state'] = "待审核";
            }
            elseif($value['f_hotel_state']==3){
                $value['f_hotel_state'] = "审核未通过";
            }
            else{
                $value['f_hotel_state'] = "商品下架";
            }
            if($value['f_hotel_states'] == 1){
                $value['f_hotel_states'] = "普通";
            }else{
                $value['f_hotel_states'] = "活动";
            }
        }
        //var_dump($resArr);
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }
    //酒店预览
    public function previewHotel(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $hotelId = $_GET["hotelId"]?$_GET["hotelId"]:"";
        $resArr = Db::table("f_hotel")->field("f_hotel_id,f_hotel_name,f_hotel_states,f_hotel_img,f_hotel_content,f_hotel_detall,f_hotel_num,f_hotel_price,f_hotel_aprice,f_hotel_astarttime,f_hotel_aendtime,f_hotel_time,f_user_phone,f_province_name,f_city_name,f_district_name")->alias("a")->join("f_seller b","a.f_hotel_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->join("f_province p","a.f_hotel_pid=p.f_province_id")->join("f_city s","a.f_hotel_cid=s.f_city_id")->join("f_district d","a.f_hotel_did=d.f_district_id")->where("f_hotel_id",$hotelId)->find();
        if($resArr != null){
            return json(['code'=>10000,'msg'=>$previewMsg["preview_success"],'data'=>$resArr,'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$previewMsg["preview_fail"],'data'=>'','url'=>'']);
        }
    }
    //景点审核
    public function examineHotel(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $hotelId = $_GET["hotelId"]?$_GET["hotelId"]:"";
        $hotelState = $_GET["hotelState"]?$_GET["hotelState"]:"";
//        var_dump($travelsId,$travelsState);exit;
        $res = Db::table("f_hotel")->where("f_hotel_id",$hotelId)->update(["f_hotel_state"=>$hotelState]);
        if($res !== 0){
            if($hotelState === "4"){
                return json(['code'=>10000,'msg'=>$previewMsg["del_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10000,'msg'=>$previewMsg["examine_success"],'data'=>'','url'=>'']);
            }
        }else{
            if($hotelState === "4"){
                return json(['code'=>10001,'msg'=>$previewMsg["del_fail"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10001,'msg'=>$previewMsg["examine_fail"],'data'=>'','url'=>'']);
            }

        }

    }
}