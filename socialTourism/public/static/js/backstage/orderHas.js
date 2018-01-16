/**
 * Created by 陈凌峰 on 2018/1/12.
 */
layui.use(['table','form'], function() {
    var table = layui.table;
    var form = layui.form;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值
        console.log(data,layEvent);

        if(layEvent === 'check'){
            $.ajax({
                type:"get",
                url:getIdPay,
                data:{orderId:data["b_order_id"]},
                dataType:"json",
                success: function ($res) {
                    console.log($res["data"]);
                    var html = "";
                    for(var i = 0;i<$res["data"].length;i++){
                        html+="<ul><li><sapn>ID:</sapn><span>"+$res["data"][i]["b_order_details_id"]+"</span></li><li><sapn>商品类型:</sapn><span>"+$res["data"][i]["b_order_details_classid"]+"</span></li><li><sapn>商品ID:</sapn><span>"+$res["data"][i]["b_order_details_gid"]+"</span></li><li><sapn>商品数量:</sapn><span>"+$res["data"][i]["b_order_details_num"]+"</span></li><li><sapn>商品总价:</sapn><span>"+$res["data"][i]["b_order_details_price"]+"</span></li><li><sapn>录入时间:</sapn><span>"+$res["data"][i]["b_order_details_entering"]+"</span></li><li><sapn>离开时间:</sapn><span>"+$res["data"][i]["b_order_details_leave"]+"</span></li><li><sapn>商家:</sapn><span>"+$res["data"][i]["f_user_phone"]+"</span></li></ul>"
                    }

                    layer.open({
                        title: '订单详情',
                        content: html,
                        btn: ['关闭'],
                        area: ['500px',"450px"],
                        yes: function(index, layero){
                            layer.close(index);
                        }
                    });
                },
                error: function ($res) {
                    console.log($res);
                }
            });
        }
    });

    //订单模糊查询
    $("#searchBut").click(function () {
        var searchKeyword = $("#orderSearch").val();
        table.reload('orderData',{
            url : getHasPay+'?keyword='+searchKeyword
        })
    });
});
