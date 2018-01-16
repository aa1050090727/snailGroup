/**
 * Created by 陈凌峰 on 2018/1/3.
 */
layui.use(['table','form'], function() {
    var table = layui.table;
    var form = layui.form;
    //layuiTable = table;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值
        console.log(data,layEvent);

        if(layEvent === 'del'){
            layer.confirm('真的删除该员工吗？', function(index){
                if(data["b_user_role_id"] === 1){
                    layer.alert("对不起超级管理员不能删除！");
                    layer.close(index);
                }else{
                    $.ajax({
                        type:"get",
                        url:delStaff,
                        data:{userId:data["b_user_id"]},
                        dataType:"json",
                        success: function ($res) {
                            //console.log($res["msg"]);
                            if($res["code"] === 10000){
                                layer.alert($res["msg"]);
                                obj.del(); //删除对应行（tr）的DOM结构
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

        else if(layEvent === 'edit'){
            $.ajax({
                type:"get",
                url:getRoleName,
                data:'',
                dataType:"json",
                success: function ($res) {
                    console.log($res);
                    var html ='<select name="role" lay-verify="required"> ';
                    if(data["b_user_id"] === 1){
                        html ='<select name="role" lay-verify="required" disabled> ';
                    }
                    for(var i = 0;i<$res.length;i++){
                        if(data["b_user_id"] === 1){
                            if(data["b_role_name"] === $res[i]["b_role_name"]){
                                html +='<option value="'+$res[i]["b_role_id"]+'" selected >'+$res[i]["b_role_name"]+'</option>';
                            }else{
                                html +='<option value="'+$res[i]["b_role_id"]+'">'+$res[i]["b_role_name"]+'</option>';
                            }
                        }else{
                            if($res[i]["b_role_name"] !== "超级管理员"){
                                if(data["b_role_name"] === $res[i]["b_role_name"]){
                                    html +='<option value="'+$res[i]["b_role_id"]+'" selected >'+$res[i]["b_role_name"]+'</option>';
                                }else{
                                    html +='<option value="'+$res[i]["b_role_id"]+'">'+$res[i]["b_role_name"]+'</option>';
                                }
                            }
                        }

                    }
                    html +=' </select>';
                    layer.open({
                        title: '员工修改'
                        ,content: '<form class="layui-form" action=""> <div class="layui-form-item"> <label class="layui-form-label">ID:</label> <div class="layui-input-block"> <input type="text" name="id" required  lay-verify="required"  class="layui-input"  disabled value="'+data["b_user_id"]+'"> </div> </div><div class="layui-form-item"> <label class="layui-form-label">员工姓名:</label> <div class="layui-input-block"> <input type="text" name="name" required  lay-verify="required" autocomplete="off" class="layui-input" value="'+data["b_user_name"]+'"> </div> </div><div class="layui-form-item"> <label class="layui-form-label">员工职位:</label> <div class="layui-input-block"> '+html+'</div> </div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="*">立即修改</button> </div> </div></form>',
                        btn: ['重置密码', '关闭'],
                        area: ['500px',"450px"],
                        yes: function(index, layero){
                            layer.confirm('你要确定重置密码？', function(index){
                                $.ajax({
                                    type:"get",
                                    url:upStaffPsw,
                                    data:{staffId:data["b_user_id"]},
                                    dataType:"json",
                                    success: function ($res) {
                                        if($res["code"] === 10000){
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
                        ,btn2: function(index, layero){
                            layer.close(index);
                        }
                    });
                    form.render();
                    form.on('submit(*)', function(data){
                        //console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
                        //console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
                        var staffArr = data.field;
                        console.log(staffArr); //当前容器的全部表单字段，名值对形式：{name: value}
                        layer.confirm('你确定修改吗？', function(index){
                            $.ajax({
                                type:"get",
                                url:upStaff,
                                data:{staffArr:staffArr},
                                dataType:"json",
                                success: function ($res) {
                                    if($res["code"] === 10000){
                                        obj.update({
                                            b_user_name: staffArr["name"],
                                            b_role_name:$res["data"][0]["b_role_name"]
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


                        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。

                    });

                },
                error: function ($res) {
                    console.log($res);
                }
            });
        }else if(layEvent === 'state'){
            var butStatus = "";
            if(data["b_user_status"] === "使用"){
                butStatus = "锁定";
            }else{
                butStatus = "使用";
            }
            layer.confirm('你要确定'+butStatus+'改用户吗？', function(index){
                if(data["b_user_role_id"] === 1){
                    layer.alert("对不起超级管理员不能锁定！");
                    layer.close(index);
                }else{
                    $.ajax({
                        type:"get",
                        url:upStaffStatus,
                        data:{staffId:data["b_user_id"],status:butStatus},
                        dataType:"json",
                        success: function ($res) {
                            console.log($res);
                            if($res["code"] === 10000){
                                obj.update({
                                    b_user_status: butStatus
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

    //添加员工
    $("#addStaff").click(function () {
        $.ajax({
            type:"get",
            url:getRoleName,
            data:'',
            dataType:"json",
            success: function ($res) {
                console.log($res);
                var html ='<select name="role" lay-verify="required"> <option value=""></option>';
                for(var i = 0;i<$res.length;i++){
                    if($res[i]["b_role_name"] !== "超级管理员"){
                        html +='<option value="'+$res[i]["b_role_id"]+'">'+$res[i]["b_role_name"]+'</option>';
                    }
                }
                html +=' </select>';
                layer.open({
                    title: '添加员工',
                    content: '<form class="layui-form" action=""> <div class="layui-form-item"> <label class="layui-form-label">员工账号：</label> <div class="layui-input-block"> <input type="text" name="staffAccount" required  lay-verify="required|staffAccount" placeholder="请输入6位的字符" autocomplete="off" class="layui-input"> </div> </div>' +
                    '<div class="layui-form-item"> <label class="layui-form-label">员工姓名：</label> <div class="layui-input-block"> <input type="text" name="staffName" required  lay-verify="required|staffName" placeholder="请输入2到6位字符" autocomplete="off" class="layui-input"> </div> </div>' +
                    '<div class="layui-form-item"> <label class="layui-form-label">员工职位：</label> <div class="layui-input-block"> '+html+'</div> </div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="go">立即添加</button> </div> </div></form>',
                    btn: ['关闭'],
                    area: ['500px',"450px"],
                    yes: function(index, layero){
                        layer.close(index);
                    }
                });
                form.render();
                //layer.tips('只想提示地精准些', '#id');
                form.verify({
                    staffName: function(value, item){ //value：表单的值、item：表单的DOM对象
                        console.log(value.length);
                        if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                            return '姓名不能有特殊字符';
                        }
                        if(/(^\_)|(\__)|(\_+$)/.test(value)){
                            return '姓名首尾不能出现下划线\'_\'';
                        }
                        if(/^\d+\d+\d$/.test(value)){
                            return '姓名不能全为数字';
                        }
                        if(value.length < 2 || value.length > 6){
                            return '姓名只能为2到6位的字符';
                        }
                    },
                    staffAccount: function(value, item){ //value：表单的值、item：表单的DOM对象
                        if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                            return '账号不能有特殊字符';
                        }
                        if(/(^\_)|(\__)|(\_+$)/.test(value)){
                            return '账号首尾不能出现下划线\'_\'';
                        }
                        if(/^\d+\d+\d$/.test(value)){
                            return '账号不能全为数字';
                        }
                        if(/[\u4e00-\u9fa5]/.test(value)){
                            return '账号不能包含汉字';
                        }
                        if(value.length != 6){
                            return '账号只能为6位';
                        }
                    }

                });

                form.on('submit(go)', function(data){
                    var staffArr = data.field;
                    console.log(staffArr); //当前容器的全部表单字段，名值对形式：{name: value}
                    layer.confirm('你确定添加吗？', function(index){
                        $.ajax({
                            type:"get",
                            url:addStaff,
                            data:{staffArr:staffArr},
                            dataType:"json",
                            success: function ($res) {
                                if($res["code"] === 10000){
                                    table.reload('staffData',{
                                        url : getStaff+'?keyword='
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

                    return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。

                });

            },
            error: function ($res) {
                console.log($res);
            }
        });
    });

    //员工模糊查询
    $("#searchBut").click(function () {
        var searchKeyword = $("#staffSearch").val();
        table.reload('staffData',{
            url : getStaff+'?keyword='+searchKeyword
        })
    });
});


