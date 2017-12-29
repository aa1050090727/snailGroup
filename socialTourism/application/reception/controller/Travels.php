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
        //$travles=Db::table('f_travel_note')->limit("0","3")->select();
       // $travles=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->limit("0","3")->select();
/*        $searchplace=input("param.place");
        Session::set("travelSearch",$searchplace);*/
        $search=Session::set("travelSearch",'');
       // $search=Session::get("travelSearch");
        //var_dump($search);
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
        $travles=Db::table('f_travel_note')->where('f_travel_note_id',$travelid)->select();
        //var_dump($travles);
        $this->assign("content",$travles);
        return $this->fetch();
    }
    /*游记搜索*/
    public function travelSearch(){
        //$view=new View();
        $searchplace=input("param.place");
/*        $search=Session::set("travelSearch",$searchplace);
        Session::get("travelSearch");
        var_dump($search);*/
        if($searchplace=='')
        {
            var_dump(1);
            $search=Session::get("travelSearch");
            var_dump($search);
        }
        else{
           Session::set("travelSearch",$searchplace);
            $search=Session::get("travelSearch");
        }
/*        if(Session::get("travelSearch")=='')
        {
            var_dump(1);
            $search=Session::set("travelSearch",$searchplace);
            var_dump($search);
        }else{
            var_dump(2);
             $search=Session::get("travelSearch");
            var_dump($search);
        }*/
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->paginate(3);
        $this->assign("list",$list);
       // $this->travels($search);
        //var_dump($searchplace);
        return $this->fetch("travels");
    }
    /*点击旅游游记*/
    public function travelGo(){
        Session::set("travelSearch","");
        $search="";
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->paginate(3);
        $this->assign("list",$list);
        return $this->fetch("travels");
    }
}