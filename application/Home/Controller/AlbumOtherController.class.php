<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:49
 */

namespace Home\Controller;
use Think\Model;

use Think\Controller;
class AlbumOtherController extends Controller{
    public function index(){
    	$model = new Model();
        $userid=$_GET['userid'];
    	$data = $model->query("select * from `image` WHERE userid=$userid");
    
    	$this->assign('data',$data);
      $this->display();
    }
}