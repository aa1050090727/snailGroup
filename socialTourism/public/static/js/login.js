/**
 * Created by fire on 2017/12/4.
 */

var app2 = new Vue({
    el: '#login',
    data: {
        phone: '',
        pwd:''
    },

    methods: {
        login:function(){
            var user ={
                'uname':this.phone,
                'pwd':this.pwd
            }
            $.ajax({
                url:logincode,
                dateType:"json",
                data:user,
                type:"post",
                success:function(res){
                    console.log(res)
                    if(res['code']==10000){
                        alert(res['msg'])
                        location.href=res['url']
                    }else if(res['code']==10001){
                        alert(res['msg'])
                    }else if(res['code']==10002){
                        alert(res['msg'])
                    }else if(res['code']==10003){
                        alert(res['msg'])
                    }
                }
            })
        }
    },

})