/**
 * Created by lenovo on 2016/12/2.
 */

var i = 0;
var time;
$(function ()
{
    $(".swapImg").eq(0).show().siblings().hide();
    showTime();
    $(".tab").hover(
        function ()
        {
            i = $(this).index();
            show();
            clearInterval(time);
        }, function ()
        {
            showTime();
        });
    $(".btnLeft").click(function ()
    {
        clearInterval(time);
        if (i == 0)
        {
            i =3;
        }
        i--;
        show();
        showTime();
    });
    $("#allswapImg").mouseover(function () {
        $(".btn").show();
    });

    $("#allswapImg").mouseleave(function () {
        $(".btn").hide();
    });

    $(".btn").mouseover(function () {
        $(".btn").show();
    });

    $(".btnRight").click(function () {
        clearInterval(time);
        if (i == 2) {
            i = -1;
        }
        i++;
        show();
        showTime();
    });
});
function show() {
    $(".swapImg").eq(i).fadeIn(500).siblings().fadeOut();
    $(".tab").eq(i).addClass("bg").siblings().removeClass("bg");
}
function showTime()
{
    time = setInterval(function () {
        i++;
        if (i == 3) {
            i = 0;
        }
        show();
    }, 4000);
};