<?php

namespace app\admin\controller;

use app\common\model\Assets;
use think\Controller;
use think\Db;
use think\Exception;
use think\Request;

class Agenda extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $id                           = $this->request->param( 'conference_id/d' );
        if( !$id ){
            $this->error( '会议id不存在' );
        }
        $pageParam                    = [];
        $pageParam[ 'conference_id' ] = $id;
        $list                         = \app\common\model\Agenda::where( 'conference_id',$id )->order( 'sort_order asc' )->paginate( '',false,$pageParam );
        $this->assign( 'list',$list );
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        if(!$this->request->param('conference_id')){
            $this->error('缺少会议id');
        }
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
        $createData = $this->request->post();

        $validate = new \app\common\validate\Agenda();

        $scene    = 'create';
        if( isset( $createData[ 'id' ] ) ){
            //如果id存在,则是更新
            $scene = 'update';
        }

        if( !$validate->scene( $scene )->check( $createData ) ){
            $this->result( [],400,$validate->getError() );
        }
        try{
            \app\common\model\Agenda::$scene( $createData );//更新/新增

            $this->result( [],200,'成功' );
        }catch( Exception $e ){
            $this->result( [],400,$e->getMessage() );
        }
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit()
    {
        $agenda = \app\common\model\Agenda::get( $this->request->param( 'id/d' ) );
        $this->assign( 'agenda',$agenda );
        return $this->fetch();
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

    public function delete()
    {
        $id = $this->request->param( 'id/d' );
        //删除产品表/产品媒体表数据
        try{
            \app\common\model\Agenda::destroy( $id );

            $this->result( [],200,'删除成功' );
        }catch( Exception $e ){

            $this->result( [],400,$e->getMessage() );
        }
    }
}
