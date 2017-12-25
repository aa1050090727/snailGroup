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
                    'code':this.code
                };
                $.ajax({
                    url:insterin,
                    dateType:"json",
                    data:user,
                    type:"post",
                    success:function(res){
                        console.log(res)
                    }
                })
            }

        }
    }
})