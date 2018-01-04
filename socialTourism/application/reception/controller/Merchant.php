<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 15:55
 */

namespace app\reception\controller;

use think\Controller;
use think\Db;
use think\response;
use think\Session;
use think\Request;
class merchant extends Controller
{
    /*页面加载*/
    public function merchant(){
        $nowlogin=Session::get("nowlogin");
        if($nowlogin=='')
        {
            return $this->fetch('Index/index');
        }else
        {
            $sell=Db::table("f_seller")->where('f_seller_uid',$nowlogin)->select();
            $sellname=Db::table("f_user")->where('f_user_id',$nowlogin)->select();
            $sellinfo=[
                [ "name"=>$sellname[0]['f_user_name'],
                    "place"=>$sell[0]['f_seller_site'],
                    "number"=>$sell[0]['f_seller_idnumber']
                ]
            ];
            Session::set("seller",$sell[0]['f_seller_id']);
            $this->assign("sellinfo",$sellinfo);
            return $this->fetch();
        }

    }
    /*省份获取*/
    public function province(){
      $province=Db::table('f_province')->select();
        return json(['province'=>$province]);
    }
    /*城市获取*/
    public function city(){
        $province=input("param.provinvce");
        $city=Db::table("f_city")->where("f_city_fid",$province)->select();
       return json(['city'=>$city]);
    }
    /*地区获取*/
    public  function district(){
        $city=input("param.city");
        $district=Db::table("f_district")->where("f_district_fid",$city)->select();
        return json(["district"=>$district]);
    }
    /*发布商品*/
    public function goodPublic(){
        date_default_timezone_set("PRC");
        $timept=date("Y-m-d",time());
        $goodsName=input('param.goodsName');
        $goodsRelease=input('param.goodsRelease');
        $goodsClass=input('param.goodsClass');
        $province=input('param.province');
        $city=input('param.city');
        $district=input('param.district');
        $Price=input('param.Price');
        $goodsPrice=input('param.goodsPrice');
        $goodsNumber=input('param.goodsNumber');
        $startTime=input('param.startTime');
        $endTime=input('param.endTime');
        $inputIntro=input('param.inputIntro');
        $content=input('param.content');
        $seller=Session::get("seller");
        // 获取表单上传文件
        $file = request()->file('img');
        // 移动到框架应用根目录/public/static/image/ 目录下
        $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'static/image');
        /*发布的是景点*/
        $request=Request::instance();
        $imgurl=$request->root();
        if($goodsClass==1 && $info){
            $imgName=$info->getFilename();
            $url=$imgurl.'/static/image/'.$imgName;
            $data=[
                "f_science_id"=>null,
                "f_science_name"=>$goodsName,
                "f_science_state"=>1,/*景点状态（审核）*/
                "f_science_states"=>$goodsRelease,/*活动、普通*/
                "f_science_classify"=>$goodsClass,/*景点、酒店*/
                "f_science_img"=>$url,
                "f_science_content"=>$inputIntro,
                "f_science_detall"=>$content,
                "f_science_price"=>$Price,
                "f_science_aprice"=>$goodsPrice,
                "f_science_astarttime"=>$startTime,
                "f_science_aendtime"=>$endTime,
                "f_science_time"=>$timept,
                "f_science_sid"=>$seller,
                "f_science_pid"=>$province,
                "f_science_cid"=>$city,
                "f_science_did"=>$district,
            ];
            $res=Db::name("f_science")->insert($data);
            echo 1;
        }elseif($goodsClass==1 && $info){

        }

    }
    /*商家申请界面跳转*/
    public function seller(){
        return $this->fetch();
    }


}