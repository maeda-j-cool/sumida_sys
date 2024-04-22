// 文字サイズ変更ボタン
$(function () {
      // クリックされたbuttonのidをクッキー（fontSize）に保存（有効期限は7日）
      // クッキー（fontSize）があれば読み込む
  let fz = $.cookie("fontSize");
  if (fz) {
    // サイズ変更ボタンから背景色と文字色のCSSを外す
    $(".bl_sizeBtn").removeClass("is_active");
    // クッキーに保存されたidと一致したら適用
    if (fz == "f_sm") {
      $("html").css("font-size", "50%");
      $("#f_sm").addClass("is_active");
    } else if (fz == "f_md") {
      // デフォルトサイズ
      $("html").css("font-size", "62.5%");
      $("#f_md").addClass("is_active");
    } else if (fz == "f_lg") {
      $("html").css("font-size", "90%");
      $("#f_lg").addClass("is_active");
    }
  }
  //サイズ変更時にクッキーへ保存
  $(".bl_sizeBtn").click(function () {
    // クリックされたbuttonのidをクッキー（fontSize）に保存（有効期限は7日）
    $.cookie("fontSize", this.id, { expires: 2 });
    // サイズ変更ボタンから背景色と文字色のCSSを外す
    $(".bl_sizeBtn").removeClass("is_active");
    // クリックされたbuttonのidと一致したら適用
    if (this.id == "f_sm") {
      $("html").css("font-size", "50%");
      $(this).addClass("is_active");
    } else if (this.id == "f_md") {
      // デフォルトサイズ
      $("html").css("font-size", "62.5%");
      $(this).addClass("is_active");
    } else if (this.id == "f_lg") {
      $("html").css("font-size", "90%");
      $(this).addClass("is_active");
    }
  });
});


// 文字サイズ変更ボタン
$(function () {
  // クッキー（fontSize）があれば読み込む
  let fz = $.cookie("fontSize");
  if (fz) {
    // サイズ変更ボタンから背景色と文字色のCSSを外す
    $(".gu_sizeBtn").removeClass("is_active");
    // クッキーに保存されたidと一致したら適用
    if (fz == "f_sm") {
      $("html").css("font-size", "50%");
      $("#f_sm").addClass("is_active");
    } else if (fz == "f_md") {
      // デフォルトサイズ
      $("html").css("font-size", "62.5%");
      $("#f_md").addClass("is_active");
    } else if (fz == "f_lg") {
      $("html").css("font-size", "90%");
      $("#f_lg").addClass("is_active");
    }
  }
  //サイズ変更時にクッキーへ保存
  $(".gu_sizeBtn").click(function () {
    // クリックされたbuttonのidをクッキー（fontSize）に保存（有効期限は7日）
    $.cookie("fontSize", this.id, { expires: 2 });
    // サイズ変更ボタンから背景色と文字色のCSSを外す
    $(".gu_sizeBtn").removeClass("is_active");
    // クリックされたbuttonのidと一致したら適用
    if (this.id == "f_sm") {
      $("html").css("font-size", "50%");
      $(this).addClass("is_active");
    } else if (this.id == "f_md") {
      // デフォルトサイズ
      $("html").css("font-size", "62.5%");
      $(this).addClass("is_active");
    } else if (this.id == "f_lg") {
      $("html").css("font-size", "90%");
      $(this).addClass("is_active");
    }
  });
});