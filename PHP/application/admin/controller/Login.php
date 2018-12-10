<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Config;
use think\facade\Session;
use think\facade\Url;

/**
 * @description 后台登录控制器
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{
    /**
     * @description 登录页面
     */
    public function index()
    {
        if( Session::has( 'adminSession' ) ){
            $this->redirect( 'Index/index' );
        }
        $this->assign('submit_url',Url::build('login'));
        return $this->fetch();
    }

    public function login()
    {
        $request      = $this->request->post();

        $validate = new \app\common\validate\Login();
        if(!$validate->check($request)){
            $this->result( [],400,$validate->getError() );
        }

        $adminstrator = Config::get( 'adminstrator.' );

        $adminstrator = \array_column( $adminstrator,null,'account' );
        if( isset( $adminstrator[ $request[ 'account' ] ] ) ){
            if( $adminstrator[ $request[ 'account' ] ][ 'pwd' ] === md5( $request[ 'pwd' ] ) ){

                $adminSession = $adminstrator[ $request[ 'account' ] ];

                unset( $adminSession[ 'pwd' ] );
                Session::set( 'adminSession',$adminSession );
                $this->result( [ 'url' => Url::build('Index/index') ],200,'登录成功' );
            }
            $this->result( [],400,'账号或密码错误-1' );
        }

        $this->result( [],400,'账号或密码错误-2' );

    }

    public function logout()
    {
        Session::delete( 'adminSession' );
        $this->redirect( 'Login/index' );
    }


}
