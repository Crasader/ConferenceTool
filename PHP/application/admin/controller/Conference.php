<?php

namespace app\admin\controller;

use think\Db;
use think\Exception;
use think\facade\App;
use think\facade\Config;
use think\Loader;

class Conference extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {

        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save()
    {
        $validate = new \app\common\validate\Conference();
        if( !$validate->scene( 'create' )->check( $this->request->post() ) ){
            $this->result( [],400,$validate->getError() );
        }
        $createData                  = $this->request->post();
        $createData[ 'start_year' ]  = substr( $createData[ 'start_time' ],0,4 );//截取年份
        $createData[ 'start_month' ] = substr( $createData[ 'start_time' ],5,2 );//截取月份
        try{
            if( isset( $createData[ 'id' ] ) ){
                \app\common\model\Conference::update( $createData );//更新
            }else{
                \app\common\model\Conference::create( $createData );//新增
            }
            $this->result( [],200,'成功' );
        }catch( Exception $e ){
            $this->result( [],400,$e->getMessage() );
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read()
    {
        $id              = $this->request->param( 'conference_id/d' );
        $data            = \app\common\model\Conference::get( $id );
        $conference_path = App::getRootPath() . 'public/qrcode/conference/';
        if( !\file_exists( $conference_path . $id . '.png' ) ){
            $path   = 'conference'; //储存二维码的文件夹
            $sence  = 'conference_id@_@' . $id;
            $page   = Config::get( 'qrcode_config' )[ $path ];
            $result = ( new GetWXACode( $path,$id,$sence,$page ) )->getSmallCode();

        }

        $this->assign( 'data',$data );
        $this->assign( 'path',$this->request->domain() . $this->request->rootUrl() . '/qrcode/conference/' );
        return $this->fetch();
    }


    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete()
    {
        //会议id
        $id = $this->request->param( 'id/d' );

        Db::startTrans();
        try{
            //删除会议表记录
            \app\common\model\Conference::destroy( $id );

            //删除会议小程序码
            \unlink( App::getRootPath() . '/public/qrcode/conference/' . $id . '.png' );
            //删除议程表数据
            \app\common\model\Agenda::destroy( function( $query ) use ( $id ){
                $query->where( 'conference_id',$id );
            } );
            //删除资料表数据
            \app\common\model\Assets::destroy( function( $query ) use ( $id ){
                $query->where( 'conference_id',$id );
            } );
            //删除产品表/产品媒体表数据
            $Product = \app\common\model\Product::all( [ 'conference_id' => $id ],'productMedia' );
            foreach( $Product as $v ){
                $v->together( 'product_media' )->delete();
            }


            Db::commit();
            $this->result( [],200,'删除成功' );
        }catch( Exception $e ){
            Db::rollback();
            $this->result( [],400,$e->getMessage() );
        }
    }
}
