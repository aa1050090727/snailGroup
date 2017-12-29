/**
 * Created by Administrator on 2017/12/27 0027.
 */
var app=new Vue({
    el:"#app",
    data:{

    },
   methods:{
       /*阅读全文*/
       travelDetails:function(){
           var travelID=$(event.target).attr("travelID");
           window.location.href=travelDetail+"?travelID="+travelID;
       },
       /*搜索游记*/
       search:function(){
           var place=$("#place").val();
            /*$.ajax({
            type:"post",
            url:travelSearch,
            data:{"search":place},
            dataType:"text",
            success:function(res){
            if (res==0){

            }
            else if(res==1){

            }
            }
            })*/
           window.location.href=travelSearch+"?place="+place;
       }
   }
});