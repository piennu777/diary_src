document.addEventListener("DOMContentLoaded", function() {
    var copyButton = document.getElementById("copyButton");

    copyButton.addEventListener("click", function(event) {
        // リンクのクリックによるページ遷移を無効化
        event.preventDefault();

        var linkURL = window.location.href;
        var pageTitle = document.title;

        var textArea = document.createElement("textarea");
        textArea.value = pageTitle + '\n' + linkURL;
        document.body.appendChild(textArea);

        alert('コピーしてやったぞ☆');

        textArea.select();
        document.execCommand("copy");

        document.body.removeChild(textArea);
    });
});