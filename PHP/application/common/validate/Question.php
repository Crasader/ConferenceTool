<?php

namespace app\common\validate;

use think\Validate;

class Question extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title'=>'require|max:255|token',
        'question_type'=>'require|between:0,2',
        'status'=>'require|between:0,1',
        'option_text'=>'checkOptions',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'=>'问题标题必填',
        'title.max'=>'标题不得大于255个字符',
        'question_type.require'=>'问题类型必填',
        'question_type.between'=>'问题类型错误',
        'status.require'=>'问题状态必填',
        'status.between'=>'问题状态错误',
        'option_text.checkOption'=>'问题状态错误',
    ];

    protected $scene = [
        'edit'  =>  ['title','question_type','status','option_text'],
    ];

    /**
     * 验证问题选项::图片和描述不能同时为空
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     */
    protected function checkOptions($value,$rule,$data){
        //填空题不必检查这项
        if($data['question_type']==\app\common\model\Question::QTYPE_BLANK) return true;

        if(empty($value) && empty($data['option_img'])) return '选项不得为空';

        foreach($value as $key=>$val){
            if(empty($val) && empty($data['option_img'][$key])) return '描述与图片不得同时为空，序号:'.($key+1);
        }
        return true;
    }
}
