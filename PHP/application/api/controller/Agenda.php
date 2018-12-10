<?php

namespace app\api\controller;

use app\api\controller\Common;
use think\Controller;
use think\Request;
use think\Db;

/**
 * @desc 会议日程
 * @author: Nicole.An
 * @time: 2018-11-27 14:50:00
 */
class Agenda extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->checkToken();
        $conference_id = request()->param('conference_id');
        $list = Db::table('tb_agenda')->where('conference_id',$conference_id)->field('id,title,lecturer,address,start_time,expired_time')->select();
        foreach($list as $key=>$value){
            $list[$key]['start_time'] = date("Y-m-d H:i",$value['start_time']) ;
            $list[$key]['expired_time'] = date("Y-m-d H:i",$value['expired_time']) ;
        }
//        dump($list);
        $this->returnSuccess($list);
        //
    }
}
