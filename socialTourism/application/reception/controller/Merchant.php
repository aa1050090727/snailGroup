<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 15:55
 */

namespace app\reception\controller;

use think\Controller;
class merchant extends Controller
{
    public function merchant(){
        return $this->fetch();
    }
}