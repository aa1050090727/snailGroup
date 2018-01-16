<?php
namespace app\reception\controller;

use think\Controller;
use think\Db;
use think\Cache;
use think\Session;
use think\Request;//使用请求类
use think\Cookie;
//首页控制器
class Rediscon extends Controller
{
	public function setredis(){
		 //->whereTime('f_science_astarttime', '>=', 'now')
         //   ->whereTime('f_science_aendtime', '>=', 'now')
		date_default_timezone_set("PRC");
        $timept=date("Y-m-d 12:00:00",time());
        $data = Db::table("f_science")
            ->where("f_science_state",1)
            ->where("f_science_states",2)
		    ->where('f_science_astarttime','=',$timept)
            ->select();
	    $redis = Cache::getHandler();
		foreach($data as $value){
			$redis->set($value['f_science_id'],$value);
		}
		// $list = $redis->lrange('f_science', 0, -1);
		//$res = $redis->lPop('f_science');
    }
	public function nowBuy(){
		//判断用户有没有登录
		if(session('?nowlogin')){
			$nowlogin = session("nowlogin");
			//登录后，立即抢购，判断库存是否足够
			
		}
		else{
			return json(["code"=>1,"tips"=>"用户未登录"]);
		}	
	}
	//活动结束后将Redis中的数据更新到数据库
	public function get_insert_data(){
		
	}
	

}

