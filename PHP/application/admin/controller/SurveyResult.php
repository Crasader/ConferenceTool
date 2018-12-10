<?php

namespace app\admin\controller;

use app\common\model\Question;
use app\common\model\QuestionRelationship;
use app\common\model\QuestionResult;
use app\common\model\Survey;
use think\facade\Url;

class SurveyResult extends AdminBase
{
    /**
     * 显示详细
     */
    public function index()
    {
        $cid = $this->request->param('cid/d');
        if(empty($cid)) $this->error('会议ID不存在');

        /*
         * 这里需要每次切换一题，计数有2种情况：一次会议有多个问卷，一个问卷里有可能多道题目（第一期还没有，逻辑上支持）
         * 以关系表和问卷表做连表查询后直接分页
         * 一道题目会有多个选项——>单独取值
         */
        $res = QuestionRelationship::withJoin(['survey'=>function($query){
                    $query->withField('id,conference_id,agenda_id,status');
                }])->where('survey.conference_id',$cid)
                   ->where('survey.status',Survey::SURVEY_ENABLE)
                   ->order('survey.agenda_id asc')->paginate(1,false,['cid'=>$cid]);

        if($res->items()){
            //当前条件下一次只有一个问题
            $question_id = $res->items()[0]->question_id;
            $survey_id = $res->items()[0]->survey->id;
            //查询当前问题的项
            $q_data = Question::with('question_options')->where('id',$question_id)->find();
            $r_data = QuestionResult::where('conference_id',$cid)
                ->where('survey_id',$survey_id)
                ->where('question_id',$question_id)->group('option_id')->column('count(*)','option_id');

            //拼数据
            $options = $data = $num_data =  [];
            foreach($q_data['question_options'] as $key=>$val){
                $options[] = $val['option_value'];
                $vote_num =  isset($r_data[$val['id']])?$r_data[$val['id']]:0;
                $data[]['coord'] = [$key,$vote_num];
                $num_data[] = $vote_num;
            }
            $this->assign('num_data',json_encode($num_data));
            $this->assign('options',json_encode($options));
            $this->assign('data',json_encode($data));
            $this->assign('title',$q_data['title']);
            //分页数据
            $this->assign('res',$res);

            return $this->fetch();
        };
        $this->error('数据不存在',Url::build('conference/read',['conference_id'=>$cid]));
    }


}
