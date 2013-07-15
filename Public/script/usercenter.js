// 用户中心的菜单栏收缩展开动画
var content_div;

$(document).ready(function() {
    $('.catalogue_btn').click(function() {
        var ifopen = $(this).attr('ifopen');
        var listname = $(this).attr('id');

        if (ifopen == 'true') {
            ifopen = 'false';
            $('#' + listname + '_list').fadeOut(0);
            $('#' + listname +  ' img').attr('src', '/PUBLIC/image/icon/add.png');
        }
        else {
            ifopen = 'true';
            $('#' + listname + '_list').fadeIn(0);
            $('#' + listname +  ' img').attr('src', '/PUBLIC/image/icon/minus.png');
        }

        $(this).attr('ifopen', ifopen);
    })
})

// 切换当前页面
function changeToPage(url) {
    // loading
    loading('#content');

    $.ajax({
        url: url,
        success: function(data) {
            $('#content').html(data);
        }
    });
}

// 切换当前页面到
function changeToURL(url) {
    window.open(url,"_blank");
}

// 初始页面载入
$(document).ready(function() {
    changeToPage('/User/personal_info_01');
})

// 页面切换loading
function loading(selector) {
    $(selector).html('<div class="loading"><img src="/Public/image/icon/loading.gif"><span>正在载入，请耐心等待~</span></div>');
}

