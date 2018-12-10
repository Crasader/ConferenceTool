<?php

namespace app\admin\controller;

use think\Controller;
use think\Exception;
use think\Request;

class Assets extends AdminBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $id = $this->request->param( 'conference_id/d' );
        if( !$id ){
            $this->error( '会议id不存在' );
        }
        $pageParam                    = [];
        $pageParam[ 'conference_id' ] = $id;
        $list                         = \app\common\model\Assets::where( 'conference_id',$id )->paginate( '',false,$pageParam );
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
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     * @return \think\Response
     */
    public function save()
    {
        $allData = $this->request->param();
        $scene   = 'create';
        if( isset( $allData[ 'id' ] ) ){
            $scene = 'update';
        }
        $validate = new \app\common\validate\Assets();
        if( !$validate->scene( $scene )->check( $allData ) ){
            $this->result( [],400,$validate->getError() );
        }

        try{
            \app\common\model\Assets::$scene( $allData );

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
        $id   = $this->request->param( 'id/d' );
        $list = \app\common\model\Assets::get( $id );
        $this->assign( 'list',$list );
        return $this->fetch();
    }

    public function delete()
    {
        $id = $this->request->param( 'id/d' );
        //删除产品表/产品媒体表数据
        try{
            \app\common\model\Assets::destroy( $id );

            $this->result( [],200,'删除成功' );
        }catch( Exception $e ){

            $this->result( [],400,$e->getMessage() );
        }
    }


}
