<?php

namespace app\api\controller;

use app\common\model\QuestionResult;
use think\Controller;
use think\Exception;
use think\Request;
use think\Db;

/**
 * @desc 调查问卷问题
 * @author: Nicole.An
 * @time: 2018-11-27 16:50:00
 */
class Question extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $user_id = $this->checkToken();
        $survey_id = request()->param('survey_id/d');
        //是否首次进入会议
        $conference = Db::name('survey')->where('id',$survey_id)->value('conference_id');
        $first = Db::name('action_log')->where('conference_id',$conference)->where('action_type_id',1)->where('user_id',$user_id)->find();
        if(empty($first)){
            $this->returnSuccess(['type'=>'survey','id'=>$survey_id,'conference_id'=>$conference],'300','未进入过会议');
        }
        $question_ids = Db::table('tb_question_relationship')->where('survey_id',$survey_id)->column('question_id');
        $question_list = Db::table('tb_question')->where('id','in',$question_ids)->field('id,title,question_type')->select();
        $question_all['conference_id'] = $conference;
        $question_all['list'] = $question_list;
        foreach($question_all['list'] as $key=>$value){
            $question_all['list'][$key]['question_options'] = Db::table('tb_question_options')->where('question_id',$value['id'])->field('id,option_value')->select();
            $array = ['A','B','C','D','E','F','G','H'];
            foreach($question_all['list'][$key]['question_options'] as $k=>$v){
                $question_all['list'][$key]['question_options'][$k]['option_letter'] = $array[$k];
            }
        }
        $this->returnSuccess($question_all);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $this->checkToken();
        $survey_id = request()->param('survey_id');

        $openid = request()->param('openId');
        $userid = Db::name('user')->where('open_id',$openid)->value('id');
        $survey_data = Db::name('survey')->where('id',$survey_id)->find();
        $question_list = request()->param('question_list');
//        dump($question_list);
        $detail = [];

        Db::startTrans();
        try {
            foreach($question_list as $key=>$value){
                if($value['question_type'] == 1){
                    $temp['question_id'] = $value['question_id'];
                    $temp['user_id'] = $userid;
                    $temp['survey_id'] = $survey_id;
                    $temp['conference_id'] = $survey_data['conference_id'];
                    $temp['agenda_id'] = $survey_data['agenda_id'];
                    $temp['option_id'] = $value['question_options_ids'][0];
                    $temp['option_value'] = Db::name('question_options')->where('id',$value['question_options_ids'][0])->value('option_value');
                    $temp['submitted_time'] = time();
                    $detail[] = $temp;
                }elseif($value['question_type'] == 2){
                    foreach($value['question_options_ids'] as $key1=>$value1){
                        $temp['question_id'] = $value['question_id'];
                        $temp['user_id'] = $userid;
                        $temp['survey_id'] = $survey_id;
                        $temp['conference_id'] = $survey_data['conference_id'];
                        $temp['agenda_id'] = $survey_data['agenda_id'];
                        $temp['option_id'] = $value1;
                        $temp['option_value'] = Db::name('question_options')->where('id',$value1)->value('option_value');
                        $temp['submitted_time'] = time();
                        $detail[] = $temp;
                    }
                }
            }
//            dump($detail);exit();
//            $result = QuestionResult::insertAll($detail);
            $result = Db::name('question_result')->data($detail)->insertAll();
            Db::commit();
            if($result){
                $this->returnSuccess($result);
            }else{
                $data = '提交失败!';
                $this->returnError($data);
            }

        }catch(Exception $e){
            Db::rollback();
            if(\strpos('Duplicate entry',$e->getMessage()) !== false){
                $data = '重复提交!';
                $this->returnError($data);//重复提交
            }
            $data = '提交失败!';
            $this->returnError($data);//插入失败
        }
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        $this->checkToken();
        $openid = request()->param('openId');
        //查询是否答过此问卷
        $survey_id = request()->param('survey_id');
        $userid = Db::name('user')->where('open_id',$openid)->value('id');
        $where = [
            'survey_id'=>$survey_id,
            'user_id'=>$userid,
        ];
        $question_result_id = Db::name('question_result')->where($where)->value('id');
        if($question_result_id){
            $is_first = 0;
        }else{
            $is_first = 1;
        }
        $this->returnSuccess(['is_first'=>$is_first]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
