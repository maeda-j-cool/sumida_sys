{capture name=header}
<script>
{literal}
(function(d) {
    var config = {
        kitId: 'btv7ivp',
        scriptTimeout: 3000,
        async: true
    },
    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
})(document);
{/literal}
</script>
{/capture}
<div class="Wrapper login-bg">
{include file=$headerTemplate}
<main id="login-top-main">


{kenshu_include template="message"}

{*
    <!--<section class="login-notice">
        <div class="login-notice__notice">
            <p class="title">【岐阜市からのお知らせ】</p>
            <p class="text">2023/05/17　岐阜市女性センター主催講座のご案内について</p>
        </div>
        <div class="login-alarm">
            <p class="text">2023/01/23　非常に強い寒気による配送影響について</p>
        </div>
    </section>-->
*}
{* <!-- スライダー --> *}
{kenshu_include template="slider" use_default=true}

{* <!-- スライダー 終わり--> *}

    <article class="login-related pc-none">
        <section class="login-related__box">
            <div class="item">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000/">
                    <figure class="item__image"><img src="/assets/img/sp/product-list.png" alt="商品一覧"></figure>
                    <p class="item__text">商品一覧</p>
                </a>
            </div>
            <div class="item">
                <a href="#support-portal"><figure class="item__image"><img src="/assets/img/sp/support-portal.png" alt="子育て支援ポータル"></figure>
                    <p class="item__text">子育て支援ポータル</p></a>
            </div>
            <div class="item">
                <a href="/guide/faq/"><figure class="item__image"><img src="/assets/img/sp/FAQ.png" alt="よくあるご質問"></figure>
                    <p class="item__text">よくあるご質問</p></a>
            </div>
            <div class="item">
                <a href="{wt_action_url mod="Toiawase" act="Toiawase"}"><figure class="item__image"><img src="/assets/img/sp/inquiry.png" alt="お問い合わせ"></figure>
                    <p class="item__text">お問い合わせ</p></a>
            </div>
        </section>
    </article>

    <article class="login-point">
    <h2 class="login-point__title">ポイント別でさがす</h2>
        <ul class="point-box">
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110010/tabAllFlg/1" class="text">1,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110020/tabAllFlg/1" class="text">2,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110030/tabAllFlg/1" class="text">3,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110040/tabAllFlg/1" class="text">4,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110050/tabAllFlg/1" class="text">5,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110060/tabAllFlg/1" class="text">6,000~7,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110070/tabAllFlg/1" class="text">8,000~9,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110080/tabAllFlg/1" class="text">10,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110090/tabAllFlg/1" class="text">11,000~15,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110100/tabAllFlg/1" class="text">16,000~19,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110110/tabAllFlg/1" class="text">20,000~29,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110120/tabAllFlg/1" class="text">30,000~39,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110130/tabAllFlg/1" class="text">40,000~49,000pt</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110140/tabAllFlg/1" class="text">50,000pt</a>
            </li>
        </ul>
    </article>

    <article class="login-point">
        <h2 class="login-point__title">カテゴリ別でさがす</h2>
        <ul class="point-box">
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111000/tabAllFlg/1" class="text">乳幼児衣料品</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111100/tabAllFlg/1" class="text">育児消耗品</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111200/tabAllFlg/1" class="text">育児日用品</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111300/tabAllFlg/1" class="text">ママ・パパサポート</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111400/tabAllFlg/1" class="text">玩具</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111500/tabAllFlg/1" class="text">内祝い</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111600/tabAllFlg/1" class="text">食品</a>
            </li>
            <li class="login-point__point">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111700/tabAllFlg/1" class="text">体験・金券</a>
            </li>
		</ul>
    </article>
    
{if !empty($ranking_list1)}
    <article class="login-product">
        <h2 class="login-product__title">人気の商品はこちら</h2>
        <div class="product-box1">
{foreach from=$ranking_list1 item=row name=rank1}
            <a href="{$row.linkToShosai|escape}" class="product-box1__item">
                <div class="inner">
                    <p class="numbero1">{$smarty.foreach.rank1.iteration}</p>
                    <figure>{strip}
{tms_html_image alt="{$row.M02SNAME|escape}" shohin_code=$row.M02SHOHNCD image_type="1"}
                    {/strip}</figure>
                    <p class="text"><u>{$row.M02SNAME|escape}{if $row.M02BRAND}／{$row.M02BRAND|escape}{/if}</u></p>
                    <p class="productList__point">{$row.M02VPOINT|escape}point</p>
                </div>
            </a>
{/foreach}
{*
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box1__item">
                <div class="inner">
                    <p class="numbero1">1</p>
                    <figure><img src="/assets/img/product1.png" alt=""></figure>
                    <p class="text"><u>産後はじめてセット／ピジョン</u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box1__item">
                <div class="inner">
                    <p class="numbero2">2</p>
                    <figure><img src="/assets/img/product2.png" alt=""></figure>
                    <p class="text"><u>妊婦帯／ピジョン</u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box1__item">
                <div class="inner">
                    <p class="numbero3">3</p>
                    <figure><img src="/assets/img/product3.png" alt=""></figure>
                    <p class="text"><u>骨盤ベルト＋産前産後ショーツ／ローズマダム</u></p>
                </div>
            </a>
*}
        </div>
{if !empty($ranking_list2)}
        <div class="product-box2">
{foreach from=$ranking_list2 item=row name=rank2}
            <a href="{$row.linkToShosai|escape}" class="product-box2__item">
                <div class="inner">
                    <p class="numbero4">{$smarty.foreach.rank2.iteration+3}</p>
                    <figure class="product-figure">{strip}
{tms_html_image alt="{$row.M02SNAME|escape}" shohin_code=$row.M02SHOHNCD image_type="1"}
                    {/strip}</figure>
                    <p class="text"><u>{$row.M02SNAME|escape}{if $row.M02BRAND}／{$row.M02BRAND|escape}{/if}</u></p>
                    <p class="productList__point">{$row.M02VPOINT|escape}point</p>
                </div>
            </a>
{/foreach}
{*
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box2__item">
                <div class="inner">
                    <p class="numbero4">4</p>
                    <figure class="product-figure"><img src="/assets/img/product4.png" alt=""></figure>
                    <p class="text"><u>マタニティサイズブラキャミソール／千趣会 </u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box2__item">
                <div class="inner">
                    <p class="numbero5">5</p>
                    <figure class="product-figure"><img src="/assets/img/product5.png" alt=""></figure>
                    <p class="text"><u>母乳を吸収するコットン授乳ブラ／ピジョン</u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box2__item">
                <div class="inner">
                    <p class="numbero6">6</p>
                    <figure class="product-figure"><img src="/assets/img/product6.png" alt=""></figure>
                    <p class="text"><u>マタニティはらまきパンツ／千趣会</u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box2__item">
                <div class="inner">
                    <p class="numbero7">7</p>
                    <figure class="product-figure"><img src="/assets/img/product7.png" alt=""></figure>
                    <p class="text"><u>産前産後ショーツセット／ローズマダム</u></p>
                </div>
            </a>
*}
        </div>
{/if}
{if !empty($ranking_list3)}
        <div class="product-box3">
{foreach from=$ranking_list3 item=row name=rank3}
            <a href="{$row.linkToShosai|escape}" class="product-box3__item">
                <div class="inner">
                    <p class="numbero4">{$smarty.foreach.rank3.iteration+7}</p>
                    <figure class="product-figure">{strip}
{tms_html_image alt="{$row.M02SNAME|escape}" shohin_code=$row.M02SHOHNCD image_type="1"}
                    {/strip}</figure>
                    <p class="text"><u>{$row.M02SNAME|escape}{if $row.M02BRAND}／{$row.M02BRAND|escape}{/if}</u></p>
                    <p class="productList__point">{$row.M02VPOINT|escape}point</p>
                </div>
            </a>
{/foreach}
{*
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box3__item">
                <div class="inner">
                    <p class="numbero8">8</p>
                    <figure class="product-figure"><img src="/assets/img/product8.png" alt=""></figure>
                    <p class="text"><u>マタニティサイズショーツ3枚セット／千趣会 </u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box3__item">
                <div class="inner">
                    <p class="numbero9">9</p>
                    <figure class="product-figure"><img src="/assets/img/product9.png" alt=""></figure>
                    <p class="text"><u>マタニティパジャマ／ローズマダム</u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box3__item">
                <div class="inner">
                    <p class="numbero10">10</p>
                    <figure class="product-figure"><img src="/assets/img/product10.png" alt=""></figure>
                    <p class="text"><u>入院準備に！マタニティサイズパジャマ／千趣会 </u></p>
                </div>
            </a>
            <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-/tabAllFlg/1" class="product-box3__item">
                <div class="inner">
                    <p class="numbero11">11</p>
                    <figure class="product-figure"><img src="/assets/img/product11.png" alt=""></figure>
                    <p class="text"><u>マタニティ入院準備８点セット／千趣会</u></p>
                </div>
            </a>
*}
        </div>
        <div class="look-btn">
            <a href="" class="look-btn__link">全ての商品を見る</a>
        </div>
{/if}
    </article>
{/if}

    <!-- <article class="seek">
        <section class="moon-age">
            <h2 class="moon-age__title">月齢別でさがす</h2>
            <ul class="moon-age__box">
                <li class="moon-age__list">
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113000/tabAllFlg/1" class="text">0カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113010/tabAllFlg/1" class="text">1カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113020/tabAllFlg/1" class="text">2カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113030/tabAllFlg/1" class="text">3カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113040/tabAllFlg/1" class="text">4カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113050/tabAllFlg/1" class="text">5カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113060/tabAllFlg/1" class="text">6カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113070/tabAllFlg/1" class="text">7カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113080/tabAllFlg/1" class="text">8カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113090/tabAllFlg/1" class="text">9カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113100/tabAllFlg/1" class="text">10カ月から</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-113110/tabAllFlg/1" class="text">1歳から</a>
                    </div>
                </li>
            </ul>
        </section>
        <section class="brand">
            <h2 class="brand__title">ブランド別でさがす</h2>
            <ul class="brand__box">
                <li class="brand__list">
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112000/tabAllFlg/1" class="text">10mois</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112010/tabAllFlg/1" class="text">アップリカ</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112020/tabAllFlg/1" class="text">カトージ</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112030/tabAllFlg/1" class="text">コンビ</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112040/tabAllFlg/1" class="text">ディズニー</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112050/tabAllFlg/1" class="text">ドクターベッタ</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112060/tabAllFlg/1" class="text">プリスティン</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112070/tabAllFlg/1" class="text">ボーネルンド</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112080/tabAllFlg/1" class="text">ポロ ラルフ ローレン</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112090/tabAllFlg/1" class="text">ミキハウス</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112100/tabAllFlg/1" class="text">ムーンスター</a>
                    </div>
                    <div class="btn">
                        <a href="{wt_action_url mod="SS" act="CS"}group/cat_brand/catid/0-112110/tabAllFlg/1" class="text">Leapepe</a>
                    </div>

                </li>
            </ul>
        </section>
    </article> -->
	
    <article class="special-feature">
        <section class="special-feature__inner">
            <h2 class="title" id="top-Special-feature">おすすめ特集</h2>
            <p class="text">なかの First Birthdayをよりご体感いただける<br>お楽しみ企画・取り組みをご紹介します。</p>
        </section>
        <div class="special-feature__box">
            <div class="item2">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-110000-111300/tabAllFlg/1" class="item2__text">forママ・パパ</a>
            </div>
            <div class="item3">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-114020/tabAllFlg/1" class="item3__text">内祝い好適品</a>
            </div>
            <div class="item4">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-114010/tabAllFlg/1" class="item4__text">中野区ゆかりの品</a>
            </div>
            <!-- <div class="item5">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-114030/tabAllFlg/1" class="item5__text">防災用品</a>
            </div> -->
            <!-- <div class="item6">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-114040/tabAllFlg/1" class="item6__text">授産製品</a>
            </div> -->
            <div class="item1">
                <a href="{wt_action_url mod="SS" act="CS"}group/cat_shobun/catid/0-114080/tabAllFlg/1" class="item1__text">おめでとうパッケージ</a>
            </div>
        </div>
    </article>

    <article class="connection">
        <h2 class="connection__sub-title" id="top-connection">中野区の子育て支援情報</h2>
        <p class="connection__text">中野区はお子さまの健やかな成長を願って、さまざまな形で皆さまの子育てを応援しています。</p>
        <div class="connection-image">
            <div class="connection-box">
                <figure class="connection-item"><a href="https://www.city.tokyo-nakano.lg.jp/kosodate/kosodatesite_ohirune/mokuteki/kenshin/hanokenko/kodomonoha.html" target="_blank" target="_blank"><img alt="子どもの歯のおはなし"
                      src="/assets/img/connection1.png" width="490" height="190"></a>
                </figure>
                <figure class="connection-item"><a href="https://www.city.tokyo-nakano.lg.jp/kosodate/kosodatesite_ohirune/mokuteki/odekake/index.html" target="_blank"><img alt="でかける・居場所" src="/assets/img/connection2.png" width="490"
                    height="190"></a>
                </figure>
                <figure class="connection-item"><a href="https://www.city.tokyo-nakano.lg.jp/bosai/index.html" target="_blank"><img src="/assets/img/connection3.png" alt="中野区防災サイト"></a>
                </figure>
                <figure class="connection-item"><a href="https://www.city.tokyo-nakano.lg.jp/kosodate/kosodatesite_ohirune/index.html" target="_blank"><img src="/assets/img/connection4.png" alt="中野区子育てサイトおひるね"></a>
                </figure>
              </div>
        </div>
    </article>
</main>
</div>
{include file=$footerTemplate}
<div class="modal-container modal-container-popup active" id="modalPopup">
    <div class="modal-body">
        <div class="modal-close modal-close-popup">×</div>
        <div class="modal-content modal_popup">
            <span class="modal-close"><img src="/assets/img/modal-close.png" alt="モーダルクローズボタン"></span>
            <img src="/assets/img/popup_banr.png" alt="「子育て県 なかの」情報発信サイト Coloful">
            <div class="c-popup__btn">
                <a href="https://kagawa-colorful.com/" target="_blank"><img src="/assets/img/popup-btn.png" alt="くわしくはこちら"></a>
            </div>
        </div>
    </div>
</div>
{capture name=js}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<script>
{literal}
var $sample = $('#modalPopup');
// クッキーの存在を確認
if (!$.cookie('sample_hidden')) {
    $sample.show();
}
    $('.modal-close', $sample).on('click', function () {
      $sample.fadeOut();
      $.cookie('sample_hidden', 'true', { expires: 7 });
      return false;
    });
    $('.layer_board_bg', $sample).on('click', function () {
      $sample.fadeOut();
      $.cookie('sample_hidden', 'true', { expires: 7 });
      return false;
    });

$(document).ready(function () {
    // すべての.tabsを隠す
    $('.tab_content').hide();

    // 各.tabsごとに最初の.tab_itemにクラスを追加
    $('.tabs').each(function () {
        $(this).find('.tab_content').first().css('display', 'flex');
    });

    // タブ内の各ラベルがクリックされたときの処理
    $('.tabs .tab_item').click(function () {
        var tabIndex = $(this).parent().children('.tab_item').index(this);
        var tabsContainer = $(this).closest('.tabs');
        tabsContainer.find('.tab_content').hide();
        tabsContainer.find('.tab_content').eq(tabIndex).css('display', 'flex');
        $('.tab_item').removeClass('tab_item_active');
        $(this).addClass('tab_item_active');
    });
});
{/literal}
</script>
{/capture}
