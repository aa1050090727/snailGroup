<?php
namespace app\reception\controller;

use think\Controller;

class Travels extends Controller
{
    public function travels(){
        return $this->fetch();
    }
    public function travelPublic(){
        return $this->fetch();
    }
    public function travelDetails(){
        return $this->fetch();
    }
}