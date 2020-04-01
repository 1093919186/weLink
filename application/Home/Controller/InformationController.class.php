<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:38
 */
namespace Home\Controller;
use Think\Controller;
class InformationController extends Controller{
    public function index(){
        $receiveid =$_SESSION['userid']['userid'];
        $rs = M('appove')->where("receiveid={$receiveid} and flag=0")->order("dateandtime desc")->select();
        foreach ($rs as $k => $v) {
            $arr1 = M("user")->where("userid = {$v['sendid']} ")->find();
            $rs[$k]['sendname']=$arr1['name'];
            $rs[$k]['sendicon']=$arr1['iconpath'];
        }
        $this ->assign('rs',$rs);

        $rs1 = M('appove')->where("receiveid={$receiveid} and flag=1")->select();
        foreach ($rs1 as $k => $v) {
            $arr2 = M("user")->where("userid = {$v['sendid']} ")->find();
            $rs1[$k]['sendname']=$arr2['name'];
            $rs1[$k]['sendicon']=$arr2['iconpath'];
        }
        $this ->assign('rs1',$rs1);
        $this->display();
    }
    public function add($newsid){
        $id1 = $_POST['userid'];
        $id2 = $_POST['friendid'];
        $ck = M('link')->where('userid='.$id1)->find();
        if ($ck['friendid']==$id2) {
            echo 2;
            exit;
        }
        $arr['userid'] = $id2;
        $arr['friendid']= $id1;
        $m = M('link');
        $m->startTrans();
        $rs1 = $m->data($_POST)->add();
        $rs2 = $m->data($arr)->add();
        if ($rs1 >0 && $rs2>0) {
            $data['flag']=1;
            M('appove')->where("newsid ={$newsid}")->save($data);
            $m->commit();
            echo 1;
        }else{
            $m->rollback();
            echo 0;
        }

    }
    public function refuse(){
        $newsid = $_POST['newsid'];
        $data['flag']=1;
        M('appove')->where("newsid = {$newsid}")->save($data);
    }
    public function delete($newsid){
        M('appove')->where("newsid = {$newsid}")->delete();
        header("location:".__APP__."/information/index");
    }
}