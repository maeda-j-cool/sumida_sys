<!DOCTYPE html>
<html class="html" lang="ja">

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">
  {include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"gtm_head.tpl"}
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta property="og:image" content="/assets/img/ogp.jpg">
  <title>ログイン | {$smarty.const.HTML_TITLE_SUFFIX|escape}</title>
  <meta name="description"
    content="「墨田区ファーストバースデーサポート」は墨田区で子育てをスタートするご家庭に「出産や子育てを応援するギフト」をお贈りする国および墨田区の事業です。ポイント制で家庭ごとにカスタマイズ可能な商品選択、生活ステージに応じた全面サポート。最新情報、FAQ、地域別支援情報も盛りだくさん。" />
  <link rel="icon" href="/assets/img/favicon/tmp/icon-32.ico">
  <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;700&display=swap" rel="stylesheet">
  <link rel="icon" href="/assets/img/MacOSX/icon_32x32.png">
  <link rel="icon" href="/assets/img/Windows8/icon-50.png">
  <link rel="icon" href="/assets/img/WindowsXP/tmp/icon-32.ico">
  <link rel="icon" href="/assets/img/favicon/tmp/icon-48.ico">
  <link rel="apple-touch-icon" sizes="36x36" href="/assets/img/Android/drawable_ldpi/ic_launcher.png">
  <link rel="apple-touch-icon" sizes="29x29" href="/assets/img/iOS/Icon-Small.png">
  <link rel="apple-touch-icon" sizes="29x29" href="/assets/img/iOS7/Icon-Small.png">
  <link rel="apple-touch-icon" sizes="62x62" href="/assets/img/WindowsPhone/for_application/ApplicationIcon.png">
  <link rel="apple-touch-icon" sizes="99x99" href="/assets/img/WindowsPhone/for_marketplace/AppName-99.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/apple-touch-icons/apple-touch-icon-60x60.png">
  <link rel="canonical" href="https://nakano-birthday.smart-gift.net/">
  <link rel="stylesheet" href="/assets/css/reset.css">
  <link rel="stylesheet" href="/assets/css/common.css">
  <link rel="stylesheet" href="/assets/css/common-2.css">
  <link rel="stylesheet" href="/assets/css/layout.css">
  <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body id="body">
  {include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"gtm_body.tpl"}
  <div class="Wrapper">
    <header class="header">
      <div class="header_inner">
      </div>
    </header>

    <main id="top-main">
    <article class="mv-top">
        <div class="mv-top__bg">
        <div class="mv-top__inner">
            <div class="title-box">
                <h1 class="title"><span class="span">
                        <picture class="title__image">
                            <source media="(max-width: 768px)" srcset="/assets/img/sp/mv-text_sp.png">
                            <source media="(min-width: 769px)" srcset="/assets/img/mv-text.png">
                            <img src="/assets/img/mv-text.png" alt="墨田区ファーストバースデーサポート">
                        </picture>
                    </span></h1>
            </div>
            <div class="top-explanation">
                <section class="explanation">
                    <p>初めてご利用になる方は、案内書をお手元にご用意のうえ、<br>
                        以下のボタンから利用者登録とアンケートにお進みください</p>
                </section>
                <div class="btn-wrap">
                    <p>
                        <a class="act--register btn-first" href="javascript:void(0);">初回登録</a>
                    </p>
                    <p>
                        <span class="note">初回登録がお済みの方は、以下のボタンからログインしてください。</span>
                        <a class="act--login btn-login" href="javascript:void(0);">ログイン</a>
                    </p>
                </div>
                <div class="top-others">
                    <p id="act-preview" class="top-others__hover">交換はせず商品を見たい方はこちら</p>
                </div>
            </div>
        </div>
        </div>
    </article>
</main>
  </div>
  <footer id="footer">
    <div class="topfooter_bg">
    <section class="inquiry">
        <div class="inquiry__inner-top">
            <h3 class="title">墨田区ファーストバースデー<br class="pc-none">サポートお客様センター
            </h3>
            <p>フリーダイヤル 0800-100-1265</p>
            <p>受付時間9:00~17:00<br class="pc-none">(土日祝日、お盆8/10～8/18、年末年始12/31～1/5除く)</p>
        </div>
    </section>
    </div>
  </footer>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
  <script src="/assets/js/common.js"></script>
  <script src="/assets/js/text-size.js"></script>
  <form method="post" id="login-form" action="{wt_action_url mod='Default' act='Login'}">
    <div class="modal-container" id="modal">
      <div class="modal-body">
        <div class="modal-content">
          <div class="modal-content__wrapper">
            <span class="modal-close"><img alt="モーダルクローズボタン" src="/assets/img/modal-close.png" /></span>
            <div id="modal-contents"></div>
            <div id="modal-loading" style="text-align:center;">
              <img src="/images/loading_80_80.gif" alt="" width="80" height="80">
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div id="error-message-area" style="display:none;">
    <div class="p-login__alert--mark"><img src="/assets/img/alert.png" alt="注意マーク"></div>
    <ul class="p-login__alert--list ul-error">
      <li></li>
    </ul>
    <div class="p-login__alert--damy"></div>
  </div>

  {* ---------------------------------------------------------------------------------------- *}
  {* 2 - IDについて *}
  {* ---------------------------------------------------------------------------------------- *}
  <div id="popup_sample" class="p-login_mobal guide">
    <div class="layer_board_bg"></div>
    <div class="layer_board">
      <h2>ID番号・パスワードについて</h2>
      <p class="popup_sample_txt">
        ID番号・パスワードはご案内状に記載されています。<br>
        下図の赤枠で示した部分をご確認ください。
      </p>
      <p><img src="/assets/img/login/post_sample.png" alt="ご案内状"></p>
      <a href="#" class="btn_close">×</a>
    </div>
  </div>
  {* ---------------------------------------------------------------------------------------- *}
  {* 2 - 初回登録フォーム *}
  {* ---------------------------------------------------------------------------------------- *}
  <div style="display:none;" id="d-register">
    <h2 class="l-section__modaltitle">初回登録</h2>
    <div class="p-login__form">
      <div class="p-login__alert errors" style="display:none;"></div>
      <div class="p-login__form--newUser">
        <dl class="p-login__form--wrap">
          <dt>ID番号</dt>
          <dd>
            <input type="number" name="register_id" value="" class="p-login__form--box" autocomplete="off" pattern="\d+">
          </dd>
        </dl>
        <dl class="p-login__form--wrap">
          <dt>配布パスワード</dt>
          <dd>
            <input type="password" name="register_password" value="" class="p-login__form--box" autocomplete="off">
          </dd>
        </dl>
      </div>
      <div class="modal-onetime-box">
        <button type="submit" class="p-login__form--btn modal-onetime" disabled>利用者情報登録へ</button>
      </div>
    </div>
    <div class="p-login__info">
      <p>※有効期限を過ぎますとポイントは失効します。</p>
    </div>
    <div class="p-login__cap">
      <p>
        ID番号・パスワードはご案内状に記載されています。<br class="pc" />
        詳しくは <a class="p-login__cap--btn2 normal-link p-login__poplink" data-target="popup_sample"
          href="javascript:void(0);">こちら</a> をご覧ください。
      </p>
      <!-- <p>
        ログインに複数回失敗しますと、アカウントがロックされます。<br />
        アカウントロックの解除については、<br class="pc" />フリーコール（{$settings.call_center_phone|escape}）へお問い合わせください。
      </p> -->
    </div>
  </div>
  {* ---------------------------------------------------------------------------------------- *}
  {* 2 - ログイン入力フォーム *}
  {* ---------------------------------------------------------------------------------------- *}
  <div style="display:none;" id="d-login">
    <h2 class="l-section__modaltitle">ログイン</h2>
    <div class="p-login__form">
      <div class="p-login__alert errors" style="display:none;"></div>
      <div class="p-login__form--newUser">
        <dl class="p-login__form--wrap">
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="login_id" value="" class="p-login__form--box" autocomplete="off">
          </dd>
        </dl>
        <dl class="p-login__form--wrap">
          <dt>ユーザーパスワード</dt>
          <dd>
            <input type="password" name="login_password" value="" class="p-login__form--box" autocomplete="off">
          </dd>
          <dd class="pass-resetting">
            ※パスワードをお忘れの方は<a href="javascript:void(0);" id="act-reissue">こちら</a>
          </dd>
        </dl>
      </div>
      <div class="modal-btn">
        <button type="submit" class="p-login__form--btn modal-onetime" disabled>
          <span class="pc">ワンタイムパスワードをおくる</span><span class="sp">ログイン</span>
        </button>
      </div>
    </div>
    <div class="p-login__info">
      <p>※有効期限を過ぎますとポイントは失効します。</p>
    </div>
    <!-- <div class="p-login__cap">
      <p>
        ログインに複数回失敗しますと、アカウントがロックされます。<br />
        アカウントロックの解除については、<br class="pc" />
        フリーコール({$settings.call_center_phone|escape})へお問い合わせください。
      </p>
    </div> -->
  </div>
  {* ---------------------------------------------------------------------------------------- *}
  {* 3−1 - ゲスト認証コード入力 *}
  {* ---------------------------------------------------------------------------------------- *}
  <div style="display:none;" id="d-inputmail">
    <h2 class="l-section__modal__title">ご利用するメールアドレスを入力してください。</h2>
    <div class="p-login__form">
      <div class="p-login__alert errors" style="display:none;"></div>
      <div class="p-login__form--newUser mt20">
        <dl class="p-login__form--wrap">
          <dd>
            <input type="text" name="register_email" value="" class="p-login__form--box" autocomplete="off">
          </dd>
        </dl>
      </div>
      <div class="modal-btn sp">
        <button type="submit" class="p-login__form--btn modal-onetimeFix" disabled>ワンタイムパスワードを送る</button>
      </div>
      <div class="p-login__note">
        <p>
          ※「＠」の直前にドットのあるメールアドレスや、連続でドットを含む<br class="pc">
          　メールアドレスはご利用いただけません。<br>
          　例）sample.@sample.net、sample...sample@sample.net など
        </p>
        <p>
          ※ここで登録したメールアドレスは今後ワンタイムパスワードを<br class="pc">
          　受け取るために利用いたします。変更などはできませんのでご注意ください。
        </p>
      </div>
      <div class="modal-btn pc">
        <button type="submit" class="p-login__form--btn modal-onetimeFix" disabled>ワンタイムパスワードを送る</button>
      </div>
    </div>
  </div>
  {* ---------------------------------------------------------------------------------------- *}
  {* 4 - ワンタイム認証コード入力 *}
  {* ---------------------------------------------------------------------------------------- *}
  <div style="display:none;" id="d-inputcode">
    <h2 class="l-section__modal__subtitle">
      <span class="mailmessage"></span><br>
      メールに記載されたワンタイムパスワードをご入力ください。
    </h2>
    <div class="p-login__form">
      <div class="p-login__alert errors" style="display:none;"></div>
      <div class="p-login__form--newUser mt20">
        <dl class="p-login__form--wrap">
          <dd>
            <input type="number" name="onetime_code" value="" class="p-login__form--box" autocomplete="off" pattern="\d+">
          </dd>
        </dl>
      </div>
      <div class="p-login__note p-login__note__center">
        <div>
          <p class="c_text-indent">
            ※ワンタイムパスワードの送信先メールアドレスを変更されたい場合</p>
          <p class="c_text-indent">・<span class="p-login__captext">利用者登録が完了していない方</span>：<br>初めから入力してください。</p>
          <p class="c_text-indent">・<span class="p-login__captext">利用者登録済の方</span>：<br><a href="{wt_action_url mod="Toiawase" act="Toiawase" }" class="p-login__cap--btn2 normal-link" target="_blank">こちら</a>からお問い合わせください。
          </p>
        </div>


      </div>
      <div class="modal-btn">
        <button type="submit" class="p-login__form--btn" disabled>送信する</button>
      </div>
    </div>
    <div class="p-login__cap p-login__cap--code">
      <p>
        <a href="javascript:void(0);" id="act-resendmail" class="p-login__cap--btn2">ワンタイムパスワードを再送する</a>
      </p>
    </div>
  </div>
  {* ---------------------------------------------------------------------------------------- *}
  {* 2 - ログイン入力フォーム - パスワードをお忘れの方はこちら *}
  {* ---------------------------------------------------------------------------------------- *}
  <div style="display:none;" id="d-reissue">
    <h2 class="l-section__modaltitle">パスワードをお忘れの場合</h2>
    <div class="p-login__cap">
      <p>
        会員登録いただいた際のメールアドレスと電話番号を入力し、送信してください。<br>
        パスワード再設定用のワンタイムパスワードを登録メールに送信いたします。
      </p>
    </div>
    <div class="p-login__form">
      <div class="p-login__alert errors" style="display:none;"></div>
      <div class="p-login__form--newUser p-login__form--edit">
        <dl class="p-login__form--wrap">
          <dt>メールアドレス</dt>
          <dd>
            <input type="email" name="reissue_mail" value="" class="p-login__form--box" autocomplete="off" maxlength="129">
          </dd>
        </dl>
        <dl class="p-login__form--wrap">
          <dt>電話番号</dt>
          <dd>
            <input type="tel" name="reissue_tel" value="" class="p-login__form--box" autocomplete="off">
          </dd>
          <span class="p-login__form--sideNote">※ハイフン（-）なしでご入力ください。</span>
        </dl>
        <dl class="p-login__form--wrap">
          <dt>お子様のお名前</dt>
          <dd>
            姓<input type="text" name="reissue_c_sei" value="" class="p-login__form--box" autocomplete="off" maxlength="8" style="width:42%;margin-left:10px;">
            &nbsp;
            名<input type="text" name="reissue_c_mei" value="" class="p-login__form--box" autocomplete="off" maxlength="8" style="width:42%;margin-left:10px;">
          </dd>
        </dl>
      </div>
      <div class="modal-btn">
        <button type="submit" class="p-login__form--btn" disabled>ワンタイムパスワードを送る</button>
      </div>
    </div>
  </div>
  {* ---------------------------------------------------------------------------------------- *}
  {* エラー用 *}
  {* ---------------------------------------------------------------------------------------- *}
  <div style="display:none;" id="d-error">
    <p class="error-message"></p>
  </div>
  <input type="hidden" id="token" value="{$ajax_token}">
  <input type="hidden" id="init-action" value="{$init_action}">
  <input type="hidden" id="qr-gcno" value="{$qr_gcno|escape}">
  <input type="hidden" id="qr-pin" value="{$qr_pin|escape}">
  <script src="/js/login.js"></script>
  <script>
    {* common.jsから移動：他画面でこれが設定されているとJSエラーになるので *}

    // スクロールするとナビの色変更
    $(function () {
      $(window).on("scroll", function () {
        const questionTop = $(".top-thanks").offset().top;
        if (questionTop < $(this).scrollTop()) {
          $(".button_span").addClass("headerLogoScroll");
        } else {
          $(".button_span").removeClass("headerLogoScroll");
        }
      });
    });
    document.querySelector('.modal-container').addEventListener('click', function (e) {
      if (e.target.classList.contains('p-login__poplink')) {
        $('#popup_sample').fadeIn();
        return false;
      }
    });
    var $sample = $('#popup_sample');

    $('.btn_close', $sample).on('click', function () {
      $sample.fadeOut();
      return false;
    });
    $('.layer_board_bg', $sample).on('click', function () {
      $sample.fadeOut();
      return false;
    });
  </script>
    <style>
      .stt-lang-select {
        display: block !important;
      }
    </style>
	
</body>

</html>