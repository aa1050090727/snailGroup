/**
 * Created by Administrator on 2018/1/3 0003.
 */
var app=new Vue({
    el:"#sell",
    data:{
        loginState:0,
        nowlogin:[]
    },
    created: function () {
        this.login();
    },
    methods: {
        /*卖家入驻*/
        seller: function () {
            $.ajax({
                url:sell,
                data:'',
                dataType:'json',
                type:'post',
                success:function(res){
                    if(res['code']==1){
                        alert(res['msg']);
                    }
                   else {
                        location.href=res['url'];
                    }
                },
                error:function(res){
                    console.log("error",res)
                }
            })
        },
        /*登录显示*/
        login:function(){
            var _this=this;
            $.ajax({
                type:'get',
                dataType:'json',
                url:loginUrl,
                success:function(res){
                    _this.loginState=res.loginState;
                    _this.nowlogin=res.nowlogin;
                }
            })
        },
        /*退出登录*/
        loginout:function(){
            var _this=this;
          if (confirm("确定退出？")){
              $.ajax({
                  dataType:'json',
                  type:'get',
                  url:loginOutUrl,
                  success: function (res) {
                      alert('退出成功！');
                      _this.loginState=res.loginState;
                      _this.nowlogin=res.nowlogin;
                      window.location.reload()
                  }
              })
          }
        },
    }
})
/*小屏幕下*/
var app=new Vue({
    el:"#screen",
    methods:{
        /*首页*/
        index:function () {
            window.location.href=indexUrl;
        },
        /*目的地*/
        place:function(){
            window.location.href=placeUrl;
        },
        /*游记*/
        travels:function(){
            window.location.href=travelsUrl;
        },
        /*商品活动*/
        activity:function(){
            window.location.href=activityUrl;
        },
        /*酒店*/
        hotel:function(){
            window.location.href=hotelUrl;
        },
        /*商家入驻*/
        settled:function(){
            $.ajax({
                url:sell,
                data:'',
                dataType:'json',
                type:'post',
                success:function(res){
                    if(res['code']==1){
                        alert(res['msg']);
                    }
                    else {
                        location.href=res['url'];
                    }
                },
                error:function(res){
                    console.log("error",res)
                }
            })
        },
    }
})