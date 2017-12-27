<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/26
 * Time: 9:41
 */

namespace app\reception\controller;

use think\Controller;

//酒店详情控制器
class Hoteldetails extends Controller
{
    //跳转到酒店详情
    public function hotelDetails(){
        return $this->fetch();
    }
}