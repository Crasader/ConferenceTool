<?php

namespace app\common\model;

use think\Log;
use think\Model;

class Agenda extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * @description 开始时间
     * @param $value
     * @return false|int
     */
    public function setStartTimeAttr( $value,$data )
    {
        //更新的时候,不允许修改会议id,只能查询
        if(!isset($data['conference_id'])){
            $conference = self::get($data['id']);
            $conference_id = $conference->id;
        }else{
            $conference_id =  $data['conference_id'];
        }

        //获取会议信息
        $conference = \app\common\model\Conference::get( $conference_id );
        if($conference){
            $StartTime = $conference->start_time;
            \think\facade\Log::record('下面是设置的时间1');
            \think\facade\Log::record(\substr($StartTime,0,10).' '.$value);
            //时间拼接 会议的年月日
            return \strtotime( \substr($StartTime,0,10).' '.$value );
        }
    }

    /**
     * @description 结束时间
     * @param $value
     * @return false|int
     */
    public function setExpiredTimeAttr( $value,$data )
    {
        //更新的时候,不允许修改会议id,只能查询
        if(!isset($data['conference_id'])){
            $conference = self::get($data['id']);
            $conference_id = $conference->id;
        }else{
            $conference_id =  $data['conference_id'];
        }
        //获取会议信息
        $conference = \app\common\model\Conference::get( $conference_id );
        if($conference){
            $ExpiredTime = $conference->expired_time;
            //时间拼接 会议的年月日
            return \strtotime( \substr($ExpiredTime,0,10).' '.$value );
        }
    }

    /**
     * @description 开始时间
     * @param $value
     * @return false|int
     */
    public function getStartTimeAttr( $value )
    {
        //只保留时间字符串的后5位
        return \substr(\date( 'Y-m-d H:i',$value ),11,5);
    }

    /**
     * @description 结束时间
     * @param $value
     * @return false|int
     */
    public function getExpiredTimeAttr( $value )
    {
        //只保留时间字符串的后5位
        return \substr(\date( 'Y-m-d H:i',$value ),11,5);
    }


}
