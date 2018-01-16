<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2017/12/27
 * Time: 9:47
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;
use think\Session;

class Home extends Controller
{
    public function home(){

        return $this->fetch();
    }
    public function main(){
        return $this->fetch();
    }
    public function getThisUser(){
        $backstageUser = Session::get("backstageUser");
        if(!empty($backstageUser)){
            $retArr = Db::table('b_rm')->alias('a')->join('b_menu b','a.b_rm_menu_id=b.b_menu_id')->where("a.b_rm_role_id",$backstageUser["b_user_role_id"])->select();
            return json(['code'=>10000,'msg'=>'','data'=>$retArr,'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>'','data'=>'','url'=>url('backstage/Index/index')]);
        }
//        var_dump($backstageUser);
        //echo json_encode($backstageUser);
    }
    public function build(){

    }
}