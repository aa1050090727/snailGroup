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
            nowBuy:function(id){
                //alert(id);
                $.ajax({
                    type:"post",
                    url:nowBuy_url,
                    dataType:"json",
                    data:{"science_id":id},
                    success:function(result){
                        console.log(result);
                    },
                    error:function(result){
                        console.log("error",result);
                    }
                });
            },
            putShopCar:function(){
                $.ajax({
                    type:"post",
                    url:nowBuy_url,
                    dataType:"json",
                    data:{"science_id":id},
                    success:function(result){
                        console.log(result);
                    },
                    error:function(result){
                        console.log("error",result);
                    }
                });
            }
        }
    });
});
