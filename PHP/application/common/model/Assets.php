<?php

namespace app\common\model;

use think\Model;

class Assets extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';
}
