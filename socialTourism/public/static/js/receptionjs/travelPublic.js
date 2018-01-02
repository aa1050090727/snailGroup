/**
 * Created by Administrator on 2017/12/29 0029.
 */
var app=new Vue({
    el: "#app",
    data: {},
    methods:{
        /*提交内容*/
        travelPublic:function(){
            if(confirm("确定提交？")){
                var head=$("#name").val();
                var place=$("#place").val();
                var introduce=$("#inputIntro").val();
                var content=editor.getContent() ;
                var img=document.getElementById("inputImg").files[0];
                var data=new FormData();
                data.append("head",head);
                data.append("place",place);
                data.append("introduce",introduce);
                data.append("content",content);
                data.append("img",img);
                $.ajax({
                    type:"post",
                    url:notePublic,
                    data:data,
                    cache:false,
                    contentType:false,
                    processData:false,
                    dataType:"text",
                    success:function(res) {
                        if (res==1)
                        {
                            alert("提交成功，等待审核");
                        }else if(res==0){
                            alert("提交失败");
                        }
                    }
                })
            }

        },
        /*图片预览*/
        travelImg:function(event){
            //图片预览
            var picture=event.target.files[0];
            if (picture!=undefined)
            {
                /*检验对浏览器的支持*/
                if(window.FileReader)
                {
                    var file=new FileReader();
                    /*读取完成触发，无论成功或失败*/
                    file.onloadend=function(res)
                    {
                        $("#img").attr("src",res.target.result);
                    };
                    file.readAsDataURL(picture);
                }
            }
        }
    }
})
