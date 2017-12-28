<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/27
 * Time: 9:47
 */

namespace app\backstage\controller;


use think\Controller;

class Home extends Controller
{
    public function home(){
        return $this->fetch();
    }
    public function main(){
        return $this->fetch();
    }
}