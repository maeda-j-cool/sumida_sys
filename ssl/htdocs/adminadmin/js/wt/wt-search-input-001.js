(function($) {
    var DEFAULTS = {
        idFrom : '',
        idTo   : '',
        idSep  : '',
        phList : {
            1 : [ '※完全一致', '　', '' ],
            2 : [ '※前方一致', '　', '' ],
            3 : [ '※開始',     '～', '※終了' ],
            4 : [ '※後方一致', '　', '' ],
            5 : [ '※部分一致', '　', '' ]
        }
    };
    $.fn.wtSearchInput001 = function(options) {
        var settings = $.extend({}, DEFAULTS, options);
        var changeView = function(type) {
            var ph = settings.phList[type];
            if (ph !== undefined) {
                var $inputFrom = $(settings.idFrom);
                var $textSep   = $(settings.idSep);
                var $inputTo   = $(settings.idTo);
                if ($inputFrom.length) {
                    $inputFrom.prop('placeholder', ph[0]);
                }
                if ($textSep.length) {
                    $textSep.html(ph[1]);
                }
                if ($inputTo.length) {
                    $inputTo.prop('placeholder', ph[2]);
                    if (ph[2].length) {
                        $inputTo.prop('disabled', false);
                    } else {
                        $inputTo.prop('disabled', true);
                        $inputTo.val('');
                    }
                }
            }
        };
        changeView(this.val());
        var target = this;
        this.click(function() { changeView(target.val()); });
        return false;
    };
})(jQuery);
