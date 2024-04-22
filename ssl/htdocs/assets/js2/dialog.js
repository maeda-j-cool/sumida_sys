function showGrowl(comment, completeGrowl)
{
    // @see /90/style2/dialog.js
    str  = '<div id="sgGrowl" class="sgDialog"><div class="win">';
    str += '<p>'+comment+'</p>';
    str += '<p class="close"><a title="CLOSE">X</a></p>';
    str += '</div></div>';

    /* リセット */
    if($('#sgGrowl').length) {
        $('#sgGrowl').stop();
        $('#sgGrowl').remove();
    }

    /* 表示する */
    $('body').append(str);
    $("#sgGrowl").show();
    mt = $("#sgGrowl .win").height()/2;
    $("#sgGrowl .win").css({ marginTop : -mt });

    /* 2秒表示後、じょじょに消す */
    $("#sgGrowl").delay(2000).animate({
        opacity:0
    },{
        duration:2000,
        complete:function() {
            $(this).remove();
            completeGrowl();
        }
    });

    /* CLOSEボタンが押さえれたら消す */
    $("#sgGrowl p.close a").click(function() {
        $('#sgGrowl').stop();
        $('#sgGrowl').remove();
        completeGrowl();
    });
}

function showAlert(comment, okword, onOK)
{
    str  = '<div id="sgAlert" class="sgDialog">';
    str += '<div class="bg"></div>';
    str += '<div class="win">';
    str += '<p>'+comment+'</p>';
    str += '<div class="btns"><ul>';
    str += '<li class="ok"><a>'+okword+'</a></li>';
    str += '</ul></div>';
    str += '<p class="close"><a title="CLOSE">X</a></p>';
    str += '</div>';
    str += '</div>';

    /* リセット */
    if($('#sgAlert').length) {
        $('#sgAlert').stop();
        $('#sgAlert').remove();
    }

    /* 表示する */
    $('body').append(str);
    $("#sgAlert").show();
    mt = $("#sgAlert .win").height()/2;
    $("#sgAlert .win").css({ marginTop : -mt });


    /* OKボタンが押さえれたらOK処理 */
    $("#sgAlert .btns ul li.ok a").click(function() {
        $('#sgAlert').stop();
        $('#sgAlert').remove();
        onOK();
    });

    /* CLOSEボタンが押さえれたらNG処理 */
    $("#sgAlert p.close a").click(function() {
        $('#sgAlert').stop();
        $('#sgAlert').remove();
        onOK();
    });

    /* 黒背景が押さえれたらNG処理 */
    $("#sgAlert .bg").click(function() {
        $('#sgAlert').stop();
        $('#sgAlert').remove();
        onOK();
    });
}

function showConfirm(title, comment, okword, ngword, onOK, onNG)
{
    // @see /90/style2/dialog.js
    str  = '<div id="sgConfirm" class="sgDialog">';
    str += '<div class="bg"></div>';
    str += '<div class="win">';
    if(title.length) str += '<h3>'+title+'</h3>';
    str += '<p>'+comment+'</p>';
    str += '<div class="btns"><ul>';
    str += '<li class="ng"><a>'+ngword+'</a></li>';
    str += '<li class="ok"><a>'+okword+'</a></li>';
    str += '</ul></div>';
    str += '<p class="close"><a title="CLOSE">X</a></p>';
    str += '</div>';
    str += '</div>';

    /* リセット */
    if($('#sgConfirm').length) {
        $('#sgConfirm').stop();
        $('#sgConfirm').remove();
    }

    /* 表示する */
    $('body').append(str);
    $("#sgConfirm").show();
    mt = $("#sgConfirm .win").height() / 2;
    $("#sgConfirm .win").css({ marginTop : -mt });


    /* NGボタンが押さえれたらNG処理 */
    $("#sgConfirm .btns ul li.ok a").click(function() {
        $('#sgConfirm').stop();
        $('#sgConfirm').remove();
        onOK();
    });

    /* NGボタンが押さえれたらNG処理 */
    $("#sgConfirm .btns ul li.ng a").click(function() {
        $('#sgConfirm').stop();
        $('#sgConfirm').remove();
        onNG();
    });

    /* CLOSEボタンが押さえれたらNG処理 */
    $("#sgConfirm p.close a").click(function() {
        $('#sgConfirm').stop();
        $('#sgConfirm').remove();
        onNG();
    });

    /* 黒背景が押さえれたらNG処理 */
    $("#sgConfirm .bg").click(function() {
        $('#sgConfirm').stop();
        $('#sgConfirm').remove();
        onNG();
    });
}
