<?php
/**
 * Created by PhpStorm.
 * User: jie.yu
 * Date: 2018/11/27
 * Time: 14:12
 */

namespace app\admin\controller;

use app\common\model\Agenda;
use app\common\model\Question;
use app\common\model\QuestionRelationship;
use think\Db;
use think\facade\Config;
use think\facade\Url;

class Survey extends AdminBase
{
    /**
     * 问卷列表
     * @return mixed
     */
    public function surveyList(){

        $cid = $this->request->param('conference_id/d',0);
        if(empty($cid)) $this->error('缺少会议参数',Url::build('index/index'));
        $search = $this->request->get('search/s','');

        $query = \app\common\model\Survey::with('agenda')->where('conference_id',$cid);

        if($search){
            $query->where('survey_title','like','%'.$search.'%');
        }

        $list = $query->paginate(null,false,['conference_id'=>$cid]);

        $this->assign('list',$list);
        $this->assign('search',$search);
        return $this->fetch('survey_list');
    }

    public function index(){
        $cid = $this->request->param('conference_id/d',0);
        if(empty($cid)) $this->error('缺少会议参数',Url::build('index/index'));

        $data = null;
        if($sid = $this->request->param('sid/d',0)){
            $data = \app\common\model\Survey::with('agenda,question')->where('id',$sid)->find();
            //小程序码
            $img_path = $this->app->getRootPath().'public/qrcode/survey/';
            if( !file_exists( $img_path. $sid . '.png' ) ){
                $scene  = 'survey_id@_@' . $sid;
                $page   = Config::get( 'qrcode_config' )[ 'survey' ];
                ( new GetWXACode( 'survey',$sid,$scene,$page ) )->getSmallCode();
            }
        }

        //议程
        $agenda = Agenda::where('conference_id',$cid)->column('title','id');
        //题目
        $questions = Question::where('status',Question::QSTATUS_ENABLE)->order('id desc')->field('id,title as text')->select();
        //select2需要json格式
        $this->assign('questions',json_encode($questions,JSON_UNESCAPED_UNICODE));
        $this->assign('agenda',$agenda);
        $this->assign('sid',$data?$sid:0);
        $this->assign('cid',$cid);
        $this->assign('data',$data);
        $this->assign( 'path',$this->request->domain() . $this->request->rootUrl() . '/qrcode/survey/' );

        return $this->fetch('survey');
    }

    /**
     * 新建/编辑问卷
     */
    public function save(){
        $data = $this->request->post();

        $result = $this->validate($data,'\app\common\validate\Survey.edit');

        if($result!==true){
            $this->result(['token'=>$this->request->token()],400,$result);
        }
        $sid = 0;
        if(isset($data['id'])){
            $sid= $data['id'];
            unset($data['id']);
        }

        Db::startTrans();
        try {
            if(empty($sid)){
                $res = \app\common\model\Survey::create($data);
            }else{
                $res = \app\common\model\Survey::update($data,['id'=>$sid]);
                QuestionRelationship::where('survey_id',$sid)->delete();
            }
            //整理选项数据
            $q_data = [];
            foreach ($data['question'] as $key => $val) {
                $q_data[] = [
                    'survey_id'      => $sid ?: $res->id,
                    'question_id'     => $val,
                    'sort'      => $key,
                ];
            }
            QuestionRelationship::insertAll($q_data);
            // 提交事务
            Db::commit();

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->result(['token'=>$this->request->token()],400,$e->getMessage());
        }
        $this->result([],200,'成功');
    }

    /**
     * 删除问卷
     */
    public function delete(){
        $sid = $this->request->param('sid/d',0);
        if(empty($sid)) $this->result([],400,'ID不存在');

        Db::startTrans();
        try{
            \app\common\model\Survey::destroy($sid);
            QuestionRelationship::where('survey_id',$sid)->delete();
            Db::commit();
        }catch(\Exception $e){
            // 回滚事务
            Db::rollback();
            $this->result([],400,$e->getMessage());
        }
        $this->result([],200,'success');
    }

    /**
     * 检查问卷是否被回答过
     */
    public function surveyUsed(){
        $id = $this->request->param('sid/d');
        $res = \app\common\model\QuestionResult::where('survey_id',$id)->find();
        if($res){
            $this->result([],201,'已经被回答过');
        }
        $this->result([],200,'OK');
    }
}