<?php
namespace Admin\Controller;
use Think\Controller;
class RegisterController extends Controller
{
	public function index()
	{
		if($_SESSION['username']) {
			$this->display();
		}else{
			$this->success('您还没有登录',__APP__.'/Index/index',3);
		}
	}
	public function adduser(){
		$data["username"]=$_POST["username"];
		$data["password"]=$_POST["password"];
		$data["telephone"]=$_POST["telephone"];		
		$result = M("manager")->add($data);
		if($result>0){
			$this->success('添加成功！');
		}else{
			$this->success('添加失败！');
		}
	}
}
