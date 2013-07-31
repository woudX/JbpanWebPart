/* header的相关js代码 */
/* 全局AJAX设置 */
$(document).ready(function() {
    $.ajaxSetup({
        type: "GET",
        dataType: "HTML",
        cache: "FALSE",
        global: "TRUE"
    })
})

//获取元素的纵坐标
function getTop(e){
    var offset=e.offsetTop;
    if(e.offsetParent!=null) offset+=getTop(e.offsetParent);
    return offset;
}
//获取元素的横坐标
function getLeft(e){
    var offset=e.offsetLeft;
    if(e.offsetParent!=null) offset+=getLeft(e.offsetParent);
    return offset;
}

/* 公告栏滑动淡入淡出 */
$(document).ready(function() {
    $showFlag = false;

    // 公告颜色改变
    $("#scpt1").hover(function() {
        $("#sc_tt").animate({
            color: "#fc0"
        })
    },function() {
        $("#sc_tt").animate({
            color: "#f60"
        })
    })

    // 隐藏公告栏显示
    $("#scpt1").click(function() {
        if ($showFlag)
        {
            $("#flow_scpt1").fadeOut();
            $showFlag = false;
        }
        else
        {
            $("#flow_scpt1").fadeIn();
            $showFlag = true;
        }
        ;    })

    $("#flow_scpt1").hover(function() {
    },function() {
        $("#flow_scpt1").fadeOut();
        $showFlag = false;
    })
})

/* 搜索栏的内容自动清除 */
$(document).ready(function() {
    $("#srh_input").focus(function() {
        this.value = "";
        $("#srh_input").css("font-style","normal");
        $("#srh_input").css("color","#2a2a2a");
    })
})

/* 搜索栏选项 */
$(document).ready(function() {
    var showFlag = false

    /* 开启关闭选项单 */
    $("#type_list_selected").click(function() {
        if (showFlag)
        {
            $("#type_list").fadeOut();
            showFlag = false;
        }
        else
        {
            $("#type_list").fadeIn();
            showFlag = true;
        }
    })

    $("#type_list").mouseleave(function() {
        $("#type_list").fadeOut();
        showFlag = false;
    })

    /* 更改选项 */
    $("#type_list li").click(function() {
        $("#type_list_selected").html(this.textContent);
        $("#type_list_selected").removeClass().addClass($(this).attr("color"));
        $("#srh_type").attr("value",$(this).attr("type"));
        $("#type_list").fadeOut(10);
        showFlag = false;
    })

})

/* 用户菜单栏 */
$(document).ready(function() {

    //下拉菜单显示
    $("#ac_list_selected").mouseover(function() {
        $("#ac_list").fadeIn();
    })

    $("#ac_list_selected_logged").mouseover(function() {
        $("#ac_list").fadeIn();
    })

    $("#ac_list").mouseleave(function() {
        $("#ac_list").fadeOut();
    })

    //登录
    $("#ac_list_selected").click(function() {
        $("#login_box").fadeIn();
    })

    //关闭登录界面
    $("#login_close_btn").click(function() {
        $("#login_box").fadeOut();
    })
})

