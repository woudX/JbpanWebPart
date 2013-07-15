// 页面输入框选中特效

$(document).ready(function() {
    $(".input_textbox,.input_textarea").focus(function() {
        $(this).animate({
            borderColor:"#f60"
        })

        $(this).parent().next().children("span").fadeIn();
    })

    $(".input_textbox,.input_textarea").focusout(function() {
        $(this).animate({
            borderColor:"#8a8a8a"
        })

        $(this).parent().next().children("span").fadeOut();
    })
})

// 页面输入框选中后的提示文字

$(document).ready(function() {

})

// 气泡提示效果
$(document).ready(function() {
    $(".btn_composition img").mousemove(function(event) {
        var hint = $(this).next();
        hint.fadeIn(100)
    }).mouseleave(function() {
        $(this).next().fadeOut(100);
    })
})