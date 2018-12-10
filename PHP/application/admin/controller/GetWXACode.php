<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\App;
use think\Request;
use wechat\Wechatapi;

class GetWXACode
{
    private $path;
    private $id;
    private $scene;
    private $page;

    public function __construct( $path,$id,$scene,$page )
    {
        $this->path  = $path;
        $this->id    = $id;
        $this->scene = $scene;
        $this->page  = $page;
    }

    public function getSmallCode()
    {
        $img = new Wechatapi( 1 );
        $img = $img->newSmallCode( [ 'scene' => $this->scene,'page' => $this->page ] );
        if( \is_object( \json_decode( $img ) ) ){
            return false;
        }
        \file_put_contents( App::getRootPath() . '/public/qrcode/' . $this->path . '/' . $this->id . '.png',$img );

        return true;
    }
}
