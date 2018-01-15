<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2018/1/11
 * Time: 16:51
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;

class Order extends Controller
{
    public function orderHas(){
        return $this->fetch();
    }
    public function orderNot(){
        return $this->fetch();
    }
    //获取未支付订单信息
    public function getNotPay(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;
//        var_dump($page);
//        var_dump($limit);
        $resArr = Db::table("b_order")->field("b_order_id,b_order_total_price,b_order_total_number,b_order_time,b_order_state,f_user_phone")->alias("a")->join("f_user b","a.b_order_user=b.f_user_id")->where("b_order_state='未支付' or b_order_state='交易关闭'")->where('b_order_id|b_order_total_price|b_order_total_number|b_order_time|b_order_state|f_user_phone','like','%'.$keyword.'%')->limit($start,10)->select();

        $resCount = Db::table("b_order")->field("b_order_id,b_order_total_price,b_order_total_number,b_order_time,b_order_state,f_user_phone")->alias("a")->join("f_user b","a.b_order_user=b.f_user_id")->where("b_order_state='未支付' or b_order_state='交易关闭'")->where('b_order_id|b_order_total_price|b_order_total_number|b_order_time|b_order_state|f_user_phone','like','%'.$keyword.'%')->count();

        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }
    //通过ID获取订单详情
    public function getIdPay(){
        $orderId = $_GET["orderId"]?$_GET["orderId"]:"";
        $resArr = Db::table("b_order_details")->field("b_order_details_id,b_order_details_classid,b_order_details_gid,b_order_details_num,b_order_details_price,b_order_details_entering,b_order_details_leave,f_user_phone")->alias("a")->join("f_seller b","a.b_order_details_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->where("b_order_details_oid",$orderId)->select();

        foreach($resArr as $key=>&$value){
            if($value["b_order_details_classid"] == 1 ){
                $value["b_order_details_classid"] = "酒店";
            }else{
                $value["b_order_details_classid"] = "景点";
            }
        }
        return json(['code'=>10001,'msg'=>'','data'=>$resArr,'url'=>'']);
    }

    //获取已支付订单信息
    public function getHasPay(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;
//        var_dump($page);
//        var_dump($limit);
        $resArr = Db::table("b_order")->field("b_order_id,b_order_total_price,b_order_total_number,b_order_time,b_order_state,f_user_phone")->alias("a")->join("f_user b","a.b_order_user=b.f_user_id")->where("b_order_state='已支付' or b_order_state='交易成功'")->where('b_order_id|b_order_total_price|b_order_total_number|b_order_time|b_order_state|f_user_phone','like','%'.$keyword.'%')->limit($start,10)->select();

        $resCount = Db::table("b_order")->field("b_order_id,b_order_total_price,b_order_total_number,b_order_time,b_order_state,f_user_phone")->alias("a")->join("f_user b","a.b_order_user=b.f_user_id")->where("b_order_state='已支付' or b_order_state='交易成功'")->where('b_order_id|b_order_total_price|b_order_total_number|b_order_time|b_order_state|f_user_phone','like','%'.$keyword.'%')->count();

        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }


}