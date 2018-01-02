<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25
 * Time: 10:57
 */

namespace app\reception\controller;

use think\Controller;//使用基类
use think\Db;//使用数据库
use think\Request;//使用请求类
use think\Cookie;
use think\Session;
class Viewport extends Controller
{
    //引入页面
    public function viewport(){
        return $this->fetch();
    }
    public function viewpos(){
        return $this->fetch("viewpos");
    }
    public function viewdetailed(){
        session("nowlogin",1);
//        $f_science_id = isset($_GET["science_id"])?$_GET["science_id"]:'';
        $f_science_id = Cookie::get("science_id");
        $viewComment = Db::table("f_science_comment")
            ->alias('a')
            ->join('f_user b','a.f_sc_uid = b.f_user_id')
            ->where("a.f_sc_sid",$f_science_id)
            ->field(['a.*','b.f_user_name','b.f_user_img'])
            ->paginate(3,false,[
                'fragment'=>'comment'
            ]);
            $this->assign('viewComment', $viewComment);
        //var_dump($viewComment);
        // var_dump($viewComment);exit;
        return $this->fetch("viewdetailed");
    }
    public function goerror(){
        return $this->fetch("error");
    }
    /**********************省市页面**********************/
    //省份
    public function posData(){
        $nowPage = isset($_POST["nowPage"])?$_POST["nowPage"]:"";//当前页
        $offset = 5;//显示条数
        $startIndex = ($nowPage - 1)*$offset;//开始下标
        $data_province = Db::table('f_province')->limit($startIndex,$offset)->select();//省份
        $data_city = [];
        $city = [];//市
        foreach($data_province as $value) {
            $city[] = Db::table('f_city')->where("f_city_fid",$value["f_province_id"])->select();
        }
        //数据整理
        foreach($city as $val){
            foreach($val as $v){
                $data_city[] = $v;
            }
        }
        //总页数
        $count = Db::table('f_province')->count();
        $totalPage = ceil($count/$offset);
        if(empty($data_province)){
            return json(["code"=>1,"data"=>["pro"=>[],"city"=>[],"totalPage"=>0]]);
        }
        else{
            return json(["code"=>2,"data"=>["pro"=>$data_province,"city"=>$data_city,"totalPage"=>$totalPage]]);
        }
    }
    //设置cookie：省、市、当前页
    public function set_cookie(){
        $province = isset($_POST["province"])?$_POST["province"]:"";
        $city = isset($_POST["city"])?$_POST["city"]:"";
//        $nowPage = isset($_POST["nowPage"])?$_POST["nowPage"]:"";
        Cookie::set('nowPos',["province"=>$province,"city"=>$city]);
        return json(["code"=>1,"msg"=>"设置成功"]);
    }
    //获取cookie值：省、市、当前页
    public function get_cookie(){
        $nowPos = Cookie::get('nowPos');
        return json(["code"=>1,"nowPos"=>$nowPos]);
    }
    //搜索存省市区
    public function search_like(){
        $like = isset($_POST["like"])?$_POST["like"]:"";
        session("like",$like);
        if(session("?like")){
            return json(["code"=>1]);
        }
        else{
            return json(["code"=>2]);
        }
    }
    /**********************景点列表页面*******************/
    //判断是否有搜索字眼的存在
    public function haslike(){
        if(session("?like")){
            return json(["code"=>1,"like"=>session("like")]);
        }
        else{
            return json(["code"=>2,"like"=>""]);
        }
    }
    //删除搜索
    public function unsetlike(){
        if(session("?like")){
            session('like', null);
            return json(["code"=>1]);
        }
        else{
            return json(["code"=>2]);
        }

    }
    //获取区、景点数据（初始化）
    public function getDistrict(){
        $city_id = isset($_POST["cityId"])?$_POST["cityId"]:"";
        if($city_id!=""){
            //向数据库获取数据--区
            $districtData = Db::table('f_district')->alias('a')->join("f_city b","a.f_district_fid = b.f_city_id")->where('b.f_city_id',$city_id)->select();
        }
        else{
            $districtData = [];
        }
        if(empty($districtData)){
            return json(["code"=>1,"district"=>[],"tips"=>"..."]);
        }
        else{
            return json(["code"=>2,"district"=>$districtData,"tips"=>"获取成功"]);
        }

    }
    //获取景点--初始化
    public function getView(){
        $cityId = isset($_POST["city_id"])?$_POST["city_id"]:"";//市
        $provinceId = isset($_POST["province_id"])?$_POST["province_id"]:"";//省
        $district = isset($_POST["district_id"])?$_POST["district_id"]:"";//区
        $like = isset($_POST["like"])?$_POST["like"]:"";//模糊查询
        $nowPage = isset($_POST["nowPage"])?$_POST["nowPage"]:"";//当前页
        //var_dump($cityId,$provinceId,$district,$nowPage);exit;
        $offset = 6;//显示条数
        $startIndex = ($nowPage - 1)*$offset;//开始下标
        //$data_view = Db::table('f_science')->fetchSql(true)->limit($startIndex,$offset)->select();//省份
        if($like == ""){
            if($district==""){
                $data = [
                    "f_science_pid"=>$provinceId,
                    "f_science_cid"=> $cityId
                ];
            }
            else{
                $data = [
                    "f_science_pid"=>$provinceId,
                    "f_science_cid"=> $cityId,
                    "f_science_did"=>$district
                ];
            }
        }
        else{
            $data = [
                "f_science_name"=>["like","%{$like}%"]
            ];
        }
        $data_view = Db::table('f_science')
            ->where($data)
//                ->where("f_science_pid='{$provinceId}'")
//                ->where("f_science_cid= '{$cityId}'")
//                ->where("f_science_did='{$district}' or 1=1")
//                ->where('f_science_state',1)
                ->limit($startIndex,$offset)
                ->select();
//        var_dump($data_view);exit;
        $view_total = count($data_view);
//        var_dump($view_total);exit;
        $totalPage = ceil($view_total/$offset);
        //var_dump($totalPage);exit;
        if(empty($data_view)){
            return json(["index"=>1,"data_view"=>[],"total_Page"=>$totalPage]);
        }
        else{
            return json(["index"=>2,"data_view"=>$data_view,"total_Page"=>$totalPage]);
        }

    }
    //存景点id
    public function setViewId(){
        $f_science_id = isset($_POST["f_science_id"])?$_POST["f_science_id"]:"";
        Cookie("science_id",$f_science_id);
        if(Cookie::has("science_id")){
            return json(["result"=>true]);
        }
        else{
            return json(["result"=>false]);
        }
    }
    /***********************景点详情页面*****************/
    //获取景点详情页面
    public function getViewDetail(){
        if(Cookie::has("science_id")){
            $f_science_id = Cookie::get("science_id");
//            var_dump($f_science_id);exit;
            $viewDetail = Db::table("f_science")->where('f_science_id',$f_science_id)->select();
            if(empty($viewDetail)){
                return json(["viewDetail"=>[]]);
            }
            else{
                return json(["viewDetail"=>$viewDetail]);
            }
        }
    }
    //点击立即购买
    public function nowBuy(){
        if(Session::has('nowlogin')){
            //生成订单--跳转页面
            $science_id = input('science_id');//商品id
            $science_price = input('science_price');//商品id
            $science_sid = input('science_sid');//商品id
            $userId = session("nowlogin");//用户id
            date_default_timezone_set("PRC");
            $timept=date("Y-m-d",time());
            //生成订单
            $data = [
                "b_order_id"=>null,
                "b_order_user"=>$userId,
                "b_order_total_price"=>$science_price,
                "b_order_total_number"=>1,
                "b_order_time"=>$timept,
                "b_order_state"=>"未支付"
            ];
            $insertOrder = Db::table("b_order")->insert($data);
            if($insertOrder){
                $orderId = Db::table('b_order')->getLastInsID();
//                var_dump($orderId);exit;
                $data_details = [
                    "b_order_details_id"=>null,
                    "b_order_details_classid"=>1,
                    "b_order_details_gid"=>$science_id,
                    "b_order_details_num"=>1,
                    "b_order_details_price"=>$science_price,
                    "b_order_details_sid"=>$science_sid,
                    "b_order_details_uid"=>$userId,
                    "b_order_details_oid"=>$orderId,
                ];
                $insertOrder_details = Db::table("b_order_details")->insert($data_details);
                if($insertOrder_details){
                    return json(["code"=>1,"tips"=>"添加成功"]);
                }
                else{
                    return json(["code"=>2,"tips"=>"添加失败"]);
                }
            }
        }
        else{
            return json(["code"=>3,"tips"=>"您还没有登录"]);
        }
    }
    //加入购物车
    public function putShopCar(){
        if(session('?nowlogin')){
            //判断购物车是否已经有该商品，如果有的话，更新数量；如果没有的话新增意见数据
            $science_id = input('science_id');
            $userId = session("nowlogin");
            //该商品是否被该用户加入购物车过
            $result = Db::table("f_shopping_cart")
                ->where("f_shopping_cart_gid",$science_id)
                ->where("f_shopping_cart_uid",$userId)
                ->select();
//            var_dump(empty($result));exit;
            //用户没有添加过该商品
            if(empty($result)){
                //写入购物车--insert--提示加入成功
                $data = [
                    "f_shopping_cart_id"=>null,
                    "f_shopping_cart_classid"=>1,
                    "f_shopping_cart_gid"=>$science_id,
                    "f_shopping_cart_uid"=>$userId,
                    "f_shopping_cart_uum"=>1
                ];
                $putShoppingCar = Db::table("f_shopping_cart")->insert($data);
                //var_dump($putShoppingCar);exit;
                if($putShoppingCar){
                    return json(["code"=>1,"tips"=>"添加成功"]);
                }
                else{
                    return json(["code"=>2,"tips"=>"添加失败"]);
                }
            }
            else{
                //用户已经添加过该商品
                $updateShoppingCar = Db::table('f_shopping_cart')
                ->where("f_shopping_cart_gid",$science_id)
                ->where("f_shopping_cart_uid",$userId)
                ->setInc('f_shopping_cart_uum');
//                var_dump($updateShoppingCar);exit;
                if($updateShoppingCar == 1){
                    return json(["code"=>1,"tips"=>"添加成功"]);
                }
                else{
                    return json(["code"=>2,"tips"=>"添加失败"]);
                }
            }
        }
        else{
            return json(["code"=>3,"tips"=>"您还没有登录"]);
        }
    }
    /***********************景点推荐*****************/
    public function recommend_view(){
        $data_recommend = Db::table("f_science")->order('rand()')->limit(4)->select();
        return json(['data_recommend'=>$data_recommend]);
    }
}