<?php
namespace  Admin\Controller;
use Think\Controller;

class FhController extends Controller{
    public function index($userid){
        if($_SESSION['username']) {
            $rs = M('user')->where("userid = {$userid}")->find();
            $this->assign('rs',$rs);
            $this->display();
        }else{
            $this->success('您还没有登录',__APP__.'/Index/index',3);
        }

    }
    public function modify($userid){
        $seconds = $_POST['day']*3600*24;
        $jftime = time() + $seconds;
        $data=array('jftime'=>$jftime,'power'=>0);
        $rs =M('user')->where("userid={$userid}")->save($data);
        if($rs>0){
            $this->success ('该用户将于'.$_POST['day'].'天后解封',__APP__.'/content/user',3);
        }else{
            $this->success('操作失败',__APP__.'/content/user',3);
        }
    }
    public function jf($userid){
        $data  = array('power'=>1,'jftime'=>'0');
        $rs = M('user')->where("userid={$userid}")->save($data);
        if($rs>0){
            $this->success ('该用户解封成功',__APP__.'/content/user',3);
        }else{
            $this->success('该用户解封失败',__APP__.'/content/user',3);
        }
    }
}
