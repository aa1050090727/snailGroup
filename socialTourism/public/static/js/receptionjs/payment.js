/**
 * Created by fire on 2018/1/3.
 */


function buygood(){
    $type = $("input[name='only']:checked").val()
    if($type=='1'){

    }else if ($type=='2'){

    }else if($type=='3'){
        if(confirm('确定付款吗？')){
            $pwd = $("#pwd").val();
            $mon =  $("#allprice").html()
            $id = $("#orderid").html()
            $.ajax({
                url:pay,
                dateType:"json",
                data:'id='+$id+'&mon='+$mon+'&pwd='+$pwd,
                type:"post",
                success:function(res){
                    if(res['code']==10000){
                        alert(res['msg'])
                        window.location = res['url']
                    }else if(res['code']==10001){
                        alert(res['msg'])
                        window.location = res['url']
                    }else if(res['code']==10002){
                        alert(res['msg'])
                    }
                    else if(res['code']==10003){
                        alert(res['msg'])
                    }
                    else if(res['code']==10004){
                        alert(res['msg'])
                    }else if(res['code']==10005){
                        alert(res['msg'])
                    }else if(res['code']==10006){
                        alert(res['msg'])
                    }
                }
            })
        }
    }
}


function mycenter(){
    window.location = center
}