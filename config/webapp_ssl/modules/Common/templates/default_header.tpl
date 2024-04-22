{if $wt__is_login || $is_virtual}
{*-- ログイン済ヘッダ --*}
<header class="header header_bg">
    <div class="guestsBar">
        <div class="guestsBar__inner">
            <div class="leftColumn">
                <div class="l-navitem ac-pc">
                    <a href="javascript:void(0);">
                        <span class="material-icons-outlined" data-stt-ignore="">language</span>
                    </a>
                    <ul class="ac-pc_under">
                        <li>
                            <a href="#" data-stt-changelang="ja" data-stt-ignore="" data-stt-active="">日本語</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="id" data-stt-ignore="" data-stt-active="">Indonesia</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="es" data-stt-ignore="" data-stt-active="">Español</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="tl" data-stt-ignore="" data-stt-active="">Tagalog</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="vi" data-stt-ignore="" data-stt-active="">Tiếng Việt</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="pt" data-stt-ignore="" data-stt-active="">Português</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="en" data-stt-ignore="">English</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="ko" data-stt-ignore="">한국</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="zh-CN" data-stt-ignore="">简体中文</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="zh-TW" data-stt-ignore="">繁體中文</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="ur" data-stt-ignore="">اردو</a>
                        </li>
                    </ul>
                </div>
                <p class="leftColumn__text">
                    <a>ようこそ<span data-stt-ignore>{$user_name|escape}さん</span></a>
                </p>
                <div class="bl_sizeBtn_wrap">
                    <span class="span">文字サイズ</span>
                    <button type="button" class="gu_sizeBtn" id="f_lg">大</button>
                    <button type="button" class="gu_sizeBtn is_active" id="f_md">中</button>
                    <button type="button" class="gu_sizeBtn" id="f_sm">小</button>
                </div>
            </div>
{if !$is_virtual}
            <div class="rightColumn">
{if $expiry_ymd}
                <p class="rightColumn_deadline">
                    <a href="{wt_action_url mod="" act=""}/CardList/CardList/#cardlist">有効期限<span>{$expiry_ymd|wt_date_format:'Y年m月d日(D)'}</span>まで</a>
                </p>
{/if}
                <p class="rightColumn_points">
                    保有ポイント<span>{$remain_point|number_format}</span>pt
                </p>
            </div>
{/if}
        </div>
    </div>
{if isset($header_banner_message) && $header_banner_message}
    <div id="header_pickup_banner">
        <a href="{$header_banner_link|escape}" class="pr">{$header_banner_message|escape|nl2br}</a>
    </div>
{/if}
    <script>
        $(document).ready(function() {
            if ($(window).width() <= 768) {
                var $element = $('#header_pickup_banner');
                $('main').prepend($element);
            }
        });
        </script>
    <div class="utility">
        <form action="{wt_action_url mod="" act=""}" method="post" id="search">
            <input type="search" name="keyword" class="search_input" placeholder="なにをお探しですか？">
            <input type="submit" name="BTN_SEARCH" value="" class="search-magnifier">
        </form>
        <div class="utility_navigation">
            <ul class="utility_navigation_list">
{if !$is_virtual}
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='ShukaJyokyo' act='ShukaJyokyo'}"><img src="/assets/img/icon_change.svg" alt="交換履歴">
                        <p>交換履歴</p>
                    </a>
                </li>
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='ShohinRireki' act='ShohinCheckRirekiCookie'}"><img src="/assets/img/icon_eyes.svg" alt="閲覧履歴">
                        <p>閲覧履歴</p>
                    </a>
                </li>
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='OkiniiriIchiran' act='OkiniiriIchiran'}"><img src="/assets/img/icon_favorite.svg" alt="お気に入り">
                        <p>お気に入り</p>
                    </a>
                </li>
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='Default' act='Mypage'}"><img src="/assets/img/icon_mypage.svg" alt="マイページ">
                        <p>マイページ</p>
                    </a>
                </li>
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='Order' act='OrdererInfoInput'}"><img src="/assets/img/icon_cart.svg" alt="カート">
                        <p>カート</p>
                    </a>
                </li>
{/if}
                <li class="utility_navigation_item">
                    <div class="button_pcNav"><span></span><span></span><span></span></div>
                    <ul class="nav_pcTop">
                        <li class="nav__list"><a href="{wt_action_url mod="" act=""}#top-connection">墨田区の子育て支援情報</a></li>
{if !$is_virtual}
                        <li class="nav__list"><a href="{wt_action_url mod='Default' act='Mypage'}">マイページ</a></li>
{/if}
                        <li class="nav__list"><a href="/guide/faq/">よくある質問</a></li>
{if !$is_virtual}
                        <li class="nav__list"><a href="{wt_action_url mod='ShukaJyokyo' act='ShukaJyokyo'}">交換履歴</a></li>
                        <li class="nav__list"><a href="{wt_action_url mod='ShohinRireki' act='ShohinCheckRirekiCookie'}">閲覧履歴</a></li>
{/if}

                        <!-- <li class="nav__list pc-none"><a href="#">Language</a></li> -->
                        <!-- <li class="nav__list"><a href="#">文字サイズ</a></li> -->
                        <!-- <li class="nav__list"><a href="/guide/">ご利用ガイド</a></li> -->
                        <!-- <li class="nav__list"><a href="/support/">健康相談サポートダイヤル</a></li> -->
                        <!-- <li class="nav__list"><a href="/privacy/">プライバシーポリシー</a></li> -->
                        <!-- <li class="nav__list"><a href="{wt_action_url app="webapp" mod="Withdraw" act="Withdrawal"}">ポイント利用辞退申請</a></li> -->
                        <li class="nav__list"><a href="{wt_action_url mod='Default' act='Logout'}">ログアウト</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="citizen_wrap">
            <!-- <p class="citizen"><span class="citizen__span">{$settings.kenshu_name}民の</span></p> -->
            <a href="{wt_action_url mod="" act=""}"><img src="/assets/img/logo_after.svg" alt="墨田区ファーストバースデーサポート"></a>
        </div>
    </div>
    <div class="sp-utility">
        <div class="utility_navigation">
            <h1 class="utility_navigation_logo">
                <!-- <p class="citizen"><span class="citizen__span">{$settings.kenshu_name}民の</span></p> -->
                <a href="{wt_action_url mod="" act=""}"><img src="/assets/img/sp/logo_after.svg" alt="{$settings.kenshu_name}民の墨田区ファーストバースデーサポート" class="citizen_img" width="200"></a>
            </h1>
            <ul class="utility_navigation_list">
{if !$is_virtual}
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='OkiniiriIchiran' act='OkiniiriIchiran'}"><img src="/assets/img/icon_favorite_white.svg" alt="お気に入り" width="50px"  height="50px">
                        <p>お気に入り</p>
                    </a>
                </li>
                <li class="utility_navigation_item">
                    <a href="{wt_action_url mod='Order' act='OrdererInfoInput'}"><img src="/assets/img/icon_cart_white.svg" alt="カート" width="50px"  height="50px">
                        <p>カート</p>
                    </a>
                </li>
{/if}
                <li class="utility_navigation_item">
                    <div class="button"><span></span><span></span><span></span></div>
                    <ul class="nav">
                        <li class="nav__list"><a href="{wt_action_url mod="" act=""}#top-connection">墨田区の子育て支援情報</a></li>
{if !$is_virtual}
                        <li class="nav__list"><a href="{wt_action_url mod='Default' act='Mypage'}">マイページ</a></li>
{/if}
                        <!-- <li class="nav__list"><a href="/guide/">ご利用ガイド</a></li> -->
                        <!-- <li class="nav__list"><a href="/support/">健康相談<br>サポートダイヤル</a></li> -->
                        <li class="nav__list"><a href="/guide/faq/">よくある質問</a></li>
{if !$is_virtual}
                        <li class="nav__list"><a href="{wt_action_url mod='ShukaJyokyo' act='ShukaJyokyo'}">交換履歴</a></li>
                        <li class="nav__list"><a href="{wt_action_url mod='ShohinRireki' act='ShohinCheckRirekiCookie'}">閲覧履歴</a></li>
{/if}
                        <li class="nav-accordion">
                        <div id="container">
                            <div class="faq" id="main-area">
                                <section class="list" id="area-1">
                                    <ul class="accordion-area">
                                        <li class="accordion-area__list">
                                            <section>
                                                <dl>
                                                    <div class="question accordion-sub-title">
                                                        <dt class="accordion-area__title sp-none">商品を探す</dt>
                                                        <dt class="accordion-area__title pc-none"><span class="accordion-area-span">商品を探す</span></dt>
                                                    </div>
                                                    <div class="answer_box" style="display: none;">
                                                        <dd class="line-mdse">
                                                        <div class="p-topitem__searchbox">
                                                        <form action="{wt_action_url mod="" act=""}" method="post" id="search">
                                                            <input type="search" name="keyword" class="search_input" placeholder="なにをお探しですか？">
                                                            <input type="submit" name="BTN_SEARCH" value="" class="search-magnifier">
                                                        </form>
                                                        </div>
                                                        </dd>
                                                        <dd class="line-mdse">
                                                            <h2 class="p-topitem__catbox--title">月齢別でさがす</h2>
                                                            <div class="p-topitem__catbox">
                                                            <ul class="p-topitem__catbox--inner">
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83000/tabAllFlg/1">0ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83010/tabAllFlg/1">1ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83020/tabAllFlg/1">2ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83030/tabAllFlg/1">3ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83040/tabAllFlg/1">4ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83050/tabAllFlg/1">5ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83060/tabAllFlg/1">6ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83070/tabAllFlg/1">7ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83080/tabAllFlg/1">8ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83090/tabAllFlg/1">9ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83100/tabAllFlg/1">10ヶ月から</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-83110/tabAllFlg/1">1歳から</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        </dd>
                                                        <dd class="line-mdse">
                                                            <h2 class="p-topitem__catbox--title">ポイント別でさがす</h2>
                                                            <div class="p-topitem__catbox">
                                                            <ul class="p-topitem__catbox--inner">
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80010/tabAllFlg/1">1,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80020/tabAllFlg/1">2,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80030/tabAllFlg/1">3,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80040/tabAllFlg/1">4,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80050/tabAllFlg/1">5,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80060/tabAllFlg/1">6,000~7,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80070/tabAllFlg/1">8,000~9,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80080/tabAllFlg/1">10,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80090/tabAllFlg/1">11,000~15,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80100/tabAllFlg/1">16,000~19,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80110/tabAllFlg/1">20,000~29,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80120/tabAllFlg/1">30,000~39,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80130/tabAllFlg/1">40,000~49,000pt</a>
                                                                </li>
                                                                <li class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-80140/tabAllFlg/1">50,000pt</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        </dd>
                                                        <dd class="line-mdse">
                                                            <h2 class="p-topitem__catbox--title">特集からさがす</h2>
                                                            <div class="p-topitem__catbox">
                                                            <ul class="p-topitem__catbox--inner">
                                                                <ul class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-84070/tabAllFlg/1">マタニティ特集</a>
                                                                </ul>
                                                                <ul class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-84040/tabAllFlg/1">内祝い好適品</a>
                                                                </ul>
                                                                <ul class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-84080/tabAllFlg/1">出産準備特集</a>
                                                                </ul>
                                                                <ul class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-84050/tabAllFlg/1">防災用品</a>
                                                                </ul>
                                                                <ul class="c-more__arrow bgGray_">
                                                                    <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-84090/tabAllFlg/1">赤ちゃんとお出かけ</a>
                                                                </ul>
                                                            </ul>
                                                        </div>
                                                        </dd>
                                                    </div>
                                                </dl>
                                            </section>
                                        </li>
                                    </ul>
                                </section> <!-- area-1 -->
                            </div>
                        </div>
                        </li>
                        <li class="nav-accordion">
                            <div id="container">
                                <div class="faq" id="main-area">
                                    <section class="list" id="area-1">
                                        <ul class="accordion-area">
                                            <li class="accordion-area__list">
                                                <section>
                                                    <dl>
                                                        <div class="question accordion-sub-title">
                                                            <dt class="accordion-area__title sp-none">Language</dt>
                                                            <dt class="accordion-area__title pc-none"><span class="accordion-area-span">Language</span></dt>
                                                        </div>
                                                        <div class="answer_box" style="display: none;">
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="ja" data-stt-ignore="" data-stt-active="">日本語</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="" data-stt-ignore="" data-stt-active="">Indonesia</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="" data-stt-ignore="" data-stt-active="">Español</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="tl" data-stt-ignore="" data-stt-active="">Tagalog</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="vi" data-stt-ignore="" data-stt-active="">Tiếng Việt</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="pt" data-stt-ignore="" data-stt-active="">Português</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="en" data-stt-ignore="">English</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="ko" data-stt-ignore="">한국</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="zh-CN" data-stt-ignore="">简体中文</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="zh-TW" data-stt-ignore="">繁體中文</a>
                                                            </dd>
                                                            <dd class="line">
                                                                <a href="#" data-stt-changelang="" data-stt-ignore="">اردو</a>
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                </section>
                                            </li>
                                        </ul>
                                    </section> <!-- area-1 -->
                                </div>
                            </div>
                        </li>
                        <!-- <li class="nav__list"><a href="#">文字サイズ</a></li> -->
                        <!-- <li class="nav__list"><a href="{wt_action_url app="webapp" mod="Withdraw" act="Withdrawal"}">ポイント利用辞退申請</a></li> -->
                        <li class="nav__list"><a href="{wt_action_url mod='Default' act='Logout'}">ログアウト</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="header_navigation">
        <ul class="header_navigation_list">
            <li class="header_navigation_item">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-80000/"><img src="/assets/img/nav_01.svg" alt="すべての商品">
                    <p>すべての商品</p>
                </a>
            </li>
            <li class="header_navigation_item">
                <a href="{wt_action_url mod="" act=""}#top-connection"><img src="/assets/img/nav_02.svg" alt="墨田区の子育て支援情報">
                    <p>墨田区の子育て支援情報</p>
                </a>
            </li>
            <li class="header_navigation_item">
                <a href="/guide/faq/"><img src="/assets/img/nav_03.svg" alt="よくあるご質問">
                    <p>よくあるご質問</p>
                </a>
            </li>
            <li class="header_navigation_item">
                <a href="{wt_action_url app="webapp" mod="Toiawase" act="Toiawase"}"><img src="/assets/img/nav_04.svg" alt="お問い合わせ">
                    <p>お問い合わせ</p>
                </a>
            </li>
        </ul>
    </div>
    <div class="consultation">
        <span class="consultation__text">チャットで相談する</span>
    </div>
</header>
{else}
{*-- 未ログインヘッダ --*}
<header class="header before-login-header">
    <div class="button">
        <span class="button_span"></span>
        <span class="button_span"></span>
        <span class="button_span"></span>
    </div>
    <ul class="nav">
        <li class="nav__list"><a href="{wt_action_url mod="" act=""}#top-connection">墨田区の子育て支援情報</a></li>
        <li class="nav__list"><a class="modal-login" href="{wt_action_url mod="Default" act="Login"}?act=l">ログイン</a></li>
        <li class="nav__list"><a class="modal-first" href="{wt_action_url mod="Default" act="Login"}?act=r">初回登録</a></li>
    </ul>
    <div class="header_inner">
        <h1 class="before-login-logo">
            <figure class="sp-none"><a href="{wt_action_url mod="" act=""}"><img src="/assets/img/before-login.svg" alt="ロゴ"></a></figure>
            <figure class="pc-none img-size"><a href="{wt_action_url mod="" act=""}"><img src="/assets/img/before-login.svg" alt="ロゴ"></a></figure>
        </h1>
        <nav aria-label="サイト内メニュー" class="headerNav">
            <div class="headerList ac-pc">
                <p class="headerList__lang">
                    <a href="javascript:void(0);">
                       language
                    </a>
                    <ul class="ac-pc_under">
                        <li>
                            <a href="#" data-stt-changelang="ja" data-stt-ignore="" data-stt-active="">日本語</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="" data-stt-ignore="" data-stt-active="">Indonesia</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="" data-stt-ignore="" data-stt-active="">Español</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="tl" data-stt-ignore="" data-stt-active="">Tagalog</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="vi" data-stt-ignore="" data-stt-active="">Tiếng Việt</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="pt" data-stt-ignore="" data-stt-active="">Português</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="en" data-stt-ignore="">English</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="ko" data-stt-ignore="">한국</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="zh-CN" data-stt-ignore="">简体中文</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="zh-TW" data-stt-ignore="">繁體中文</a>
                        </li>
                        <li>
                            <a href="#" data-stt-changelang="" data-stt-ignore="">اردو</a>
                        </li>
                    </ul>
                </p>
            </div>
            <div class="bl_sizeBtn_wrap">
                <span class="span">文字サイズ</span>
                <button class="bl_sizeBtn big" id="f_lg" type="button">大</button>
                <button class="bl_sizeBtn is_active small" id="f_md" type="button">中</button>
                <button class="bl_sizeBtn" id="f_sm" type="button">小</button>
            </div>
        </nav>
    </div>
    <div class="consultation">
        <span class="consultation__text">チャットで相談する</span>
    </div>
</header>
<div>
{/if}
