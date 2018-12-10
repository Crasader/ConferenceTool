<?php

namespace app\common\model;

use think\Model;

class Question extends Model
{
    const QTYPE_BLANK  = 0; //填空题
    const QTYPE_SINGLE = 1; //单选题
    const QTYPE_MULTI  = 2; //多选题

    const QSTATUS_ENABLE = 1; //启用
    const QSTATUS_DISABLE = 0; //不启用


    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    public function getStatusAttr($value){
        $status = [self::QSTATUS_DISABLE=>'不启用',self::QSTATUS_ENABLE=>'启用'];
        return $status[$value];
    }

    public function getQuestionTypeAttr($value){
        $type = [
            self::QTYPE_BLANK=>'填空',
            self::QTYPE_SINGLE=>'单选',
            self::QTYPE_MULTI=>'多选'
        ];

        return isset($type[$value])?$type[$value]:'未知类型';
    }

    public function questionOptions(){
        return $this->hasMany('QuestionOptions','question_id','id')->order('option_sort asc');
    }
}
