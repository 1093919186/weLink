<?php
namespace Home\Controller;
use Think\Controller;
class RegisterController extends Controller{
    public function index(){
        $this->display();
     }
    public function showcode(){
    	$verify = new \Think\Verify();
    	$verify->length = 4;//设置字符的个数
    	$verify->fontSize = 15;//设置字符的大小
    	$verify->imageH=  35;               // 验证码图片高度
    	$verify->imageW=  100;               // 验证码图片宽度
    	$verify->useCurve= false;
    	$verify->entry();//显示验证码
     }
    public function insert(){
		$checkCode = $_POST["checkCode"];
		$username=$_POST['username'];
		$verify = new \Think\Verify();
		$result=$verify->check($checkCode);
		if($result){
				$result = M("user")->data($_POST)->add();
				if($result > 0){
					$sql=M("user")->where("username = '{$username}'")->find();
					$_SESSION['userid']=$sql;
					$userid=$_SESSION['userid']['userid'];
					M("link")->execute("insert into link(userid,friendid)values('$userid','$userid')");
						$this->redirect("Person/index");
				}else{
						$this->redirect("Register/index");
				}	
	   }else{
	   	$this->redirect("Register/index");
	    }
    }
}