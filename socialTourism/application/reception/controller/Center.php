<?php
/**
 * Created by PhpStorm.
 * User: fire
 * Date: 2017/12/26
 * Time: 18:51
 */

namespace app\reception\controller;
use think\Controller;

class Center extends Controller
{
    public function center(){
        return $this->fetch();
    }
}