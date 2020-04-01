<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:35
 */
namespace Home\Controller;
use Think\Controller;
class ContentController extends Controller{
    public $x=0;
    public $circlenum=0;
    public $newcirnum=0;
    public $oldfrinum=0;
    public function index(){
        $userid=$_SESSION['userid']['userid'];
        $friendnum=M('link')->where("userid=$userid")->count();
        $userinfo=M('user')->where("userid=$userid")->find();
        $name=$userinfo['name'];
        $iconpath=$userinfo['iconpath'];
        cookie('name',"$name",3600);
        cookie('iconpath',"$iconpath",3600);
        $lunbo=M("lunbo")->where("picId=2")->find();
        $this->oldfrinum=M('appove')->where("receiveid=$userid")->count();
        $_SESSION['oldfrinum']=$this->oldfrinum;
        /**/
        $cir=M("circle")->order("dateandtime desc")->limit(0,10)->select();
        foreach($cir as $k=>$v){
            $sendman=$v['fbman'];
            $friendid=M("user")->where("name='$sendman'")->find();

            $icon=$friendid['iconpath'];
            $friendid=$friendid['userid'];
            $myfriend=M("link")->where("userid=$userid")->select();
            $v['fbmanicon']=$icon;
            foreach($myfriend as $k1=>$vv) {
                if($friendid==$vv['friendid']) {
                    $circleid=$v['circleid'];
                    $img=M("circleimage")->where("circleid=$circleid")->select();

                    $reviews=M("reviews")->where("circleid=$circleid")->select();
                    $circle[]=$v;
                    $circle[$this->x]['img']=$img;
                    $circle[$this->x]['review']=$reviews;
                    $this->x++;
                }
            }
        }

/*      var_dump($circle);
      exit();*/
        $arr = $_SESSION['userid'];
        $this->assign('arr',$arr);
        /*$this->assign("friend",$friend);*/
        $this->assign("circle",$circle);
        $this->assign("lunbo",$lunbo);
        $this->assign("userinfo",$userinfo);
        $this->assign("friendnum",$friendnum);


/*即时通讯*/
        $cir=M("circle")->order("dateandtime desc")->select();
        foreach($cir as $k=>$v){
            $sendman=$v['fbman'];
            $friendid=M("user")->where("name='$sendman'")->find();

            $icon=$friendid['iconpath'];
            $friendid=$friendid['userid'];
            $myfriend=M("link")->where("userid=$userid")->select();
            $v['fbmanicon']=$icon;
            foreach($myfriend as $k1=>$vv) {

                if($friendid==$vv['friendid']) {
                    $circleid=$v['circleid'];
                    $img=M("circleimage")->where("circleid=$circleid")->select();

                    $reviews=M("reviews")->where("circleid=$circleid")->select();
                    $circle[]=$v;
                    $circle[$this->circlenum]['img']=$img;
                    $circle[$this->circlenum]['review']=$reviews;
                    $this->circlenum++;
                }
            }
        }
        $circlenum=$this->circlenum;
        $_SESSION['circlenum']=$circlenum;
        $this->display();
    }

    public function info(){
        $oldcirclenum=$_SESSION['circlenum'];
        $userid=$_SESSION['userid']['userid'];
        $cir=M("circle")->order("dateandtime desc")->select();
        foreach($cir as $k=>$v){
            $sendman=$v['fbman'];
            $friendid=M("user")->where("name='$sendman'")->find();

            $icon=$friendid['iconpath'];
            $friendid=$friendid['userid'];
            $myfriend=M("link")->where("userid=$userid")->select();
            $v['fbmanicon']=$icon;
            foreach($myfriend as $k1=>$vv) {

                if($friendid==$vv['friendid']) {
                    $circleid=$v['circleid'];
                    $img=M("circleimage")->where("circleid=$circleid")->select();

                    $reviews=M("reviews")->where("circleid=$circleid")->select();
                    $circle[]=$v;
                    $circle[$this->newcirnum]['img']=$img;
                    $circle[$this->newcirnum]['review']=$reviews;
                    $this->newcirnum++;
                }
            }
        }
        $newcirnum=$this->newcirnum;
        if($newcirnum>$oldcirclenum) {
            echo 1;
        }
        $olfrinum=$_SESSION['oldfrinum'];
        $friendnum=M('appove')->where("receiveid=$userid")->count();
        if($friendnum>$olfrinum){
            echo 3;
        }

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
        $name=cookie('name');
        $icon=cookie('iconpath');
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

    public function wantmore(){
        $p=$_POST['p'];
        $p=$p*10;
        $userid=$_SESSION['userid']['userid'];

        /**/
        $cir=M("circle")->order("dateandtime desc")->limit($p,10)->select();
        foreach($cir as $k=>$v){
            $sendman=$v['fbman'];
            $friendid=M("user")->where("name='$sendman'")->find();
            $icon=$friendid['iconpath'];
            $friendid=$friendid['userid'];
            $myfriend=M("link")->where("userid=$userid")->select();
            $v['fbmanicon']=$icon;
            foreach($myfriend as $vv) {
                if($friendid==$vv['friendid']) {
                    $circleid=$v['circleid'];
                    $img=M("circleimage")->where("circleid=$circleid")->select();
                    $reviews=M("reviews")->where("circleid=$circleid")->select();
                    $circle[]=$v;
                    $circle[$this->x]['img']=$img;
                    $circle[$this->x]['review']=$reviews;
                    $this->x++;
                }
            }
        }

        foreach($circle as $k=>$vo) {
            echo ' <div style="width: 712px;height: auto;background-color: #ffffff;border: 1px solid lightgrey;float: left;margin-top: 5px">
            <a href="'.__APP__.'/FriendHome/tiaozhuan/friendid/'.$vo['fbman'].'"><img src="/weLink/'.$vo['fbmanicon'].'" style="width: 80px;height: 80px;margin-top: 10px;margin: 10px;float: left"></a>

            <div style="margin-top: 10px;width: 530px;height: auto;float: left;margin-bottom: 5px"><a style="text-decoration: none" href="'.__APP__.'/FriendHome/tiaozhuan/friendid/'.$vo['fbman'].'">
            <span style=\'font-size: 15px;font-family: "arial", "微软雅黑";color: dodgerblue;font-family: "arial", "微软雅黑";\'>'.$vo['fbman'].'</span></a><br><br>
                <span>'.$vo['content'].'</span>
            </div>

            <div style="width: 692px;height: 25px;float: left;margin-left: 10px">
                <img src="/weLink/public/images/赞.PNG" style="margin-right: 5px;float: left;margin-top: -3px"><span class="numzan'.$vo['circleid'].'" style=\'margin-right: 5px;float: left;font-family: "arial", "微软雅黑";color: #808080\'>'.$vo['numzan'].'</span>
                <img src="/weLink/public/images/评论.PNG" style="margin-right: 5px;float: left;margin-top: -2px"><span style=\'float: left;font-family: "arial", "微软雅黑";color: grey;margin-right: 20px\' class="numpl'.$vo['circleid'].'">'.$vo['numpl'].'</span>
                <a onclick="$.windowbox.zan('.$vo['circleid'].'); " style=\'color: dodgerblue;font-family: "arial", "微软雅黑";float: left;margin-top: -4px;cursor: pointer\'>赞</a>
            </div>

            <!--朋友圈图片-->
            <div style="width: 520px;height: auto;margin-left: 95px;margin-bottom: 10px;float: left">';
            foreach($vo['img'] as $k2=>$voo){
                echo   '<img src="/weLink/'.$voo['picpath'].'" style="width: 160px;height: 175px;margin-right: 5px;">';
            }
            echo '</div>
            <!--end-->

            <div style="width: 100%;height: 25px;float: left">
                <div class="plbut" style="width: 50px;height: 20px;float: right;border-radius: 3px;border: 1px solid dodgerblue;
                color: dodgerblue;line-height: 20px;text-align: center;margin-right: 10px;cursor: pointer">评论</div>
            </div>

            <!--评论-->
            <div class="plcont" style="width: 100%;height: auto;background-color: aliceblue;float: left;display: none">
                <div style="margin: 20px auto;width: 692px;height: auto;">
                    <span style=\'font-family: "arial", "微软雅黑";font-size: 13px;color: darkgrey\'>显示之前评论</span><br>
                    <div style="width: 100%;height: auto;" class="pl'.$vo['circleid'].'">';
            foreach($vo['review'] as $k3=>$voo) {
                echo '<div style="width: 100%;height: auto;margin-bottom: 10px;float: left" >
                            <img src="/weLink/'.$voo['iconpath'].'" style="width: 40px;height: 40px;float: left">
                            <div style="width: 645px;height: auto;float: left;margin-left: 5px">
                                <a href="'.__APP__.'/Content/panduan/plman/'.$voo['plman'].'"  style=\'font-family: "arial", "微软雅黑";color: dodgerblue;font-size: 15px;text-decoration: none\'>'.$voo['plman'].'</a><br>
                                <div style="width: 645px;"><span style=\'font-family: "arial", "微软雅黑";color: #000000;font-size: 13px;\'>'.$voo['content'].'</span></div>
                            </div>
                        </div>';
            }

            echo '</div>

                </div>

                    <input class="plnr'.$vo['circleid'].'" name="pinglun" type="text" style="margin-bottom:10px;width: 688px;height: 30px;margin-left: 12px;border: 1px solid lightgrey;border-radius: 5px;box-shadow: 2px 2px 2px lightgrey;color: lightgrey" value="添加评论">
                    <input onclick="$.windowbox.pinglun('.$vo['circleid'].');$.windowbox.numpl('.$vo['circleid'].') " name="but" type="submit" style="width: 50px;height: 25px;background-color:aliceblue ;margin-top: 10px;margin-left: 10px;margin-bottom: 10px;display: none;border-radius: 3px;border: 1px solid lightgrey" value="评论">
            </div>
            <!--评论end-->
        </div>
        <!--朋友圈end-->';

        }





/*end*/
        echo '<script>
    $(function(){
        $(\'input[name="pinglun"]\').focus(function(){
            $(this).val(\'\');
            $(this).css(\'color\',\'black\');
            $(this).parent().find(\'input[name="but"]\').slideDown();
        })

        $(\'input[name="but"]\').hover(function(){
            $(\'input[name="but"]\').css(\'background-color\',\'#e9e9e9\')
        },function(){
            $(\'input[name="but"]\').css(\'background-color\',\'aliceblue\')
        });


        $(\'.updateface\').hover(function(){
            $(this).css(\'background-color\',\' rgba(11,73,113,0.85)\')
        },function(){
            $(this).css(\'background-color\',\'white\')
        });

        $(\'.nava\').mouseenter(function(){
            $(\'.nava\').css(\'background-color\',\'#e9e9e9\');
        })
        $(\'.nava\').mouseleave(function(){
            $(\'.nava\').css(\'background-color\',\'white\');
        })

        $("#pic").click(function () {
            $("#upload").click();  //隐藏了input:file样式后，点击头像就可以本地上传
            $("#upload").on("change",function(){
                var objUrl = getObjectURL(this.files[0]) ; //获取图片的路径，该路径不是图片在本地的路径
                if (objUrl) {
                    $("#pic").attr("src", objUrl) ; //将图片路径存入src中，显示出图片
                }
            });
        });

        $(\'.plbut\').mouseenter(function(){
            $(this).parent().css(\'background-color\',\'aliceblue\');
            $(this).parent().parent().find(\'.plcont\').slideDown();
        })

        /*返回首页*/
        $(function () {
            $(window).scroll(function(){
                if ($(window).scrollTop()>100){        /*据顶距离多长出现*/
                    $("#置顶键").fadeIn(1500);
                }
                else
                {
                    $("#置顶键").fadeOut(1500);
                }
            });
//当点击跳转链接后，回到页面顶部位置
            $("#置顶键").click(function(){
                $(\'body,html\').animate({scrollTop:0},1000);   /*置顶的距离以及完成动画的时间*/
                return false;             /*可有可无*/
            });
        });

        $.windowbox = {
//定义一个方法aa
            zan: function ($a) {
                $.ajax({
                    type:"post",
                    url:"'.__APP__.'/Content/ajax",
                    "data":{
                        "p":$a
                    },
                    "success":function(response,status,xhr){
                        //若ajax.php输出值为1，则在ｐ标签总写入ｙｅｓ，
                        //否则写入no
                        if(response){
                            $(".numzan"+$a).html(response);
                        }
                    }
                });
            },

            pinglun: function ($a) {
                $.ajax({
                    type:"post",
                    url:"'.__APP__.'/Content/addreview",
                    "data":{
                        "plnr":$(".plnr"+$a).val(),
                        "circleid":$a
                    },
                    "success":function(response,status,xhr){
                        //若ajax.php输出值为1，则在ｐ标签总写入ｙｅｓ，
                        //否则写入no
                        if(response){
                            $(".pl"+$a).append(response);
                            $(\'input[name="but"]\').css(\'display\',\'none\');
                            $(\'input[name="pinglun"]\').val(\'\');
                        }
                    }
                });
            },

            numpl: function ($a) {
                $.ajax({
                    type:"post",
                    url:"'.__APP__.'/Content/numpl",
                    "data":{
                        "p":$a
                    },
                    "success":function(response,status,xhr){
                        //若ajax.php输出值为1，则在ｐ标签总写入ｙｅｓ，
                        //否则写入no
                        if(response){
                            $(".numpl"+$a).html(response);
                        }
                    }
                });
            },

        }

        var x=1;
        $(\'.wantmore\').click(function(){
            $.ajax({
                "url":"__APP__/Content/wantmore",
                "type":"post",
                /*"data":"username=jack&password=3",*/
                "data":{
                    "p":x
                },
                "success":function(response,status,xhr){
                    //若ajax.php输出值为1，则在ｐ标签总写入ｙｅｓ，
                    //否则写入no
                    $(".wantmore").before(response);
                    x++;
                }
            });
        })

        $(\'input[name="but"]\').css(\'background-color\',\'aliceblue\')

    })
</script>';
        echo '    <script src="/weLink/public/js/jquery-3.1.0.min.js"></script>
    <script src="/weLink/public/js/showTurn.js"></script>';
    }

    public function modifyicon($userid){
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->exts = array('jpg','gif','png','jpeg');
        $upload->rootPath= './public/picture/Iconimage/';
        $upload->savePath = '';
        $upload->autoSub=false;
        $info= $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->redirect("content/index/userid/$userid");
        }
        $rs1 = M('user')->field('iconpath,name')->where(" userid = {$userid} ")->find();
        unlink($rs1['iconpath']);
        $rs2['iconpath']='public/picture/Iconimage/'.$info['file']['savename'];
        $rs = M('user')->execute(" update user set  iconpath='{$rs2['iconpath']}' where userid ={$userid} ");
        $rs = M('reviews')->execute(" update reviews set  iconpath='{$rs2['iconpath']}' where plman ='{$rs1['name']}'");
        $_SESSION['userid']['iconpath']=$rs2['iconpath'];
        $this->redirect("content/index/userid/$userid");
    }

    public function panduan(){
        $userid=$_SESSION['userid']['userid'];
        $friendid=$_GET['plman'];
        $friendid=M("user")->where("name='$friendid'")->find();
        $friendid=$friendid['userid'];
        $re=M("link")->where("userid=$userid and friendid=$friendid")->find();
        if($re){
            $this->redirect("FriendHome/index/friendid/$friendid");
        }elseif($userid==$friendid){
            $this->redirect("Person/index");
        }else{
            $this->redirect("ForeignHome/index/friendid/$friendid");
        }
        exit();
    }

}