<?php

namespace app\common\validate;

use think\Validate;

class Survey extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'survey_title'=>'require|max:30|token',
        'conference_id'=>'require|number',
        'agenda_id'=>'require|number',
        'survey_description'=>'max:255',
        'status'=>'require|between:0,1',
        'question'=>'require|array|checkQuestion',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'survey_title.require'=>'问卷标题必填',
        'survey_title.max'=>'标题不得大于30个字符',
        'conference_id.require'=>'会议必选',
        'conference_id.number'=>'会议类型错误',
        'agenda_id.require'=>'议程ID必填',
        'agenda_id.number'=>'议程类型错误',
        'survey_description.max'=>'问卷描述最多255个字符',
        'status.require'=>'问卷状态必填',
        'status.between'=>'问卷状态错误',
        'question.require'=>'问题不能为空',
        'question.array'=>'问题格式错误',
    ];

    protected $scene = [
        'edit'  =>  ['survey_title','conference_id','agenda_id','status','question'],
    ];

    /**
     * 检查问题数组
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     */
    protected function checkQuestion($value,$rule,$data){
       if(empty($value)) return '问题不得为空';
       foreach($value as $key=>$v){
           if(empty($v)) return '问题不可为空，题目序号:'.($key+1);
           if(!is_numeric($v)) return '问题类型错误';
       }
       return true;
    }

}
