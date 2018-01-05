/**
 * Created by Administrator on 2017/12/26.
 */
$(function(){
    //非父子组件通信直通车
    var bus = new Vue();
    //省市组件
    Vue.component("view-port", {
        props:["url"],
        //绑定标签
        template: '<div>\
                    <div class="col-lg-4 col-md-4 col-sm-4" v-for="viewItem in dataView">\
                           <div class="thumbnail">\
                                <img class="view_img_height" v-bind:src="viewItem.f_science_img" alt="...">\
                                <div class="caption">\
                                    <h3>{{viewItem.f_science_name}}</h3>\
                                    <p class="content_p">{{viewItem.f_science_content}}</p>\
                                    <p>\
                                        <span class="rec_price" v-if="viewItem.f_science_states==2">￥{{viewItem.f_science_aprice}}</span>\
                                        <span v-if="viewItem.f_science_states==2" class="rec_aprice">￥{{viewItem.f_science_price}}</span>\
                                        <span v-else="viewItem.f_science_states==1" class="rec_price">￥{{viewItem.f_science_price}}</span></p>\
                                    <p class="text-right"><a href="javascript:void(0)" v-on:click="goDetail(viewItem)">查看详情</a></p>\
                                </div>\
                           </div>\
                       </div>\
                  </div>',
        //    数据
        data: function () {
            return {
                province:{},
                city:{},
                district:{},
                totalPage:1,//总页数
                nowPage:1,//当前页
                dataView:[],
                like:""
            }
        },
        //    发送ajax，向数据库请求数据
        created: function () {
            var _this = this;
            bus.$on("p_c",function(pro,city,district){
                _this.province = pro;
                _this.city = city;
                _this.district = district;
                _this.getViewData(_this._props.url,_this.nowPage,_this.district,'');
            });
            //点击区
            bus.$on("district",function(pro,city,district){
                _this.province = pro;
                _this.city = city;
                _this.district = district;
                _this.getViewData(_this._props.url,_this.nowPage,_this.district,'');
            });
            //搜索
            bus.$on("like",function(pro,city,district,like){
                _this.province = pro;
                _this.city = city;
                _this.district = district;
                _this.like = like
                _this.getViewData(_this._props.url,_this.nowPage,_this.district,_this.like);
            });
            //上一页
            bus.$on("prevPage",function(){
                // console.log("xiaoxi ");
                console.log(_this.nowPage);
                if(_this.nowPage <= 1){
                    alert("当前已经是第一页了");
                }
                else{
                    _this.nowPage = _this.nowPage -1 <= 0 ? 1 : _this.nowPage -1;
                    _this.getViewData(_this._props.url,_this.nowPage,"","");
                }
            });
            //下一页
            bus.$on("nextPage",function(){
                console.log(_this.nowPage,_this.totalPage);
                if(_this.nowPage >= _this.totalPage){
                    alert("当前已经是最后一页了");
                }
                else{
                    _this.nowPage = _this.nowPage +1 >= _this.totalPage ? _this.totalPage : _this.nowPage +1;
                    _this.getViewData(_this._props.url,_this.nowPage,"","");
                }
            });
            //点击页数
            bus.$on("changePage",function(index){
                // console.log("xiaoxi ");
                _this.nowPage = index;
                _this.getViewData(_this._props.url,_this.nowPage,"","");
            });
        },
        //方法
        methods:{
            //景点数据
            getViewData:function(url,nowPage,district,like){
                var _this = this;
                $.ajax({
                    type:"post",
                    data:{"province_id":_this.province.f_province_id,
                        "city_id":_this.city.f_city_id,
                        "district_id":_this.district.f_district_id,
                        "like":like,
                        "nowPage":nowPage
                    },
                    dataType:"json",
                    url:url,
                    success:function(resView){
                        _this.dataView = resView.data_view;
                        _this.totalPage = resView.total_Page;
                        bus.$emit("page",_this.totalPage);
                    },
                    error:function(resView){
                        console.log("error",resView);
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
    //分页按钮组件
    Vue.component("page",{
        template:'<div>\
                        <nav aria-label="Page navigation">\
                            <ul class="pagination">\
                                <li v-on:click="prev">\
                                    <a href="javascript:void(0)" aria-label="Previous">\
                                        <span aria-hidden="true">&laquo;</span>\
                                    </a>\
                                </li>\
                                <li v-for="index in pageNum" v-on:click="clickPage(index)"><a href="javascript:void(0)">{{index}}</a></li>\
                                <li v-on:click="next">\
                                    <a href="javascript:void(0)" aria-label="Next">\
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
                bus.$emit("nextPage");
            },
            //换一页
            clickPage:function(index){
                //  console.log(index);
                bus.$emit("changePage",index);
            }
        }

    });
    var viewpos = new Vue({
        el:"#viewpos",
        data:{
            province:{},
            city:{},
            nowPage:0,
            districtData:[],
            district:{},
            viewData:[],
            like:''
        },
        created:function(){
            var _this = this;
            $.ajax({
                type:"post",
                dataType:"json",
                url:haslike_url,
                success:function(reslike){
                    console.log(reslike);
                    if(reslike.code == 1){
                        //cunzai
                        $("#menu").css({
                            display:"none"
                        });
                        $(".districtList").css({
                            display:"none"
                        });
                        _this.like = reslike.like;
                        bus.$emit("like",_this.province,_this.city,_this.district,_this.like);
                    }
                    else{
                        //二级菜单联动
                        $.ajax({
                            type:"post",
                            dataType:"json",
                            url:getCookie_url,
                            success:function(resNowPos){
                                _this.city = resNowPos.nowPos.city;
                                _this.province = resNowPos.nowPos.province;
                                bus.$emit("p_c",_this.province,_this.city,_this.district);
                                //区数据请求
                                $.ajax({
                                    type:"post",
                                    data:{"cityId":_this.city.f_city_id},
                                    dataType:"json",
                                    url:getDistrict_url,
                                    success:function(resDistrict){
                                        // console.log(resDistrict.district);
                                        _this.districtData = resDistrict.district;
                                        // console.log(_this.districtData);
                                    },
                                    error:function(resDistrict){
                                        console.log("error",resDistrict);
                                    }
                                });
                                //景点数据初始化
                            },
                            error:function(resNowPos){
                                console.log("error",resNowPos);
                            }
                        });
                    }

                },
                error:function(reslike){
                    console.log("error",reslike);
                }
            });

        },
        methods:{
            //当前地区下的景点
            searchView:function(disItem){
                console.log(disItem);
                console.log(this.province,this.city);
                bus.$emit("district",this.province,this.city,disItem);
            },
            //删除like
            unsetlike:function(){
                $.ajax({
                    type:"post",
                    dataType:'json',
                    url:unsetlike_url,
                    success:function(res){
                        if(res.code == 1){
                            window.location.href = viewport_url;
                        }
                        else{
                            window.location.href = viewport_url;
                        }
                    },
                    error:function(res){

                    }
                });
            }
        }
    });









});