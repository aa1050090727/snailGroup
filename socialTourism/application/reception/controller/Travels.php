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
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->where('a.f_travel_note_state',1)->paginate(3);
        $this->assign("list",$list);
        return $this->fetch();
    }
    /*游记发布页面*/
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
        $list=Db::table('f_travel_note')->alias('a')->join('f_user b','a.f_travel_note_uid = b.f_user_id')->where('f_travel_note_place','like','%'.$search.'%')->where('a.f_travel_note_state',1)->paginate(3);
        $this->assign("list",$list);
        return $this->fetch("travels");
    }
    /*点击流量了加一*/
    public function travelAdd(){
        $travelId=input('param.travelID');
        $res=Db::table('f_travel_note')->where('f_travel_note_id', $travelId)->select();
        $browse=$res[0]['f_travel_note_browse'];
        $browseAdd=$browse+1;
        Session::set('travelId',$travelId);
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
        $nowlogin=Session::get("nowlogin");
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
                "f_travel_note_uid"=>$nowlogin,
                "f_travel_note_place"=>$place,
                "f_travel_note_collect"=>0,
                "f_travel_note_browse"=>0,
                "f_travel_note_recommend"=>"否",
                "f_travel_note_state"=>2
            ];
            $res=Db::name('f_travel_note')->insert($data);
            echo 1;
        }else{
            echo 2;
        }

    }
    /*点击评论判断是否登录*/
    public function writecommond(){
        $nowlogin=Session::get('nowlogin');
        if($nowlogin==''){
            return json(['code'=>2]);
        }else{
            return json(['code'=>1]);
        }
    }
    /*发布评论*/
    public function writeSure(){
        $nowlogin=Session::get('nowlogin');
        $content=input('param.wreteContent');
        $travelId=Session::get('travelId');
        date_default_timezone_set("PRC");
        $time=date("Y-m-d H:i:s",time());
        $data=[
            'f_tn_comment_id'=>null,
            'f_tn_comment_nid'=>$travelId,
            'f_tn_comment_uid'=>$nowlogin,
            'f_tn_comment_time'=>$time,
            'f_tn_comment_content'=>$content,
            'f_tn_comment_to'=>null
        ];
        $res=Db::table('f_tn_comment')->insert($data);
        if($res){
            return json(['code'=>1]);
        }else{
            return json(['code'=>2]);
        }
    }
    /*评论显示*/
    public function conmmentUrl(){
        $travelId=Session::get('travelId');
        $page=input('param.page');
        $travelComment=Db::table('f_tn_comment')->alias('a')->join('f_user b','a.f_tn_comment_uid=b.f_user_id')->where('f_tn_comment_nid',$travelId)->page($page,3)->select();
        $travelpage=Db::table('f_tn_comment')->alias('a')->join('f_user b','a.f_tn_comment_uid=b.f_user_id')->where('f_tn_comment_nid',$travelId)->count();
        $request = Request::instance();
        /*替换图片路径*/
        foreach( $travelComment as &$value){
                $value['f_user_img']=str_replace("__STATIC__",$request->root()."/static",$value['f_user_img']);//字符串替换
        }
        $allpage=ceil($travelpage/3);
        return json(['comment'=>$travelComment,'allpage'=>$allpage,"travelNowpage"=>$page]);
    }
    /*点击收藏*/
    public function collectUrl(){
        $nowlogin=Session::get("nowlogin");
        $travelId=Session::get('travelId');
        $collectNumber=Db::table('f_travel_note')->where('f_travel_note_id',$travelId)->select();
        $number=$collectNumber[0]['f_travel_note_collect'];
        if($nowlogin==''){
            return json(['code'=>0,'collect'=>0,'collectNumber'=>$number]);
        }else{
            $collect=Db::table('f_enshrine')->where('f_enshrine_uid',$nowlogin)->where('f_enshrine_cid',3)->where('f_enshrine_sid',$travelId)->select();
            if($collect){
                return json(['code'=>1,'collect'=>1,'collectNumber'=>$number]);
            }else{
                $data=[
                    'f_enshrine_id'=>null,
                    'f_enshrine_uid'=>$nowlogin,
                    'f_enshrine_cid'=>3,
                    'f_enshrine_sid'=>$travelId
                ];
                $res=Db::table('f_enshrine')->insert($data);
                $numberChange=$number+1;
                $travelChange=Db::table('f_travel_note')->where('f_travel_note_id',$travelId)->update(['f_travel_note_collect'=>$numberChange]);
                if($res && $travelChange){
                    return json(['code'=>2,'collect'=>1,'collectNumber'=>$numberChange]);
                }else{
                    return json(['code'=>3,'collect'=>0,'collectNumber'=>$number]);
                }
            }
        }
    }
    /*收藏显示*/
    public function collectShow(){
        $nowlogin=Session::get("nowlogin");
        $travelId=Session::get('travelId');
        $collectNumber=Db::table('f_travel_note')->where('f_travel_note_id',$travelId)->select();
        $number=$collectNumber[0]['f_travel_note_collect'];
        if($nowlogin==''){
            return json(['collect'=>0,'collectNumber'=>$number]);
        }else{
            $collect=Db::table('f_enshrine')->where('f_enshrine_uid',$nowlogin)->where('f_enshrine_cid',3)->where('f_enshrine_sid',$travelId)->select();
            if($collect){
                return json(['collect'=>1,'collectNumber'=>$number]);
            }else{
                return json(['collect'=>0,'collectNumber'=>$number]);
            }
        }
    }
    /*取消收藏*/
    public function collectCancle(){
        $nowlogin=Session::get("nowlogin");
        $travelId=Session::get('travelId');
        $collectNumber=Db::table('f_travel_note')->where('f_travel_note_id',$travelId)->select();
        $number=$collectNumber[0]['f_travel_note_collect'];
        $numberChange=$number-1;
        $travelChange=Db::table('f_travel_note')->where('f_travel_note_id',$travelId)->update(['f_travel_note_collect'=>$numberChange]);
        $res=Db::table('f_enshrine')->where('f_enshrine_uid',$nowlogin)->where('f_enshrine_cid',3)->where('f_enshrine_sid',$travelId)->delete();
        if($travelChange && $res){
            return json(['code'=>1,'collect'=>0,'collectNumber'=>$numberChange]);
        }else{
            return json(['code'=>2,'collect'=>1,'collectNumber'=>$number]);
        }
    }
}