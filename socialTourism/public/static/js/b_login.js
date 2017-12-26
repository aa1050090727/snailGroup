/**
 * Created by fire on 2017/12/26.
 */



var app2 = new Vue({
    el: '#login',
    data: {
        phone: '',
        pwd:'',
        code:''
    },

    methods: {
        login:function(){
            var user ={
                'uname':this.phone,
                'pwd':this.pwd,
                'code':this.code
            } ;
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
                    }else if(res['code']==10004){
                        alert(res['msg'])
                    }else if(res['code']==10005){
                        alert(res['msg'])
                    }
                }
            })
        },



        tell:function(){
            if(this.phone==''){
                $('#telltps').css('color','red');
                $('#telltps').css('font-size','20px');
                $("#telltps").html("× 输入不能为空")
            }else {
                $('#telltps').css('color','green');
                $('#telltps').css('font-size','20px');
                $("#telltps").html("√")
            }
        },


        doSomethingElse:function(){
            if(this.pwd==''){
                $('#pwdtps').css('color','red');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("× 输入不能为空")
            }else if(this.pwd.length<6){
                $('#pwdtps').css('color','red');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("× 输入长度小于6位")
            }else if(this.pwd.length>20){
                $('#pwdtps').css('color','red');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("× 输入长度大于20位")
            }else {
                $('#pwdtps').css('color','green');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("√")
            }
        },


        codein:function(){
            if(this.code==''){
                $('#codeon').css('color','red');
                $('#codeon').css('font-size','20px');
                $("#codeon").html("× 输入不能为空")
            }else {
                $('#codeon').css('color','green');
                $('#codeon').css('font-size','20px');
                $("#codeon").html("√")
            }
        }
    }
})