/**
 * Created by fire on 2017/12/28.
 */



var myset = new Vue({
    el:"#myset",
    data:{
        myname:'',
        mynoname:'',
        mymon:'',
        myphone:'',
        mysex:"",
        paw:"",
        pwd:"",
        pass:""
    },
    created:function(){
       this.init()
    },
    methods:{
        init:function(){
            var _this = this;
            $.ajax({
                url:getmy,
                dateType:"json",
                data:'',
                type:"post",
                success:function(res){
                    _this.myname=res['f_user_name']
                    _this.mynoname=res['f_user_name']
                    _this.mymon=res['f_user_money']
                    _this.myphone=res['f_user_phone']
                    _this.mysex=res['f_user_sex']
                }
            })
        },

        updatamyname:function(){
            var _this = this;
            if(confirm('确定要修改昵称吗？')){
                $user = {
                    'id':_this.myphone,
                    'nowname':this.myname
                }
                $.ajax({
                    url:updatamyname,
                    dateType:"json",
                    data:$user,
                    type:"post",
                    success:function(res){
                        if(res==1) {
                            alert('昵称修改成功');
                            _this.init()
                        }else if(res==5){
                            alert('昵称长度不能大于8')
                            _this.init()
                        }else if(res=0){
                            alert('昵称修改失败')
                            _this.init()
                        }
                    }
                })
            }
        },

        updatamypaw:function(){
            var _this = this;
            if(confirm('确定要修改密码吗？')){
                $user = {
                    'id':_this.myphone,
                    'pwd':this.pwd,
                    'paw':this.paw,
                    'pass':this.pass
                }
                $.ajax({
                    url:updatamypaw,
                    dateType:"json",
                    data:$user,
                    type:"post",
                    success:function(res){
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
            }
        }

    }

})


