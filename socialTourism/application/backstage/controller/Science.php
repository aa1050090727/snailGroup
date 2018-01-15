<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2
 * Time: 15:19
 */

namespace app\backstage\controller;

use think\Controller;
use think\Db;
class Science extends Controller
{
    public function science(){
        return $this->fetch();
    }
    //获取景点信息
    public function getScience(){
        $page = $_GET["page"]?$_GET["page"]:"";
        $limit = $_GET["limit"]?$_GET["limit"]:"";
        $keyword = $_GET["keyword"]?$_GET["keyword"]:"";
        $start = ($page - 1)*$limit;
//        var_dump($page);
//        var_dump($limit);
        $resArr = Db::table("f_science")->field("f_science_id,f_science_name,f_science_state,f_science_states,f_science_num,f_science_price,f_user_phone,f_science_time")->alias("a")->join("f_seller b","a.f_science_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->where('f_science_id|f_science_name|f_science_state|f_science_states|f_science_num|f_science_price|f_user_phone|f_science_time','like','%'.$keyword.'%')->limit($start,10)->select();
        $resCount = Db::table("f_science")->field("f_science_id,f_science_name,f_science_state,f_science_states,f_science_num,f_science_price,f_user_phone,f_science_time")->alias("a")->join("f_seller b","a.f_science_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->where('f_science_id|f_science_name|f_science_state|f_science_states|f_science_num|f_science_price|f_user_phone|f_science_time','like','%'.$keyword.'%')->count();

        foreach($resArr as $key=>&$value){
            if($value['f_science_state']==1){
                $value['f_science_state'] = "审核通过";
            }
            elseif($value['f_science_state']==2){
                $value['f_science_state'] = "待审核";
            }
            elseif($value['f_science_state']==3){
                $value['f_science_state'] = "审核未通过";
            }
            else{
                $value['f_science_state'] = "商品下架";
            }
            if($value['f_science_states'] == 1){
                $value['f_science_states'] = "普通";
            }else{
                $value['f_science_states'] = "活动";
            }
        }
        //var_dump($resArr);
        $resArrJson = '{"code": 0,"msg": "","count": '.$resCount.',"data": '.json_encode($resArr,JSON_UNESCAPED_UNICODE).'}';
        return json_decode($resArrJson);
    }
    //预览景点
    public function scienceScience(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $scienceId = $_GET["scienceId"]?$_GET["scienceId"]:"";
        $resArr = Db::table("f_science")->field("f_science_id,f_science_name,f_science_states,f_science_img,f_science_content,f_science_detall,f_science_num,f_science_price,f_science_aprice,f_science_astarttime,f_science_aendtime,f_science_time,f_user_phone,f_province_name,f_city_name,f_district_name")->alias("a")->join("f_seller b","a.f_science_sid=b.f_seller_id")->join("f_user c","b.f_seller_uid=c.f_user_id")->join("f_province p","a.f_science_pid=p.f_province_id")->join("f_city s","a.f_science_cid=s.f_city_id")->join("f_district d","a.f_science_did=d.f_district_id")->where("f_science_id",$scienceId)->find();
        if($resArr != null){
            return json(['code'=>10000,'msg'=>$previewMsg["preview_success"],'data'=>$resArr,'url'=>'']);
        }else{
            return json(['code'=>10001,'msg'=>$previewMsg["preview_fail"],'data'=>'','url'=>'']);
        }
    }
    //景点审核
    public function examineScience(){
        $previewMsg = config('msg')['preview_msg']; //调用配置文件
        $scienceId = $_GET["scienceId"]?$_GET["scienceId"]:"";
        $scienceState = $_GET["scienceState"]?$_GET["scienceState"]:"";
//        var_dump($travelsId,$travelsState);exit;
        $res = Db::table("f_science")->where("f_science_id",$scienceId)->update(["f_science_state"=>$scienceState]);
        if($res !== 0){
            if($scienceState === "4"){
                return json(['code'=>10000,'msg'=>$previewMsg["del_success"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10000,'msg'=>$previewMsg["examine_success"],'data'=>'','url'=>'']);
            }
        }else{
            if($scienceState === "4"){
                return json(['code'=>10001,'msg'=>$previewMsg["del_fail"],'data'=>'','url'=>'']);
            }else{
                return json(['code'=>10001,'msg'=>$previewMsg["examine_fail"],'data'=>'','url'=>'']);
            }

        }

    }
}