/**
 * Created by 陈凌峰 on 2017/12/28.
 */

var menu = new Vue({
    el:"#menu",
    data:{
        list:[]
    },
    created: function () {
        var _this = this;
        $.ajax({
            type:"get",
            url:getMenuArr,
            dataType:"json",
            success: function ($res) {
                //console.log($res["code"]);
                if($res["code"] == 10000){
                    console.log($res["data"]);
                    _this.list = $res["data"];
                }else{
                    location.href = $res["url"];
                }
            },
            error: function ($res) {
                console.log($res);
            }
        });
    }
});