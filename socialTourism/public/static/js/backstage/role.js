/**
 * Created by 陈凌峰 on 2017/12/29.
 */

//$("#searchBut").click(function () {
//    var roleSearch = $("#roleSearch").val();
//});
$("#searchBut").click(function () {
    console.log(layuiTable);
    var searchKeyword = $("#roleSearch").val();
    layuiTable.reload('test',{
        url : url+'?keyword='+searchKeyword
    })
});