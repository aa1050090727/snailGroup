<?php
namespace app\reception\controller;

use think\Controller;

//首页控制器
class Index extends Controller
{
    //跳转到首页
    public function index(){
        return $this->fetch();
    }

}

