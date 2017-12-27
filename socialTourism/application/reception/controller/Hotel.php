<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/25
 * Time: 15:48
 */

namespace app\reception\controller;


use think\Controller;

class Hotel extends Controller
{
    //跳转到酒店
    public function hotel(){
        return $this->fetch("hotel");
    }
}