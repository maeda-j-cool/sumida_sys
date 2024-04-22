(function(global, undefined) {
    'use strict';

    var wtUtil = {};

    // --- 変数型判定 ---
    //   wtUtil.type.is(type, obj)
    //   wtUtil.type.isArray(obj)
    //   wtUtil.type.isString(obj)
    //   wtUtil.type.isObject(obj)
    // --- 文字列処理系 ---
    //   wtUtil.string.trim(s, charMask)
    //   wtUtil.string.hiragana2katakana(s)
    //   wtUtil.string.katakana2hiragana(s)
    //   wtUtil.string.zen2han(s, ctype)
    //   wtUtil.string.zen2hanAlpha(s)
    //   wtUtil.string.zen2hanNumeric(s)
    //   wtUtil.string.zen2hanSymbol(s)
    //   wtUtil.string.zen2hanAlphaNumeric(s)
    //   wtUtil.string.zen2hanAscii(s)
    //   wtUtil.string.zen2hanKatakana(s)
    //   wtUtil.string.zen2hanAll(s)
    //   wtUtil.string.han2zen(s, ctype)
    //   wtUtil.string.han2zenAlpha(s)
    //   wtUtil.string.han2zenNumeric(s)
    //   wtUtil.string.han2zenSymbol(s)
    //   wtUtil.string.han2zenAlphaNumeric(s)
    //   wtUtil.string.han2zenAscii(s)
    //   wtUtil.string.han2zenKatakana(s)
    //   wtUtil.string.han2zenAllfunction(s)
    //   wtUtil.string.numberFormat(number)
    // --- HTML関連 ---
    //   wtUtil.html.escape(s)
    //   wtUtil.html.unescape(s):
    //   wtUtil.html.stripTags(s)
    //   wtUtil.html.highlight(text, kw, color)

    wtUtil.type = {
        // https://github.com/BonsaiDen/JavaScript-Garden/blob/master/doc/ja/types/typeof.md
        // type ⇒ (Arguments | Array | Boolean | Date | Error | Function | JSON | Math | Number | Object | RegExp | String)
        is : function(type, obj) {
            var clas = Object.prototype.toString.call(obj).slice(8, -1);
            return (obj !== undefined) && (obj !== null) && (clas === type);
        },
        isArray : function(obj) {
            return wtUtil.type.is('Array', obj);
        },
        isString : function(obj) {
            return wtUtil.type.is('String', obj);
        },
        isObject : function(obj) {
            return wtUtil.type.is('Object', obj);
        }
    };

    // 文字種別
    var CTYPE = {
        ALPHA        : 0x01, // 英字
        NUMERIC      : 0x02, // 数字
        ALPHANUMERIC : 0x03, // 英数字
        SYMBOL       : 0x04, // 記号
        ASCII        : 0x07, // 英数字＋記号
        KATAKANA     : 0x08, // カタカナ
        HIRAGANA     : 0x10  // ひらがな
    };

    // カタカナの全角から半角への変換用マップ
    var KATAKANA_Z2H_MAP = {
        '\u30AC':'\uFF76\uFF9E', '\u30AE':'\uFF77\uFF9E', '\u30B0':'\uFF78\uFF9E', '\u30B2':'\uFF79\uFF9E', '\u30B4':'\uFF7A\uFF9E', // ガギグゲゴ ⇒ ｶﾞｷﾞｸﾞｹﾞｺﾞ
        '\u30B6':'\uFF7B\uFF9E', '\u30B8':'\uFF7C\uFF9E', '\u30BA':'\uFF7D\uFF9E', '\u30BC':'\uFF7E\uFF9E', '\u30BE':'\uFF7F\uFF9E', // ザジズゼゾ ⇒ ｻﾞｼﾞｽﾞｾﾞｿﾞ
        '\u30C0':'\uFF80\uFF9E', '\u30C2':'\uFF81\uFF9E', '\u30C5':'\uFF82\uFF9E', '\u30C7':'\uFF83\uFF9E', '\u30C9':'\uFF84\uFF9E', // ダヂヅデド ⇒ ﾀﾞﾁﾞﾂﾞﾃﾞﾄﾞ
        '\u30D0':'\uFF8A\uFF9E', '\u30D3':'\uFF8B\uFF9E', '\u30D6':'\uFF8C\uFF9E', '\u30D9':'\uFF8D\uFF9E', '\u30DC':'\uFF8E\uFF9E', // バビブベボ ⇒ ﾊﾞﾋﾞﾌﾞﾍﾞﾎﾞ
        '\u30D1':'\uFF8A\uFF9F', '\u30D4':'\uFF8B\uFF9F', '\u30D7':'\uFF8C\uFF9F', '\u30DA':'\uFF8D\uFF9F', '\u30DD':'\uFF8E\uFF9F', // パピプペポ ⇒ ﾊﾟﾋﾟﾌﾟﾍﾟﾎﾟ
        '\u30F4':'\uFF73\uFF9E', '\u30F7':'\uFF9C\uFF9E', '\u30FA':'\uFF66\uFF9E',                                                   // ヴヷヺ     ⇒ ｳﾞﾜﾞｦﾞ    
        '\u30A2':'\uFF71',       '\u30A4':'\uFF72',       '\u30A6':'\uFF73',       '\u30A8':'\uFF74',       '\u30AA':'\uFF75',       // アイウエオ ⇒ ｱ ｲ ｳ ｴ ｵ 
        '\u30AB':'\uFF76',       '\u30AD':'\uFF77',       '\u30AF':'\uFF78',       '\u30B1':'\uFF79',       '\u30B3':'\uFF7A',       // カキクケコ ⇒ ｶ ｷ ｸ ｹ ｺ 
        '\u30B5':'\uFF7B',       '\u30B7':'\uFF7C',       '\u30B9':'\uFF7D',       '\u30BB':'\uFF7E',       '\u30BD':'\uFF7F',       // サシスセソ ⇒ ｻ ｼ ｽ ｾ ｿ 
        '\u30BF':'\uFF80',       '\u30C1':'\uFF81',       '\u30C4':'\uFF82',       '\u30C6':'\uFF83',       '\u30C8':'\uFF84',       // タチツテト ⇒ ﾀ ﾁ ﾂ ﾃ ﾄ 
        '\u30CA':'\uFF85',       '\u30CB':'\uFF86',       '\u30CC':'\uFF87',       '\u30CD':'\uFF88',       '\u30CE':'\uFF89',       // ナニヌネノ ⇒ ﾅ ﾆ ﾇ ﾈ ﾉ 
        '\u30CF':'\uFF8A',       '\u30D2':'\uFF8B',       '\u30D5':'\uFF8C',       '\u30D8':'\uFF8D',       '\u30DB':'\uFF8E',       // ハヒフヘホ ⇒ ﾊ ﾋ ﾌ ﾍ ﾎ 
        '\u30DE':'\uFF8F',       '\u30DF':'\uFF90',       '\u30E0':'\uFF91',       '\u30E1':'\uFF92',       '\u30E2':'\uFF93',       // マミムメモ ⇒ ﾏ ﾐ ﾑ ﾒ ﾓ 
        '\u30E4':'\uFF94',                                '\u30E6':'\uFF95',                                '\u30E8':'\uFF96',       // ヤ  ユ  ヨ ⇒ ﾔ   ﾕ   ﾖ 
        '\u30E9':'\uFF97',       '\u30EA':'\uFF98',       '\u30EB':'\uFF99',       '\u30EC':'\uFF9A',       '\u30ED':'\uFF9B',       // ラリルレロ ⇒ ﾗ ﾘ ﾙ ﾚ ﾛ 
        '\u30EF':'\uFF9C',                                '\u30F2':'\uFF66',                                '\u30F3':'\uFF9D',       // ワ  ヲ  ン ⇒ ﾜ   ｦ   ﾝ 
        '\u30A1':'\uFF67',       '\u30A3':'\uFF68',       '\u30A5':'\uFF69',       '\u30A7':'\uFF6A',       '\u30A9':'\uFF6B',       // ァィゥェォ ⇒ ｧ ｨ ｩ ｪ ｫ 
        '\u30C3':'\uFF6F',       '\u30E3':'\uFF6C',       '\u30E5':'\uFF6D',       '\u30E7':'\uFF6E',                                // ッャュョ   ⇒ ｯ ｬ ｭ ｮ   
        '\u3002':'\uFF61',       '\u3001':'\uFF64',       '\u300C':'\uFF62',       '\u300D':'\uFF63',       '\u30FB':'\uFF65',       // 。、「」・ ⇒ ｡ ､ ｢ ｣ ･ 
        '\u30FC':'\uFF70'                                                                                                            // ー ⇒ ｰ (長音)
    };

    // カタカナの半角から全角への変換用マップ
    // ※全角⇒半角マップから動的に生成する場合(flip)
    // var KATAKANA_H2Z_MAP = {};
    // for (var z in KATAKANA_Z2H_MAP) {
    //     if (KATAKANA_Z2H_MAP.hasOwnProperty(z)) {
    //         KATAKANA_H2Z_MAP[KATAKANA_Z2H_MAP[z]] = z;
    //     }
    // }
    var KATAKANA_H2Z_MAP = {
        '\uFF76\uFF9E':'\u30AC', '\uFF77\uFF9E':'\u30AE', '\uFF78\uFF9E':'\u30B0', '\uFF79\uFF9E':'\u30B2', '\uFF7A\uFF9E':'\u30B4', // ｶﾞｷﾞｸﾞｹﾞｺﾞ ⇒ ガギグゲゴ
        '\uFF7B\uFF9E':'\u30B6', '\uFF7C\uFF9E':'\u30B8', '\uFF7D\uFF9E':'\u30BA', '\uFF7E\uFF9E':'\u30BC', '\uFF7F\uFF9E':'\u30BE', // ｻﾞｼﾞｽﾞｾﾞｿﾞ ⇒ ザジズゼゾ
        '\uFF80\uFF9E':'\u30C0', '\uFF81\uFF9E':'\u30C2', '\uFF82\uFF9E':'\u30C5', '\uFF83\uFF9E':'\u30C7', '\uFF84\uFF9E':'\u30C9', // ﾀﾞﾁﾞﾂﾞﾃﾞﾄﾞ ⇒ ダヂヅデド
        '\uFF8A\uFF9E':'\u30D0', '\uFF8B\uFF9E':'\u30D3', '\uFF8C\uFF9E':'\u30D6', '\uFF8D\uFF9E':'\u30D9', '\uFF8E\uFF9E':'\u30DC', // ﾊﾞﾋﾞﾌﾞﾍﾞﾎﾞ ⇒ バビブベボ
        '\uFF8A\uFF9F':'\u30D1', '\uFF8B\uFF9F':'\u30D4', '\uFF8C\uFF9F':'\u30D7', '\uFF8D\uFF9F':'\u30DA', '\uFF8E\uFF9F':'\u30DD', // ﾊﾟﾋﾟﾌﾟﾍﾟﾎﾟ ⇒ パピプペポ
        '\uFF73\uFF9E':'\u30F4', '\uFF9C\uFF9E':'\u30F7', '\uFF66\uFF9E':'\u30FA',                                                   // ｳﾞﾜﾞｦﾞ     ⇒ ヴヷヺ    
        '\uFF71':'\u30A2',       '\uFF72':'\u30A4',       '\uFF73':'\u30A6',       '\uFF74':'\u30A8',       '\uFF75':'\u30AA',       // ｱ ｲ ｳ ｴ ｵ  ⇒ アイウエオ
        '\uFF76':'\u30AB',       '\uFF77':'\u30AD',       '\uFF78':'\u30AF',       '\uFF79':'\u30B1',       '\uFF7A':'\u30B3',       // ｶ ｷ ｸ ｹ ｺ  ⇒ カキクケコ
        '\uFF7B':'\u30B5',       '\uFF7C':'\u30B7',       '\uFF7D':'\u30B9',       '\uFF7E':'\u30BB',       '\uFF7F':'\u30BD',       // ｻ ｼ ｽ ｾ ｿ  ⇒ サシスセソ
        '\uFF80':'\u30BF',       '\uFF81':'\u30C1',       '\uFF82':'\u30C4',       '\uFF83':'\u30C6',       '\uFF84':'\u30C8',       // ﾀ ﾁ ﾂ ﾃ ﾄ  ⇒ タチツテト
        '\uFF85':'\u30CA',       '\uFF86':'\u30CB',       '\uFF87':'\u30CC',       '\uFF88':'\u30CD',       '\uFF89':'\u30CE',       // ﾅ ﾆ ﾇ ﾈ ﾉ  ⇒ ナニヌネノ
        '\uFF8A':'\u30CF',       '\uFF8B':'\u30D2',       '\uFF8C':'\u30D5',       '\uFF8D':'\u30D8',       '\uFF8E':'\u30DB',       // ﾊ ﾋ ﾌ ﾍ ﾎ  ⇒ ハヒフヘホ
        '\uFF8F':'\u30DE',       '\uFF90':'\u30DF',       '\uFF91':'\u30E0',       '\uFF92':'\u30E1',       '\uFF93':'\u30E2',       // ﾏ ﾐ ﾑ ﾒ ﾓ  ⇒ マミムメモ
        '\uFF94':'\u30E4',                                '\uFF95':'\u30E6',                                '\uFF96':'\u30E8',       // ﾔ   ﾕ   ﾖ  ⇒ ヤ  ユ  ヨ
        '\uFF97':'\u30E9',       '\uFF98':'\u30EA',       '\uFF99':'\u30EB',       '\uFF9A':'\u30EC',       '\uFF9B':'\u30ED',       // ﾗ ﾘ ﾙ ﾚ ﾛ  ⇒ ラリルレロ
        '\uFF9C':'\u30EF',                                '\uFF66':'\u30F2',                                '\uFF9D':'\u30F3',       // ﾜ   ｦ   ﾝ  ⇒ ワ  ヲ  ン
        '\uFF67':'\u30A1',       '\uFF68':'\u30A3',       '\uFF69':'\u30A5',       '\uFF6A':'\u30A7',       '\uFF6B':'\u30A9',       // ｧ ｨ ｩ ｪ ｫ  ⇒ ァィゥェォ
        '\uFF6F':'\u30C3',       '\uFF6C':'\u30E3',       '\uFF6D':'\u30E5',       '\uFF6E':'\u30E7',                                // ｯ ｬ ｭ ｮ    ⇒ ッャュョ  
        '\uFF61':'\u3002',       '\uFF64':'\u3001',       '\uFF62':'\u300C',       '\uFF63':'\u300D',       '\uFF65':'\u30FB',       // ｡ ､ ｢ ｣ ･  ⇒ 。、「」・
        '\uFF70':'\u30FC'                                                                                                            // ｰ ⇒ ー (長音)
    };

    // 文字列処理系
    wtUtil.string = {
        CTYPE : CTYPE,
        // トリム処理 (マルチバイト＋指定文字対応)
        trim : function(s, charMask) {
            var charList = [];
            if (wtUtil.type.isString(charMask)) {
                var charList = [];
                for (var i = 0; i < charMask.length; i++) {
                    charList[i] = charMask.charAt(i);
                }
            } else if (wtUtil.type.isArray(charMask)) {
                charList = charMask;
            } else { // (typeof charMask === 'undefined')
                charList = [ '\\s', '\\u3000' ];
            }
            var words = charList.join('|');
            return s.replace(new RegExp('^[' + words + ']+|[' + words + ']+$', 'g'), '');
        },
        hiragana2katakana : function(s) {
            return s.replace(/[\\u3041-\\u3096]/g, function(s) {
                return String.fromCharCode(s.charCodeAt(0) + 0x0060);
            });
        },
        katakana2hiragana : function(s) {
            return s.replace(/[\\u30A1-\\u30F6]/g, function(s) {
                return String.fromCharCode(s.charCodeAt(0) - 0x0060);
            });
        },
        // 全角から半角に変換
        zen2han : function(s, ctype) {
            if (typeof ctype === 'undefined') {
                // デフォルトは英数字
                ctype = CTYPE.ALPHANUMERIC;
            }
            var c = '';
            if (ctype === CTYPE.ASCII) {
                c = '\\uFF01-\\uFF5E';
            } else {
                if (ctype & CTYPE.ALPHA) {
                    c += '\\uFF21-\\uFF3A\\uFF41-\\uFF5A';
                }
                if (ctype & CTYPE.NUMERIC) {
                    c += '\\uFF10-\\uFF19';
                }
                if (ctype & CTYPE.SYMBOL) {
                    c += '\\uFF01-\\uFF0F\\uFF1A-\\uFF20\\uFF3B-\\uFF40\\uFF5B-\\uFF5E';
                }
            }
            // 英数記号の半角化(Unicodeでのオフセット-0xFEE0)
            var hv = s.replace(new RegExp('[' + c + ']', 'g'), function(s) {
                return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
            });
            if (ctype & CTYPE.KATAKANA) {
                // カタカナはデフォルトのASCIIとは別にする(特殊)
                var r = new RegExp('(' + Object.keys(KATAKANA_Z2H_MAP).join('|') + ')', 'g');
                hv = hv.replace(r, function(match) {
                    return KATAKANA_Z2H_MAP[match];
                });
            }
            if (ctype & CTYPE.SYMBOL) {
                // 文字コードシフトで対応できない文字の変換
                var replaceMap = {
                    '\u3000' : '\u0020', // 全角スペース ⇒ 半角スペース
                    '\u201C' : '\u0022', // U+201C: LEFT DOUBLE QUOTATION MARK  ⇒ U+0022: QUOTATION MARK  (“ ⇒ ")
                    '\u201D' : '\u0022', // U+201D: RIGHT DOUBLE QUOTATION MARK ⇒ U+0022: QUOTATION MARK  (” ⇒ ")
                    '\u2018' : '\u0060', // U+2018: LEFT SINGLE QUOTATION MARK  ⇒ U+0060: GRAVE ACCENT    (‘ ⇒ `)
                    '\u2019' : '\u0027', // U+2019: RIGHT SINGLE QUOTATION MARK ⇒ U+0027: APOSTROPHE      (’ ⇒ ')
                    '\uFFE5' : '\u005C', // U+FFE5: FULLWIDTH YEN SIGN          ⇒ U+005C: REVERSE SOLIDUS (￥ ⇒ \)
                    '\u301C' : '\u007E', // U+301C: WAVE DASH                   ⇒ U+007E: TILDE           (〜 ⇒ ~)
                    // まぎらわしいものを半角ハイフンに統一
                    '\u2010' : '\u002D', // U+2010: HYPHEN         (‐)
                    '\u2212' : '\u002D', // U+2212: MINUS SIGN     (−)
                    '\u2012' : '\u002D', // U+2012: FIGURE DASH    (‒)
                    '\u2014' : '\u002D', // U+2014: EM DASH        (—)
                    '\u2013' : '\u002D', // U+2013: EN DASH        (–)
                    '\u2015' : '\u002D'  // U+2015: HORIZONTAL BAR (―)
                };
                var r = new RegExp('(' + Object.keys(replaceMap).join('|') + ')', 'g');
                hv = hv.replace(r, function(match) {
                    return replaceMap[match];
                });
            }
            return hv;
        },
        zen2hanAlpha : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.ALPHA);
        },
        zen2hanNumeric : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.NUMERIC);
        },
        zen2hanSymbol : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.SYMBOL);
        },
        zen2hanAlphaNumeric : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.ALPHANUMERIC);
        },
        zen2hanAscii : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.ASCII);
        },
        zen2hanKatakana : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.KATAKANA);
        },
        zen2hanAll : function(s) {
            return wtUtil.string.zen2han(s, CTYPE.ASCII | CTYPE.KATAKANA);
        },

        // 半角から全角に変換
        han2zen : function(s, ctype) {
            if (typeof ctype === 'undefined') {
                // デフォルトは英数字
                ctype = CTYPE.ALPHANUMERIC;
            }
            var c = '';
            if (ctype === CTYPE.ASCII) {
                c = '\\u0021-\\u007E';
            } else {
                if (ctype & CTYPE.ALPHA) {
                    c += 'A-Za-z';
                }
                if (ctype & CTYPE.NUMERIC) {
                    c += '0-9';
                }
                if (ctype & CTYPE.SYMBOL) {
                    c += '\\u0021-\\u002F\\u003A-\\u0040\\u005B-\\u0060\\u007B-\\u007E';
                }
            }
            // 英数記号の全角化(Unicodeでのオフセット+0xFEE0)
            var zv = s.replace(new RegExp('[' + c + ']', 'g'), function(s) {
                return String.fromCharCode(s.charCodeAt(0) + 0xFEE0);
            });
            if (ctype & CTYPE.KATAKANA) {
                // カタカナはデフォルトのASCIIとは別にする(特殊)
                var r = new RegExp('(' + Object.keys(KATAKANA_H2Z_MAP).join('|') + ')', 'g');
                zv = zv.replace(r, function(match) {
                    return KATAKANA_H2Z_MAP[match];
                });
            }
            if (ctype & CTYPE.SYMBOL) {
                // 文字コードシフトで対応できない文字の変換
                var replaceMap = {
                    '\u0020' : '\u3000', // 半角スペース ⇒ 全角スペース
                    '\uFF02' : '\u201D', // U+FF02: FULLWIDTH QUOTATION MARK ⇒ U+201D: RIGHT DOUBLE QUOTATION MARK (＂ ⇒ ”)
                    '\uFF07' : '\u2019', // U+FF07: FULLWIDTH APOSTROPHE     ⇒ U+2019: RIGHT SINGLE QUOTATION MARK (＇ ⇒ ’)
                    // まぎらわしいものを全角ハイフンマイナスに統一 (U+FF0D: FULLWIDTH HYPHEN-MINUS)
                    '\u2010' : '\uFF0D', // U+2010: HYPHEN         (‐)
                    '\u2212' : '\uFF0D', // U+2212: MINUS SIGN     (−)
                    '\u2012' : '\uFF0D', // U+2012: FIGURE DASH    (‒)
                    '\u2014' : '\uFF0D', // U+2014: EM DASH        (—)
                    '\u2013' : '\uFF0D', // U+2013: EN DASH        (–)
                    '\u2015' : '\uFF0D'  // U+2015: HORIZONTAL BAR (―)
                };
                var r = new RegExp('(' + Object.keys(replaceMap).join('|') + ')', 'g');
                zv = zv.replace(r, function(match) {
                    return replaceMap[match];
                });
            }
            return zv;
        },
        han2zenAlpha : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.ALPHA);
        },
        han2zenNumeric : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.NUMERIC);
        },
        han2zenSymbol : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.SYMBOL);
        },
        han2zenAlphaNumeric : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.ALPHANUMERIC);
        },
        han2zenAscii : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.ASCII);
        },
        han2zenKatakana : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.KATAKANA);
        },
        han2zenAll : function(s) {
            return wtUtil.string.han2zen(s, CTYPE.ASCII | CTYPE.KATAKANA);
        },
        // 数字を千位毎にグループ化してフォーマットする
        // ※php の number_format() に該当
        numberFormat : function(number) {
            var strNum = new String(number).replace(/,/g, '');
            while (strNum != (strNum = strNum.replace(/^(-?\d+)(\d{3})/, '$1,$2')));
            return strNum;
        }
    };

    wtUtil.html = {
        escape : function(s) {
            return s
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        },
        unescape : function(s) {
            return s
                .replace(/&amp;/g,  '&')
                .replace(/&lt;/g,   '<')
                .replace(/&gt;/g,   '>')
                .replace(/&quot;/g, '"')
                .replace(/&#39;/g,  "'");
        },
        stripTags : function(s) {
            return s.replace(/<\w+(\s+("[^"]*"|'[^']*'|[^>])+)?(\/)?>|<\/\w+>/gi, '');
        },
        highlight : function(text, kw, color) {
            // 文字列のハイライト処理(全半角大文字小文字)
            if ((typeof color === 'undefined') || !color) {
                color = '#ff0000';
            }
            var ut = wtUtil.string.zen2han(text, CTYPE.ASCII).toUpperCase();
            var uk = wtUtil.string.zen2han(kw,   CTYPE.ASCII).toUpperCase();
            // 大文字化によるlengthの変化('\uFB02'など)は考慮しない
            var pos, result = '';
            while ((pos = ut.indexOf(uk)) >= 0) {
                if (pos) {
                    result += text.substring(0, pos);
                    text = text.substring(pos);
                }
                kw = text.substring(0, kw.length);
                text = text.substring(kw.length);
                result += '<span style="color:' + color + '">' + kw + '</span>';
                ut = ut.substring(pos + uk.length);
            }
            return result + text;
        }
    };

    global.wtUtil=wtUtil;
})(this);
