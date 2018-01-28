/**
 * Created by Administrator on 2018/1/9 0009.
 */
var app=new Vue({
    el:"#app",
    data:{
        comment:[],
        allpage:[],
        travelNowpage:1,
        collectState:0,
        collectNumber:0,
    },
    created:function(){
        this.commentShow(1);
        this.collectShow();
    },
    methods:{
        /*点击点评*/
        writeRemark:function(){
            $.ajax({
               dataType:'json',
                type:'get',
                url:writecommond,
                success:function(res){
                    if (res.code==1){
                        $('#myModal').modal('show')
                    }else {
                        alert("请先登录！")
                    }
                }
            });

        },
        /*发布评论*/
        writeSure:function(){
            var wreteContent=editor.getContent();
            var _this=this;
            $.ajax({
                data:{'wreteContent':wreteContent},
                type:'post',
                dataType:'json',
                url:writeSure,
                success:function(res){
                    if(res.code==1)
                    {
                        alert('评论成功！')
                        $('#myModal').modal('hide');
                        _this.commentShow(1);
                    }else {
                        alert("评论失败！")
                    }
                }
            })
        },
        /*评论显示*/
        commentShow:function(page){
            var _this=this;
            $.ajax({
                type:'get',
                dataType:"json",
                data:{'page':page},
                url:conmmentUrl,
                success:function(res){
                    _this.comment=res.comment;
                    _this.allpage=res.allpage;
                    _this.travelNowpage=res.travelNowpage
                }
            })
        },
        /*评论当前页*/
        nowPage: function () {
            var _this=this;
            var nowPage=$(event.target).html();
            _this.travelNowpage=nowPage;
            _this.commentShow( _this.travelNowpage);
        },
        /*评论上一页*/
        lastPage:function(){
            var _this=this;
            if(parseInt( _this.travelNowpage)-1<=0){
                alert("当前已经是最前页！")
            }else {
                _this.commentShow(parseInt( _this.travelNowpage)-1);
            }
        },
        /*评论下一页*/
        nextPage:function(){
            var _this=this;
            if(parseInt( _this.travelNowpage)+1>_this.allpage){
                alert("当前已经是最后一页！")
            }else {
                _this.commentShow(parseInt( _this.travelNowpage)+1);
            }
        },
        /*点击收藏游记*/
        collect:function(){
            var _this=this;
            $.ajax({
                url:collectUrl,
                type:'post',
                dataType:'json',
                success:function(res){
                    if(res.code==0)
                    {
                        alert('请先登录！');
                        _this.collectState=res.collect;
                    }else if(res.code==1){
                        alert('已经收藏过')
                        _this.collectState=res.collect;
                    }else if(res.code==2){
                        alert('收藏成功')
                        _this.collectState=res.collect;
                        _this.collectNumber=res.collectNumber;
                    }else if(res.code==3){
                        alert('收藏失败')
                        _this.collectState=res.collect;
                    }
                }
            })
        },
        /*收藏显示*/
        collectShow:function(){
            var _this=this;
            $.ajax({
                url:collectShowUrl,
                type:'post',
                dataType:'json',
                success:function(res){
                    _this.collectState=res.collect;
                    _this.collectNumber=res.collectNumber;
                }
            })
        },
        /*取消收藏*/
        collectCancle:function(){
            var _this=this;
            if (confirm("确定取消收藏？"))
            {
                $.ajax({
                    url:collectCancleUrl,
                    type:'post',
                    dataType:'json',
                    success:function(res){
                        if(res.code==1)
                        {
                            alert('取消收藏成功！');
                            _this.collectState=res.collect;
                            _this.collectNumber=res.collectNumber;
                        }else {
                            alert("取消失败！")
                            _this.collectState=res.collect;
                            _this.collectNumber=res.collectNumber;
                        }
                    }
                })
            }
        },
    }
})