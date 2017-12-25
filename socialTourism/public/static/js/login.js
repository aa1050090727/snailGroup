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
                    if(res==0){
                        alert('登录成功')
                    }else if(res==-1){
                        alert('密码错误')
                    }else if(res==-2){
                        alert('输入不能为空')
                    }else if(res==-3){
                        alert('账号错误')
                    }
                }
            })
        }
    },

})