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
        return $this->fetch();
    }
}