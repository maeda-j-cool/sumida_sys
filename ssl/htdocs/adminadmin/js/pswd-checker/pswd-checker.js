(function($) {
    var selfPath = (
        document.currentScript
            ? document.currentScript.src
            : $('script').last().attr('src')
    ).replace(/\/pswd-checker\.js$/, '/');

    $.getScript(selfPath + 'zxcvbn.js');

    var DEFAULTS = {
        idAll: '#zxcvbn-result',
        idScore: '#zxcvbn-score',
        idCrackT: '#zxcvbn-crack-time'
    };

    var SCORE_INFO = [
        {color: '#f00', text: '弱い'},
        {color: '#c90', text: 'やや弱'},
        {color: '#999', text: 'まあまあ'},
        {color: '#3c3', text: 'やや強'},
        {color: '#66f', text: '強い'}
    ];

    $.fn.pswdChecker = function(options) {
        var settings = $.extend({}, DEFAULTS, options);
        this.keyup(function(e) {
            var password = $(this).val();
            if (password.length) {
                result = zxcvbn(password);
                $(settings.idAll).css('color', SCORE_INFO[result.score].color);
                if (settings.idScore !== undefined) {
                    $(settings.idScore).html(SCORE_INFO[result.score].text);
                }
                if (settings.idCrackT) {
                    var s = String(result.crack_times_display['online_no_throttling_10_per_second'])
                        .replace('less than a second', '1秒以内')
                        .replace('centuries', '100年以上')
                        .replace(/ seconds?/, ' 秒')
                        .replace(/ minutes?/, ' 分')
                        .replace(/ hours?/  , ' 時間')
                        .replace(/ days?/   , ' 日')
                        .replace(/ months?/ , ' ヶ月')
                        .replace(/ years?/  , ' 年');
                    $(settings.idCrackT).html('【推定クラック時間: ' + s + ' (online 10/sec)】');
                }
                $(settings.idAll).show();
            } else {
                $(settings.idAll).hide();
            }
        });
        return(this);
    };
})(jQuery);
