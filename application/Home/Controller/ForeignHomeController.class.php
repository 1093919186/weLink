<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:47
 */

namespace Home\Controller;
use Think\Controller;
class ForeignHomeController extends Controller{
    public function index(){
        $userid=$_GET['friendid'];
        $userinfo=M('user')->where("userid=$userid")->find();
        $name=$userinfo['name'];
        $iconpath=$userinfo['iconpath'];
        cookie('name',"$name",3600);
        cookie('iconpath',"$iconpath",3600);
        $lunbo=M("lunbo")->where("picId=3")->find();

        $circle=M("circle")->where("fbman='$name'")->order("dateandtime desc")->limit(0,10)->select();

        foreach($circle as $k=>$v){
            $circleid=$v["circleid"];
            $circle[$k]['icon']=M("user")->where("name='$name'")->find();
            $circle[$k]['images']=M("circleimage")->where("circleid=$circleid")->select();
        }
        foreach($circle as $k=>$vv){
            $circleid=$vv["circleid"];
            $circle[$k]['reviews']=M("reviews")->where("circleid=$circleid")->select();
            $reviews=$circle[$k]['reviews'];
            foreach($reviews as $k=>$r){
                $plman=$r['plman'];
            }
        }
        $rs = M("user")->where("userid  = {$userid}")->find();
        $this->assign("rs",$rs);
        $this->assign("circle",$circle);
        $this->assign("lunbo",$lunbo);
        $this->assign("userinfo",$userinfo);
        $this->display();
    }

    public function ajax(){
        $circleid=$_POST['p'];
        $numzan=M("circle")->where("circleid=$circleid")->find();
        $numzan=$numzan['numzan'];
        $numzan=$numzan+1;
        M("circle")->where("circleid=$circleid")->save(array(
            'numzan'=>"$numzan"
        ));
        $numzan=M("circle")->where("circleid=$circleid")->find();
        $numzan=$numzan['numzan'];
        echo $numzan;
    }

    public function addreview(){
        $review=$_POST['plnr'];
        $name=$_SESSION['userid']['name'];
        $icon=$_SESSION['userid']['iconpath'];
        $circleid=$_POST['circleid'];

        $result = M("reviews")->execute("insert into reviews(circleid,plman,content,iconpath)values('$circleid','$name','$review','$icon')");
        if($result){
            M("circle")->execute("update circle set numpl=numpl+1 where circleid = $circleid");
            $newpl=M("reviews")->where("circleid=$circleid")->order("dateandtime desc")->limit(0,1)->find();
            $newplimage=$newpl['iconpath'];
            $newplman=$newpl['plman'];
            $newplcontent=$newpl['content'];

        }
        echo '<div style="width: 100%;height: auto;margin-bottom: 10px;float: left">';
        echo '<img src="/weLink/'.$newplimage.'" style="width: 40px;height: 40px;float: left">';
        echo '<div style="width: 645px;height: auto;float: left;margin-left: 5px">
                                <a href="#"  style=\'font-family: "arial", "微软雅黑";color: dodgerblue;font-size: 15px;text-decoration: none\'>'.$newplman.'</a><br>
                                <div style="width: 645px;"><span style=\'font-family: "arial", "微软雅黑";color: #000000;font-size: 13px;\'>'.$newplcontent.'</span></div>
                            </div>
                        </div>';
    }

    public function numpl(){
        $circleid=$_POST['p'];
        $numpl=M("circle")->where("circleid=$circleid")->find();
        $numpl=$numpl['numpl'];
        echo $numpl;
    }
    public function sendnews($userid){
        $sendid=$_SESSION['userid']['userid'];
        $receiveid = $userid;
        $_POST['sendid']=$sendid;
        $_POST['receiveid']=$receiveid;
        $content=$_POST['content'];
        $rend=M('appove')->where("sendid=$sendid and receiveid=$receiveid")->find();
        if($rend){
            $rendid=$rend['newsid'];
            $rs=M('appove')->where("newsid=$rendid")->save(
                array( 'content'=>"$content")
            );
        }else {
            $rs = M('appove')->add($_POST);
        }
        if($rs>0){
            echo 1;
        }else{
            echo 0;
        }
    }

}