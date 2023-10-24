        // ページが読み込まれた後に実行されるコード
        window.onload = function() {
            // Facebookのシェアボタンの要素を取得
            var facebookButton = document.getElementById("FacebookButton");

            // ボタンがクリックされたときに実行される関数
            facebookButton.onclick = function() {
                // 現在のページのタイトルを取得
                var pageTitle = document.title;

                // 現在のページのURLを取得
                var pageURL = window.location.href;

                // Facebookのシェア用のURLを生成
                var facebookShareURL = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(pageURL);

                // Facebookのシェアウィンドウを新しいウィンドウで開く
                window.open(facebookShareURL, "facebookShare", "width=600, height=400");
            };
        };