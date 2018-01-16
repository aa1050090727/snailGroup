<?php
/**
 * Created by PhpStorm.
 * User: 陈凌峰
 * Date: 2018/1/7
 * Time: 22:31
 */

namespace app\backstage\controller;


use think\Controller;
use think\Db;

class Travels extends Controller
{
    public function travels(){
        return $this->fetch();
    }
    //获取游记信息
    public function getTravels(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;

        $resArr = Db::table("f_travel_note")->field("f_travel_note_id,f_travel_note_head,f_travel_note_time,f_travel_note_place,f_travel_note_collect,f_travel_note_browse,f_travel_note_state,f_user_phone")->alias("a")->join("f_user b","a.f_travel_note_uid=b.f_user_id")->where('f_travel_note_id|f_travel_note_head|f_travel_note_time|f_travel_note_place|f_travel_note_state|f_user_phone','like','%'.$keyword.'%')->limit("".$start.",10")->select();

        $resCount = Db::table("f_travel_note")->field("f_travel_note_id,f_travel_note_head,f_travel_note_time,f_travel_note_place,f_travel_note_collect,f_travel_note_browse,f_travel_note_state,f_user_phone")->alias("a")->join("f_user b","a.f_travel_note_uid=b.f_user_id")->where('f_travel_note_id|f_travel_note_head|f_travel_note_time|f_travel_note_place|f_travel_note_state|f_user_phone','like','%'.$keyword.'%')->count();
//        var_dump($resArr);exit;

        foreach($resArr as $key=>&$value){
            if($value['f_travel_note_state']==1){
                $value['f_travel_note_state'] = "审核通过";
            }
            elseif($value['f_travel_note_state']==2){
                $value['f_travel_note_state'] = "待审核";
            }
            elseif($value['f_travel_note_state']==3){
                $value['f_travel_note_state'] = "审核未通过";
            }else{
                $value['f_travel_note_state'] = "下架游记";
            }
        }
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';

        return json_decode($resArrJson);
    }
    //预览游记
    public function previewTravels(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $travelsId = $_GET["travelsId"]?$_GET["travelsId"]:"";
        $resArr = Db::table("f_travel_note")->field("f_travel_note_content")->where("f_travel_note_id",$travelsId)->find();
        if($resArr != null){
            return json(['code'=>10000,'msg'=>$previewMsg["preview_success"],'data'=>$resArr,'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$previewMsg["preview_fail"],'data'=>'','url'=>'']);
        }
    }
    //游记审核
    public function examineTravels(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $travelsId = $_GET["travelsId"]?$_GET["travelsId"]:"";
        $travelsState = $_GET["travelsState"]?$_GET["travelsState"]:"";
//        var_dump($travelsId,$travelsState);exit;
        $res = Db::table("f_travel_note")->where("f_travel_note_id",$travelsId)->update(["f_travel_note_state"=>$travelsState]);
        if($res !== 0){
            if($travelsState === "4"){
                return json(['code'=>10000,'msg'=>$previewMsg["del_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10000,'msg'=>$previewMsg["examine_success"],'data'=>'','url'=>'']);
            }
        }else{
            if($travelsState === "4"){
                return json(['code'=>10001,'msg'=>$previewMsg["del_fail"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10001,'msg'=>$previewMsg["examine_fail"],'data'=>'','url'=>'']);
            }

        }

    }
}


