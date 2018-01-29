/**
 * Created by 陈凌峰 on 2018/1/14.
 */
layui.use('table', function() {
    var table = layui.table;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
        if(layEvent === 'edit'){
            layer.confirm('你确定通过吗？', function(index){
                if(data["f_user_sell"] === "商家被锁定" || data["f_user_sell"] === "审核未通过" || data["f_user_sell"] === "成为卖家"){
                    if(data["f_user_sell"] === "审核未通过" || data["f_user_sell"] === "成为卖家"){
                        layer.alert("该商家已审核过");
                    }else{
                        layer.alert("商家被锁定");
                    }

                }else{
                    $.ajax({
                        type:"get",
                        url:upSellerState,
                        data:{userPhone:data["f_user_phone"],stats:5},
                        dataType:"json",
                        success: function ($res) {
                            //console.log($res["msg"]);
                            if($res["code"] === 10000){
                                obj.update({
                                    f_user_sell: "成为卖家"
                                });
                                layer.alert($res["msg"]);
                            }else{
                                layer.alert($res["msg"]);
                            }
                            layer.close(index);
                        },
                        error: function ($res) {
                            console.log($res);
                        }
                    });
                }


            });
        } else if(layEvent === 'del'){
            layer.confirm('你确定不通过吗？', function(index){
                if(data["f_user_sell"] === "商家被锁定" || data["f_user_sell"] === "审核未通过" || data["f_user_sell"] === "成为卖家"){
                    if(data["f_user_sell"] === "审核未通过" || data["f_user_sell"] === "成为卖家"){
                        layer.alert("该商家已审核过");
                    }else{
                        layer.alert("商家被锁定");
                    }

                }else{
                    $.ajax({
                        type:"get",
                        url:upSellerState,
                        data:{userPhone:data["f_user_phone"],stats:3},
                        dataType:"json",
                        success: function ($res) {
                            //console.log($res["msg"]);
                            if($res["code"] === 10000){
                                obj.update({
                                    f_user_sell: "审核未通过"
                                });
                                layer.alert($res["msg"]);
                            }else{
                                layer.alert($res["msg"]);
                            }
                            layer.close(index);
                        },
                        error: function ($res) {
                            console.log($res);
                        }
                    });
                }


            });
        }
    });

    //商家模糊查询
    $("#searchBut").click(function () {
        var searchKeyword = $("#sellerSearch").val();
        table.reload('sellerData',{
            url : getSeller+'?keyword='+searchKeyword
        })
    });
});