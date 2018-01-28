<?php

/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2018/1/22
 * Time: 11:13
 */
namespace app\weixin\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function getADS(){
        $resArr = Db::table("f_ads")->where("f_ads_states","使用")->select();
        //var_dump($resArr);
        if($resArr != null){
            return json(['code'=>10000,'msg'=>'','data'=>$resArr,'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>'','data'=>'','url'=>'']);
        }

    }
}