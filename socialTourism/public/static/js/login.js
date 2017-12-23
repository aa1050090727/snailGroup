/**
 * Created by fire on 2017/12/4.
 */

$("#")
var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope,$http) {
    $scope.showmy =function(){
        alert(123)
        var user = {
            'uname':$scope.name,
            'pwd':$scope.pwd,
            'code':$scope.code
        }
        $http({
            method: 'POST',
            url: loginUrl,
            data:user
        }).then(function (res) {
            console.log(res);
            if(res.data==0){
                location.href=myUrl;
            }else if(res.data==-1) {
                alert('账号密码错误')
            }else if(res.data==-2) {
                alert('验证码错误')
            }
            // 请求成功执行代码
        }, function (res) {
            // 请求失败执行代码
        });
    }

});