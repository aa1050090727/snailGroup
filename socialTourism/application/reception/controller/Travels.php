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
/*引用视图*/
use \think\View;
class Travels extends Controller
{
    /*游记主页面跳转*/
    public function travels(){
        $search=Session::set("travelSearch",'');
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->paginate(3);
        $this->assign("list",$list);
        return $this->fetch();
    }
    /*游记发布*/
    public function travelPublic(){
        return $this->fetch();
    }
    /*游记详情*/
    public function travelDetails(){
        $travelid=input("param.travelID");
        $travles=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_id',$travelid)->select();
        $travles[0]['f_travel_note_content'] = str_replace('img','img class="img-responsive center-block"',$travles[0]['f_travel_note_content']);
        $this->assign("content",$travles);
        return $this->fetch();
    }
    /*游记搜索*/
    public function travelSearch(){
        $searchplace=input("param.place");
        if($searchplace=='')
        {
            $search=Session::get("travelSearch");
        }
        else{
           Session::set("travelSearch",$searchplace);
            $search=Session::get("travelSearch");
        }
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->paginate(3);
        $this->assign("list",$list);
        return $this->fetch("travels");
    }
    /*点击流量了加一*/
    public function travelAdd(){
        $travelId=input('param.travelID');
        $res=Db::table('f_travel_note')->where('f_travel_note_id', $travelId)->select();
        $browse=$res[0]['f_travel_note_browse'];
        $browseAdd=$browse+1;
        //var_dump($res);
        $result=Db::table('f_travel_note')->where('f_travel_note_id', $travelId)->update(['f_travel_note_browse' => $browseAdd]);
        if($result==0)
        {
            echo "0";
        }else{
            echo "1";
        }
    }

}