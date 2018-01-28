/**
 * Created by 陈凌峰 on 2018/1/10.
 */
layui.use('table', function() {
    var table = layui.table;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
        if(layEvent === 'preview'){
            $.ajax({
                type:"get",
                url:scienceScience,
                data:{scienceId:data["f_science_id"]},
                dataType:"json",
                success: function ($res) {
                    //console.log($res["data"]);
                    if($res["code"] === 10000){
                        layer.open({
                            title: '景点内容',
                            content: '<div>'+$res["data"]["f_science_name"]+'</div><div>'+$res["data"]["f_science_detall"]+'</div>',
                            area: ['100%',"100%"],
                            btn:['审核通过','审核不通过','关闭']
                            //审核通过
                            ,yes: function(index, layero){
                                layer.confirm('你确定通过吗？', function(index){
                                    if(data["f_science_state"] === "审核通过" || data["f_science_state"] === "审核未通过" || data["f_science_state"] === "商品下架"){
                                        if(data["f_science_state"] === "商品下架"){
                                            layer.alert("该商品已被下架");
                                        }else{
                                            layer.alert("该商品已审核过");
                                        }

                                    }else{
                                        $.ajax({
                                            type:"get",
                                            url:examineScience,
                                            data:{scienceId:data["f_science_id"],scienceState:1},
                                            dataType:"json",
                                            success: function ($res) {
                                                //console.log($res["msg"]);
                                                if($res["code"] === 10000){
                                                    obj.update({
                                                        f_science_state: "审核通过"
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
                            //审核为通过
                            ,btn2: function(index, layero){
                                layer.confirm('你确定不通过吗？', function(index){
                                    if(data["f_science_state"] === "审核通过" || data["f_science_state"] === "审核未通过" || data["f_science_state"] === "商品下架"){
                                        if(data["f_science_state"] === "商品下架"){
                                            layer.alert("该商品已被下架");
                                        }else{
                                            layer.alert("该商品已审核过");
                                        }
                                    }else{
                                        $.ajax({
                                            type:"get",
                                            url:examineScience,
                                            data:{scienceId:data["f_science_id"],scienceState:3},
                                            dataType:"json",
                                            success: function ($res) {
                                                //console.log($res["msg"]);
                                                if($res["code"] === 10000){
                                                    obj.update({
                                                        f_science_state: "审核未通过"
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
        } else if(layEvent === 'del'){
            layer.confirm('你确定要下架该景点吗？', function(index){
                if(data["f_science_state"] === "审核通过"){
                    $.ajax({
                        type:"get",
                        url:examineScience,
                        data:{scienceId:data["f_science_id"],scienceState:4},
                        dataType:"json",
                        success: function ($res) {
                            //console.log($res["msg"]);
                            if($res["code"] === 10000){
                                obj.update({
                                    f_science_state: "商品下架"
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
        var searchKeyword = $("#scienceSearch").val();
        table.reload('scienceData',{
            url : getScience+'?keyword='+searchKeyword
        })
    });
});

