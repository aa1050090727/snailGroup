/**
 * Created by Administrator on 2018/1/3.
 */
//加入购物车
function putShoppingCar(id){
    var date = new Date();
    var nowDate = new Date(date.toLocaleDateString()).getTime();//当前时间戳
    var startDate = new Date(new Date(now_date.val()).toLocaleDateString()).getTime();//入住时间戳
    var endDate = new Date(new Date(now_date1.val()).toLocaleDateString()).getTime();//离开时间戳
//console.log(nowDate,startDate);
    //入住时间大于等于当前时间；离开时间大于等于入住时间
    if(startDate>=nowDate && endDate>=startDate){
        var startTime = new Date(now_date.val()).toLocaleDateString();//入住时间
        var endTime = new Date(now_date1.val()).toLocaleDateString();//离开时间
        $.ajax({
            type:"post",
            data:{
                "hotel_id":id,
                "enter_time":startTime,
                "leave_time":endTime
            },
            dataType:"json",
            url:putShoppingCar_url,
            success:function(res){
                console.log(res);
                if(res.code == 1){
                    alert("添加成功");
                }
                else if(res.code==2){
                    alert("添加失败");
                }
                else{
                    alert("您还没有登录");
                }
            },
            error:function(res){
                console.log("error",res);
            }
        });
    }
    else{
        alert("请选择正确的时间");
    }

}
//立即购买
function rightnowBuy(hotel_id,hotel_price,hotel_sid){
    var date = new Date();
    var nowDate = new Date(date.toLocaleDateString()).getTime();//当前时间戳
    var startDate = new Date(new Date(now_date.val()).toLocaleDateString()).getTime();//入住时间戳
    var endDate = new Date(new Date(now_date1.val()).toLocaleDateString()).getTime();//离开时间戳
//console.log(hotel_id,hotel_price,hotel_sid);
    //入住时间大于等于当前时间；离开时间大于等于入住时间
    if(startDate>=nowDate && endDate>=startDate){
        var startTime = new Date(now_date.val()).toLocaleDateString();//入住时间
        var endTime = new Date(now_date1.val()).toLocaleDateString();//离开时间
        $.ajax({
            type:"post",
            data:{
                "hotel_id":hotel_id,
                "hotel_price":hotel_price,
                "hotel_sid":hotel_sid,
                "enter_time":startTime,
                "leave_time":endTime
            },
            dataType:"json",
            url:rightnowBuy_url,
            success:function(res){
                console.log(res);
                if(res["code"]==1){
                    if(confirm("立即支付")){
                        //跳转至支付页面
                        window.location.href = center_url;
                    }

                }
                else if(res["code"]==2){
                    alert("添加失败");
                }
                else{
                    alert("您还没有登录哦~");
                }
            },
            error:function(res){
                console.log("error",res);
            }
        });
    }
    else{
        alert("请选择正确的时间");
    }
}
$(function(){
    $("#roomNum").val(1);
    //减数量
    $("#subRoom").on("click",function(){
        $("#roomNum").val(parseInt($("#roomNum").val())-1);
        if($("#roomNum").val(1)){
            $("#roomNum").val(1);
        }
    });
    //加数量
    $("#plusRoom").on("click",function(){
        $("#roomNum").val(parseInt($("#roomNum").val())+1);
    });
   //var vue = new Vue({
   //    el:"#content",
   //    data:{
   //        roomNum:1
   //    },
   //    created:function(){
   //
   //    },
   //    methods:{
   //        plusRoom:function(){
   //            this.roomNum++;
   //        },
   //        subRoom:function(){
   //            this.roomNum--;
   //            if(this.roomNum<=0){
   //                this.roomNum = 1;
   //            }
   //        }
   //    }
   //});
});