/**
 * Created by fire on 2017/12/28.
 */
Vue.component('mytravels',{
    template : '<div>\
                        <div v-for="item in wenjlist">\
                            <div class="panel panel-default">\
                                <div class="panel-body">\
                                    {{item.ID}}\
                                </div>\
                                <div class="panel-footer">{{item.wname}}</div>\
                            </div>\
                        </div>\
                        <nav>\
                            <ul class="pager">\
                                <li><a href="#" v-on:click="prev()">上一页</a></li>\
                                <li><a href="#" @click="next()">下一页</a></li>\
                            </ul>\
                        </nav>\
                    </div>',
    data:function(){
        return{
            wenjlist:[
                {'wname':'123','ID':"10056"},
                {'wname':'1232','ID':"10057"},
                {'wname':'1233','ID':"10058"},
                {'wname':'1234','ID':"10059"},
                {'wname':'1235','ID':"10060"}
            ],
            allpage:1,
            nowpage:1
        }
    },
    created:function(){
        this.init(this.nowpage)
    },
    methods:{
        init:function(page){
            var _this = this;
            $.ajax({
                url:"index.php?c=mian&a=getwj&nowpage="+page,
                dateType:"json",
                data:"",
                type:"get",
                success:function(res){
                    $arr = JSON.parse(res)
                    _this.wenjlist = $arr[0]
                    _this.allpage = $arr[1]['allPage']
                    _this.nowpage = $arr[1]['nowPage']
                }
            })
        },
        //上一页
        prev:function(){
            var lastpage = parseInt(this.nowpage)-1 <= 0 ? 1 : parseInt(this.nowpage)-1;
            this.init(lastpage)

        },
        //下一页
        next: function () {
            var nextpage = parseInt(this.nowpage)+1 > this.allpage ? this.allpage : parseInt(this.nowpage)+1;
            this.init(nextpage)
        }
    }
});
new Vue({
    el: '#showwj'
})

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



