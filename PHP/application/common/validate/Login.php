<?php

namespace app\common\validate;

use think\Validate;

/**
 * @description 后台登录页面
 * Class Login
 * @package app\common\validate
 */
class Login extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'account' => 'require',
        'pwd'     => 'require'
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'account.require' => '账号必填',
        'pwd.require'     => '密码必填',
    ];
}
