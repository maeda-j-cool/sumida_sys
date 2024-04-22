// @require dialog.js
$(function(){
    $('#shohin_list').on('click', '.okiniiri', function() {
        // @see /90/tssjs/shohinadd.js
        var $button = $(this);
        var shohinNo = $button.attr('name');
        var shohinName = $button.attr('rel');
        if (shohinNo) {
            $.ajax({
                type: 'GET',
                cache: false,
                async: false,
                url: '/index.php/module/ShohinShosai/action/ShohinShosai/kind/okiniiri/shohin/' + shohinNo,
                success: function(html) {
                    if (html.match('システムエラー') === null) {
                        if (html.match('error:') === null) {
                            // 処理成功時には、ボタンの切り替えを行う。
                            showGrowl('<b>' + shohinName + '</b><br />をお気に入りに追加しました。', function() {});
                            $button.unbind();
                            $button.removeClass('okiniiri');
                            $('img', $button).attr('src', './image/common/icon_favorite_on.png');
                        } else if (html.match('loginErorr') !== null) {
                            // ログインエラー時は、ログイン画面へ遷移させる。
                            location.href = 'https://' + location.hostname + '/index.php/module/Default/action/Logout/';
                        } else {
                            // 処理失敗時は、その旨を示すメッセージを表示する。
                            showAlert(html.replace('error:', ''), 'OK', function() {});
                        }
                    } else {
                        showAlert('システムエラーが発生した為、処理を中断しました。', 'OK', function() {});
                    }
                },
                error: function() {
                    showAlert('通信エラーが発生した為、処理を中断しました。', 'OK', function() {});
                }
            });
        }
    });
    $('.cart-in').click(function() {
        // @see /90/style2/detail.js
        if ($(this).hasClass('sake')) {
            var sakeText = '当サイトでは未成年の方への酒類の販売はいたしておりません。<br>20歳以上でしたら「はい」を押してお進みください。';
            if ($(this).hasClass('sake2')) {
                sakeText = '当サイトでは転売を目的とした交換は固くお断りしております。<br>転売を目的とした交換ではない場合は、<br>「はい」を押してお進みください。';
            } else if ($(this).hasClass('sake3')) {
                sakeText = '本商品は季節限定の商品です。<br>お届け期間終了間際のご注文は、商品のお届けが次の期間になる場合がございます。<br>ご了承いただけましたら「はい」を押してお進みください。';
            }
            showConfirm('', sakeText,'はい','いいえ',function() {
                $('form[name="frmshohinshosai1"]').submit();
            },function(){});
        } else {
            $('form[name="frmshohinshosai1"]').submit();
        }
        return false;
    })
});
