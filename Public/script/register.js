// 页面输入框选中特效

$(document).ready(function() {
    $(".input_textbox,.input_textarea").focus(function() {
        $(this).animate({
            boxShadow: "0 0 5px #f60",
            borderColor:"#f60"
        })

        $(this).parent().next().children("span").fadeIn();
    })

    $(".input_textbox,.input_textarea").focusout(function() {
        $(this).animate({
            boxShadow: "0 0 0px #f60",
            borderColor:"#8a8a8a"
        })

        $(this).parent().next().children("span").fadeOut();
    })
})
