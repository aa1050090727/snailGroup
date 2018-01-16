/**
 * Created by 陈凌峰 on 2018/1/11.
 */
/**
 * Created by 陈凌峰 on 2018/1/10.
 */
layui.use('table', function() {
    var table = layui.table;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
        //商品预览
        if(layEvent === 'preview'){
            $.ajax({
                type:"get",
                url:previewHotel,
                data:{hotelId:data["f_hotel_id"]},
                dataType:"json",
                success: function ($res) {
                    //console.log($res["data"]);
                    if($res["code"] === 10000){
                        layer.open({
                            title: '酒店内容',
                            content: '<div>'+$res["data"]["f_hotel_name"]+'</div><div>'+$res["data"]["f_hotel_content"]+'</div>',
                            area: ['100%',"100%"],
                            btn:['审核通过','审核不通过','关闭']
                            //审核通过
                            ,yes: function(index, layero){
                                layer.confirm('你确定通过吗？', function(index){
                                    if(data["f_hotel_state"] === "审核通过" || data["f_hotel_state"] === "审核未通过" || data["f_hotel_state"] === "商品下架"){
                                        if(data["f_hotel_state"] === "商品下架"){
                                            layer.alert("该商品已被下架");
                                        }else{
                                            layer.alert("该商品已审核过");
                                        }

                                    }else{
                                        $.ajax({
                                            type:"get",
                                            url:examineHotel,
                                            data:{hotelId:data["f_hotel_id"],hotelState:1},
                                            dataType:"json",
                                            success: function ($res) {
                                                //console.log($res["msg"]);
                                                if($res["code"] === 10000){
                                                    obj.update({
                                                        f_hotel_state: "审核通过"
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
                            //审核未通过
                            ,btn2: function(index, layero){
                                layer.confirm('你确定不通过吗？', function(index){
                                    if(data["f_hotel_state"] === "审核通过" || data["f_hotel_state"] === "审核未通过" || data["f_hotel_state"] === "商品下架"){
                                        if(data["f_hotel_state"] === "商品下架"){
                                            layer.alert("该商品已被下架");
                                        }else{
                                            layer.alert("该商品已审核过");
                                        }
                                    }else{
                                        $.ajax({
                                            type:"get",
                                            url:examineHotel,
                                            data:{hotelId:data["f_hotel_id"],hotelState:3},
                                            dataType:"json",
                                            success: function ($res) {
                                                //console.log($res["msg"]);
                                                if($res["code"] === 10000){
                                                    obj.update({
                                                        f_hotel_state: "审核未通过"
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
                            ,btn3: function(index, layero){
                                layer.close(index);
                            }
                        });
                    }else{
                        layer.alert($res["msg"]);
                    }
                    //layer.close(index);
                },
                error: function ($res) {
                    console.log($res);
                }
            });
        }
        //商品下架
        else if(layEvent === 'del'){
            layer.confirm('你确定要下架该酒店吗？', function(index){
                if(data["f_hotel_state"] === "审核通过"){
                    $.ajax({
                        type:"get",
                        url:examineHotel,
                        data:{hotelId:data["f_hotel_id"],hotelState:4},
                        dataType:"json",
                        success: function ($res) {
                            //console.log($res["msg"]);
                            if($res["code"] === 10000){
                                obj.update({
                                    f_hotel_state: "商品下架"
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
                }else{
                    layer.alert("只有审核通过的才能下架");
                }
            });
        }
    });

    //酒店模糊查询
    $("#searchBut").click(function () {
        var searchKeyword = $("#hotelSearch").val();
        table.reload('hotelData',{
            url : getHotel+'?keyword='+searchKeyword
        })
    });
});

