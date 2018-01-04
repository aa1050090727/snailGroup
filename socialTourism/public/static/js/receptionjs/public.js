/**
 * Created by Administrator on 2018/1/3 0003.
 */
var app=new Vue({
    el:"#sell",
    data:{

    },
    methods: {
        seller: function () {
            $.ajax({
                url:sell,
                data:'',
                dataType:'json',
                type:'post',
                success:function(res){
                    if(res['code']==1){
                        alert(res['msg']);
                    }
                   else {
                        location.href=res['url'];
                    }
                },
                error:function(res){
                    console.log("error",res)
                }
            })
        }
    }
})