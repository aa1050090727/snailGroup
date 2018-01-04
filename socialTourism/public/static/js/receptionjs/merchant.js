/**
 * Created by Administrator on 2018/1/3 0003.
 */
$(function(){
    var app=new Vue({
        el: "#app",
        data: {
            province:{},
            city:{},
            district:{}
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
            })
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
                    data.append("content",content);
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
                            }
                        }
                    })
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
                }else{
                    $(".startTime").removeAttr("disabled");
                    $(".endTime").removeAttr("disabled");
                    $(".goodsPrices").removeAttr("disabled");
                }
            },
            /*酒店还是景点*/
            goodsClass:function(){
                var goodsClass=$(".goodsClass").val();
                if (goodsClass==1){
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
            }
        }
    })
});
