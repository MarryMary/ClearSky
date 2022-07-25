<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found</title>
</head>
<body>
    <h1>Page Not Found</h1>
    <p>このページは、あなたがリクエストしたページがこのサーバー上に存在しないことを示すページです。</p>
    {% if(isset($IsDebug) && $IsDebug) %}
        <hr>
        <h2>システム開発者へのメッセージ</h2>
        {% HTMLSpecialCharsOff %}
            <p>{{ $err }}</p>
            <small>
                本メッセージはフレームワークの設定ファイルで「IsDebug」オプションを「true」にした場合(デバッグモードON状態)に表示されます。<br>
                本メッセージを表示させたくない場合は、設定ファイルの「IsDebug」オプションを「false」にするか、または項目を削除します。
            </small>
        {% endHTMLSpecialCharsOff %}
        <h2>テンプレートエンジン情報</h2>
            {% debug %}
    {% endif %}
</body>
</html>