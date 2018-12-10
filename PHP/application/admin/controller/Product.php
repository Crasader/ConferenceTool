<?php

namespace app\admin\controller;

use app\common\model\ProductMedia;
use think\Controller;
use think\Db;
use think\Exception;
use think\facade\App;
use think\facade\Config;
use think\Request;

class Product extends AdminBase
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
        $list                         = \app\common\model\Product::where( 'conference_id',$id )->paginate( '',false,$pageParam );
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
        if( !$this->request->param( 'conference_id' ) ){
            $this->error( '缺少会议id' );
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
        $allData = $this->request->param();
        $scene   = 'create';
        if( isset( $allData[ 'product' ][ 'id' ] ) ){
            $scene = 'update';
        }
        $validate = new \app\common\validate\Product();
        if( !$validate->scene( $scene )->check( $allData ) ){
            $this->result( [],400,$validate->getError() );
        }

        //新建产品表数据


        Db::startTrans();
        try{
            $product = \app\common\model\Product::$scene( $allData[ 'product' ] );
            foreach( $allData[ 'product_media' ] as $v ){
                $createData = $v;
                //新建的时候需要产品id
                if( !isset( $allData[ 'id' ] ) ){
                    $createData[ 'product_id' ] = $product->id;
                }
                ProductMedia::$scene( $createData );
            }
            Db::commit();
            $this->result( [],200,'新建成功' );
        }catch( Exception $e ){
            Db::rollback();
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
        $product_id = $this->request->param( 'id/d' );
        $list       = ProductMedia::all( [ 'product_id' => $product_id ] );

        $product_path = App::getRootPath() . 'public/qrcode/product/';
        if( !\file_exists( $product_path . $product_id . '.png' ) ){
            $path   = 'product'; //储存二维码的文件夹
            $sence  = 'product_id@_@' . $product_id;
            $page   = Config::get( 'qrcode_config' )[ $path ];
            $result = ( new GetWXACode( $path,$product_id,$sence,$page ) )->getSmallCode();

        }

        $this->assign( 'list',$list );
        $this->assign( 'path',$this->request->domain() . $this->request->rootUrl() . '/qrcode/product/' );
        return $this->fetch();
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit( $id )
    {
        $product_id = $this->request->param( 'id/d' );

        $list = \app\common\model\Product::with( 'productMedia' )->where( 'id',$product_id )->find();
//        echo "<pre>";print_r( $list->toArray() );die;
        $this->assign( 'list',$list );
        return $this->fetch();
    }

    public function delete()
    {
        $product_id = $this->request->param( 'id/d' );
        //删除产品表/产品媒体表数据
        try{
            $Product = \app\common\model\Product::get( $product_id,'productMedia' );
            $Product->together( 'product_media' )->delete();
            $this->result( [],200,'删除成功' );
        }catch( Exception $e ){
            $this->result( [],400,$e->getMessage() );
        }
    }


}
