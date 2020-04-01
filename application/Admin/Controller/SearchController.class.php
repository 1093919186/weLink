<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/7
 * Time: 20:34
 */

namespace Admin\Controller;
use Think\Controller;
class SearchController extends Controller
{
    public function index(){
     if($_SESSION['username']) {
      cookie('searchType','name',3600*24*7);
      cookie('value',null,3600*24*7);
      if($_POST['searchType']=='name' || (cookie('searchType')=='name' && $_POST['searchType']!='username' ) ){
       if($_POST['searchType']=='name'){ cookie('searchType',$_POST['searchType'],3600*24*7);
        cookie('value',$_POST['value'],3600*24*7);}
       $p= isset($_GET['p'])?$_GET['p']:1;
       $name= isset($_POST['value'])?$_POST['value']:cookie('value');
       $rs = M('user')->where("name like '%$name%' " )->page("$p,5")->select();
       $count = M('user')->where("name like '%$name%' " )->count();
       $page = new \Think\Page($count,5);
       $show = $page->show();
       $this->assign('show',$show);
       $this->assign('rs',$rs);
       $this->display();
      }
      else{
       if($_POST['searchType']=='username'){ cookie('searchType',$_POST['searchType'],3600*24*7);
        cookie('value',$_POST['value'],3600*24*7);}
       $p= isset($_GET['p'])?$_GET['p']:1;
       $name= isset($_POST['value'])?$_POST['value']:cookie('value');
       $rs = M('user')->where("username like '%$name%' " )->page("$p,5")->select();
       $count = M('user')->where("username like '%$name%' " )->count();
       $page = new \Think\Page($count,5);
       $show = $page->show();
       $this->assign('show',$show);
       $this->assign('rs',$rs);
       $this->display();
      }
     }else{
      $this->success('您还没有登录',__APP__.'/Index/index',3);
     }


    }
}