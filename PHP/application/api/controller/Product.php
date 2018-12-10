<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use Config;

/**
 * @desc 会议产品
 * @author: Nicole.An
 * @time: 2018-11-27 14:50:00
 */
class Product extends Common
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
        $openid = request()->param('openId');
        $userid = Db::name('user')->where('open_id',$openid)->value('id');
        $where_log =[
            'conference_id' => $conference_id,
            'user_id' => $userid,
            'action_type_id' => 7,
        ];
        $product_ids = Db::table('tb_action_log')->where($where_log)->column('target_id');
        $list = Db::table('tb_product')->where(['id'=>$product_ids])->field('id,product_name')->select();
        if($list){
            $this->returnSuccess($list);
        }else{
            $data = '没有浏览过的产品';
            $this->returnError($data);//没有浏览过的产品
        }

    }


    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        $user_id = $this->checkToken();
        $host_url = Config::get('host_url');
        $product_id = request()->param('product_id/d');
        $product = Db::table('tb_product')->where('id',$product_id)->find();
//        $product = Db::table('tb_product')->where('id',$product_id)->field('id,conference_id,product_name,brand,brand_logo,product_description')->find();
        $conference_status = Db::table('tb_conference')->where('id',$product['conference_id'])->value('status');
        if($conference_status == 0){
            $this->returnError([],'404','会议未发布！');
        }
        //是否首次进入会议
        $first = Db::name('action_log')->where('conference_id',$product['conference_id'])->where('action_type_id',1)->where('user_id',$user_id)->find();
        if(empty($first)){
            $this->returnSuccess(['type'=>'product','id'=>$product_id,'conference_id'=>$product['conference_id']],'300','未进入过会议');
        }
        $product['brand_logo'] = isset($product['brand_logo'])?$host_url.$product['brand_logo']:'';
        $product_media = Db::table('tb_product_media')->where('product_id',$product_id)->field('url,media_type,media_flag')->select();

        $video = [];
        $img = [];
        foreach($product_media as $key=>$value){
            if($value['media_type']){
                $video['url'] = isset($value['url'])?$host_url.$value['url']:'';
                continue;
            }
            $img[$key]['url'] = isset($value['url'])?$host_url.$value['url']:'';
            $img[$key]['media_flag'] = $value['media_flag'];
        }
        $product['product_video'] = $video;
        $product['product_img'] = $img;
        $product['cover_img'] = $host_url.$product['cover_img'];
        $this->returnSuccess($product);
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
