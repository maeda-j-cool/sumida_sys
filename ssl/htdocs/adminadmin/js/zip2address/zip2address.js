(function($) {
    var selfPath = (
        document.currentScript
            ? document.currentScript.src
            : $('script').last().attr('src')
    ).replace(/\/zip2address\.js$/, '/');

    var DEFAULTS = {
        popup: false,
        idZip1: '#zipcode',        // 郵便番号入力欄のid (zip2を指定する場合は上3桁の入力欄)
        idZip2: '',                // 郵便番号上4桁の入力欄のid (1つの入力欄にする場合は空文字列)
        idAddr1: '#address1',       // 住所1(都道府県)の[入力|選択]欄のid
        idAddr2: '#address2',       // 住所2(市区町村)の[入力|選択]欄のid
        idError: '#zipcode-error',  // エラーメッセージ表示欄のid (空文字列を指定した場合はalertダイアログ表示)
        urlApi: '/adminadmin/api/zip2address.php' // 郵便番号検索を実行するAPIのURL
    };

    $.getScript(selfPath + 'jquery.tmpl.min.js');

    var wp;

    $.fn.zip2address = function(options) {
        var settings = $.extend({}, DEFAULTS, options);
        if (settings.popup && window.opener && !window.opener.closed) {
            window.close();
        }
        var ziplistTpl, tmplFile = settings.popup ? 'zip2address.popup.tmpl' : 'zip2address.layer.tmpl';
        $.get(selfPath + tmplFile, function(tmpl) { ziplistTpl = tmpl; });

        this.click(function(e) {
            var zip1 = $(settings.idZip1).val().trim();
            var zip2 = settings.idZip2 ? $(settings.idZip2).val().trim() : '';
            var raiseError = function(message) {
                if ($(settings.idError).length) {
                    $(settings.idError).html(message).show();
                } else {
                    alert(message);
                }
                return false;
            }
            if (settings.idError) {
                $(settings.idError).html('').hide();
            }
            if (settings.idZip2) {
                if ((zip1.length < 3) || (zip1.length > 3)) {
                    return raiseError('郵便番号の上桁を数字3文字で入力してください。');
                }
                if (zip2.length > 4) {
                    return raiseError('郵便番号の下桁は数字4文字以内で入力してください。');
                }
            } else {
                zip1 = zip1.replace(/[^\d]/g, '');
                if (zip1.length > 7) {
                    return raiseError('郵便番号は3～7桁の数字で入力してください。');
                }
            }

            $.ajax({
                type: 'GET',
                url: settings.urlApi + '?zipcode=' + zip1 + zip2,
                dataType: 'json',
                async: false,
                success: function(json) {
                    if (typeof json.message === 'undefined') {
                        return raiseError('システムで問題が発生しました。');
                    }
                    if (json.message !== null) {
                        return raiseError('該当する郵便番号が見つかりません。');
                        //return raiseError(json.message);
                    }
                    if (json.results.length === 1) {
                        if (settings.idZip2) {
                            $(settings.idZip1).val(json.results[0].zipcode1);
                            $(settings.idZip2).val(json.results[0].zipcode2);
                        } else {
                            $(settings.idZip1).val(json.results[0].zipcode1 + '-' + json.results[0].zipcode2);
                        }
                        $(settings.idAddr1).val(json.results[0].address1);
                        $(settings.idAddr2).val(json.results[0].address2 + json.results[0].address3);
                    } else {
                        var params = {
                            zipcodeList: json.results,
                            idZip1: settings.idZip1,
                            idZip2: settings.idZip2,
                            idAddr1: settings.idAddr1,
                            idAddr2: settings.idAddr2
                        };
                        var $htmlBody = $.tmpl(ziplistTpl, params);
                        if (settings.popup) {
                            var popupTitle = '郵便番号検索';
                            // $.tmplでヘッダを含むHTMLがパースできないので
                            var html = '<!DOCTYPE html>'
                                     + '<html lang="ja">'
                                     + '<head>'
                                     +   '<meta charset="UTF-8">'
                                     +   '<title>' + popupTitle + '</title>'
                                     + '</head>'
                                     + '<body>' + $('<div/>').append($htmlBody).html() + '</body>'
                                     + '</html>';
                            if (wp) {
                                wp.close();
                            }
                            wp = window.open("", "zipcode-list", "width=640,height=640,resizable=1,scrollbars=1");
                            wp.document.write(html);
                            wp.document.close();
                            $(wp).load(function() {
                                $('<link>').attr({
                                    rel: 'stylesheet',
                                    type: 'text/css',
                                    href: selfPath + 'zip2address.popup.css'
                                }).appendTo(wp.$('head'));
                            });
                        } else {
                            $('<link>').attr({
                                rel  : 'stylesheet',
                                type : 'text/css',
                                href : selfPath + 'zip2address.layer.css'
                            }).appendTo('head');
                            $('<div id="zipcode-bg">').appendTo('body').fadeIn();
                            $('<div id="zipcode-list">').appendTo('body').hide();
                            $htmlBody.appendTo('#zipcode-list');
                            var $list = $('#zipcode-list');
                            var winW = $(window).width();
                            var winH = $(window).height();
                            var layW = Math.min(winW - 60, $list.width() + 18);
                            var layH = Math.min(winH - 60, $list.height());
                            var listPosLeft = Math.floor((winW - layW) / 2);
                            var listPosTop  = Math.floor((winH - layH) / 2);
                            if (listPosTop < 0){ listPosTop = 0; }
                            $list.css({
                                'top': listPosTop,
                                'left': listPosLeft,
                                'width': layW + 'px',
                                'height': layH + 'px'
                            }).fadeIn();
                            $('#zipcode-bg').click(function() {
                                $('#zipcode-list, #zipcode-bg').fadeOut().remove();
                            });
                        }
                    }
                }
            });
            return false;
        });
    };
})(jQuery);
