/**
 * Created by Administrator on 2018/1/3 0003.
 */
$(function(){
    var app=new Vue({
        el: "#app",
        data: {
            province:{},
            city:{},
            district:{},
            order:[],
            allpage:[],
            orderNowpage:1,
            goods:[],
            goodsallpage:[],
            goodsNowpage:1,
            goodsclass:1,
        },
        created:function(){
            var _this=this;
            $.ajax({
                type:'get',
                data:'',
                dataType:'json',
                url:provinceUrl,
                success: function (res) {
                    _this.province=res.province;
                },
                error:function(res){
                }
            });
            this.orderShow(1);
            this.goodShow(1);
        },
        methods:{
            /*提交内容*/
            travelPublic:function(){
                if(confirm("确定提交？")){
                    var goodsName=$("#name").val();
                    var img=document.getElementById("inputImg").files[0];
                    var goodsRelease=$(".goodsRelease").val();
                    var goodsClass=$(".goodsClass").val();
                    var province=$(".province").val();
                    var city=$(".city").val();
                    var district=$(".district").val();
                    var Price=$(".goodsPrice").val();
                    var goodsPrice=$(".goodsPrices").val();
                    var goodsNumber=$(".goodsNumber").val();
                    var startTime=$(".startTime").val();
                    var endTime=$(".endTime").val();
                    var inputIntro=$("#inputIntro").val();
                    var content=editor.getContent() ;
                    var data=new FormData();
                    data.append("goodsName",goodsName);
                    data.append("img",img);
                    data.append("goodsRelease",goodsRelease);
                    data.append("goodsClass",goodsClass);
                    data.append("province",province);
                    data.append("city",city);
                    data.append("district",district);
                    data.append("Price",Price);
                    data.append("goodsPrice",goodsPrice);
                    data.append("goodsNumber",goodsNumber);
                    data.append("startTime",startTime);
                    data.append("endTime",endTime);
                    data.append("inputIntro",inputIntro);
                    data.append("content",inputIntro);
                    /*判断是否有没填写的*/
                    if (goodsName==''|| img==undefined || goodsClass==0|| province==0|| city==0|| district==0 || Price==''|| inputIntro==''|| inputIntro==''){
                        alert('请正确填写信息！');
                    }else {
                        /*是普通发布*/
                        if(goodsRelease==1)
                        {
                            /*景点发布*/
                            if(goodsClass==1){
                                $.ajax({
                                    type:"post",
                                    url:goodPublic,
                                    data:data,
                                    cache:false,
                                    contentType:false,
                                    processData:false,
                                    dataType:"text",
                                    success:function(res) {
                                        if (res==1)
                                        {
                                            alert("提交成功，等待审核");
                                        }else if(res==0){
                                            alert("提交失败");
                                        }else if (res==2)
                                        {
                                            alert('请发布对应类型商品！');
                                        }
                                    }
                                })
                            }else {
                                if (goodsNumber=='')
                                {
                                    alert('请填写库存！')
                                }else {
                                    $.ajax({
                                        type:"post",
                                        url:goodPublic,
                                        data:data,
                                        cache:false,
                                        contentType:false,
                                        processData:false,
                                        dataType:"text",
                                        success:function(res) {
                                            if (res==1)
                                            {
                                                alert("提交成功，等待审核");
                                            }else if(res==0){
                                                alert("提交失败");
                                            }else if (res==2)
                                            {
                                                alert('请发布对应类型商品！');
                                            }
                                        }
                                    })
                                }
                            }
                        }
                        /*活动发布*/
                        else
                        {
                            if(goodsPrice==''|| startTime==''|| endTime==''||goodsNumber=='')
                            {
                                alert('请正确填写活动信息！');
                            }else {
                                $.ajax({
                                    type:"post",
                                    url:goodPublic,
                                    data:data,
                                    cache:false,
                                    contentType:false,
                                    processData:false,
                                    dataType:"text",
                                    success:function(res) {
                                        if (res==1)
                                        {
                                            alert("提交成功，等待审核");
                                        }else if(res==0){
                                            alert("提交失败");
                                        }else if (res==2)
                                        {
                                            alert('请发布对应类型商品！');
                                        }
                                    }
                                });
                            }
                        }
                    }
                }

            },
            /*图片预览*/
            goodsImg:function(event){
                //图片预览
                var picture=event.target.files[0];
                if (picture!=undefined)
                {
                    /*检验对浏览器的支持*/
                    if(window.FileReader)
                    {
                        var file=new FileReader();
                        /*读取完成触发，无论成功或失败*/
                        file.onloadend=function(res)
                        {
                            $("#img").attr("src",res.target.result);
                        };
                        file.readAsDataURL(picture);
                    }
                }
            },
            /*普通还是活动商品*/
            goodsRelease:function(){
                var goodsRelease = $(".goodsRelease").val();
                if(goodsRelease == 1){
                    $(".startTime").attr("disabled","disabled");
                    $(".endTime").attr("disabled","disabled");
                    $(".goodsPrices").attr("disabled","disabled");
                    $(".goodsNumber").attr("disabled","disabled")
                }else{
                    $(".startTime").removeAttr("disabled");
                    $(".endTime").removeAttr("disabled");
                    $(".goodsPrices").removeAttr("disabled");
                    $(".goodsNumber").removeAttr("disabled");
                }
            },
            /*酒店还是景点*/
            goodsClass:function(){
                var goodsRelease = $(".goodsRelease").val();
                var goodsClass=$(".goodsClass").val();
                if (goodsClass==1 && goodsRelease==1){
                    $('.goodsNumber').attr('disabled','disabled')
                }else {
                    $('.goodsNumber').removeAttr('disabled');

                }
            },
            /*根据省份选城市*/
            changeProvince:function (){
                var _this=this;
                var provinvce=$('.province').val();
                $.ajax({
                    url:cityUrl,
                    data:{"provinvce":provinvce},
                    type:'get',
                    dataType:"json",
                    success:function(res){
                        _this.city=res.city;

                    }
                })
            },
            /*根据城市选地区*/
            changeCity:function (){
                var _this=this;
                var city=$('.city').val();
                $.ajax({
                    url:districe,
                    data:{"city":city},
                    type:'get',
                    dataType:"json",
                    success:function(res){
                        _this.district=res.district;

                    }
                })
            },
            /*订单分页显示*/
            orderShow:function(page){
                var _this=this;
                $.ajax({
                    type:'get',
                    dataType:"json",
                    data:{'page':page},
                    url:orderUrl,
                    success:function(res){
                        _this.order=res.order;
                        _this.allpage=res.allpage;
                        _this.orderNowpage=res.nowpage
                    }
                })
            },
            /*订单第几页*/
            ordernowpage:function(){
                var _this=this;
                var nowpage=$(event.target).html();
                _this.orderShow(nowpage)
            },
            /*订单下一页*/
            orderNext: function () {
                var _this=this;
                if(parseInt(_this.orderNowpage)+1 > this.allpage){
                    alert("已经是最后一页了");
                }else {
                    var page=parseInt(this.orderNowpage)+1
                    _this.orderShow(page);
                }

            },
            /*订单上一页*/
            orderLast: function () {
                var _this=this;
                if(parseInt(_this.orderNowpage)-1 <= 0){
                    alert("已经是最前页了");
                }else {
                    var page=parseInt(this.orderNowpage)-1
                    _this.orderShow(page);
                }

            },
            /*订单出单*/
            orderIssu:function(){
                var _this=this;
                var orderId=$(event.target).parent().attr('orderid');
                if (confirm("确定出单？"))
                {
                    $.ajax({
                        data:{'orderId':orderId},
                        type:'post',
                        dataType:'json',
                        url:orderIssuUrl,
                        success:function(res){
                            if(res.code==1)
                            {
                                alert('出单成功')
                                _this.orderShow(_this.orderNowpage);
                            }else{
                                alert('出单失败！')
                            }
                        }
                    })
                }

            },
            /*商品分页显示*/
            goodShow:function(page){
                var _this=this;
                $.ajax({
                    type:'get',
                    dataType:"json",
                    data:{'page':page},
                    url:goodsUrl,
                    success:function(res){
                        _this.goods=res.order;
                        _this.goodsallpage=res.allpage;
                        _this.goodsNowpage=res.nowpage;
                        _this.goodsclass=res.class
                    }
                })
            },
            /*商品第几页*/
            goodsnowpage:function(){
                var _this=this;
                var nowpage=$(event.target).html();
                _this.goodShow(nowpage)
            },
            /*商品下一页*/
            goodsNext: function () {
                var _this=this;
                if(parseInt(_this.goodsNowpage)+1 > this.goodsallpage){
                    alert("已经是最后一页了");
                }else {
                    var page=parseInt(this.goodsNowpage)+1
                    _this.goodShow(page);
                }

            },
            /*商品上一页*/
            goodsLast: function () {
                var _this=this;
                if(parseInt(_this.goodsNowpage)-1 <= 0){
                    alert("已经是最前页了");
                }else {
                    var page=parseInt(this.goodsNowpage)-1
                    _this.goodShow(page);
                }

            },
            /*商品下架*/
            goodsDown:function(){
                var _this=this;
                if(confirm("确定下架？"))
                {
                    var goodsid=$(event.target).parent().attr('goodsid');
                    $.ajax({
                        data:{'goodsid':goodsid},
                        type:'post',
                        dataType:'json',
                        url:goodsDownUrl,
                        success:function(res){
                            if(res.code==1)
                            {
                                alert('下架成功')
                                _this.goodShow(_this.goodsNowpage);
                            }else{
                                alert('下架失败！')
                            }
                        }
                    })
                }
            },
            /*商品上架*/
            goodsUp:function(){
                var _this=this;
                if(confirm("确定上架？"))
                {
                    var goodsid=$(event.target).parent().attr('goodsid');
                    $.ajax({
                        data:{'goodsid':goodsid},
                        type:'post',
                        dataType:'json',
                        url:goodsUpUrl,
                        success:function(res){
                            if(res.code==1)
                            {
                                alert('上架成功')
                                _this.goodShow(_this.goodsNowpage);
                            }else{
                                alert('上架失败！')
                            }
                        }
                    })
                }
            },
        }
    })
});
