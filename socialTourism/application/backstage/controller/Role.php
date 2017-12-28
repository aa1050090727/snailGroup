<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/28
 * Time: 0:19
 */

namespace app\backstage\controller;


use think\Controller;

class Role extends Controller
{
    public function role(){
        return $this->fetch();
    }

}