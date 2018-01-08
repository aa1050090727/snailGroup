/**
 * Created by Administrator on 2017/12/28.
 */
$(function(){
    var now_href = window.location.href;
    //console.log(now_href.indexOf("#comment"));
    if(now_href.indexOf("#comment")!=-1){
        $('#tab_profile').addClass("active");
        $('#profile').addClass("active");
        $('#tab_home').removeClass("active");
        $('#home').removeClass("active");
    }
    new Vue({
        el:"#view_detail",
        data:{
            viewDetail:{}
        },
        created:function(){
            var _this = this;
            $.ajax({
                type:"post",
                dataType:"json",
                url:getViewDetail,
                success:function(resDetail){
                   // console.log(resDetail.viewDetail);
                    if(resDetail.length==0){
                        window.location.href = error_url;
                    }
                    else{
                        _this.viewDetail = resDetail.viewDetail[0];
                    }
                },
                error:function(resDetail){
                    console.log("error",resDetail);
                }
            });
        },
        methods:{
            //点击立即购买
            nowBuy:function(viewDetail){
                //alert(id);
                $.ajax({
                    type:"post",
                    url:nowBuy_url,
                    dataType:"json",
                    data:{
                        "science_id":viewDetail.f_science_id,
                        "science_price":viewDetail.f_science_price,
                        "science_sid":viewDetail.f_science_sid
                    },
                    success:function(result){
                        console.log(result);
                        if(result["code"]==1){
                            if(confirm("立即支付")){
                                //跳转至支付页面
                                window.location.href=center_url+"?orderID="+result.orderId;
                            }

                        }
                        else if(result["code"]==2){
                            alert("添加失败");
                        }
                        else{
                            alert("您还没有登录哦~");
                        }
                    },
                    error:function(result){
                        console.log("error",result);
                    }
                });
            },
            putShopCar:function(id){
                $.ajax({
                    type:"post",
                    url:putShopCar_url,
                    dataType:"json",
                    data:{"science_id":id},
                    success:function(result){
                        console.log("购物车",result);
                        if(result["code"]==1){
                            alert("添加成功");
                        }
                        else if(result["code"]==2){
                            alert("添加失败");
                        }
                        else{
                            alert("您还没有登录哦~");
                        }
                    },
                    error:function(result){
                        console.log("error",result);
                    }
                });
            },
            panicBuying:function(viewDetail){
                alert(1)
            }
        }
    });
});
