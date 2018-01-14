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
           $.ajax({
            type:"post",
            url:travelAdd,
            data:{"travelID":travelID},
            dataType:"text",
            success:function(res){
            if (res==0){
                window.location.href=travelDetail+"?travelID="+travelID;
            }
            else if(res==1){
                window.location.href=travelDetail+"?travelID="+travelID;
            }
            }
            })

       },
       /*搜索游记*/
       search:function(){
           var place=$("#place").val();
           window.location.href=travelSearch+"?place="+place;
       }
   }
});