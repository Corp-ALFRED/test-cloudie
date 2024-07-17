// ユーザーバーの高さを取得する
jQuery(document).ready(function ($) {
    let user_bar_vh = jQuery("div#wpadminbar").height();
    jQuery("html").css("--user_bar_vh", user_bar_vh + "px");
});


// フッターの高さを取得する
jQuery(document).ready(function ($) {
    let footer_vh = jQuery(".bill-no-print").height();
    jQuery("html").css("--footer_vh", footer_vh + "px");
});