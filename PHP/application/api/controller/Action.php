<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;

class Action extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->checkToken();
        $openid = request()->param('openId');
        $userid = Db::name('user')->where('open_id',$openid)->value('id');
        $conference_id = request()->param('conference_id');
        $action_type_id = request()->param('action_type_id');
        $target_id = request()->param('param_id');
        $data  = [
            'conference_id'=>$conference_id,
            'action_type_id'=>$action_type_id,
            'target_id'=>$target_id,
            'user_id'=>$userid,
            'created_at' => time(),
        ];
        $result = Db::name('action_log')->insert($data);
        $this->returnSuccess($result);
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
        //查询是否首次进入会议
        $conference_id = request()->param('conference_id');
        $action_type_id = request()->param('action_type_id');//扫码进入会议
        $userid = Db::name('user')->where('open_id',$openid)->value('id');
        $where = [
            'conference_id'=>$conference_id,
            'action_type_id'=>$action_type_id,
            'user_id'=>$userid,
        ];
        $count = Db::name('action_log')->where($where)->count('id');
        if($count >= 1){
            $is_first = 0;
        }else{
            $is_first = 1;
        }
        $this->returnSuccess(['is_first'=>$is_first]);
    }

}
