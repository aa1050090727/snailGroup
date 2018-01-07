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
            $sellname=Db::table("f_user")->where('f_user_id',$nowlogin)->select();
            $sellerState=$sellname[0]['f_user_sell'];
            /*判断是否为卖家*/
            if($sellerState!=5)
            {
                return $this->fetch('Index/index');
            }
            else{
                $sell=Db::table("f_seller")->where('f_seller_uid',$nowlogin)->select();

                if($sell[0]['f_seller_stats']==1)
                {
                    $sellerClass="景点";
                }else{
                    $sellerClass="酒店";
                }
                $sellinfo=[
                    [ "name"=>$sellname[0]['f_user_name'],
                        "place"=>$sell[0]['f_seller_site'],
                        "number"=>$sell[0]['f_seller_idnumber'],
                        "state"=>$sellerClass
                    ]
                ];
                Session::set("seller",$sell[0]['f_seller_id']);
                $this->assign("sellinfo",$sellinfo);
                return $this->fetch();
            }

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
        $sellState=Db::table("f_seller")->where('f_seller_id',$seller)->select();
        $sellerClass=$sellState[0]['f_seller_stats'];
       /*查找根目录*/
        $request=Request::instance();
        $imgurl=$request->root();
        /*判断是否是对应的商家类型*/
        if($sellerClass==$goodsClass)
        {
            // 获取表单上传文件
            $file = request()->file('img');
            // 移动到框架应用根目录/public/static/image/ 目录下
            $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'static/image');
            /*发布的是景点*/
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
                    "f_science_num"=>$goodsNumber,
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
            }elseif($goodsClass==2 && $info){
                $imgName=$info->getFilename();
                $url=$imgurl.'/static/image/'.$imgName;
                $data=[
                    "f_hotel_id"=>null,
                    "f_hotel_name"=>$goodsName,
                    "f_hotel_state"=>1,/*酒店状态（审核）*/
                    "f_hotel_states"=>$goodsRelease,/*活动、普通*/
                    "f_science_classify"=>$goodsClass,/*景点、酒店*/
                    "f_hotel_img"=>$url,
                    "f_hotel_content"=>$inputIntro,
                    "f_hotel_num"=>$goodsNumber,
                    "f_hotel_detall"=>$content,
                    "f_hotel_price"=>$Price,
                    "f_hotel_aprice"=>$goodsPrice,
                    "f_hotel_astarttime"=>$startTime,
                    "f_hotel_aendtime"=>$endTime,
                    "f_hotel_time"=>$timept,
                    "f_hotel_sid"=>$seller,
                    "f_hotel_pid"=>$province,
                    "f_hotel_cid"=>$city,
                    "f_hotel_did"=>$district,
                ];
                $res=Db::name("f_hotel")->insert($data);
                echo 1;
            }
        }else{
            echo 2;
        }

    }
    /*商家申请界面跳转*/
    public function seller(){
        return $this->fetch();
    }
    /*申请商家状态判断*/
    public function sellState(){
       $nowlogin=Session::get("nowlogin");
        $seller=Db::table("f_user")->where("f_user_id",$nowlogin)->select();
        return json(['sellerState'=>$seller[0]['f_user_sell']]);
    }
    /*商家申请*/
    public function sellsubmit(){
        $nowlogin=Session::get("nowlogin");
        $goodsClass=input("param.goodsClass");
        $provinvce=input("param.provinvce");
        $city=input("param.city");
        $district=input("param.district");
        $inputIntro=input("param.inputIntro");
        $idNumber=input("param.idNumber");
        $data=[
            "f_seller_id"=>null,
            "f_seller_uid"=>$nowlogin,
            "f_seller_stats"=>$goodsClass,
            "f_seller_pid"=>$provinvce,
            "f_seller_cid"=>$city,
            "f_seller_did"=>$district,
            "f_seller_site"=>$inputIntro,
            "f_seller_idnumber"=>$idNumber,
        ];
        /*卖家表插入信息*/
        $res=Db::table("f_seller")->insert($data);
        /*更改用户表数据*/
        $changeState=Db::table('f_user')->where('f_user_id', $nowlogin)->update(['f_user_sell' => 2]);
        if($res || $changeState){
            return json(["code"=>1,"msg"=>"申请成功，等待审核！"]);
        }else{
            return json(["code"=>2,"msg"=>"申请失败！"]);
        }
    }
    /*卖家重新申请*/
    public function sellrReup(){
        $nowlogin=Session::get("nowlogin");
        $changeState=Db::table('f_user')->where('f_user_id', $nowlogin)->update(['f_user_sell' => 1]);
        if($changeState){
            return json(["code"=>1,"msg"=>"申请成功，返回申请页面"]);
        }else{
            return json(["code"=>2,"msg"=>"申请失败！"]);
        }
    }
}