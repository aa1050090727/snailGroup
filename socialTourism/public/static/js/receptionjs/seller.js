/**
 * Created by Administrator on 2018/1/4 0004.
 */
var app=new Vue({
    el:"#app",
    data:{
        sellerState:{},
        province:{},
        city:{},
        district:{},
        number:"",
        place:''
    },
    created:function(){
        var _this=this;
        /*页面显示*/
        $.ajax({
            url:sellState,
            type:'post',
            dataType:"json",
            success:function(res){
                _this.sellerState=res.sellerState;
            }
        });
        /*省份获取*/
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
        /*身份证失焦*/
        idNumber:function(){
            var _this=this;
            var idNumber=$("#idNumber").val();
            if(fun_idNumber(idNumber)){
                _this.number="";
            }else {
                _this.number="请输入正确身份证号码！";
            }
        },
        /*地址失焦*/
        sellPlace: function () {
            var _this=this;
            var inputIntro=$("#inputIntro").val();
            if(inputIntro=='')
            {
                _this.place="请填写正确地址！";
            }else {
                _this.place="";
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
        /*提交信息*/
        sellsubmit:function(){
            var idNumber=$("#idNumber").val();
            var provinvce=$('.province').val();
            var city=$('.city').val();
            var district=$('.district').val();
            var goodsClass=$('.goodsClass').val();
            var inputIntro=$('#inputIntro').val();
            if (fun_idNumber(idNumber)==false|| provinvce==0||city==0||district==0||goodsClass==0){
                alert('请正确填写信息！');
            }else {
                $.ajax({
                    url:sellsubmit,
                    type:"post",
                    data:{'idNumber':idNumber,"provinvce":provinvce,"city":city,"district":district,"goodsClass":goodsClass,"inputIntro":inputIntro},
                    dataType:"json",
                    success: function (res) {
                        if(res['code']==1){
                            alert(res['msg']);
                            location.reload();
                        }else {
                            alert(res['msg']);
                        }
                    }
                })
            }
        },
        /*重新申请*/
        reup:function(){
            $.ajax({
                url:sellrReup,
                type:"post",
                dataType:"json",
                success:function(res){
                    if(res['code']==1){
                        location.reload();
                    }else {
                        alert(res['msg']);
                    }
                }
            })
        }

    }
})
/*身份证验证*/
function fun_idNumber(s){
    var regu =/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}[0-9Xx]$)/;
    var re = new RegExp(regu);
    if (re.test(s)) {
        return true;
    }else{
        return false;
    }
}
