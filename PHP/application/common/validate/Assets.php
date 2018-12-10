<?php

namespace app\common\validate;

use think\Validate;

class Assets extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'asset_title'   => 'require|max:100|unique:assets',
        'asset_url'     => 'require|max:255',
        'sort_order'    => 'require|max:3',
        'conference_id' => 'require|number|check_conference_id',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'asset_title.require'   => '资料名称不能为空',
        'asset_title.max'       => '资料名称最多100个字符',
        'asset_title.unique'    => '资料名称已存在',
        'asset_url.unique'      => '资料文件不能为空',
        'asset_url.max'         => '资料文件地址长度最多为255个字符',
        'sort_order.require'    => '排序不能为空',
        'sort_order.max'        => '排序场3个字符',
        'conference_id.require' => '会议id不存在',
        'conference_id.number'  => '会议id必须是数字',
    ];

    protected $scene = [
        'create' => 'asset_title,asset_url,sort_order,conference_id',
        'update' => 'asset_title,asset_url,sort_order',
    ];


    /**
     * @description 检测会议id是否存在
     * @param $conference_id
     * @param $data
     * @return bool|string
     */
    public function check_conference_id( $conference_id,$data )
    {
        $conference = \app\common\model\Conference::get( $conference_id );
        if( !$conference ){
            return '会议id不存在';
        }

        return true;
    }
}
