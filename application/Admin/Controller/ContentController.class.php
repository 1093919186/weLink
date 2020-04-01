<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/7
 * Time: 20:33
 */
namespace Admin\Controller;
use Think\Controller;
class ContentController extends Controller
{
    public function index()
    {
        if($_SESSION['username']) {
            $this->display();
        }else{
            $this->success('您还没有登录',__APP__.'/Index/index',3);
        }
    }
    public function user(){
        $p= isset($_GET['p'])?$_GET['p']:1;
        $rs = M('user')->page($p,10)->select();
        $count = M('user')->count();
        $page = new \Think\Page($count,10);
        $show = $page->show();
        $this->assign('show',$show);
        $this->assign('rs',$rs);
        $this->display();
    }
    public function del($userid){
        $rs = M('appove')->where(" sendid={$userid} or receiveid = {$userid} ")->delete();
        $rs = M('user')->where(" userid = {$userid}")->delete();
        if($rs>0){
            $this->success('操作完成',__APP__.'/Content/user',3);
        }else{
            $this->success('操作失败',__APP__.'/Content/user',3);
        }
    }
    public function insert(){
        for ($i=0; $i < 100; $i++) {
            $array=array(
                "name"=>'中',
                "username"=>$i,
                "password"=>'123456',
                'email'=>'213213@qq.com',
                'school'=>'长沙大内',
                'gexing'=>'给小难过'
            );
        M('user')->add($array);
        }
        $this->success('操作',__APP__.'/Content/user',1);
    }

}
