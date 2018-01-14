/**
 * Created by 陈凌峰 on 2018/1/14.
 *//**
 * Created by 陈凌峰 on 2018/1/12.
 */
$.ajax({
    type:"post",
    url:businessCount,
    dataType:"json",
    success:function(res){
        //console.log(res);
        $countArr = res;
        var title = '2018年营业额统计表';
        var subTitle = '';
        //    if(selcetType == 1){
        //        title = '2017年用户注册量统计表';
        //    }else if(selcetType == 2){
        //        title = '2017年营销额统计表';
        //    }
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('showData'));
        option = {
            title: {
                text: title,
                subtext: subTitle,
                left:'center'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'line'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            legend: {
                data:['']
            },
            grid: {
                left: '8%',
                right: '4%',
                bottom: '8%',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
                    "axisLabel":{
                        interval: 0
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'',
                    type:'bar',
                    data:$countArr
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    },
    error:function(){
        alert('出错了')
    }
})
