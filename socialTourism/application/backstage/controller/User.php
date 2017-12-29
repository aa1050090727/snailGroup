<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/27
 * Time: 18:54
 */

namespace app\backstage\controller;


use think\Controller;

class User extends Controller
{
    public function user(){
        return $this->fetch();
    }
}