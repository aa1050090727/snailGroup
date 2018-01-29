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
        cellcode:"",
        passwords:""
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
                    'passwords':this.passwords,
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
            if(this.name==""){
                alert('您的名号为空请输入后再获取验证码')
            }else if(this.pwd==""){
                alert('您的密码为空请输入后再获取验证码')
            }
            else if(this.password==""){
                alert('您的二次密码为空请输入后再获取验证码')
            }
            else if(this.passwords==""){
                alert('您的支付密码为空请输入后再获取验证码')
            }else {
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

                            }
                        })
                    }
                })
            }
        },

        user:function(){
            if(this.name==''){
                $('#nametps').css('color','red');
                $('#nametps').css('font-size','20px');
                $("#nametps").html("× 输入不能为空")
            }else if(this.name.length>8){
                $('#nametps').css('color','red');
                $('#nametps').css('font-size','20px');
                $("#nametps").html("× 输入不能大于八位数")
            }else{
                $('#nametps').css('color','green');
                $('#nametps').css('font-size','20px');
                $("#nametps").html("√ 用户名可用")
            }
        },

        pwdin:function(){
            if(this.pwd==''){
                $('#pwdtps').css('color','red');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("× 输入不能为空")
            }else if(this.pwd.length>20){
                $('#pwdtps').css('color','red');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("× 输入不能大于20位数")
            }else if(this.pwd.length<6){
                $('#pwdtps').css('color','red');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("× 输入不能小于6位数")
            }else{
                $('#pwdtps').css('color','green');
                $('#pwdtps').css('font-size','20px');
                $("#pwdtps").html("√ 密码可用")
            }
        },

        passwordin:function(){
            if(this.pwd!=this.password){
                $('#passwordtps').css('color','red');
                $('#passwordtps').css('font-size','20px');
                $("#passwordtps").html("× 两次密码输入不一致")
            }else {
                $('#passwordtps').css('color','green');
                $('#passwordtps').css('font-size','20px');
                $("#passwordtps").html("√ ")
            }
        },

        codein:function(){
            if(this.passwords==''){
                $('#codetps').css('color','red');
                $('#codetps').css('font-size','20px');
                $("#codetps").html("× 输入不能为空")
            }else if(this.passwords.length<6 ){
                $('#codetps').css('color','red');
                $('#codetps').css('font-size','20px');
                $("#codetps").html("×长度不能小于6")
            }else if(this.passwords.length>6 ){
                $('#codetps').css('color','red');
                $('#codetps').css('font-size','20px');
                $("#codetps").html("×长度不能于于6")
            }else {
                $('#codetps').css('color','green');
                $('#codetps').css('font-size','20px');
                $("#codetps").html("√ ")
            }
        } ,

        cellcodein:function(){
            if(this.cellcode==''){
                $('#cellcodetps').css('color','red');
                $('#cellcodetps').css('font-size','20px');
                $("#cellcodetps").html("× 手机验证码不能为空")
            }else {
                $('#cellcodetps').css('color','green');
                $('#cellcodetps').css('font-size','20px');
                $("#cellcodetps").html("√ ")
            }
        }
    }
})