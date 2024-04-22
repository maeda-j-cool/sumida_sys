// require wtUtil
(function($) {
    $.fn.wtHighlightKeyword = function(keyword, color) {
        if (!!keyword.length) {
            this.each(function() {
                var text = $(this).html();
                if (text.length >= keyword.length) {
                    $(this).html(wtUtil.html.highlight(text, keyword, color));
                }
            });
        }
        return false;
    };
})(jQuery);
