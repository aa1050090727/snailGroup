<?php
namespace app\reception\controller;

use think\Controller;

class Index extends Controller
{
    public function index(){
        return $this->fetch();
    }
    public function viewport(){
        return $this->fetch("viewport");
    }
}
