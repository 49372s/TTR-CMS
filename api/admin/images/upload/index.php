<?php
//画像アップロードAPI
include('../../../connect/func.php');
$upload_file = '';
$up_success = false;

foreach($_FILES['file']['error'] as $key => $error){
    if($error != UPLOAD_ERR_OK){
        APIResponse(false,"UPLOAD SYSTEM IS DETECTED ERROR!");
    }
    $tmp = isset($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'][$key] : "";
    $org = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : "";

    //命名規則設定
    if(!empty($_GET['mode'])){
        if($_GET['mode']=="0"){
            //ファイル名をそのまま表示する。全角が含まれる場合も構わず表示する。全角ファイルをアップロードする場合は、あらかじめアップローダで設定を行う。
            //また、ファイル名にピリオドが含まれる場合、最初のピリオドで打ち切られ、ファイル名が確定します。やめよう、二重拡張子！
            $name = explode(".",$org)[0];
        }elseif($_GET['mode']=="1"){
            //ファイル名をUnixタイムスタンプをもとにしたハッシュ値で表示する。
            //md5を利用するが、変更したい場合はmd5()をhash()に変える。
            $name = md5(time());
        }
    }

    if($tmp != "" && is_uploaded_file($tmp)){
        $split = explode(".",$tmp);
        $ext = end($split);
        if($ext != "" && $ext!=$org){
            //アップロードファイル名を指定する
            $upload_file = "core/resource/image/upload/".$name.$ext;
            //ファイルを指定のフォルダと名前に移動する
            move_uploaded_file($tmp,$upload_file);
            //TODO: アップデートでテーブル作成の処理を開発次第、データベースに挿入するコードを挿入する。
        }
    }
}
APIResponse(true,"All file upload is success!");
?>