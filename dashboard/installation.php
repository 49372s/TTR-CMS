<?php
/**警告
 * このスクリプトではサイトの設定を行います
 * オーバーライドも可能になっています
 * 自動削除ができなかった場合、必ず手動で削除してください。
 */
?>
<!DOCTYPE html>
<html lang="ja-jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自動インストール | TTR-CMS</title>
</head>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@200&family=Noto+Sans+JP&family=Noto+Serif+JP:wght@200;400&display=swap');
        *{
            font-family: 'Noto Serif JP';
        }
        h1,h2,h3,h4,h5,h6{
            font-family: 'Inter','Noto Sans JP';
        }
        input{
            font-family: 'Inter','Noto Sans JP';
        }
        input[type=text],input[type=password],input[type=number],input[type=email]{
            /*border: 1px solid;*/
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, .25) inset;
            padding: 5px 10px 5px 10px;
            min-width: 20px;
            width: fit-content;
            transition: all .4s ease-in-out;
        }
        input[type=text]:focus,input[type=password]:focus,input[type=number]:focus,input[type=email]:focus{
            box-shadow: 0 0 5px rgba(0, 0, 0, .25);
            outline: none;
        }
        @media(min-width: 700px){
            body{
                background-color: #dfdfdf;
            }
            .wrap{
                width: 80vw;
                height: auto;
                margin: 50px auto;
                background-color: whitesmoke;
                padding: 20px;
                box-shadow: 0 0 50px rgba(255, 255, 255, .25);
                border-radius: 20px;
            }
        }
    </style>
    <div class="wrap">
        <h1>自動インストール</h1>
        <p>ここでは、TTR-CMSの設定が可能です。設定が完了すると、このページは自動的に削除されます。</p>
        <p>また、ホスト名は現在表示されているURL部分が利用されます。ローカル環境(localhostや192.168...)で設定をする場合、外部公開時に別途主導での設定が必要な場合があります。</p>
        <hr>
        <form method="post">
            <h2>管理者の上限</h2>
            <p>管理者の上限です。ここで[0]を設定した場合、管理者ユーザー数に制限が付きません。おすすめは[1]です。</p>
            <p>上限<input type="number" name="admin_limit" value="1" min="0">人まで</p>
            <h2>管理者名の固定</h2>
            <p>管理者名を固定するかどうかの設定です。管理者名を固定する場合、次の項目の設定が有効になります。この設定のチェックを外している場合、次の項目の設定を行っても、その設定はスキップされます。</p>
            <p>また、固定を外している場合のみ、管理者名はあとでダッシュボードにて変更できます。</p>
            <p><input type="checkbox" name="admin_set" id="adminSet"><label for="adminSet">管理者名を固定する</label></p>
            <h2>管理者名</h2>
            <p>前の項目でチェックが入っている場合、この設定が適用されます。</p>
            <p>記事の作成者の名前を設定します。</p>
            <p><input type="text" name="admin_name" placeholder="記事作成者名"></p>
            <h2>サイト名</h2>
            <p>サイトの名前を設定します。影響は基本出ないと思います。</p>
            <p><input type="text" name="site_name" placeholder="サイト名"></p>
            <h2>SQL互換モード</h2>
            <p>MySQL互換性モード設定です。この環境は、レンタルサーバーにおいて有効です。</p>
            <p>MySQL 最新版に対応してない、できない場合はこの設定を有効にすると、互換性を重視した動作に移行し、エラーを回避します。</p>
            <p>現在、スターサーバー、Xfreeサーバーなどでこれを有効化しないと動作しないことが判明しています。</p>
            <p><input type="checkbox" name="sql_compatible" id="sqlCom"><label for="sqlCom">SQL互換性を維持する</label></p>
            <hr>
            <input type="submit" value="設定を完了する">
        </form>
    </div>
</body>
</html>