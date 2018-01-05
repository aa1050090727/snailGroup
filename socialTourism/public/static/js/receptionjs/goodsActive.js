/**
 * Created by Administrator on 2017/12/29.
 */
$(function(){
    var now_href = window.location.href;
    if(now_href.indexOf("goodsactive_hotel")!=-1){
        $(':radio[name="a_classify"]:last').attr("checked",true);
    }
    else{
        $(':radio[name="a_classify"]:first').attr("checked",true);
    }
    $(':radio[name="a_classify"]').on("change",function(){
        if($(event.target).val()=="f_science"){
            window.location.href = f_science_url;
        }
        else{
            window.location.href = f_hotel_url;
        }
    });
});
function g_detail(classify,id){
    //console.log(classify,"classify");
    //console.log(id,"id");
    if(classify=='1'){
        //将景点id存进cookie
        $.ajax({
            type:"post",
            data:{"f_science_id":id},
            dataType:"json",
            url:setViewCookie_url,
            success:function(res){
                console.log(res.result);
                if(res.result == true) {
                    window.location.href = science_detail_url;
                }
                else{
                    window.location.href = error_url;
                }
            },
            error:function(res){
                console.log("error",res);
            }
        });
    }
    else{
        //将酒店id存进cookie
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
    }
}