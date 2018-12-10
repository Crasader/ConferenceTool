<?php

namespace app\common\model;

use think\Model;

class Survey extends Model
{
    const SURVEY_ENABLE = 1; //启用
    const SURVEY_DISABLE = 0; //不启用

    // 定义时间戳字段名
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    public function getStatusAttr($value){
        $status = [self::SURVEY_DISABLE=>'不启用',self::SURVEY_ENABLE=>'启用'];
        return $status[$value];
    }

    public function agenda(){
        return $this->hasOne('Agenda','id','agenda_id')->field('id,title');
    }

    public function question(){
        return $this->hasMany('QuestionRelationship','survey_id','id')->order('sort asc');
    }
}
