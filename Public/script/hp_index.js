/*
    主页动态效果代码
    修改日期: 2013/5/27
*/

/* 计算网页背景图片的宽高 */
function change_bg_size() {
    //获取窗体大小
    var scrollWidth = $("#container").width();
    var scrollHeight = $("#container").height();

    //获得背景图片宽高
    var ig = new Image();
    ig.src = $("#hp_bg_img").attr("src");
    var picWidth = ig.width;
    var picHeight = ig.height;

    if (picWidth / picHeight < scrollWidth / scrollHeight) { // 按照宽度缩放
        //计算宽度放缩值
        var widthDelta = scrollWidth / picWidth;
        picWidth = picWidth * widthDelta;
        picHeight = picHeight * widthDelta;
    } else {
        //计算高度放缩值
        var heightDelta = scrollHeight / picHeight;
        picWidth = picWidth * heightDelta;
        picHeight = picHeight * heightDelta;
    }

    //添加参数
    $("#hp_bg_img").css("width",picWidth + "px").css("height",picHeight + "px");
    $("#hp_bg").css("top",0 + "px").css("width",scrollWidth).css("height", scrollHeight);
}

$(document).ready(function() {
    //捕捉窗口变化

    window.onresize = function(){
        change_bg_size();
    }

    window.onload = function() {
        change_bg_size();
    }
    //初始调用一次
})

/* 站点背景图片更换 */
$(document).ready(function() {
    var bg_id = 1,
        max_id = 20; //背景总数

    function change_bg(next) {
        $("#hp_bg img").fadeOut(600, function() {
            $("#hp_bg img").attr("src","/Public/image/bg/background" + next + ".jpg");

        });
    }

    //进入图片随机
    var bg_id = parseInt(1 + max_id * Math.random());
    $("#hp_bg img").attr("src","/Public/image/bg/background" + bg_id + ".jpg");

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

/* 背景完成加载后显示 */
function displayBG() {
    change_bg_size();
    $("#hp_bg img").fadeIn(600);
}


/* 背景切换按钮特效 */
$(document).ready(function() {
    $(".btn_area").mouseover(function() {
        $(this).children("img").css("opacity", "0.7");
    }).mouseleave(function() {
        $(this).children("img").css("opacity", "0.3");
    })
})