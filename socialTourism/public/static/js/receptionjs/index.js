/**
 * Created by 陈凌峰 on 2018/1/2.
 */
    /*游记模块*/
var app=new Vue({
    el:"#travel",
    data:{
        hotNowPage:1,
        hotAllPage:[],
        hotTravel:[],
        newNowPage:1,
        newAllPage:[],
        newTravel:[],
    },
    created:function(){
        this.hottravel(1);
        this.newtravel(1);
    },
    methods:{
        /*热门游记*/
        hottravel:function (nowPage){
            var _this=this
            $.ajax({
                type:'get',
                dataType:'json',
                url:hotTravelUrl,
                data:{'nowPage':nowPage},
                success:function(res){
                    _this.hotNowPage=res.nowPage;
                    _this.hotTravel=res.hotTravel;
                    _this.hotAllPage=res.allPage;
                }
            })
        },
        /*热门游记当前页*/
        hotNow:function(){
            var _this=this;
            var nowpage=$(event.target).html();
            _this.hottravel(nowpage);
        },
        /*热门游记上一页*/
        hotLast: function () {
            var _this=this;
            var nowpage=parseInt(_this.hotNowPage)-1;
            if(nowpage<=0){
                alert("当前已经是最前页！")
            }else {
                _this.hottravel(nowpage);
            }
        },
        /*热门游记下一页*/
        hotNext:function(){
            var _this=this;
            var nowpage=parseInt(_this.hotNowPage)+1;
            if(nowpage>_this.hotAllPage){
                alert("当前已经是最后一页！")
            }else {
                _this.hottravel(nowpage);
            }
        },
        /*热门游记阅读详情*/
        readHot:function(){
            var travelID=$(event.target).attr("travelID");
            $.ajax({
                type:"post",
                url:hotTravelAdd,
                data:{"travelID":travelID},
                dataType:"text",
                success:function(res){
                    if (res==0){
                        window.location.href=travelDetail+"?travelID="+travelID;
                    }
                    else if(res==1){
                        window.location.href=travelDetail+"?travelID="+travelID;
                    }
                }
            })
        },
        /*新游记*/
        newtravel:function (nowPage){
            var _this=this
            $.ajax({
                type:'get',
                dataType:'json',
                url:newTravelUrl,
                data:{'nowPage':nowPage},
                success:function(res){
                    _this.newNowPage=res.nowPage;
                    _this.newTravel=res.hotTravel;
                    _this.newAllPage=res.allPage;
                }
            })
        },
        /*新游记当前页*/
        newNow:function(){
            var _this=this;
            var nowpage=$(event.target).html();
            _this.newtravel(nowpage);
        },
        /*新游记上一页*/
        newLast: function () {
            var _this=this;
            var nowpage=parseInt(_this.newNowPage)-1;
            if(nowpage<=0){
                alert("当前已经是最前页！")
            }else {
                _this.newtravel(nowpage);
            }
        },
        /*新游记下一页*/
        newNext:function(){
            var _this=this;
            var nowpage=parseInt(_this.newNowPage)+1;
            if(nowpage>_this.newAllPage){
                alert("当前已经是最后一页！")
            }else {
                _this.newtravel(nowpage);
            }
        },
    }
})
