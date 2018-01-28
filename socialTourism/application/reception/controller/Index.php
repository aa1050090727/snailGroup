<?php
namespace app\reception\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Response;
use think\Request;
use think\Cache;
//首页控制器
class Index extends Controller
{
    //跳转到首页
    public function index(){
        return $this->fetch();
    }
    /*热门游记显示*/
    public function hotTravel(){
        $nowpage=input('param.nowPage');
        $count=Db::table('f_travel_note')->where('f_travel_note_state',1)->order('f_travel_note_browse desc' )->limit(20)->count();
        $allPage=ceil($count/5);
        $res=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid=b.f_user_id')->where('f_travel_note_state',1)->order('f_travel_note_browse desc' )->limit(20)->page($nowpage,5)->select();
        $request = Request::instance();
        /*替换图片路径*/
        foreach( $res as &$value){
            $value['f_travel_note_img']=str_replace("__STATIC__",$request->root()."/static",$value['f_travel_note_img']);//字符串替换
        }
        return json(['nowPage'=>$nowpage,'allPage'=>$allPage,'hotTravel'=>$res]);
    }
    /*新游记显示*/
    public function newTravel(){
        $nowpage=input('param.nowPage');
        $count=Db::table('f_travel_note')->where('f_travel_note_state',1)->order('f_travel_note_time desc' )->limit(20)->count();
        $allPage=ceil($count/5);
        $res=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid=b.f_user_id')->where('f_travel_note_state',1)->order('f_travel_note_time desc' )->limit(20)->page($nowpage,5)->select();
        $request = Request::instance();
        /*替换图片路径*/
        foreach( $res as &$value){
            $value['f_travel_note_img']=str_replace("__STATIC__",$request->root()."/static",$value['f_travel_note_img']);//字符串替换
        }
        return json(['nowPage'=>$nowpage,'allPage'=>$allPage,'hotTravel'=>$res]);
    }
	public function setredis(){
		date_default_timezone_set("PRC");
        $timept=date("Y-m-d 12:00:00",time());
        $data = Db::table("f_science")
            ->where("f_science_state",1)
            ->where("f_science_states",2)
		    ->where('f_science_astarttime','=',$timept)
            ->whereTime('f_science_astarttime', '>=', 'now')
            ->whereTime('f_science_aendtime', '>=', 'now')
            ->select();
	    $redis = Cache::getHandler();
		foreach($data as $value){
			$redis->set($value.f_science_id,$value);
		}
		// $list = $redis->lrange('f_science', 0, -1);
		//$res = $redis->lPop('f_science');
    }
}

