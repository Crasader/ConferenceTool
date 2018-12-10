<?php

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Db;
use Config;

/**
 * @desc 会议
 * @author: Nicole.An
 * @time: 2018-11-27 14:50:00
 */
class Conference extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
//        $this->checkToken();
        $host_url      = Config::get( 'host_url' );
        $conference_id = $this->request->param( 'conference_id/d' );
        if( !$conference_id ){
            $this->returnError( [] );
        }
        $list = Db::table( 'tb_conference' )->where( 'id',$conference_id )->field( 'id,conference_name,conference_qrcode,conference_big_img,conference_img' )->find();

//        $list['conference_qrcode']=isset($list['conference_qrcode'])?$host_url.$list['conference_qrcode']:'';
//        $list['conference_big_img']=isset($list['conference_big_img'])?$host_url.$list['conference_big_img']:'';
        if( !isset( $list[ 'conference_img' ] ) ){
            $this->returnError( [],400,'会议id不存在' );
        }
        $list[ 'conference_img' ] = $host_url . $list[ 'conference_img' ];
        $this->returnSuccess( $list );
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save( Request $request )
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read( $id )
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update( Request $request,$id )
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete( $id )
    {
        //
    }
}
