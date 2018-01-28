/**
 * Created by 陈凌峰 on 2017/12/29.
 */

layui.use(['table','form'], function() {
    var table = layui.table;
    var form = layui.form;

    //正则表达式
    form.verify({
        roleName: function(value, item){ //value：表单的值、item：表单的DOM对象
            console.log(value.length);
            if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                return '角色名不能有特殊字符';
            }
            if(/(^\_)|(\__)|(\_+$)/.test(value)){
                return '角色名首尾不能出现下划线\'_\'';
            }
            if(/^\d+\d+\d$/.test(value)){
                return '角色名不能全为数字';
            }
            if(value.length< 2 || value.length> 6){
                return '角色名只能为2到6位的字符';
            }
        },
        roleDes: function(value, item){ //value：表单的值、item：表单的DOM对象
            if(value.length > 10){
                return '角色描述字符长度不能大于10位';
            }
        }

    });
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
        if(layEvent === 'del'){
            layer.confirm('真的删除该角色吗？', function(index){
                if(data["b_role_id"] === 1){
                    layer.alert("对不起超级管理员不能删除！");
                    layer.close(index);
                }else{
                    $.ajax({
                        type:"get",
                        url:delRole,
                        data:{roleId:data["b_role_id"]},
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
        } else if(layEvent === 'edit'){
            $.ajax({
                type:"get",
                url:getIdRole,
                data:{roleId:data["b_role_id"]},
                dataType:"json",
                success: function ($res) {
                    //console.log($res["data"]);
                    if($res["code"] === 10000){
                        layer.open({
                            title: '角色修改'
                            ,content: '<form class="layui-form" action=""> <div class="layui-form-item"> <label class="layui-form-label">ID:</label> <div class="layui-input-block"> <input type="text" name="id" required  lay-verify="required"  class="layui-input"  disabled value="'+$res["data"]["b_role_id"]+'"> </div> </div><div class="layui-form-item"> <label class="layui-form-label">角色名:</label> <div class="layui-input-block"> <input type="text" name="name" required  lay-verify="required|roleName" autocomplete="off" class="layui-input" value="'+$res["data"]["b_role_name"]+'"> </div> </div><div class="layui-form-item layui-form-text"> <label class="layui-form-label">角色描述:</label> <div class="layui-input-block"> <textarea name="desc" placeholder="请输入内容" class="layui-textarea" lay-verify="roleDes">'+$res["data"]["b_role_des"]+'</textarea> </div> </div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="*">立即修改</button> </div> </div></form>',
                            area: ['500px',"450px"],
                            btn: ['关闭'],
                            yes: function(index, layero){
                                layer.close(index);
                            }
                        });
                    }else{
                        layer.alert($res["msg"]);
                    }
                },
                error: function ($res) {
                    console.log($res);
                }
            });
            form.render();

            form.on('submit(*)', function(fromObj){
                //console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
                //console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
                var roleArr = fromObj.field;
                //console.log(roleArr); //当前容器的全部表单字段，名值对形式：{name: value}
                layer.confirm('你确定修改吗？', function(index){
                    $.ajax({
                        type:"get",
                        url:upRole,
                        data:{roleArr:roleArr,roleName:data["b_role_name"]},
                        dataType:"json",
                        success: function ($res) {
                            //console.log($res["msg"]);
                            if($res["code"] === 10000){
                                obj.update({
                                    b_role_name: roleArr["name"],
                                    b_role_des:roleArr["desc"]
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
        }
    });
    //添加角色
    $("#addRole").click(function () {
        layer.open({
            title: '添加角色',
            content: '<form class="layui-form" action=""> <div class="layui-form-item"> <label class="layui-form-label">角色名:</label> <div class="layui-input-block"> <input type="text" name="name" required  lay-verify="required|roleName" autocomplete="off" class="layui-input" placeholder="必填项,请输入2到6位字符"> </div> </div><div class="layui-form-item layui-form-text"> <label class="layui-form-label">角色描述:</label> <div class="layui-input-block"> <textarea name="desc" placeholder="请输入内容" class="layui-textarea" lay-verify="roleDes"></textarea> </div> </div><div class="layui-form-item"> <div class="layui-input-block"> <button class="layui-btn" lay-submit lay-filter="addRole">立即添加</button> </div> </div></form>',
            btn: ['关闭'],
            area: ['500px',"360px"],
            yes: function(index, layero){
                layer.close(index);
            }
        });

        form.render();
        form.on('submit(addRole)', function(fromObj){
            var addRoleArr = fromObj.field;
            console.log(addRoleArr); //当前容器的全部表单字段，名值对形式：{name: value}
            layer.confirm('你确定添加吗？', function(index){
                $.ajax({
                    type:"get",
                    url:addRole,
                    data:{addRoleArr:addRoleArr},
                    dataType:"json",
                    success: function ($res) {
                        if($res["code"] === 10000){
                            table.reload('roleData',{
                                url : getRole+'?keyword='
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
    });
});
