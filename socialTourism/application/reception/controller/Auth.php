<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/25
 * Time: 16:15
 */

namespace app\reception\controller;


use think\Controller;

class Auth extends Controller
{
    //跳转到首页
    public function index(){
        return $this->fetch();
    }

}