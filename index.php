<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

// データベースに接続
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("データベース接続エラー: " . $conn->connect_error);
}

// ハッシュ化されたパスワードのセットアップ
$hashedPassword = password_hash("", PASSWORD_DEFAULT); // 実際のパスワードをここに設定

// 投稿フォームの表示判定
$managerMode = isset($_GET['manager']) && $_GET['manager'] == 'on';

// 投稿フォーム処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // パスワードの確認
    $providedPassword = $_POST['password'];
    if (password_verify($providedPassword, $hashedPassword)) {
        // パスワードが正しい場合
        $name = $_POST['name'];
        $tags = $_POST['tags'];
        $current_time = date("Y-m-d H:i:s"); // 現在の時刻を取得

        $time_blog = date("Y年m月d日"); // 2023-10-14 12:34:56 のようなフォーマット

        //内容
        $content = $_POST['content'];
// 行ごとに改行を分割
$lines = explode("\n", $content);

// 各行に対して処理
$content_with_line_breaks = '';
foreach ($lines as $line) {
    // 行が </h3> または </h4> で終わらない場合に <br> タグを追加
    if (!preg_match('/<\/[hH]3>|<\/[hH]4>/', trim($line))) {
        $content_with_line_breaks .= $line . '<br>';
    } else {
        $content_with_line_breaks .= $line;
    }
}

        // 新しいコード：記事の保存先ディレクトリを作成
        $uploadDirectory = $tags . '/' . $name . '/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // ファイル名を一意に生成
        $filename = $uploadDirectory . 'index.html';

        // ブログの中身テンプレートを生成
        $blogContent = <<<HTML
        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="utf-8" />
            <title>$name</title>
            <meta name="keywords" content="PIENNU、ピエンヌ">
            <meta name="description" content="PIENNUのホームページ。それ以上でもそれ以下でもない。とりあえず遊びにこいよ！">
            <meta property="og:image" content="https://piennu777.ml/images/icon.png">
            <meta property="og:image:alt" content="バナー画像">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
            <meta name="keywords" content="PIENNU,ブログ,PIENNU Blog">
            <link href="https://diary.piennu777.jp/blog.css" rel="stylesheet" />
        </head>
        <body>
            <header>
                <h1 class="headline">
                    <a href="https://piennu777.jp">PIENNU</a>
                </h1>
                <ul class="nav-list">
                    <li class="nav-list-item"><a href="https://diary.piennu777.jp/">日記</a></li>
                    <li class="nav-list-item"><a href="https://object.piennu777.jp">物置部屋</a></li>
                    <li class="nav-list-item"><a href="https://zenya.piennu777.jp/" class="menuItem">BOT</a></li>
                    <li class="nav-list-item"><a href="https://contact.piennu777.jp">お問い合わせ</a></li>
                </ul>
            </header>
            <main id="main">
                <div id="content">
                    <!-- 左側のコンテンツ -->
                    <h2 class="title">$name<span style="font-size: 20px; margin-left: 10px;">$time_blog<i style="margin-left: 10px;" class="fa-solid fa-hashtag">$tags</i></span></h2>
                    <p>
                    $content_with_line_breaks
                     </p>
                     <br>
                     <div style="font-size: 10px;">
                    <a id="shareButton" href="#" target="_blank" class="small-button">
                    <img src="https://img.shields.io/badge/X-SHARE-8D8D8D.svg?style=for-the-badge" alt="X-SHARE">
                </a>
                <a id="FacebookButton" href="#" target="_blank" class="small-button">
                    <img src="https://img.shields.io/badge/Facebook-SHARE-00B3FF.svg?style=for-the-badge" alt="Link Copy">
                </a>
                <a id="copyButton" href="#" target="_blank" id="shareButton" class="small-button">
                    <img src="https://img.shields.io/badge/Link-Copy-42FF7B.svg?style=for-the-badge" alt="Link Copy">
                </a>
                </div>   
                    <!-- JavaScriptの巣窟 -->
                    <script src="https://diary.piennu777.jp/js/x.js" crossorigin="anonymous"></script>
                    <script src="https://diary.piennu777.jp/js/copy.js" crossorigin="anonymous"></script>
                    <script src="https://diary.piennu777.jp/js/facebook.js" crossorigin="anonymous"></script>
                    <script src="https://kit.fontawesome.com/dd69661a1b.js" crossorigin="anonymous"></script>
                    <!-- オシマイ☆ -->
                </div>
            </main>
        </body>
        </html>
        HTML;

        // ブログの中身をファイルに保存
        file_put_contents($filename, $blogContent);

        // データベースに記事情報を保存
        $sql = "INSERT INTO posts (name, content, tags, filepath, date) VALUES ('$name', '$content', '$tags', '$filename', '$current_time')";
        if ($conn->query($sql) === TRUE) {
            echo "投稿が成功しました";
        } else {
            echo "エラー: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // パスワードが正しくない場合
        echo "エラー: パスワードが正しくありません";
    }
}


// 記事一覧を取得
$sql = "SELECT * FROM posts ORDER BY date DESC";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <title>PIENNU｜日記</title>
    <meta name="keywords" content="PIENNU、ピエンヌ">
    <meta name="description" content="PIENNUのホームページ。それ以上でもそれ以下でもない。とりあえず遊びにこいよ！">
    <meta property="og:image" content="https://piennu777.ml/images/icon.png">
    <meta property="og:image:alt" content="バナー画像">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <meta name="keywords" content="PIENNU,ブログ,PIENNU Blog">
    <link href="https://diary.piennu777.jp/index.css" rel="stylesheet" />
</head>
<body>
    <header>
        <h1 class="headline">
            <a href="https://piennu777.jp">PIENNU</a>
        </h1>
        <ul class="nav-list">
                    <li class="nav-list-item"><a href="https://diary.piennu777.jp/">日記</a></li>
                    <li class="nav-list-item"><a href="https://object.piennu777.jp">物置部屋</a></li>
                    <li class="nav-list-item"><a href="https://zenya.piennu777.jp/" class="menuItem">BOT</a></li>
                    <li class="nav-list-item"><a href="https://contact.piennu777.jp">お問い合わせ</a></li>
      </ul>
    </header>

    <main id="main">
        <div id="content">
        <?php if ($managerMode): ?>
            <!-- 投稿フォーム -->
            <h2>投稿フォーム（Admin Only）</h2>
            <form method="POST" action="index.php">
                名前: <input type="text" name="name"><br>
                内容: <textarea name="content"></textarea><br>
                タグ: <input type="text" name="tags"><br>
                パスワード: <input type="password" name="password"><br> <!-- パスワード入力フィールド -->
                <!-- 現在の時刻を隠しフィールドとして追加 -->
                <input type="hidden" name="current_time" value="<?php echo date('Y年m月d日'); ?>">
                <!-- 写真アップロードフィールドをここに追加 -->
                <input type="submit" value="投稿">
            </form>
            <?php endif; ?>

          <h2>日記とは</h2> 
          <p>まぁ日記というか俺の独り言？w<br>まぁブログみたいな感じです。<br>前みたいに名前を「ブログ」ってのでもいいんですけど僕硬いの嫌いなんですよね（意味不）<br>内容について質問でもありましたら<a href="https://contact.piennu777.jp/" style="color: blue;">こちら</a>まで。</p>

          <!--
          <h2>検索</h2>
          <form id="searchForm">
            <input type="text" id="searchInput" class="search-box" onkeyup="searchBlog()" onkeydown="checkEnterKey(event)" placeholder="🔍｜検索（Enterを押してね♡）">
        </form>
        -->
        <p class="kuu1"></p>
        <h2>最近の投稿</h2>
        <ul class="styled-list">
<?php
$sql = "SELECT * FROM posts ORDER BY date DESC LIMIT 5";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $url = $row['filepath'];
    $title = $row['name'];
    $date = date('Y年m月d日', strtotime($row['date']));
    $category = $row['tags'];

    echo '<li data-title="' . $title . '"><a href="' . $url . '" style="color: black;">' . $title . ' <span style="color: #464646; font-size: 15px;">' . $date . ' <i class="fa-solid fa-hashtag">' . $category . '</i></span></a></li>';
}

?>
</ul>
<p class="kuu1"></p>
        <h2>#日記</h2>
        <ul class="styled-list">
        <?php
        // タグが "日記" の記事を取得
$sql = "SELECT * FROM posts WHERE tags = '日記' ORDER BY date DESC";
$result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $url = $row['filepath'];
            $title = $row['name'];
            $date = date('Y年m月d日', strtotime($row['date']));
            $category = $row['tags'];

            echo '<li><a href="' . $url . '" style="color: black;">' . $title . ' <span style="color: #464646; font-size: 15px;">' . $date . ' <i class="fa-solid fa-hashtag">' . $category . '</i></span></a></li>';
        }
    ?>
</ul>
<p class="kuu1"></p>
        <h2>#PC</h2>
        <ul class="styled-list">
        <?php
        // タグが "PC" の記事を取得
$sql = "SELECT * FROM posts WHERE tags = 'PC' ORDER BY date DESC";
$result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $url = $row['filepath'];
            $title = $row['name'];
            $date = date('Y年m月d日', strtotime($row['date']));
            $category = $row['tags'];

            echo '<li><a href="' . $url . '" style="color: black;">' . $title . ' <span style="color: #464646; font-size: 15px;">' . $date . ' <i class="fa-solid fa-hashtag">' . $category . '</i></span></a></li>';
        }
    ?>
</ul>
<p class="kuu1"></p>
        <h2>#テスト</h2>
        <ul class="styled-list">
        <?php
        // タグが "テスト" の記事を取得
$sql = "SELECT * FROM posts WHERE tags = 'テスト' ORDER BY date DESC";
$result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $url = $row['filepath'];
            $title = $row['name'];
            $date = date('Y年m月d日', strtotime($row['date']));
            $category = $row['tags'];

            echo '<li><a href="' . $url . '" style="color: black;">' . $title . ' <span style="color: #464646; font-size: 15px;">' . $date . ' <i class="fa-solid fa-hashtag">' . $category . '</i></span></a></li>';
        }
    ?>
</ul>
        <p class="kuu1"></p>
        <h2>一覧の投稿</h2>
        <ul class="styled-list">
        <?php
// 記事一覧を取得（最新の投稿が一番上に来るように）
$sql = "SELECT * FROM posts ORDER BY date DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $url = $row['filepath'];  // ファイルパスを取得
    $title = $row['name'];
    $date = date('Y年m月d日', strtotime($row['date']));  // 年月日を表示
    $category = $row['tags'];

    echo '<li><a href="' . $url . '" style="color: black;">' . $title . ' <span style="color: #464646; font-size: 15px;">' . $date . ' <i class="fa-solid fa-hashtag">' . $category . '</i></span></a></li>';
}

?>
</ul>

<br>

<div style="font-size: 10px;">
<a id="shareButton" href="#" target="_blank" class="small-button">
                    <img src="https://img.shields.io/badge/X-SHARE-8D8D8D.svg?style=for-the-badge" alt="X-SHARE">
                </a>
                <a id="FacebookButton" href="#" target="_blank" class="small-button">
                    <img src="https://img.shields.io/badge/Facebook-SHARE-00B3FF.svg?style=for-the-badge" alt="Link Copy">
                </a>
                <a id="copyButton" href="#" target="_blank" id="shareButton" class="small-button">
                    <img src="https://img.shields.io/badge/Link-Copy-42FF7B.svg?style=for-the-badge" alt="Link Copy">
                </a>
</div>   

</div>

        <div class="sub_item">
          <ul class="contactWrap-profile">
          <img src="https://piennu777.jp/images/piennu.webp" alt="My Icon" width="140" height="140" />
          <li class="contactWrap-profileName" >PIENNU</li>
          <li class="contactWrap-profileText">プログラミング、ゲーム、PC、マルウェアなどが好きです。<br />YouTubeもやっていますのでぜひチャンネル登録していってください！<br>メールでのお問い合わせはpien@piennu777.mlにてお願いします。</li>
          <a href="https://www.youtube.com/@piennu_777/"><i class="fa-brands fa-youtube" ></i></a>
          <a href="https://github.com/piennu777"><i class="fa-brands fa-github"></i></a>
          <a href="https://discord.gg/Fw59PYCYvY"><i class="fa-brands fa-discord"></i></a>
          </ul>
          </div>
                           
                  <!-- JavaScriptの巣窟 -->
  <script src="https://kit.fontawesome.com/dd69661a1b.js" crossorigin="anonymous"></script>
    <!-- ブログの検索機能について -->
    <script>
        function searchBlog() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById('blogList');
            li = ul.getElementsByTagName('li');

            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName('a')[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = '';
                } else {
                    li[i].style.display = 'none';
                }
            }
        }
    </script>
  <script src="https://diary.piennu777.jp/js/facebook.js" crossorigin="anonymous"></script>
  <script>
    document.getElementById("shareButton").addEventListener("click", function() {
            // ツイートに含めたいテキストとURLを指定
            var text = "PIENNUの日記（ほぼブログ）！\n適当な内容を投稿してるのでぜひ見てってくださいね♡";
            var url = "https://diary.piennu777.jp/"; // ツイートに含めるURL

            // Twitterの共有URLを作成
            var twitterURL = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + encodeURIComponent(url);

            // Twitterウィンドウを開く
            window.open(twitterURL, '_blank', 'width=600,height=300');
});
  </script>
      <script>
        // シェアボタンがクリックされたときの処理
        document.getElementById('FacebookButton').addEventListener('click', function() {
            // サイトのタイトルを取得
            var siteTitle = document.title;
            
            // サイトのURLを取得
            var siteURL = window.location.href;

            // FacebookのシェアURLを作成
            var facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(siteURL);

            // Facebookウィンドウを開く
            window.open(facebookURL, 'Facebook シェア', 'width=600,height=300');
        });
    </script>
  <script>
    document.getElementById("copyButton").addEventListener("click", function() {
    // サイト名を取得
    var siteName = document.title.split(" - ")[0];

    // アクセスしているサイトのリンク
    var currentUrl = window.location.href;

    // コピー用のテキストエリアを生成
    var tempInput = document.createElement("textarea");
    tempInput.style = "position: absolute; left: -1000px";
    tempInput.value = "PIENNUの日記（ほぼブログ）！\n適当な内容を投稿してるのでぜひ見てってくださいね♡" + currentUrl;

    // テキストエリアをページに追加
    document.body.appendChild(tempInput);

    // テキストを選択し、クリップボードにコピー
    tempInput.select();
    document.execCommand("copy");

    // テキストエリアを削除
    document.body.removeChild(tempInput);

    alert("リンクがクリップボードにコピーされました");
});
</script>
</body>
</body>
</html>
  <!-- オシマイ☆ -->
</body>
</html>

<?php
$conn->close();
?>
