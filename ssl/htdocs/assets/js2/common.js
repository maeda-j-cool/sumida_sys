function hamburger() {
    document.getElementById('line1').classList.toggle('line_1');
    document.getElementById('line2').classList.toggle('line_2');
    document.getElementById('line3').classList.toggle('line_3');
    document.getElementById('nav').classList.toggle('in');
}

$(function () {
    $('#hamburger').on('click', function() {
        hamburger();
    });

    //タイトルがクリックされたら
    $(".ac-title").on('click', function() {
        //クリックしたac-title以外の全てのopenを取る
        $(".ac-title").not(this).removeClass("open");
        //クリックされたac-title以外のcontentを閉じる
        $(".ac-title").not(this).next().slideUp(300);
        //thisにopenクラスを付与
        $(this).toggleClass("open");
        //thisのcontentを展開、開いていれば閉じる
        $(this).next().slideToggle(300);
    });

    var step = 1;
    var min = 0;
    var max = 100;
    $('.spinner_up').click(function() {
        var $div = $(this).parents('.pulldown01');
        var sn = $('.input-quantity', $div).val();
        if (!sn.length) {
            sn = 0;
        }
        var number = parseInt(sn) + step;
        if (number > max) {
            number = max;
        }
        $('.input-quantity', $div).val(number);
    });
    $('.spinner_down').click(function() {
        var $div = $(this).parents('.pulldown01');
        var sn = $('.input-quantity', $div).val();
        if (!sn.length) {
            sn = 0;
        }
        var number = parseInt(sn) - step;
        if (number < min) {
            number = min;
        }
        $('.input-quantity', $div).val(number);
    });

  /*  $('input').keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        }
        return true;
    });*/

    $('.ac-pc').on({
        'mouseenter':function(){
            $('.ac-pc').not(this).removeClass("hover");
            //マウスカーソルが重なった時の処理
            $(this).addClass("hover");
            clearTimeout(sethover);

        },
        'mouseleave':function(){
            $object = this;
            sethover = setTimeout($.proxy(function(){
                $(this).removeClass("hover");
            } ,$object),500);
        }
    });

    $('.tableInfo-acBtn input').on('click', function() {
        $('.is-tableInfo-ac').slideToggle();
    });
});
