/*
    主页动态效果代码
    修改日期: 2013/5/27
*/


/* 计算网页背景图片的宽高（以宽为主） */
$(document).ready(function() {
    //捕捉窗口变化

    window.onresize = function(){
        change_bg_size();
    }

    function change_bg_size() {
        //获取窗体大小
        var scrollWidth = $("#container").width();
        var scrollHeight = $("#container").height();

        //获得背景图片宽高
        var ig = new Image();
        ig.src = $("#hp_bg_img").attr("src");
        var picWidth = ig.width;
        var picHeight = ig.height;

        //计算宽度放缩值
        var widthDelta = scrollWidth / picWidth;
        picWidth = picWidth * widthDelta;
        picHeight = picHeight * widthDelta;

        //添加参数
        $("#hp_bg_img").css("width",picWidth + "px").css("height",picHeight + "px");
        $("#hp_bg").css("top",0 + "px").css("width",scrollWidth).css("height", scrollHeight);
    }

    window.onload = function() {
        change_bg_size();

    }
    //初始调用一次
})

/* 站点背景图片更换 */
$(document).ready(function() {
    var bg_id = 1,
        max_id = 6; //背景总数

    function change_bg(next) {
        $("#hp_bg img").fadeOut(600, function() {
            $("#hp_bg img").attr("src","/Public/image/bg/background" + next + ".jpg");
            $("#hp_bg img").fadeIn(600);
        });
    }

    //进入图片随机
    var randId = parseInt(1 + max_id * Math.random());
    $("#hp_bg img").attr("src","/Public/image/bg/background" + randId + ".jpg");

    //下一张背景图
    $("#next_bg").click(function() {
        bg_id++;
        if (bg_id > max_id)
            bg_id = 1;

        change_bg(bg_id);
    })

    //上一张背景图
    $("#prev_bg").click(function() {
        bg_id--;
        if (bg_id < 1)
            bg_id = max_id;

        change_bg(bg_id);
    })
})

