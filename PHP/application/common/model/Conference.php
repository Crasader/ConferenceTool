<?php

namespace app\common\model;

use think\Model;

class Conference extends Model
{

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * @description 开始时间
     * @param $value
     * @return false|int
     */
    public function setStartTimeAttr( $value )
    {
        return \strtotime( $value );
    }

    /**
     * @description 结束时间
     * @param $value
     * @return false|int
     */
    public function setExpiredTimeAttr( $value )
    {
        return \strtotime( $value );
    }

    /**
     * @description 开始时间
     * @param $value
     * @return false|int
     */
    public function getStartTimeAttr( $value )
    {
        return \date( 'Y-m-d H:i',$value );
    }

    /**
     * @description 结束时间
     * @param $value
     * @return false|int
     */
    public function getExpiredTimeAttr( $value )
    {
        return \date( 'Y-m-d H:i',$value );
    }
}
