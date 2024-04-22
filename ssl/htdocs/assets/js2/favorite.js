$(function(){
    $('.delete-item').click(function() {
        var shohinNo = $(this).attr('data-sno');
        if (shohinNo !== undefined && shohinNo.length) {
            var text = 'この商品をお気に入りリストから<br />削除してもよろしいでしょうか？';
            if (shohinNo === 'all') {
                text = '全ての商品をお気に入りリストから<br />削除してもよろしいでしょうか？';
            }
            showConfirm('', text, 'はい', 'いいえ',
                function() {
                    $('<form>', {
                        action: '/index.php/module/OkiniiriIchiran/action/OkiniiriIchiran/',
                        method: 'post'
                    }).append($('<input>', {
                        type: 'hidden',
                        name: 'shohinNo',
                        value: shohinNo
                    })).appendTo(document.body).submit();
                },
                function() {}
            );
        }
    });
});
