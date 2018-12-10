<?php

namespace app\common\validate;

use think\Validate;

class Conference extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'conference_name'    => 'require|max:100|unique:conference',
        'description'        => 'max:1000',
        'conference_big_img' => 'max:255',
        'conference_img'     => 'max:255',
        'start_time'         => 'require|date|checkStartTime',
        'expired_time'       => 'require|date|checkExpiredTime',
        'status'             => 'require|in:0,1',
        'is_redirect'        => 'require|in:0,1',
        'redirect_appid'     => 'checkRedirectAppid',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'conference_name.require'    => '会议名称不能为空',
        'conference_name.max'        => '会议名称最多100个字符',
        'conference_name.unique'     => '会议名称已存在',
        'description.require'        => '会议介绍不能为空',
        'description.max'            => '会议接受最多1000个字符',
        'conference_big_img.require' => '大屏图片不能为空',
        'conference_big_img.max'     => '大屏图片名称不能超过255个字符',
        'conference_img.require'     => '首页图片不能为空',
        'conference_img.max'         => '首页图片名称不能超过255个字符',
        'start_time.require'         => '开始时间不能为空',
        'start_time.date'            => '开始时间有误',
        'expired_time.require'       => '结束时间不能为空',
        'expired_time.date'          => '结束时间有误',
        'status.require'             => '状态不能为空',
        'status.in'                  => '状态的值 非法',
    ];

    protected $scene = [
        'create' => [ 'conference_name','description','conference_big_img','conference_img','start_time','expired_time','status' ],
    ];

    public function checkStartTime( $time,$rule,$data )
    {
        if( $time < \date( 'Y-m-d H:i' ) ){
            return '开始时间不得小于当前时间';
        }
        return true;
    }

    public function checkExpiredTime( $time,$rule,$data )
    {
        if( $time < $data[ 'start_time' ] ){
            return '结束时间 不得小于 开始时间';
        }
        return true;

    }

    public function checkRedirectAppid( $redirect_appid,$rule,$data )
    {
        if( ( $data[ 'is_redirect' ] == 1 ) && empty( $redirect_appid ) ){
            return '跳转目标小程序的APPID不可缺少';
        }
        return true;

    }
}
