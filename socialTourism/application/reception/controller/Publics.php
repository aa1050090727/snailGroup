<?php
namespace app\reception\controller;
use think\Controller;
/*引用内置查询*/
use think\Db;
/*引用输出文件*/
use think\Response;
/*session引用*/
use think\Session;
/*引用请求类*/
use \think\Request;
class Publics extends Controller{
    /*商家入驻*/
    public function seller(){
        $nowlogin=Session::get("nowlogin");
        if(empty($nowlogin))
        {
            return json(['code'=>1,'msg'=>'请先登录！','data'=>[],'url' => []]);
        }else{
            $sell=Db::table('f_user')->where('f_user_id',$nowlogin)->select();
            /*1未申请、2未审核、3未通过、4被禁用*/
            if($sell[0]['f_user_sell']=="1" || $sell[0]['f_user_sell']=="2"||$sell[0]['f_user_sell']=="3"||$sell[0]['f_user_sell']=="4"){
                return json(["code"=>2,"msg"=>'已经有人登录','data'=>[],'url'=>url('reception/Merchant/seller')]);
            }else{
                return json(["code"=>2,"msg"=>'已经有人登录','data'=>[],'url'=>url('reception/Merchant/merchant')]);
            }
        }

    }
    /*登录显示*/
    public function login(){
        $nowlogin=Session::get('nowlogin');
        if($nowlogin){
            $res=Db::table('f_user')->where('f_user_id',$nowlogin)->select();
            $request = Request::instance();
            $res[0]['f_user_img']=str_replace("__STATIC__",$request->root()."/static",$res[0]['f_user_img']);//字符串替换
            return json(['loginState'=>1,'nowlogin'=>$res]);
        }else{
            return json(['loginState'=>0,'nowlogin'=>'']);
        }
    }
    /*退出登录*/
    public function loginOut(){
        Session::set("nowlogin",'');
        return json(['loginState'=>0,'nowlogin'=>'']);
    }
}