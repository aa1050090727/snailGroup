/**
 * Created by Administrator on 2017/12/24.
 */
$(function(){
    $("#pos").on("click",function(){
        window.location.href = viewport_url;
    });
    $("#go_search").on("click",function(){
        window.location.href = viewport_url;
    });
    //vue
    //非父子组件通信直通车
    var bus = new Vue();
    //省市组件
    Vue.component("pro-city", {
        props:["url"],
        //绑定标签
        template: '<div>\
                       <div class="media pos_div" v-for="value in province">\
                           <div class="media-left pos_media">\
                                <span class="pos_style media-object">{{ value.f_province_name }}</span>\
                           </div>\
                           <div class="media-body">\
                                <ul class="pos_div_ul" >\
                                    <li class="pos_div_li" v-for="v in city" v-if=" value.f_province_id == v.f_city_fid" v-on:click="viewpos(value,v)">{{v.f_city_name}} </li>\
                               </ul>\
                           </div>\
                       </div>\
                  </div>',
        //    数据
        data: function () {
            return {
                province:"",//省
                city:[],//市
                totalPage:1,//总页数
                nowPage:1//当前页
            }
        },
        //    发送ajax，向数据库请求数据
        created: function () {
            var _this = this;
            this.getProvinceData(this.nowPage,this._props.url);
            bus.$on("prevPage",function(){
               // console.log("xiaoxi ");
                if(_this.nowPage <= 1){
                    alert("当前已经是第一页了");
                }
                else{
                    _this.nowPage = _this.nowPage -1 <= 0 ? 1 : _this.nowPage -1;
                    _this.getProvinceData(_this.nowPage,_this._props.url);
                }
            });
            bus.$on("nextPage",function(){
                // console.log("xiaoxi ");
                if(_this.nowPage >= _this.totalPage){
                    alert("当前已经是最后一页了");
                }
                else{
                    _this.nowPage = _this.nowPage +1 >= _this.totalPage ? _this.totalPage : _this.nowPage +1;
                    _this.getProvinceData(_this.nowPage,_this._props.url);
                }
            });
            bus.$on("changePage",function(index){
                // console.log("xiaoxi ");
                _this.nowPage = index;
                _this.getProvinceData(_this.nowPage,_this._props.url);
            });
        },
        //方法
        methods:{
            //ajax获取省市地区
            getProvinceData:function(nowPage,url){
                var _this = this;
                $.ajax({
                    type:"post",
                    url:url,
                    data:{"nowPage":nowPage},
                    dataType:"json",
                    success:function(resData){
                        _this.province = resData.data.pro;
                        _this.city = resData.data.city;
                        _this.totalPage = resData.data.totalPage;
                        bus.$emit("page",_this.totalPage);
                        //console.log(_this.totalPage);
                    },
                    error:function(resData){
                        console.log("error",resData);
                    }
                });
            },
            //页面跳转
            viewpos:function(pro,city){
                var _this = this;
                $.ajax({
                    type:"post",
                    url:setCookie_url,
                    data:{"province":pro,"city":city},
                    dataText:"json",
                    success:function(returnRes){
                        if(returnRes.code == 1){
                              window.location.href = viewport_url;
                        }
                    },
                    error:function(returnRes){
                        console.log("error");
                    }
                });
            }
        }
    });
    //分页按钮组件
    Vue.component("page",{
        template:'<div>\
                        <nav aria-label="Page navigation">\
                            <ul class="pagination">\
                                <li>\
                                    <a href="javascript:void(0)" aria-label="Previous" v-on:click="prev">\
                                        <span aria-hidden="true">&laquo;</span>\
                                    </a>\
                                </li>\
                                <li v-for="index in pageNum" v-on:click="clickPage(index)"><a href="javascript:void(0)">{{index}}</a></li>\
                                <li>\
                                    <a href="javascript:void(0)" aria-label="Next" v-on:click="next">\
                                        <span aria-hidden="true">&raquo;</span>\
                                    </a>\
                                </li>\
                            </ul>\
                        </nav>\
                    </div>',
        data:function(){
            return{
                pageNum:0
            }
        },
        created:function(){
            var _this = this;
            bus.$on("page",function(num){
               // console.log(num);
                _this.pageNum = num;
            });
        },
        methods:{
            prev:function(){
                bus.$emit("prevPage","goprev");
            },
            next:function(){
                bus.$emit("nextPage","gonext");
            },
            //换一页
            clickPage:function(index){
              //  console.log(index);
                bus.$emit("changePage",index);
            }
        }

    });
    var myPositionVue = new Vue({
        el:"#myPositionVue"
    });
    var myRecommend = new Vue({
        el:"#myRecommend",
        data:{
            recommendData:[]
        },
        created:function(){
            this.view_recommend();
        },
        methods: {
            //景点推荐
            view_recommend: function () {
                var _this = this;
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: recommend_url,
                    success: function (resRec) {
                        console.log(resRec);
                        _this.recommendData = resRec.data_recommend;
                    },
                    error: function (resRec) {
                        console.log("error", resRec);
                    }
                });
            },
            //页面跳转
            goDetail:function(view){
                //console.log(view);
                $.ajax({
                    type:"post",
                    data:{"f_science_id":view.f_science_id},
                    dataType:"json",
                    url:setViewId_url,
                    success:function(res){
                        console.log(res.result);
                        if(res.result == true) {
                            window.location.href = goViewdetailed_url;
                        }
                        else{
                            window.location.href = error_url;
                        }
                    },
                    error:function(res){
                        console.log("error",res);
                    }
                });
            }
        }
    });
});
