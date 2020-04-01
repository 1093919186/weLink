<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    
    public function index(){
        
        $this->display();
    }

    public function login(){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $sql=M("user")->where("username = '{$username}' && password = '{$password}'")->find();
        if ($sql==true){
            if(time() <= $sql['jftime']){
                $_SESSION =array();
                session_destroy();
                $time = date('Y年m月d日 H时m分s秒',$sql['jftime']);
                $this->success ('您被封号至:&nbsp;'.$time,__APP__.'/index/index',999);
                exit();
            }
            $_SESSION['userid']=$sql;
            $this->redirect("Content/index");
        }
        else{
            $this->success ('用户信息错误', 'index/index');;

        }
    }
    
}