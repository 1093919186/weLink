<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:40
 */
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller{
    public function index(){
        $userid=$_SESSION['userid']['userid'];
        $name=$_POST['keywords'];
        $list = M("user")->order("dateandtime desc")->where("name like '%$name%'")->select();
        $foreign=array();
        foreach($list as $v){
            $friendid=$v['userid'];
            if($friendid==$userid){
               $re=1;
            }else {
                $re = M("link")->where("userid=$userid and friendid=$friendid")->find();
            }
            if(!$re){
                $foreign[]=$v;
            }
        }
        $lunbo=M("lunbo")->where("picid=1")->find();

        $this->assign("lunbo",$lunbo);
        $this->assign("foreign",$foreign);
        $this->display();
    }
}