<?php
// +----------------------------------------------------------------------
// | desc: 微信api公共方法
// +----------------------------------------------------------------------
// | author: yangsy
// +----------------------------------------------------------------------
// | time: 2018-08-11 16:02:11
// +----------------------------------------------------------------------
namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Log;

/**
 * @desc 公共基类
 * @author: Nicole.An
 * @time: 2018-11-27 16:50:00
 */
class Common extends Controller
{
    /**
     * @desc 请求成功回调
     * @author: yangsy
     * @time: 2018-08-11 16:05:54
     */
    public function returnSuccess($data,$code = 200,$msg = '请求成功'){
        $da['code'] = $code;
        $da['msg'] = $msg;
        $da['data'] = $data;
        echo json_encode($da,JSON_UNESCAPED_UNICODE); exit;
    }

    /**
     * @desc 请求失败数据返回
     * @author: yangsy
     * @time: 2018-08-11 16:07:42
     */
    public function returnError($data,$code=400,$msg = '请求失败'){
        $da['code'] = $code;
        $da['msg'] = $msg;
        $da['data'] = $data;
        echo json_encode($da,JSON_UNESCAPED_UNICODE); exit;
    }

    /**
     * @desc 定义空操作返回方法
     * @author: yangsy
     * @time: 2018-08-11 16:10:56
     */
    public function _empty(){
        $da['code'] = '404';
        $da['msg'] = '请求接口不存在';
        $da['data'] = '';
        echo json_encode($da,JSON_UNESCAPED_UNICODE); exit;
    }

    /**
     * @desc 验证用户token
     * @author: yangsy
     * @time: 2018-08-13 16:11:16
     */
    public function checkToken(){
        $token = request()->param('token');
        $openid = request()->param('openId');
        $token = Db::name('user')->where(['open_id'=>$openid,'token'=>$token])->find();
        if(empty($token) || ($token['expire_time']+28800) < time()){
            $this->returnError('','401','token无效！');
        }
        return $token['id'];
    }


}