document.getElementById("shareButton").addEventListener("click", function() {
    // サイト名を取得
    var siteName = document.title.split(" - ")[0]; // サイト名はタイトルから" - "で分割
    
    // アクセスしているサイトのリンク
    var currentUrl = window.location.href;
    
    // Twitterの共有URL
    var twitterShareUrl = "https://twitter.com/intent/tweet?url=" + encodeURIComponent(currentUrl) + "&text=" + encodeURIComponent(siteName);
    
    // Twitter共有ウィンドウを開く
    window.open(twitterShareUrl, "_blank", "width=600,height=300");
});