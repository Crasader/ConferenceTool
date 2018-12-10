<?php

namespace app\common\validate;

use think\Validate;

class Agenda extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'title'         => 'require|max:100|unique:agenda',
        'lecturer'      => 'require|max:50',
        'start_time'    => 'require|max:5',
        'expired_time'  => 'require|max:5|checkTimes',
        'address'       => 'require|max:255',
        'sort_order'    => 'require|number|between:1,999',
        'conference_id' => 'require|number|checkConferencId',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'title.require'         => '议程名称不能为空',
        'title.max'             => '议程名称最多100个字符',
        'title.unique'          => '议程名称已存在',
        'lecturer.require'      => '演讲者不能为空',
        'lecturer.max'          => '演讲者最多50个字符',
        'start_time.require'    => '开始时间不能空',
        'expired.require'       => '结束时间不能空',
        'sort_order.require'    => '排序不能为空',
        'sort_order.between'    => '排序值在1:999',
        'conference_id.require' => '会议id不能为空',
    ];

    protected $scene = [
        'create' => [ 'title','lecturer','start_time','expired_time','address','sort_order','conference_id' ],
        'update' => [ 'title','lecturer','start_time','expired_time','address','sort_order' ],
    ];


    public function checkConferencId( $conference_id,$rule,$data )
    {
        $conference = \app\common\model\Conference::get( $conference_id );
        if( !$conference ){
            return '会议id无效';
        }
        $conferenceStart   = \substr( $conference->start_time,11,17 );
        $conferenceExpired = \substr( $conference->expired_time,11,17 );

        if( $conferenceStart > $data[ 'start_time' ] ){
            return '开始时间 不得早于 会议开始时间:' . $conferenceStart;
        }

        if( $conferenceExpired < $data[ 'start_time' ] ){
            return '开始时间 不得迟于 会议结束时间:' . $conferenceExpired;
        }

        if( $conferenceExpired < $data[ 'expired_time' ] ){
            return '结束时间 不得迟于 会议结束时间:' . $conferenceExpired;
        }

        return true;
    }

    public function checkTimes( $expired_time,$rule,$data )
    {
        if( $expired_time <= $data[ 'start_time' ] ){
            return '结束时间 不能早于 开始时间';
        }
        return true;
    }
}
