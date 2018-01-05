<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/25
 * Time: 15:48
 */

namespace app\reception\controller;

use think\Controller;
use think\Db;
use think\Cookie;
use think\Session;
class Hotel extends Controller
{
    //跳转到酒店
    public function hotel(){
        $hotel = Db::table("f_hotel")
                ->where("f_hotel_state",1)
                ->paginate(4,false,[
                'fragment'=>'hotel'
            ]);
        $this->assign('hotel', $hotel);
        return $this->fetch("hotel");
    }
    //存id
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