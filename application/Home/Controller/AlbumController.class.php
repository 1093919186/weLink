<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:48
 */
namespace Home\Controller;

use Think\Controller;
use Think\Model;
use Think\Upload;

class AlbumController extends Controller{

    public function index(){

        $model = new Model();
        $userid=$_SESSION['userid']['userid'];

        $data = $model->query("select * from image where userid=$userid");
        
        $this->assign('imageList',$data);
        $this->display();
    }

    public function del(){
        $picid=$_GET['picid'];
        $picpath=M("image")->where("picid=$picid")->find();
        $picpath=$picpath['picpath'];
        $re1=unlink($picpath);
        $re2=M("image")->where("picid=$picid")->delete();
        if($re1&&$re2){
            $this->redirect("Album/index");
        }
    }

    public function uplodFile()
    {
        $userid=$_SESSION['userid']['userid'];
        $upload = new Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './public/picture/xiangce/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录
        	$upload->autoSub=false;
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $model = new Model();
            foreach($info as $file){		
                $filePath = 'public/picture/xiangce/'.$file['savepath'].$file['savename'];
                $model->execute("INSERT INTO `welink`.`image` (`picpath`,`userid`) VALUES ('".$filePath."', '$userid')");

            }
        }
        $this->redirect("Album/index");
    }
}