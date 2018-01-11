<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/26
 * Time: 9:41
 */

namespace app\reception\controller;

use think\Controller;
use think\Db;
use think\Cookie;
use think\Session;
//酒店详情控制器
class Hoteldetails extends Controller
{
    //跳转到酒店详情
    public function hotelDetails(){
        $hotel_id = cookie("hotel_id");
      //  var_dump($hotel_id);
        //酒店详细信息数据
        $data = Db::table("f_hotel")
            ->alias("a")
            ->join("f_province b","a.f_hotel_pid = b.f_province_id")
            ->join("f_city c","a.f_hotel_cid = c.f_city_id")
            ->join("f_district d","a.f_hotel_did = d.f_district_id")
            ->where("f_hotel_id",$hotel_id)
            ->field(['a.*','b.f_province_name','c.f_city_name','d.f_district_name'])
            ->select();
        //评论信息
        $hotelComment = Db::table("f_hotel_comment")
            ->alias('a')
            ->join('f_user b','a.f_hc_user = b.f_user_id')
            ->where("a.f_hc_hid",$hotel_id)
            ->field(['a.*','b.f_user_name','b.f_user_img'])
            ->paginate(2,false,[
                'fragment'=>'comment'
            ]);
        $this->assign('hotelComment', $hotelComment);
        $this->assign('hotelDetail', $data);

        return $this->fetch();
    }
    //加入购物车
    public function putShoppingCar(){
        if(session('?nowlogin')){
            //判断购物车是否已经有该商品，如果有的话，更新数量；如果没有的话新增意见数据
            $hotel_id = input('hotel_id');//酒店id
            $enter_time = input("enter_time");//入住时间
            $leave_time = input("leave_time");//离开时间
            $userId = session("nowlogin");
            //该商品是否被该用户加入购物车过
            $result = Db::table("f_shopping_cart")
                ->where("f_shopping_cart_gid",$hotel_id)
                ->where("f_shopping_cart_uid",$userId)
                ->where("f_shopping_cart_classid",2)
                ->select();
//            var_dump(empty($result));exit;
            //用户没有添加过该商品
            if(empty($result)){
                //写入购物车--insert--提示加入成功
                $data = [
                    "f_shopping_cart_id"=>null,
                    "f_shopping_cart_classid"=>2,
                    "f_shopping_cart_gid"=>$hotel_id,
                    "f_shopping_cart_uid"=>$userId,
                    "f_shopping_cart_uum"=>1,
                    "f_shopping_cart_entering"=>$enter_time,
                    "f_shopping_cart_leave"=>$leave_time
                ];
                $putShoppingCar = Db::table("f_shopping_cart")->fetchSql(true)->insert($data);
                //var_dump($putShoppingCar);exit;
                if($putShoppingCar){
                    return json(["code"=>1,"tips"=>"添加成功"]);
                }
                else{
                    return json(["code"=>2,"tips"=>"添加失败"]);
                }
            }
            else{
                //用户已经添加过该商品
                $updateShoppingCar = Db::table('f_shopping_cart')
                    ->where("f_shopping_cart_gid",$hotel_id)
                    ->where("f_shopping_cart_uid",$userId)
                    ->where("f_shopping_cart_classid",2)
                    ->setInc('f_shopping_cart_uum');
//                var_dump($updateShoppingCar);exit;
                if($updateShoppingCar == 1){
                    return json(["code"=>1,"tips"=>"添加成功"]);
                }
                else{
                    return json(["code"=>2,"tips"=>"添加失败"]);
                }
            }
        }
        else{
            return json(["code"=>3,"tips"=>"您还没有登录"]);
        }

    }
    //立即购买
    public function rightnowBuy(){
        if(Session::has('nowlogin')){
            //生成订单--跳转页面
            $hotel_id = input('hotel_id');//酒店id
            $hotel_price = input('hotel_price');//酒店price
            $hotel_sid = input('hotel_sid');//酒店sid
            $enter_time = input("enter_time");//入住时间
            $leave_time = input("leave_time");//离开时间
            $userId = session("nowlogin");
            date_default_timezone_set("PRC");
            $timept=date("Y-m-d",time());
            //生成订单
            $data = [
                "b_order_id"=>null,
                "b_order_user"=>$userId,
                "b_order_total_price"=>$hotel_price,
                "b_order_total_number"=>1,
                "b_order_time"=>$timept,
                "b_order_state"=>"未支付"
            ];
            $insertOrder = Db::table("b_order")->insert($data);
            if($insertOrder){
                $orderId = Db::table('b_order')->getLastInsID();
//                var_dump($orderId);exit;
                $data_details = [
                    "b_order_details_id"=>null,
                    "b_order_details_classid"=>2,
                    "b_order_details_gid"=>$hotel_id,
                    "b_order_details_num"=>1,
                    "b_order_details_price"=>$hotel_price,
                    "b_order_details_sid"=>$hotel_sid,
                    "b_order_details_uid"=>$userId,
                    "b_order_details_oid"=>$orderId,
                    "b_order_details_entering"=>$enter_time,
                    "b_order_details_leave"=>$leave_time
                ];
                $insertOrder_details = Db::table("b_order_details")->insert($data_details);
                if($insertOrder_details){
                    return json(["code"=>1,"tips"=>"添加成功",'orderId'=>$orderId]);
                }
                else{
                    return json(["code"=>2,"tips"=>"添加失败",'orderId'=>'']);
                }
            }
        }
        else{
            return json(["code"=>3,"tips"=>"您还没有登录",'orderId'=>'']);
        }
    }
}