<?php
/**
 * Created by PhpStorm.
 * User: fire
 * Date: 2017/12/26
 * Time: 18:51
 */

namespace app\reception\controller;
use think\Controller;
use think\Session;
use think\Db;
use think\Request;

class Center extends Controller
{
    //进入主页
    public function center(){
        session_start();
        $nowlogin = Session::get('nowlogin');
        if($nowlogin==''){
            return $this->fetch('index/index');
        }else{
            return $this->fetch();
        }
    }
    //获取个人信息
    public function getmy(){
        $nowlogin = Session::get('nowlogin');
        $where =[
            'f_user_id' => $nowlogin
        ];
        $res =  Db::table('f_user')->where($where)->find();
        $request = Request::instance();
        $res['f_user_img'] = str_replace("__STATIC__",$request->root()."/static",$res['f_user_img']);//字符串替换
        return $res;
    }
    //修改昵称
    public function updatamyname(){
        if(input('?post.nowname') && input('?post.id')){
            $uname=input('param.nowname');//接收
            $id=input('param.id');//接收
            $phone1 = strip_tags($uname);
            $name = addslashes($phone1);
            if(!isset($name{8})){
                $name =[
                    'f_user_name' => $name
                ];
                $where= [
                    'f_user_phone' => $id
                ];
                $res = Db::table('f_user')->where($where)->update($name);
                echo $res;
            }else{
                echo 5;
            }

        }
    }
    //修改密码
    public function updatamypaw(){
        $uppwdmsg = config('msg')['uppwd']; //调用配置文件
        if(input('?post.id') && input('?post.paw') && input('?post.pwd') && input('?post.pass')){
            $id=input('param.id');//接收
            $paw=input('param.paw');//接收
            $pwd=input('param.pwd');//接收
            $pass=input('param.pass');//接收
            //防止sql注入以及xss攻击
            $paw = strip_tags($paw);
            $paw = addslashes($paw);
            $pwd = strip_tags($pwd);
            $pwd = addslashes($pwd);
            $pass = strip_tags($pass);
            $pass = addslashes($pass);
            if(strlen($paw)<6){
                return json(['code'=>10001,'msg'=>$uppwdmsg['uppwd_error'],'data'=>[],'url' => []]);
            }elseif(strlen($paw)>20){
                return json(['code'=>10002,'msg'=>$uppwdmsg['uppwd_error1'],'data'=>[],'url' => []]);
            }elseif($paw!=$pass){
                return json(['code'=>10003,'msg'=>$uppwdmsg['uppwd_error2'],'data'=>[],'url' => []]);
            }else{
                $where = [
                    'f_user_phone' => $id,
                    'f_user_pwd'=>md5($pwd)
                ];
                $res =  Db::table('f_user')->where($where)->find();
                if($res){
                    $where =[
                        'f_user_phone' => $id
                    ];
                    $uppwd = [
                        'f_user_pwd'=>md5($pass)
                    ];
                    $res = Db::table('f_user')->where($where)->update($uppwd);
                    if($res){
                        Session::delete('nowlogin');
                        return json(['code'=>10000,'msg'=>$uppwdmsg['uppwd_success'],'data'=>[],'url' => url('reception/Index/index')]);
                    }else{
                        return json(['code'=>10005,'msg'=>$uppwdmsg['uppwd_error4'],'data'=>[],'url' => []]);
                    }
                }else{
                    return json(['code'=>10004,'msg'=>$uppwdmsg['uppwd_error3'],'data'=>[],'url' => []]);
                }
            }
        }
    }
    //获取我的游记
    public function getnote(){
        session_start();
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from f_travel_note WHERE f_travel_note_uid={$nowlogin}") ;
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select a.*,b.f_user_name from f_travel_note a,f_user b where a.f_travel_note_uid=b.f_user_id  and  a.f_travel_note_uid={$nowlogin} limit {$start},3");
        if(!empty($res)){
            $request = Request::instance();
            foreach($res as $key=>&$value){
                $checkHttp = strpos($value['f_travel_note_img'],"http");//判断字段中是否存在http
                //echo $value['f_travel_note_img'];
                if($checkHttp === false){//如果不存在就替换字符串路径
                    $value['f_travel_note_img'] = str_replace("__STATIC__",$request->root()."/static/",$value['f_travel_note_img']);//字符串替换
                }
            }
        }
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //支付页面
    public function payment(){
        $orderID=input("param.orderID");//接收订单id
        $where = [
            'b_order_id'=>$orderID
        ];
        $orders = Db::table('b_order')->where($where)->find();//查询订单信息
        $orders1=[$orders];//转二维数组
        $this->assign("content",$orders1);//赋值
        return $this->fetch();
    }
    //余额支付
    public function pay(){
        $paybalance = config('msg')['paybalance']; //调用配置文件
        if(input('?post.pwd')){
            $pwd=input('param.pwd');//接收
            //防止xss攻击以及sql语句注入
            $pwd = strip_tags($pwd);
            $pwd = addslashes($pwd);
            $orderID=input("param.id");
            $ordermon=input("param.mon");
            $nowlogin = Session::get('nowlogin');
            if($nowlogin!=''){
                $orderres = Db::query("select b_order_state from b_order WHERE b_order_id = {$orderID}");
                if($orderres[0]['b_order_state']=='未支付'){
                    $WHERE = [
                        'f_user_id'=>$nowlogin,
                        'f_user_pass' =>md5($pwd)
                    ];
                    $res = Db::table('f_user')->where($WHERE)->find();//判断支付密码是否正确
                    if($res!=''){
                        $mymon = Db::query("select f_user_money from f_user WHERE f_user_id={$nowlogin}");//查询当前用户的金币余额
                        if($ordermon>$mymon[0]['f_user_money']){
                            return json(['code'=>10001,'msg'=>$paybalance['paybalance_error'],'data'=>[],'url' => url('reception/Center/center')]);
                        }else{
                            $nowmon = $mymon[0]['f_user_money']-$ordermon;
                            $where = [
                                'f_user_id'=>$nowlogin
                            ];
                            $mon = [
                                'f_user_money'=>$nowmon
                            ];
                            $res = Db::table('f_user')->where($where)->update($mon);//修改支付过后的金币余额
                            if($res===1){
                                $where = [
                                    'b_order_id'=>$orderID
                                ];
                                $update = [
                                    'b_order_state'=>'待使用'
                                ];
                                Db::table('b_order')->where($where)->update($update);//修改订单状态
                                return json(['code'=>10000,'msg'=>$paybalance['paybalance_success'],'data'=>[],'url' => url('reception/Center/center')]);
                            }else{
                                return json(['code'=>10002,'msg'=>$paybalance['paybalance_error1'],'data'=>[],'url' =>'']);
                            }
                        }
                    }else{
                        return json(['code'=>10006,'msg'=>$paybalance['paybalance_error5'],'data'=>[],'url' =>'']);
                    }
                }else{
                    return json(['code'=>10004,'msg'=>$paybalance['paybalance_error3'],'data'=>[],'url' =>'']);
                }
            }else{
                return json(['code'=>10003,'msg'=>$paybalance['paybalance_error2'],'data'=>[],'url' =>'']);
            }
        }else{
            return json(['code'=>10005,'msg'=>$paybalance['paybalance_error4'],'data'=>[],'url' =>'']);
        }
    }
    //余额充值
    public function newmon(){
        $addmon = config('msg')['addmon']; //调用配置文件
        if(input('?post.upmon')){
            $upmon=input('param.upmon');//接收
            //防止sql注入以及xss攻击
            $upmon = strip_tags($upmon);
            $upmon = addslashes($upmon);
            $nowlogin = Session::get('nowlogin');
            if($upmon<=0){
                return json(['code'=>10002,'msg'=>$addmon['addmon_error1'],'data'=>[],'url' =>'']);
            }else{
                $res = preg_match('/^\\d+$/',$upmon);//正则判断
                if($res==0){
                    return json(['code'=>10003,'msg'=>$addmon['addmon_error2'],'data'=>[],'url' =>'']);
                }elseif($res==1){
                    $mymon = Db::query("select f_user_money from f_user WHERE f_user_id={$nowlogin}");//查询当前用户金币余额
                    $allmon = $mymon[0]['f_user_money']+$upmon;
                    $where = [
                        'f_user_id'=>$nowlogin
                    ];
                    $mon = [
                        'f_user_money'=>$allmon
                    ];
                    $res = Db::table('f_user')->where($where)->update($mon);//修改金币余额
                    if($res===1){
                        return json(['code'=>10000,'msg'=>$addmon['addmon_success'],'data'=>[],'url' =>'']);
                    }else{
                        return json(['code'=>10004,'msg'=>$addmon['addmon_error3'],'data'=>[],'url' =>'']);
                    }
                }
            }
        }else{
            return json(['code'=>10001,'msg'=>$addmon['addmon_error'],'data'=>[],'url' =>'']);
        }
    }
    //获取我的所有订单
    public function getallorder(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from b_order WHERE b_order_user={$nowlogin}") ;//获取订单条数
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select * from b_order where b_order_user={$nowlogin} limit {$start},3");//获取当前页的三条数据信息
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //取消订单
    public function alterorder(){
        $uppwdmsg = config('msg')['alterorder']; //调用配置文件
        $nowlogin = Session::get('nowlogin');
        if(!empty($nowlogin)){//判断当前是否登录
            $orderID=input("param.orderID");
            $where = [
                'b_order_id'=>$orderID
            ];
            $res = Db::table('b_order')->where($where)->update(['b_order_state' => '订单关闭']);//改变订单状态
            if($res==1){
                return json(['code'=>10000,'msg'=>$uppwdmsg['alterorder_success'],'data'=>[],'url' => '']);
            }
            else{
                return json(['code'=>10001,'msg'=>$uppwdmsg['alterorder_error1'],'data'=>[],'url' => '']);
            }
        }else{
            return json(['code'=>10002,'msg'=>$uppwdmsg['alterorder_error'],'data'=>[],'url' => '']);
        }
    }
    //获取待支付的订单
    public function unpaidorder(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from b_order WHERE b_order_user={$nowlogin} and b_order_state = '未支付'") ;//获取未支付的订单有多少条
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select * from b_order where b_order_user={$nowlogin} and b_order_state = '未支付' limit {$start},3");//获取当前页未支付的三条信息
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //获取待出行订单
    public function paidorders(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from b_order WHERE b_order_user={$nowlogin} and b_order_state = '待使用'") ;//获取待出行的订单有多少条
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select * from b_order where b_order_user={$nowlogin} and b_order_state = '待使用' limit {$start},3");//获取当前页待出行的三条信息
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //获取该订单详情
    public function getthisorder(){
        if(input('?post.orderID')){
            $orderID = input('param.orderID');
            $sciencearr = Db::query("select a.b_order_details_state, a.b_order_details_id, a.b_order_details_num, a.b_order_details_price , b.f_science_name , b.f_science_id from b_order_details a,f_science b WHERE a.b_order_details_classid=1 AND a.b_order_details_gid=b.f_science_id AND a.b_order_details_oid= {$orderID}");
            $hotelarr = Db::query("select a.b_order_details_state, a.b_order_details_id, a.b_order_details_num, a.b_order_details_price , b.f_hotel_name , b.f_hotel_id from b_order_details a,f_hotel b WHERE a.b_order_details_classid=2 AND a.b_order_details_gid=b.f_hotel_id AND a.b_order_details_oid= {$orderID}");
            $resarr = [
                '0'=>$sciencearr,
                '1'=>$hotelarr
            ];
            return $resarr;
        }
    }
    //获取已收藏的景点
    public function Scenic_collectionUrl(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from f_enshrine WHERE f_enshrine_uid={$nowlogin} and f_enshrine_cid = 2") ;//获取总条数
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select a.f_enshrine_sid,b.f_science_img,b.f_science_content,b.f_science_name from f_enshrine a,f_science b where a.f_enshrine_uid={$nowlogin} and a.f_enshrine_cid = 2 and a.f_enshrine_sid=b.f_science_id limit {$start},3");//获取当前页的数据
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //获取已收藏的酒店
    public function Viewport_collectionUrl(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from f_enshrine WHERE f_enshrine_uid={$nowlogin} and f_enshrine_cid = 1") ;//获取总条数
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select a.f_enshrine_sid,b.f_hotel_img,b.f_hotel_content,b.f_hotel_name from f_enshrine a,f_hotel b where a.f_enshrine_uid={$nowlogin} and a.f_enshrine_cid = 1 and a.f_enshrine_sid=b.f_hotel_id limit {$start},3");//获取当前页的数据
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }

    //获取已收藏的游记
    public function Travels_collectionUrl(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from f_enshrine WHERE f_enshrine_uid={$nowlogin} and f_enshrine_cid = 3") ;//获取总条数
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select a.f_enshrine_sid,f_travel_note_img,b.f_travel_note_intro,f_travel_note_head from f_enshrine a,f_travel_note b where a.f_enshrine_uid={$nowlogin} and a.f_enshrine_cid = 3 and a.f_enshrine_sid=b.f_travel_note_id limit {$start},3");//获取当前页的数据
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //获取待评价订单
    public function appraiseorderrUrl(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from b_order WHERE b_order_user={$nowlogin} and b_order_state = '交易成功'") ;//获取待出行的订单有多少条
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select * from b_order where b_order_user={$nowlogin} and b_order_state = '交易成功' limit {$start},3");//获取当前页待出行的三条信息
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //使用订单
    public function useUrl(){

        $uppwdmsg = config('msg')['use']; //调用配置文件

        $nowlogin = Session::get('nowlogin');

        if($nowlogin!=''){

            if(input('?post.b_order_details_id')){
                $orderID = input('param.b_order_details_id');
                $where = [
                    'b_order_details_id'=>$orderID
                ];

                $res = Db::query('UPDATE b_order_details  SET "b_order_details_state"="已使用"  WHERE  b_order_details_id = 12');//改变订单状态
//                if($res==1){
//                    return json(['code'=>10000,'msg'=>$uppwdmsg['use_success'],'data'=>[],'url' => '']);
//                }
//                else{
//                    return json(['code'=>10001,'msg'=>$uppwdmsg['use_error'],'data'=>[],'url' => '']);
//                }
            }
        }else{
            echo '非法操作';
        }

    }

    //获取景点购物车信息
    public function shopping_viewUrl(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from f_shopping_cart WHERE f_shopping_cart_uid={$nowlogin} and f_shopping_cart_classid = 1") ;//获取总条数
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select b.f_science_id,b.f_science_name,b.f_science_price from f_shopping_cart a,f_science b where a.f_shopping_cart_uid={$nowlogin} and a.f_shopping_cart_classid = 1 and a.f_shopping_cart_gid=b.f_science_id limit {$start},3");//获取当前页的数据
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }
    //获取景点购物车信息
    public function shopping_hotelUrl(){
        $nowlogin = Session::get('nowlogin');
        $count = Db::query("select count(*) count  from f_shopping_cart WHERE f_shopping_cart_uid={$nowlogin} and f_shopping_cart_classid = 2") ;//获取总条数
        $allpage = ceil($count[0]['count']/3);
        $nowpage = input('param.nowpage');
        $start = ($nowpage-1)*3;
        $res = Db::query("select b.f_hotel_id,b.f_hotel_name,b.f_hotel_price from f_shopping_cart a,f_hotel b where a.f_shopping_cart_uid={$nowlogin} and a.f_shopping_cart_classid = 2 and a.f_shopping_cart_gid=b.f_hotel_id limit {$start},3");//获取当前页的数据
        $arr = [
            'nowpage'=>$nowpage,
            'allpage'=>$allpage,
            'data'=>$res
        ];
        return $arr;
    }


}