<?php
namespace app\admin\controller;

use app\common\model\QuestionOptions;
use think\Db;

class Question extends AdminBase
{
    /**
     * 题库列表
     * @return mixed
     */
    public function questionList()
    {
        $search = $this->request->get('search/s','');

        $query = \app\common\model\Question::where('');
        if($search){
            $query->where('title','like','%'.$search.'%');
        }
        $list = $query->order('id desc')->paginate();

        $this->assign('search',$search);
        $this->assign('list',$list);
        return $this->fetch('question_list');
    }

    /**
     * 新建/编辑页面
     * @return mixed
     */
    public function index(){

        $data = null;
        if($qid = $this->request->param('qid/d',0)){
            $data = \app\common\model\Question::with('question_options')->where('id',$qid)->find();
        }

        $this->assign('qid',$data?$qid:0);
        $this->assign('data',$data);

        $this->assign( 'filePath',$this->filePath . '/uploads/' );
        return $this->fetch('question');
    }

    /**
     * 新建/更新题目
     */
    public function save(){
        $data = $this->request->post();
        $result = $this->validate($data,'\app\common\validate\Question.edit');

        if($result!==true){
            $this->result(['token'=>$this->request->token()],400,$result);
        }
        //是否更新选项
        if(!isset($data['change_option'])) $data['change_option']=1;

        //保存数据
        $main_data = ['title'=>$data['title'],'question_type'=>$data['question_type'],'status'=>$data['status']];
        $blank = \app\common\model\Question::QTYPE_BLANK;
        Db::startTrans();
        try {
            if(empty($data['id'])){
                $res = \app\common\model\Question::create($main_data);
            }else{
                $res = \app\common\model\Question::update($main_data,['id'=>$data['id']]);
                if($data['change_option'] ||$data['question_type']==$blank){
                    QuestionOptions::where('question_id',$data['id'])->delete();
                }
            }
            //填空题没有选项
            if($data['question_type']!=$blank && $data['change_option']==1) {
                //整理选项数据
                $option_data = [];
                $create      = time();
                foreach ($data['option_text'] as $key => $val) {
                    $option_data[] = [
                        'question_id'      => $data['id'] ?: $res->id,
                        'option_value'     => $val,
                        'option_media_url' => $data['option_img'][$key],
                        'option_sort'      => $key,
                        'created_at'       => $create,
                    ];
                }
                QuestionOptions::insertAll($option_data);
            }
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
     * 检查问题是否被回答过
     */
    public function questionUsed(){
        $id = $this->request->param('id/d');
        $res = \app\common\model\QuestionResult::where('question_id',$id)->find();
        if($res){
            $this->result([],201,'已经被回答过');
        }
        $this->result([],200,'OK');
    }
}
