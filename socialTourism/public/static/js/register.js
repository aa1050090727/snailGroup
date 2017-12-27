/**
 * Created by fire on 2017/12/23.
 */
function checkMobile(s){
    var regu =/^[1][3/5/8][0-9]{9}$/;
    var re = new RegExp(regu);
    if (re.test(s)) {
        return true;
    }else{
        return false;
    }
}

var vue = new Vue({
    el: '#register',
    data: {
        cellphone:''
    },
    methods: {
        regis:function(){
            if(this.cellphone.length<11)
            {
                alert('长度不能小于于11位')
            }else if(this.cellphone.length>11){
                alert('长度不能大于于11位')
            } else {
                $res = checkMobile(this.cellphone)
                if($res==true){
                    var user ={
                        'uname':this.cellphone
                    }
                    $.ajax({
                        url:verify,
                        dateType:"json",
                        data:user,
                        type:"post",
                        success:function(res){
                            console.log(res)
                            if(res['code']==10000){
                                location.href=res['url']
                            }else if(res['code']==10001){
                                alert(res['msg'])
                            }else if(res['code']==10002){
                                alert(res['msg'])
                            }
                        }
                    })
                }else {
                    alert('手机号码格式不正确')
                }
            }

        },

        phone:function(){
            if(this.cellphone==''){
                $('#phonetps').css('color','red');
                $('#phonetps').css('font-size','20px');
                $("#phonetps").html("× 输入不能为空")
            }else if(this.cellphone.length<11){
                $('#phonetps').css('color','red');
                $('#phonetps').css('font-size','20px');
                $("#phonetps").html("× 电话号码长度小于11位数")
            }else if(this.cellphone.length>11){
                $('#phonetps').css('color','red');
                $('#phonetps').css('font-size','20px');
                $("#phonetps").html("× 电话号码长度大于11位数")
            }else if(!checkMobile(this.cellphone)){
                $('#phonetps').css('color','red');
                $('#phonetps').css('font-size','20px');
                $("#phonetps").html("× 电话号码格式不正确")
            }else {
                var user ={
                    'cellphone':this.cellphone
                }
                $.ajax({
                    url:verifyphone,
                    dateType:"json",
                    data:user,
                    type:"post",
                    success:function(res){
                        if(res['code']==10000){
                            $('#phonetps').css('color','green');
                            $('#phonetps').css('font-size','20px');
                            $("#phonetps").html("√"+res['msg'])
                        }else if(res['code']==10001){
                            $('#phonetps').css('color','green');
                            $('#phonetps').css('font-size','20px');
                            $("#phonetps").html("√"+res['msg'])
                        }
                    }
                })

            }
        }

    }

})














