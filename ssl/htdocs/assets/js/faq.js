$(window).on('load', updateList);

//リンク先のidまでスムーススクロール
$('.side-menu-text a').click(function () {
	var elmHash = $(this).attr('href');
	var pos = $(elmHash).offset().top - 0;
	$('body,html').animate({
		scrollTop: pos
	}, 1000);
	return false;
});




$("#searchBox").on("input", updateList);

const keyItems = document.querySelectorAll('.key_item');
const searchBox = document.getElementById('searchBox');

keyItems.forEach(keyItem => {
  keyItem.addEventListener('click', function() {
    searchBox.value = keyItem.textContent.replace('♯', '');
	updateList();
  });
});

function updateList() {
	var searchText = $("#searchBox").val();
	$('.side-menu-text').hide(); 	
	$('.list').hide(); 	
	$('.answer_box').hide(); 	
	$(".question").removeClass('open');
	$(".sc_nothing_box").hide();
		$(this).removeClass('open');
	$(".accordion-area span.highlight").each(function() {
		$(this).replaceWith($(this).html());
	  });
	if(searchText) {
		$('.accordion-area li').hide(); // アコーディオンのコンテンツ要素を非表示にする
		
		
		var accordionAreas = $(".accordion-area:contains(" + searchText + ")");

		if (accordionAreas.length) {
		
	$(".accordion-area li").each(function () {
	  var accordionHtml = $(this).html();
	  if (accordionHtml.indexOf(searchText) !== -1) {		
		$(this).show();
		$(this).parents('.list').show();
		var list_id = $(this).parents('.list').attr('id');
		$('a[href="#' + list_id + '"]').closest('.side-menu-text').show();
	  } 
	});

	$(".question").each(function () {
	  var title = $(this).text().toLowerCase();
	  if (title.indexOf(searchText.toLowerCase()) !== -1) {
		var title_html = $(this).children(".accordion-area__title").html();
		var rptitle = title_html.replace(new RegExp(searchText, "gi"), '<span class="highlight">$&</span>');
		$(this).children(".accordion-area__title").html(rptitle);
		var findElm = $(this).next('.answer_box');
	$(findElm).show();
	$(this).addClass('open');
	  }
	});
  
	$(".answer_box").each(function () {
	  var answer = $(this).text().toLowerCase();
	  if ( answer.indexOf(searchText.toLowerCase()) !== -1) {
		var answer_html = $(this).children("dd").html();
		var rpanswer = answer_html.replace(new RegExp(searchText, "gi"), '<span class="highlight">$&</span>');
		$(this).children("dd").html(rpanswer);
		$(this).siblings('.question').addClass('open');	
		$(this).show();
	  } 

	});
} else {
	$(".sc_nothing_box").show(); 
} 
} else {
	$('.side-menu-text').show(); 	
	$('.list').show(); 
	$('.accordion-area li').show(); 
} 
}

$("form.search-form-009").submit(function(event) {
	event.preventDefault();
	st_searchText.call(this); // st_searchText関数内でthisを使用するため、thisを引数として渡す
});

$("input[name='searchBox']").keypress(function(event) {
	
	if (event.keyCode == 13) {
		st_searchText.call(this); // st_searchText関数内でthisを使用するため、thisを引数として渡す
	}
  });


function st_searchText() {	
	
	// searchTextをフォームから取得する
	var searchText = $(this).find("input[name='searchBox']").val();
	
	// searchTextを含む最初の.accordion-area要素を検索する
	var $accordionArea = $(".accordion-area:contains(" + searchText + ")").first();
  
	// 要素が見つかった場合にスクロールする
	if ($accordionArea.length) {
	  // スクロールアニメーションを開始する
	  $("html, body").animate({
		scrollTop: $accordionArea.offset().top
	  }, 1000);
	 
	}
}


$(window).on("scroll", function (e) {  // スクロールするたびに呼ばれる
	if(window.matchMedia("(min-width:767px)").matches){
var targetElement=""; // 強調表示を行う要素
$(".side-menu-text a:visible").each(function (i) {// 目次の見出しごとの処理
	 
	var headingName = $(this).attr("href").replace(/^#/, ""); // 見出し名
	var headingPosition = $("#" + headingName)[0].getBoundingClientRect().top + window.pageYOffset - 220;     // 見出しの位置格納

	scrollHeight = $(document).height();
scrollPosition = $(window).height() + $(window).scrollTop();

	if (window.scrollY>=headingPosition) {                       // 見出し位置がスクロール量より小さい
		
		targetElement=this;
		// 強調表示を行う要素を更新
	}

});

$(".side-menu-text a").removeClass("isActive");
	$(targetElement).addClass("isActive");                   // 強調表示クラスの追加
}
	});
