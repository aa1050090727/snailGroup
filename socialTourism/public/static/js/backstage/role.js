/**
 * Created by 陈凌峰 on 2017/12/29.
 */

var layuiTable = '';
layui.use('table', function() {
    var table = layui.table;
    layuiTable = table;
    //监听工具条
    table.on('tool(test)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data //获得当前行数据
            ,layEvent = obj.event; //获得 lay-event 对应的值
        if(layEvent === 'detail'){
            layer.msg('查看操作');
            //console.log(data,layEvent);
        } else if(layEvent === 'del'){
            layer.confirm('真的删除行么', function(index){
                obj.del(); //删除对应行（tr）的DOM结构
                layer.close(index);
                //向服务端发送删除指令
            });
        } else if(layEvent === 'edit'){
            layer.open({
                title: '在线调试'
                ,content: '可以填写任意的layer代码'
            });
        }
    });

});

$("#searchBut").click(function () {
    console.log(layuiTable);
    var searchKeyword = $("#roleSearch").val();
    layuiTable.reload('test',{
        url : url+'?keyword='+searchKeyword
    })
});