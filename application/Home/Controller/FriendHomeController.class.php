<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/5
 * Time: 15:45
 */
namespace Home\Controller;
use Think\Controller;
class FriendHomeController extends Controller{
    public function index(){
        $userid=$_GET['friendid'];
        $friendnum=M('link')->where("userid=$userid")->count();
        $userinfo=M('user')->where("userid=$userid")->find();
        $name=$userinfo['name'];
        $iconpath=$userinfo['iconpath'];
        cookie('name',"$name",3600);
        cookie('iconpath',"$iconpath",3600);
        $circle=M("circle")->where("fbman='$name'")->order("dateandtime desc")->limit(0,10)->select();

        $friend=M("link")->join("inner join user on user.userid = link.friendid")->where("link.userid=$userid")->select();

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

        $this->assign("circle",$circle);
        $this->assign("userinfo",$userinfo);
        $this->assign("friendnum",$friendnum);
        $this->assign("friend",$friend);
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
 /*       var_dump($icon);
        exit();*/
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

    public function panduan(){
        $userid=$_SESSION['userid']['userid'];
        $friendid=$_GET['friendid'];
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

    public function wantmore(){
        $p=$_POST['p'];
        $p=$p*10;
        $userid=$_GET['friendid'];
        $userinfo=M('user')->where("userid=$userid")->find();
        $name=$userinfo['name'];
        $circle=M("circle")->where("fbman='$name'")->order("dateandtime desc")->limit($p,10)->select();
        foreach($circle as $k=>$vv){
            $circleid=$vv["circleid"];
            $circle[$k]['icon']=M("user")->where("name='$name'")->find();
            $circle[$k]['images']=M("circleimage")->where("circleid=$circleid")->select();
        }
        foreach($circle as $k=>$vvv){
            $circleid=$vvv["circleid"];
            $circle[$k]['reviews']=M("reviews")->where("circleid=$circleid")->select();
            $reviews=$circle[$k]['reviews'];
            foreach($reviews as $k=>$r){
                $plman=$r['plman'];
            }
        }

        foreach($circle as $k=>$v) {
            echo ' <div style="width: 712px;height: auto;background-color: #ffffff;border: 1px solid lightgrey;float: left;margin-top: 5px">
            <img src="/weLink/'.$circle[$k]['icon']['iconpath'].'" style="width: 80px;height: 80px;margin-top: 10px;margin: 10px;float: left">

            <div style="margin-top: 7px;width: 590px;height: auto;float: left;margin-bottom: 5px"><span style=\'font-size: 15px;font-family: "arial", "微软雅黑";color: dodgerblue;font-family: "arial", "微软雅黑"\'>'.$v['fbman'].'</span><br><br>
                <span>'.$v['content'].'</span>
            </div>

            <div style="width: 692px;height: 25px;float: left;margin-left: 10px">
                <img src="/weLink/public/images/赞.PNG" style="margin-right: 5px;float: left;margin-top: -3px"><span class="numzan'.$v['circleid'].'" style=\'margin-right: 5px;float: left;font-family: "arial", "微软雅黑";color: #808080\'>'.$v['numzan'].'</span>
                <img src="/weLink/public/images/评论.PNG" style="margin-right: 5px;float: left;margin-top: -2px"><span style=\'float: left;font-family: "arial", "微软雅黑";color: grey;margin-right: 20px\' class="numpl'.$v['circleid'].'">'.$v['numpl'].'</span>
                <a onclick="$.windowbox.zan('.$v['circleid'].'); " style=\'color: dodgerblue;font-family: "arial", "微软雅黑";float: left;margin-top: -4px;cursor: pointer\'>赞</a>
            </div>

            <!--朋友圈图片-->
            <div style="width: 520px;height: auto;margin-left: 95px;margin-bottom: 10px;float: left">';
            foreach($circle[$k]['images'] as $vv){
                echo   '<img src="/weLink/'.$vv['picpath'].'" style="width: 160px;height: 175px;margin-right: 5px;">';
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
                    <div style="width: 100%;height: auto;" class="pl'.$v['circleid'].'">';
            foreach($circle[$k]['reviews'] as $vvv) {
                echo '<div style="width: 100%;height: auto;margin-bottom: 10px;float: left" >
                            <img src="/weLink/'.$vvv['iconpath'].'" style="width: 40px;height: 40px;float: left">
                            <div style="width: 645px;height: auto;float: left;margin-left: 5px">
                                <a href="'.__APP__.'/Content/panduan/plman/'.$vvv['plman'].'"  style=\'font-family: "arial", "微软雅黑";color: dodgerblue;font-size: 15px;text-decoration: none\'>'.$vvv['plman'].'</a><br>
                                <div style="width: 645px;"><span style=\'font-family: "arial", "微软雅黑";color: #000000;font-size: 13px;\'>'.$vvv['content'].'</span></div>
                            </div>
                        </div>';
            }

            echo '</div>

                </div>

                    <input class="plnr'.$v['circleid'].'" name="pinglun" type="text" style="margin-bottom:10px;width: 688px;height: 30px;margin-left: 12px;border: 1px solid lightgrey;border-radius: 5px;box-shadow: 2px 2px 2px lightgrey;color: lightgrey" value="添加评论">
                    <input onclick="$.windowbox.pinglun('.$v['circleid'].');$.windowbox.numpl('.$v['circleid'].') " name="but" type="submit" style="width: 50px;height: 25px;background-color:aliceblue ;margin-top: 10px;margin-left: 10px;margin-bottom: 10px;display: none;border-radius: 3px;border: 1px solid lightgrey" value="评论">
            </div>
            <!--评论end-->
        </div>
        <!--朋友圈end-->';
        }

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

    public function tiaozhuan(){
        $friendid=$_GET['friendid'];
        $friendid=M("user")->where("name='$friendid'")->find();
        $friendid=$friendid['userid'];
        $this->redirect("FriendHome/index/friendid/$friendid");
        exit();
    }

}