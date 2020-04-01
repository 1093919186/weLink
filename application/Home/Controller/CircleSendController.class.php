<?php
namespace Home\Controller;
use Think\Controller;
class CircleSendController extends Controller{
    public function index(){
        $this->display();
    }
       
    public function upload(){
    	$username=$_SESSION['userid']['username'];
    	$name = M("user")->field("name")->where("username='{$username}'")->select();
    	$_POST["fbman"]=$name[0]["name"];
    	$result=M("circle")->add($_POST);
    	if($result>0){
			$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize   =     3145728 ;// 设置附件上传大小
   		 	$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			$upload->rootPath  =     './public/picture/circleimage/'; // 设置附件上传根目录
   		 	$upload->savePath  =     ''; // 设置附件上传（子）目录
   		 	$upload->autoSub  = false;
    			// 上传文件
   		 	$info   =   $upload->upload();
   		 	if(!$info) {// 上传错误提示错误信息
    				$this->redirect('Content/index');
    			}else{// 上传成功
    				foreach ($info as $v){
    				$data['circleid']=$result;
    				$data['picpath']='public/picture/circleimage/'.$v['savename'];
    				$result1=M("circleimage")->add($data);
    				}
    				if($result1>0){
    					$this->redirect('Content/index');
    				}else{
    					$this->redirect("CircleSend/index");
    				}   				
    			}
       }else{
       	      $this->redirect('CircleSend/index');
         }
    }
    
    
}