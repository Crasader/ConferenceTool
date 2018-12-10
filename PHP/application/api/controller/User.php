<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use wechat\Wechatapi;
use think\Db;


/**
 * @desc 会议用户
 * @author: Nicole.An
 * @time: 2018-11-27 14:50:00
 */
class User extends Common
{
    /**
     * @desc 获取用户 UnionID 以及 登录 token
     * @author: Nicole.AN
     * @time: 2018-11-28 11:23:53
     */
    public function index(){
        if(request()->isPost()){
            // 验证请求数据是否正确
            $post = request()->post();
            if(empty($post)){
                $this->returnError([],'400','提交信息错误！');
            }
            $login_info = $post;

            // 解析微信用户信息
            $weixin = new Wechatapi(1);
            $wx_info = $weixin->getWechatUserInfoBySmall($login_info);

            $wx_info = json_decode($wx_info,true);
//            dump($wx_info);
            if(empty($wx_info)){
                $this->returnError([],'400','解析用户信息失败！');
            }
            $gender = '';
            if($wx_info['gender'] == 1){
                $gender = 'male';
            }elseif($wx_info['gender'] == 1){
                $gender = 'female';
            }

            // 会员信息查询接口
            $data = [
                'nickname' => $wx_info['nickName'],
                'avatar' => $wx_info['avatarUrl'],
                'gender' => $gender,
            ];

            // 验证用户是否存在，如果没有，保存基本信息
            $user = Db::name('user')->where(['open_id'=>$wx_info['openId']])->find();
            if(empty($user)){
                // 添加新用户
                $data = [
                    'nickname' => $wx_info['nickName'],
                    'open_id' => $wx_info['openId'],
                    'created_at' => time(),
                    'avatar' => $wx_info['avatarUrl']
                ];

                Db::name('user')->insert($data);
            }else{
                $data = [
                    'open_id' => $wx_info['openId'],
                ];
                Db::name('user')->where(['id'=>$user['id']])->update($data);
            }

            if(!empty($user)){
                $this->returnSuccess(['token'=>$this->token($wx_info['openId']),'openId'=>$wx_info['openId']]);
            }else{
                $this->returnSuccess(['token'=>$this->token($wx_info['openId']),'openId'=>$wx_info['openId']]);
            }
        }
    }

    /**
     * @desc 生成会员token
     * @author: yangsy
     * @time: 2018-08-13 15:05:44
     */
    public function token($open_id){
        $token = Db::name('user')->where(['open_id'=>$open_id])->find();
        $to = $this->generate_password();
        $data = [
            'open_id' => $open_id,
            'token' => $to,
            'expire_time' => time(),
        ];
        if(!empty($token)){
            $data['updated_at'] = time();
            Db::name('user')->where(['id'=>$token['id']])->update($data);
        }else{
            $data['created_at'] = time();
            // 生成新的token 并且保存
            Db::name('user')->insert($data);
        }
        return $to;
    }


    /**
     * @desc 生成随机字符串
     * @author: yangsy
     * @time: 2018-08-13 15:16:13
     */
    public function generate_password( $length = 32 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }

}
