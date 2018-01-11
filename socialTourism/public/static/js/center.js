/**
 * Created by fire on 2017/12/28.
 */

//获取个人信息
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
        pass:"",
        myimg:''
    },
    created:function(){
       this.init()
    },
    methods:{

        addmon:function(){
            var _this = this;
            if(confirm('确定充值吗？')){
                $upmon = $("#upmon").val()
                $.ajax({
                    url:newmon,
                    dateType:"json",
                    data:'upmon='+$upmon,
                    type:"post",
                    success:function(res){
                        if(res['code']==10000){
                            alert(res['msg'])
                            _this.init()
                            $("#upmon").val('')
                        }else if(res['code']==10001){
                            alert(res['msg'])
                        }else if(res['code']==10002){
                            alert(res['msg'])
                        }else if(res['code']==10003){
                            alert(res['msg'])
                        }else if(res['code']==10004){
                            alert(res['msg'])
                        }
                    }
                })
            }

        },

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
                    _this.myimg=res['f_user_img']
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
        },



    }

})

//获取游记
var showwj = new Vue({
    el:"#showwj",
    data:{
        todos : [

        ],
        allpage:1,
        nowpage:1,
        mynoname:'',
        mymon:'',
        myimg:''
    },
    created:function(){
        this.getnote(1);
        this.init();
    },
    methods:{
        getnote: function (page) {
            var _this = this;
            $.ajax({
                url:getnote,
                dateType:"json",
                data:'nowpage='+page,
                type:"post",
                success:function(res){
                    _this.todos=res['data'];
                    _this.allpage = res['allpage']
                    _this.nowpage = res['nowpage']
                }
            })
        },

        init:function(){
            var _this = this;
            $.ajax({
                url:getmy,
                dateType:"json",
                data:'',
                type:"post",
                success:function(res){
                    _this.mynoname=res['f_user_name']
                    _this.mymon=res['f_user_money']
                    _this.myimg=res['f_user_img']
                }
            })
        },

        mysetprev:function(){
            if(parseInt(this.nowpage)-1 <= 0){
                alert('当前是第一页')
            }else {
                var lastpage =parseInt(this.nowpage)-1;
                this.getnote(lastpage)
            }


        },
        mysetnext:function(){
            if(parseInt(this.nowpage)+1 > this.allpage){
                alert('当前是最后一页')
            }else {
                var nextpage =parseInt(this.nowpage)+1;
                this.getnote(nextpage)
            }


        },

        /*阅读全文*/
        travelDetails:function(){
            var travelID=$(event.target).attr("travelID");
            window.location.href=travelDetail+"?travelID="+travelID;
        },
    }
})

//获取所有订单
var allorder = new Vue({
    el:"#allorder",
    data:{
        orders : [

        ],
        allpage:1,
        nowpage:1,
        sciencedetails : [],
        hoteldetails: []
    },
    created:function(){
        this.getallorder(1)
    },
    methods:{

        //获取该订单的详情
        getdetails:function(){
            var _this = this;
            var orderID=$(event.target).attr("id");
            $.ajax({
                url:getthisorder,
                dateType:"json",
                data:'orderID='+orderID,
                type:"post",
                success:function(res){
                    _this.sciencedetails  = res[0]
                    _this.hoteldetails  = res[1]
                }
            })
        },

        //页面跳转
        goDetail:function() {
            var f_science_id = $(event.target).attr("id");
            $.ajax({
                type: "post",
                data: {"f_science_id": f_science_id},
                dataType: "json",
                url: setViewId_url,
                success: function (res) {
                    console.log(res.result);
                    if (res.result == true) {
                        window.location.href = goViewdetailed_url;
                    }
                    else {
                        window.location.href = error_url;
                    }
                },
                error: function (res) {
                    console.log("error", res);
                }
            });
        },

        showDetails:function() {
            //发送ajax，将当前地点存起来
            var id = $(event.target).attr("id");
            $.ajax({
                type: "post",
                url: hotel_setCookie_Url,
                data: {"f_hotel_id": id},
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    if (res.code == 1) {
                        location.href = hoteDetailsUrl;
                    }
                    else {
                        location.reload();
                    }
                },
                error: function (res) {
                    console.log("error", res);
                }
            })
        },

        //获取数据
        getallorder:function(page){
            var _this = this;
            $.ajax({
                url:getallorder,
                dateType:"json",
                data:'nowpage='+page,
                type:"post",
                success:function(res){
                    console.log(res)
                    _this.orders=res['data'];
                    _this.allpage = res['allpage']
                    _this.nowpage = res['nowpage']
                }
            })
        },
        //上一页
        allorderprev:function(){
            if(parseInt(this.nowpage)-1 <= 0){
                alert('当前是第一页')
            }else {
                var lastpage =parseInt(this.nowpage)-1;
                this.getallorder(lastpage)
            }


        },
        //下一页
        allordernext:function(){
            if(parseInt(this.nowpage)+1 > this.allpage){
                alert('当前是最后一页')
            }else {
                var nextpage =parseInt(this.nowpage)+1;
                this.getallorder(nextpage)
            }
        },

        //去支付
        gopay:function(){
            var orderID=$(event.target).attr("id");
            window.location.href=gopay+"?orderID="+orderID;
        },


        //取消订单
        alterorder:function(){
            var _this = this;
            if(confirm('确定要取消订单吗？')){
                var orderID=$(event.target).attr("id");
                $.ajax({
                    url:alterorder,
                    dateType:"json",
                    data:'orderID='+orderID,
                    type:"post",
                    success:function(res){
                        if(res['code']==10000){
                            alert(res['msg'])
                            _this.getallorder(1)
                        }else if(res['code']==10001){
                            alert(res['msg'])
                        }else if(res['code']==10002){
                            alert(res['msg'])
                        }
                    }
                })
            }

        }
    }
})

//获取待支付订单
var unpaidorder = new Vue({
    el:"#unpaidorder",
    data:{
        orders : [

        ],
        allpage:1,
        nowpage:1
    },
    created:function(){
        this.unpaidorder(1)
    },
    methods:{
        //获取数据
        unpaidorder:function(page){
            var _this = this;
            $.ajax({
                url:unpaidorderel,
                dateType:"json",
                data:'nowpage='+page,
                type:"post",
                success:function(res){
                    console.log(res)
                    _this.orders=res['data'];
                    _this.allpage = res['allpage']
                    _this.nowpage = res['nowpage']
                }
            })
        },

        //上一页
        unpaidprev:function(){
            if(parseInt(this.nowpage)-1 <= 0){
                alert('当前是第一页')
            }else {
                var lastpage =parseInt(this.nowpage)-1;
                this.unpaidorder(lastpage)
            }
        },
        //下一页
        unpaidnext:function(){
            if(parseInt(this.nowpage)+1 > this.allpage){
                alert('当前是最后一页')
            }else {
                var nextpage =parseInt(this.nowpage)+1;
                this.unpaidorder(nextpage)
            }
        },

        //去支付
        gopay:function(){
            var orderID=$(event.target).attr("id");
            window.location.href=gopay+"?orderID="+orderID;
        },


        //取消订单
        alterorder:function(){
            var _this = this;
            if(confirm('确定要取消订单吗？')){
                var orderID=$(event.target).attr("id");
                $.ajax({
                    url:alterorder,
                    dateType:"json",
                    data:'orderID='+orderID,
                    type:"post",
                    success:function(res){
                        if(res['code']==10000){
                            alert(res['msg'])
                            _this.unpaidorder(1)
                        }else if(res['code']==10001){
                            alert(res['msg'])
                        }else if(res['code']==10002){
                            alert(res['msg'])
                        }
                    }
                })
            }

        }
    }

})

//获取已支付订单
var paidorder = new Vue({
    el:"#paidorder",
    data:{
        orders : [

        ],
        allpage:1,
        nowpage:1
    },
    created:function(){
        this.paidordere(1)
    },
    methods:{
        //获取数据
        paidordere:function(page){
            var _this = this;
            $.ajax({
                url:paidorders,
                dateType:"json",
                data:'nowpage='+page,
                type:"post",
                success:function(res){
                    console.log(res)
                    _this.orders=res['data'];
                    _this.allpage = res['allpage']
                    _this.nowpage = res['nowpage']
                }
            })
        },

        //上一页
        paidorderprev:function(){
            if(parseInt(this.nowpage)-1 <= 0){
                alert('当前是第一页')
            }else {
                var lastpage =parseInt(this.nowpage)-1;
                this.paidordere(lastpage)
            }
        },
        //下一页
        paidordernext:function(){
            if(parseInt(this.nowpage)+1 > this.allpage){
                alert('当前是最后一页')
            }else {
                var nextpage =parseInt(this.nowpage)+1;
                this.paidordere(nextpage)
            }
        },
    }
})



