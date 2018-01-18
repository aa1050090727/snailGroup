<?php
namespace app\reception\controller;

use think\Controller;
use think\Db;
use think\Cache;
//首页控制器
class Index extends Controller
{
    //跳转到首页
    public function index(){
        return $this->fetch();
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

