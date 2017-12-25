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

        }

    }

})














