<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

return [
    //1-0测试路由
    'hello'              => [ 'api/index/index',[ 'method' => 'get' ] ],
    //1-1用户授权等
    'user/login'              => [ 'api/user/index',[ 'method' => 'post' ] ],
    //1-2获取会议日程
    'agenda/list'              => [ 'api/agenda/index',[ 'method' => 'post' ] ],
    //1-3获取会议资料
    'assets/list'              => [ 'api/assets/index',[ 'method' => 'post' ] ],
    //1-4获取产品列表
    'product/list'              => [ 'api/product/index',[ 'method' => 'post' ] ],
    //1-5获取产品详情
    'product/detail'              => [ 'api/product/read',[ 'method' => 'post' ] ],
    //1-7获取问题列表
    'survey/detail'              => [ 'api/question/index',[ 'method' => 'post' ] ],
    //1-8提交问卷答案
    'survey/submit'              => [ 'api/question/create',[ 'method' => 'post' ] ],
    //1-9记录行为日志
    'action/add'              => [ 'api/action/index',[ 'method' => 'post' ] ],
    //1-6用户首次进入
    'action/isfirst'              => [ 'api/action/read',[ 'method' => 'post' ] ],
    //1-10获取引导图片
    'conference/image'              => [ 'api/conference/index',[ 'method' => 'post' ] ],
    //1-11用户首次答卷
    'survey/isfirst'              => [ 'api/question/read',[ 'method' => 'post' ] ],
];
