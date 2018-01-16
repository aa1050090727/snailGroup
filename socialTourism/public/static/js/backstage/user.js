/**
 * Created by 陈凌峰 on 2018/1/14.
 */
layui.use(['table','form'], function() {
    var table = layui.table;
    var form = layui.form;
    //layuiTable = table;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值
        //console.log(data,layEvent);

        if(layEvent === 'reset'){
            layer.confirm('你确定重置密码吗？', function(index){
                $.ajax({
                    type:"get",
                    url:resetPsw,
                    data:{userId:data["f_user_id"]},
                    dataType:"json",
                    success: function ($res) {
                        //console.log($res["msg"]);
                        if($res["code"] === 10000){
                            layer.alert($res["msg"]+",重置后的密码为666666");
                        }else{
                            layer.alert($res["msg"]);
                        }
                        layer.close(index);
                    },
                    error: function ($res) {
                        console.log($res);
                    }
                });
            });
        } else if(layEvent === 'state'){
            var butStatus = "";
            if(data["f_user_states"] === "使用"){
                butStatus = "锁定";
            }else{
                butStatus = "使用";
            }
            layer.confirm('你确定要更改用户状态为“'+butStatus+'”吗？', function(index){
                $.ajax({
                    type:"get",
                    url:upUserState,
                    data:{userId:data["f_user_id"],status:butStatus},
                    dataType:"json",
                    success: function ($res) {
                        //console.log($res);
                        if($res["code"] === 10000){
                            obj.update({
                                f_user_states: butStatus
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
            });
        }
    });


    //员工模糊查询
    $("#searchBut").click(function () {
        var searchKeyword = $("#userSearch").val();
        table.reload('userData',{
            url : getUser+'?keyword='+searchKeyword
        })
    });
});