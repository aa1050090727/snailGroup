<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2018/1/12
 * Time: 15:48
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;

class Statistical extends Controller
{
    public function sellerStatistical(){
        return $this->fetch();
    }
    public function userStatistical(){
        return $this->fetch();
    }
    public function businessStatistical(){
        return $this->fetch();
    }
    //普通用户统计
    public function userCount(){
        $arr=[];
        for($i=1;$i<=12;$i++){
            $num = Db::query("SELECT count(*) count from f_user WHERE YEAR(f_user_time)=YEAR(NOW()) AND MONTH(f_user_time)= {$i}");
            $arr[]=$num[0]["count"];
        }
        return $arr;
    }
    //商家统计
    public function sellerCount(){
        $arr=[];
        for($i=1;$i<=12;$i++){
            $num = Db::query("SELECT count(*) count from f_seller WHERE f_seller_stats = 5 AND YEAR(f_seller_time)=YEAR(NOW()) AND MONTH(f_seller_time)= {$i}");
            $arr[]=$num[0]["count"];
        }
        return $arr;
    }
    //营业额统计
    public function businessCount(){
        $arr=[];
        for($i=1;$i<=12;$i++){
            $num = Db::query("SELECT SUM(b_order_total_price) money from b_order WHERE (b_order_state = '已支付' OR b_order_state = '交易成功') AND YEAR(b_order_time)=YEAR(NOW()) AND MONTH(b_order_time)= {$i}");
            $arr[]=$num[0]["money"];
        }
        return $arr;
    }
}