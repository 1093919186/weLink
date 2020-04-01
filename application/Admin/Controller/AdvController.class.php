<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/7
 * Time: 20:35
 */

namespace Admin\Controller;
use Think\Controller;
class AdvController extends Controller
{   
    public function index(){
        if($_SESSION['username']) {
            $rs1 = M('lunbo')->select();
            $this->assign('rs1',$rs1);
            $this->display();
        }else{
            $this->success('您还没有登录',__APP__.'/Index/index',3);
        }
    }
      //修改
   public function modify($picId=1){
    $rs = M('lunbo')->where(" picId = {$picId} ")->find();
    $this->assign('rs',$rs);
    $this->display('modify');
    }
        //上传
public function save($picId){
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     3145728 ;// 设置附件上传大小
    $upload->exts      =     array('jpg','png','jpeg','gif');// 设置附件上传类型
    $upload->rootPath  =     './public/picture/lunbo/'; // 设置附件上传根目录
    $upload->subName   = '';
    // 上传文件
    $info   =   $upload->upload();
    $_POST['picPath']=$info['savename'];
    if(!$info) {// 上传错误提示错误信息
        $this->error($upload->getError(),__APP__."/adv/modify/picId/{$picId}",3);
    }
    $_POST['picPath']='public/picture/lunbo/'.$info['pic']['savename'];

    $rs = M('lunbo')->field('picPath')->where("picId = {$picId} ")->find();
    $bool = unlink($rs['picpath']);
    if ($bool !== true) {
        $this->success('删除原图失败',__APP__."/adv/modify/picId/{$picId}",3);
        exit;}
    $rs = M('lunbo')->where("picId = {$picId} ")->save($_POST);
    if ($rs>0) {
       $this->success('更新成功',__APP__."/adv/index",3);
        exit;
   }else{
       $this->success('插入数据库失败',__APP__."/adv/modify/picId/{$picId}",3);
       exit;
   }
    }


}