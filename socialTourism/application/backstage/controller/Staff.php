<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/28
 * Time: 0:25
 */

namespace app\backstage\controller;


use think\Controller;

class Staff extends Controller
{
    public function staff(){
        return $this->fetch();
    }

}