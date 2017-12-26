/**
 * Created by fire on 2017/12/24.
 */


var vue = new Vue({
    el: '#inset',
    data: {
        name:'',
        pwd:"",
        password:"",
        code:"",
        cellcode:""
    },
    methods: {
        insert:function(){

            if(this.name.length>8){
                alert('昵称不能超过8位数')
            }else if(this.pwd.length<6){
                alert('密码长度不能小于六位数')
            }else if(this.pwd.length>20){
                alert('密码长度不能大于20位数')
            }else if(this.pwd!=this.password){
                alert('两次密码输入不一致')
            }else{
                var user ={
                    'name':this.name,
                    'pwd':this.pwd,
                    'password':this.password,
                    'code':this.code,
                    'cellcode':this.cellcode
                };
                $.ajax({
                    url:insterin,
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
                        }else if(res['code']==10006){
                            alert(res['msg'])
                        }
                    }
                })
            }

        },

        getnote:function(){
            $APPID='C41326166'
            $APPKEY  = '4fc2a6de51a7462b4121a0e1a5a1c0f9'
            $.ajax({
                url:getno ,
                dateType:"json",
                data:"",
                type:"post",
                success:function(res){
                    $.ajax({
                        url:"http://106.ihuyi.com/webservice/sms.php?method=Submit&account="+$APPID+"&password="+$APPKEY+"&mobile="+res["phone"]+"&content=您的验证码是："+res["code"]+"。请不要把验证码泄露给其他人。",
                        dateType:"json",
                        data:"",
                        type:"get",
                        success:function(res){
                            console.log(res)
                        }
                    })
                }
            })
        }
    }
})