<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'reception',
    // 禁止访问模块x
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------
//
//    'cache'                  => [
//        // 驱动方式
//        'type'   => 'redis',
//        // 缓存保存目录
//        'path'   => CACHE_PATH,
//        // 缓存前缀
//        'prefix' => '',
//        // 缓存有效期 0表示永久缓存
//        'expire' => 0,
//    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    //验证码
    'captcha' =>[
        //验证码字符集合
        'codeSet' =>'123456789zbcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        //验证码字体大小根据所需进行设置验证码字体大小
        'fontSize' => 30,
        //是否画混淆曲线
        'useCurva' =>true,
        //验证码图片高度，根据所需进行设置高度
        'imageH' =>'',
        //验证码图片宽度，根据所需进行设置宽度
        'imageW' =>'',
        //验证码位数，根据所需进行设置位数
        'length' =>4,
        //验证成功后是否重置
        'reset' =>true
    ],

    'msg' =>[
        'verify' => [
            'verify_error1' => '输入不能为空',
            'verify_error' => '已注册,请重新输入',
            'verify_success' => '未注册'
        ],
        'register' => [
            'register_error' => '用户名长度不能超过8位',
            'register_error1' => '密码长度不能小于6位',
            'register_error2' => '密码长度不能大于20位',
            'register_error3' => '两次密码不一致',
            'register_error4' => '验证码错误',
            'register_error5' => '手机验证码不正确',
            'register_error6' => '非法注册',
            'register_success' => '注册成功'
        ],
        'login' => [
            'login_error' => '输入不能为空',
            'login_error1' => '账号不存在',
            'login_error2' => '密码错误',
            'login_error4' => '验证码错误',
            'login_error3' => '账户被锁无法登陆',
            'login_success' =>'登录成功'
        ],
        'verifyphone' => [
            'verifyphone_error' => '账号已存在',
            'verifyphone_success' => '账号未注册'
        ],
        'verifycode' => [
            'verifycode_error' =>'验证码输入错误',
            'verifycode_success' =>'验证码输入正确'
        ],
        'uppwd' => [
            'uppwd_error' => '密码长度不能小于六位数',
            'uppwd_error1' => '密码长度不能大于二十位数',
            'uppwd_error2' => '两次密码输入不一致',
            'uppwd_error3' => '现用密码不正确',
            'uppwd_error4' => '密码修改错误',
            'uppwd_success' => '密码修改成功'
        ],

        'alterorder' => [
            'alterorder_error' => '未登录不能修改订单状态',
            'alterorder_error1' => '取消订单失败',
            'alterorder_success' => '取消订单成功'
        ],

        'paybalance' => [
            'paybalance_error' => '余额不足，请前往充值',
            'paybalance_error1' => '支付失败',
            'paybalance_error2' => '未登录不支付',
            'paybalance_error3' => '该订单不是未支付订单',
            'paybalance_error4' => '密码不能为空',
            'paybalance_error5' => '密码不正确',
            'paybalance_success' =>'支付成功，前往个人中心查看'
        ],
        'addmon' => [
            'addmon_error' => '输入金额不能为空'  ,
            'addmon_error1' => '输入金额不能小于等于0或者带e的数字串'  ,
            'addmon_error2' => '输入金额只能是整数不能是小数'  ,
            'addmon_error3' => '充值失败'  ,
            'addmon_success' => '充值成功'  ,
        ],

        'delmsg' => [
            'del_success' => '删除成功',
            'del_fail' => '删除失败'
        ],
        'upmsg' => [
            'up_success' => '修改成功',
            'up_fail' => '修改失败',
            'up_error' => '对不起你不能修改自己'
        ],
        'addmsg' => [
            'add_success' => '添加成功',
            'add_fail' => '添加失败',
            'add_error' => '对不起该账户重名'
        ],
        'preview_msg' => [
            'preview_success' => '预览成功',
            'preview_fail' => '预览失败',
            'examine_success' => '审核成功',
            'examine_fail' => '审核失败',
            'del_success' => '下架成功',
            'del_fail' => '下架失败'
        ],
        'open_msg' => [
            'open_success' => '打开成功',
            'open_fail' => '打开失败'
        ],
        'role_msg' => [
            'role_error' => '超级管理员的角色名不能修改',
            'role_fail' => '角色名重名',
            'up_success' => '修改成功',
            'up_fail' => '修改失败',
            'add_success' => '添加成功',
            'add_fail' => '添加失败'
        ],
        'use' =>[
            'use_error' => '使用失败',
            'use_success' => '使用成功'
        ]
    ]

];
