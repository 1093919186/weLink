<?php

namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }
    public function showcode(){
        $verify = new \Think\Verify();
        $verify->length = 4;//设置字符的个数
        $verify->fontSize = 15;//设置字符的大小
        $verify->imageH=  30;               // 验证码图片高度
        $verify->imageW=  100;               // 验证码图片宽度
        $verify->useCurve= false;
        $verify->entry();//显示验证码
    }
    public function checkLogin(){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $checkCode = $_POST["checkCode"];
        $verify = new \Think\Verify();
        $result=$verify->check($checkCode);
        if($result){
            $result = M("manager")->where("username='{$username}' && password='{$password}'")->find();
            if($result > 0)
            {
                $_SESSION['username']=$username;
                $this->success("登录成功！",__APP__."/Content/index.html");
            }
            else
            {
                $this->success("登录失败！",__APP__."/Index/index.html");
            }
        }else {
            $this->success("验证码错误！",__APP__."/Index/index.html");
        }

    }

    public function out()
    {
        session_destroy();
        $this->success("退出成功！",__APP__."/Index/index.html");
    }
}