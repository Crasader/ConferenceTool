<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\App;
use think\facade\Session;

class Upload extends Controller
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

    public function upload()
    {
        if(!Session::has('adminSession')){
            $this->result([],401,'您没有权限执行该操作！');
        }
        set_time_limit(0);
//        \ini_set('upload_max_filesize','1024M');
//        \ini_set('post_max_size ','1024M');
//        $picPath = App::getRootPath() . 'public/uploads/20181123/';
//        $files   = \scandir( $picPath );
//        foreach( $files as $v ){
//            if( $v !== '.' && $v !== '..' ){
//                \unlink( $picPath . $v );
//            }
//        }
        $file = $this->request->file('file' );

        $info = $file->validate( ['size'=>999999999, 'ext' => 'jpg,png,gif,mp4,pdf' ] )->move( App::getRootPath() . 'public/uploads/' );
        if( $info ){
            $this->result( [ 'url' => $info->getSaveName() ],200,'上传成功' );
        }
        $this->result( [ 'url' => '' ],400,$file->getError() );

    }

    public function delete(){
        if(!Session::has('adminSession')){
            $this->result([],401,'您没有权限执行该操作！');
        }

        $data = $this->request->post('data');

        $root_path = App::getRootPath().'public/uploads/';
        
        if(empty($data) || !file_exists($root_path.$data)){
            $this->result([],400,'文件不存在');
        }
        if(@unlink($root_path.$data)){
            $this->result([],200,'删除成功');
        }
        $this->result([],400,'删除失败');
    }

}
