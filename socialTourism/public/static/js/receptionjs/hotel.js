/**
 * Created by 陈凌峰 on 2017/12/26.
 */
//设置日期时间控件
//Datetime();
//function Datetime() {
//    $('#start_time').datetimepicker({
//        language: 'zh-CN',//显示中文
//        format: 'yyyy-mm-dd',//显示格式
//        minView: "month",//设置只显示到月份
//        initialDate: new Date(),
//        autoclose: true,//选中自动关闭
//        todayBtn: true,//显示今日按钮
//        locale: moment.locale('zh-cn')
//    });
//    //默认获取当前日期
//    var today = new Date();
//    var nowdate = (today.getFullYear()) + "-" + (today.getMonth() + 1) + "-" + today.getDate();
//    //对日期格式进行处理
//    var date = new Date(nowdate);
//    var mon = date.getMonth() + 1;
//    var day = date.getDate();
//    var mydate = date.getFullYear() + "-" + (mon < 10 ? "0" + mon : mon) + "-" + (day < 10 ? "0" + day : day);
//    document.getElementById("now_date").value = mydate;
//}

function showDetails(id){
    console.log(id);
    //发送ajax，将当前地点存起来
    $.ajax({
        type:"post",
        url:hotel_setCookie_Url,
        data:{"f_hotel_id":id},
        dataType:"json",
        success:function(res){
            console.log(res);
            if(res.code == 1){
                location.href = hoteDetailsUrl;
            }
            else{
                location.reload();
            }
        },
        error:function(res){
            console.log("error",res);
        }
    });
 //
}
$(function(){

});