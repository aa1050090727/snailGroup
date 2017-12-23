<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/22
 * Time: 15:24
 */
namespace app\backstage\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}