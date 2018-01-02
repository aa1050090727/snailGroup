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
    /*发布游记*/
    public function notePublic(){
        date_default_timezone_set("PRC");
        $timept=date("Y-m-d",time());
        $head=input('param.head');
        $place=input('param.place');
        $introduce=input('param.introduce');
        $content=input('param.content');
        // 获取表单上传文件
        $file = request()->file('img');
        // 移动到框架应用根目录/public/static/image/ 目录下
         $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'static/image');
        /*上传成功*/
        if($info)
        {
            $imgName=$info->getFilename();
            $url="__STATIC__/image/".$imgName;
            $data= [
                "f_travel_note_id"=>null,
                "f_travel_note_img"=>$url,
                "f_travel_note_intro"=>$introduce,
                "f_travel_note_head"=>$head,
                "f_travel_note_content"=>$content,
                "f_travel_note_time"=>$timept,
                "f_travel_note_uid"=>1,
                "f_travel_note_place"=>$place,
                "f_travel_note_collect"=>0,
                "f_travel_note_browse"=>0,
                "f_travel_note_recommend"=>"是",
                "f_travel_note_state"=>"锁定"
            ];
            $res=Db::name('f_travel_note')->insert($data);
            echo 1;
        }else{
            echo 2;
        }

    }
}