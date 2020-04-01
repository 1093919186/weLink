<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/8
 * Time: 8:47
 */
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Upload;
class DelalbumController extends Controller
{
    public function index()
    {
        $model = new Model();
        $userid = $_SESSION['userid']['userid'];

        $data = $model->query("select * from image where userid=$userid");
        $lunbo=M("lunbo")->where("picId=1")->find();
        $this->assign('imageList', $data);
        $this->assign('lunbo', $lunbo);
        $this->display();
    }

    public function del(){
        $picid=$_GET['picid'];
        $picpath=M("image")->where("picid=$picid")->find();
        $picpath=$picpath['picpath'];
        $re1=unlink($picpath);
        $re2=M("image")->where("picid=$picid")->delete();
        if($re1&&$re2){
            $this->redirect("Delalbum/index");
        }
    }
}