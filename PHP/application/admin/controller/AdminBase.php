<?php

namespace app\admin\controller;

use think\App;
use think\Collection;
use think\Controller;
use think\Db;
use think\facade\Session;

class AdminBase extends Controller
{
    protected $title;

    protected $filePath;

    public function __construct( App $app = null )
    {
        parent::__construct( $app );
//        Session::delete('conference_id');
        if($this->request->has('conference_id')){
            Session::set('conference_id',$this->request->param('conference_id/d'));
        }
        $this->checkLogin();
        $this->filePath = $this->request->domain().$this->request->rootUrl();

        $this->getConferenceList();
    }

    /**
     * @description 检测登录
     */
    private function checkLogin()
    {
        if( !Session::has( 'adminSession' ) ){
            $this->redirect( 'Login/index' );
        }
    }

     private function getConferenceList()
     {
        $data = \app\common\model\Conference::order('start_time desc,id desc')->select()->toArray();
        $newArr = [];
        foreach($data as $k => $v){
            $newArr[$v['start_year']][] = $v;
        }
        $this->assign('ConferenceList',$newArr);
        $this->assign( 'filePath',$this->filePath . '/uploads/' );
        $this->assign( 'currentConferenceUrl',$this->request->domain().\url('Conference/read',['conference_id'=>Session::get('conference_id')]) );

     }
}



