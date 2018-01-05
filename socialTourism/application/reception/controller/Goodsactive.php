<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 10:15
 */

namespace app\reception\controller;

use think\Controller;//使用基类
use think\Db;//使用数据库
use think\Request;//使用请求类
use think\Cookie;
use think\Session;
class GoodsActive extends Controller
{
    public function goodsActive(){
        //找出景点中活动的数据--活动景点、在售景点、活动时间结束时间大于当前时间//默认景点活动
        $viewData = Db::table("f_science")
            ->where("f_science_states",2)
            ->where("f_science_state",1)
            ->where('f_science_aendtime','>= time','now')
            ->paginate(2);
        $this->assign("active",$viewData);
        return $this->fetch();
    }

    public function goodsActive_hotel(){
        //找出酒店中活动的数据
        $hotelData = Db::table("f_hotel")
            ->where("f_hotel_states",2)
            ->where("f_hotel_state",1)
            ->where('f_hotel_aendtime','>= time','now')
            ->paginate(2);
        $this->assign("active",$hotelData);
        return $this->fetch("goodsActive");
    }
    //存景点id
    public function setViewId(){
        $f_science_id = isset($_POST["f_science_id"])?$_POST["f_science_id"]:"";
        Cookie("science_id",$f_science_id);
        if(Cookie::has("science_id")){
            return json(["result"=>true]);
        }
        else{
            return json(["result"=>false]);
        }
    }
    //cun酒店id
    public function hotel_setCookie(){
        $id = input("f_hotel_id");
        cookie("hotel_id",$id);
        $result = cookie("?hotel_id");
        if($result){
            return json(["code"=>1]);
        }
        else{
            return json(["code"=>2]);
        }
    }
}