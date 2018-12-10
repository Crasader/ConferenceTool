<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use Config;

/**
 * @desc 会议资料
 * @author: Nicole.An
 * @time: 2018-11-27 14:50:00
 */
class Assets extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->checkToken();
        $host_url = Config::get('host_url');
        $conference_id = request()->param('conference_id');
        $list = Db::table('tb_assets')->where('conference_id',$conference_id)->field('id,asset_title,asset_url,cover_img')->select();
        foreach($list as $key=>$value){
            $list[$key]['url'] = isset($value['asset_url'])?$host_url.$value['asset_url']:'';
        }
        $this->returnSuccess($list);
        //
    }

}
