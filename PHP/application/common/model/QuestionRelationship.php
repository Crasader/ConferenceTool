<?php

namespace app\common\model;

use think\Model;

class QuestionRelationship extends Model
{
    protected $autoWriteTimestamp = false;

    public function detail(){
        return $this->hasOne('Question','question_id','id');
    }

    public function survey(){
        return $this->hasOne('Survey','id','survey_id');
    }
}
