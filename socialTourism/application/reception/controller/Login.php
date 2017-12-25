<?php
namespace app\reception\controller;

use \think\Controller;
use \think\Db;
use \think\Request;
use think\Cache;


class Login extends Controller
{

    public function login(){
        return $this->fetch();
    }


    public function register(){
        return $this->fetch();
    }

    public function registeryan(){
        return $this->fetch();
    }

//    public function json(){
//        $arron = isset($_POST['arr'])?$_POST['arr']:"";
//        var_dump(count($arron));
//        for($i = 0;$i<count($arron);$i++){
//            $data=['f_province_name'=>$arron[$i]['name']];
//            Db::table('f_province')->insert($data);
//            for($z =0;$z<count($arron[$i]['city']);$z++){
//                $data=['f_city_name'=>$arron[$i]["city"][$z]['name'],'f_city_fid'=>$i+1];
//
//                Db::table('f_city')->insert($data);
//
//                for($k =0;$k<count($arron[$i]['city'][$z]['area']);$k++){
//
//                    $data=['f_district_name'=>$arron[$i]["city"][$z]['area'][$k],'f_district_fid'=>$z+1];
//
//                    Db::table('f_district')->insert($data);
//                }
//            }
//        }
//
//    }

    public function mian(){
//        $rawpostdata = file_get_contents('php://input');
//        $post = json_decode($rawpostdata,true);
        if(input('?post.uname')  && input('?post.pwd') && input('?post.code')){
            $uname=input('param.uname');
            $pwd=input('param.pwd');
            $code=input('param.code');

            $chak=captcha_check($code);
            if($chak){
                $where = [
                    'uname' =>   $uname,
                    'upwd' =>   $pwd
                ];
                $result  = Db::table('t_user')->where($where)->find();
                if(!empty($result)){
                    echo 0;
                }else{
                    echo -1;
                }
            }else{
                echo -2;
            }

        }

    }

    public function mydata(){
        if(input('?get.keyword')){
            $key=input('param.keyword');
            $mywenjuan=Cache::get('good_'.$key);
            if(!$mywenjuan){
                $mywenjuan = Db::table('wjx')->where('wname','like','%'.$key.'%')->paginate(5);
                Cache::set('good_'.$key,$mywenjuan,3600);
            }

        }else{
            $mywenjuan = Db::table('wjx')->paginate(5);
        }


        $title = '个人中心页面';
        $this->assign('list',$mywenjuan);
        $this->assign('pageTitle',$title);
        return $this->fetch('mian');
    }




}
