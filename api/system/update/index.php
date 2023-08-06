<?php
include('../../connect/func.php');
//アップデート実行
//もう一度バージョンを取得する
$ch = curl_init("https://api.github.com/repos/49372s/TTR-CMS/releases");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT,"TKNGH/2.01 (Apache2 / Linux)");
$response = curl_exec($ch);
curl_close($ch);
$version = 0;
$json = json_decode($response,true);
foreach($json as $val){
    if(floatval($val["tag_name"])>$version){
        //バージョンがデカければ、versionをそれで上書きしてforeachを続行する。
        $version = floatval($val["tag_name"]);
    }
}
//最新バージョンを取得できた！
$version = strval($version);
$fhd = fopen("./tmp/update.zip","w");
//ファイルをダウンロード
fwrite($fhd,file_get_contents("https://github.com/49372s/TTR-CMS/archive/refs/tags/".$version.".zip"));
fclose($fhd);

//展開
$zip = new ZipArchive;
$res = $zip->open('./tmp/update.zip');
if ($res === TRUE) {
    $zip->extractTo('./tmp/'.$version.'/');
    $zip->close();
} else {
    APIResponse(false,"Failed select zip file");
}
/**アップデートバージョン制限
 * アップデートバージョン制限を行う
 * これは、いきなり数バージョン飛ばしてアップデートを行った際に、一度でアップデートが完了しない…つまり、newfile.phpからupdate.phpに移動されたファイルがアップデートされないという現象を回避するためのものである。
 * これをかけることで、段階的なアップデートが必要となる。なお、この制限は手動で追加する(脳筋)
 */
$now_version = file_get_contents(VS_DR."/api/system/version.txt");//現在のバージョンを取得
include(VS_DR."/api/system/update/tmp/$version/cms-$version/restrict.php");
if(!empty($restrict[$now_version])){
    if($restrict[$now_version]<floatval($version)){
        //アップデートができないので一度制限内での最新バージョンで更新を行う
        //バージョンオーバー時点で最大制限バージョンが存在することは確定しているため、直接fwriteを行う
        $fhd = fopen("./tmp/update.zip","w");
        //ファイルをダウンロード
        fwrite($fhd,file_get_contents("https://github.com/49372s/TTR-CMS/archive/refs/tags/".strval($restrict[$now_version]).".zip"));
        fclose($fhd);
        //展開
        $zip = new ZipArchive;
        $res = $zip->open('./tmp/update.zip');
        if ($res === TRUE) {
            $zip->extractTo('./tmp/'.strval($restrict[$now_version]).'/');
            $zip->close();
        } else {
            APIResponse(false,"Failed select zip file");
        }
    }
}
//アップデートするファイルをconfigから読み出す
include(VS_DR."/core/resource/config/update.php");
$target = UPDATE_FILE_LIST;
//アップデートしないファイルをconfigから読みだす
if(file_exists(VS_DR."/core/resource/config/reject.php")){
    include(VS_DR."/core/resource/config/reject.php");
    $reject = UPDATE_REJECT_LIST;
}else{
    $reject = array();
}
//パスを設定する
//from
$from = VS_DR."/api/system/update/tmp/$version/TTR-CMS-$version/";
//to
$to = VS_DR."/";
//コピーを開始
foreach($target as $file){
    $reject_ = false;
    foreach($reject as $rejected){
        if($file == $rejected){
            $reject_ = true;
        }
    }
    if($reject_ == true){
        continue;
    }
    if(!copy($from.$file,$to.$file)){
        APIResponse(false,"An error was detected while copying the file. File copy was forcibly terminated.<br>For more information, please contact a technician.");
    }
}
//コピーを完了。次はConfig.phpに載っていない新規ファイルのコピー。
if(file_exists($from."new_file.php")){
    include($from."new_file.php");
    //必ず変数は($newfilesforupdate)にしてください。
    foreach($newFilesForUpdate as $file){
        if(!copy($from.$file,$to.$file)){
            APIResponse(false,"An error was detected while copying the file. File copy was forcibly terminated.<br>For more information, please contact a technician.");
        }
    }
}
//新規ファイルの作成完了。次はデータベースへのテーブル作成。
if(file_exists($from."new_table.sql")){
    $pdo = cdb();
    //SQlファイルを一度変数にダンプする
    $sql_query = file_get_contents($from."new_table.php");
    //ダンプしたSQLファイルを実行する。
    $result = $pdo->query($sql_query);
    //結果を見て、失敗した場合はAPIレスポンスを返す
    if($result == 0 || $result == false){
        APIResponse(false,"An error was detected while inserting the table. SQL file was invalid syntax. Please report this error to this project's issue.");
    }
    //切断
    $pdo = null;
}
$fhd = fopen(VS_DR."/api/system/version.txt","w");
fwrite($fhd,$version);
fclose($fhd);
APIResponse(true,"アップデートを適用しました");
?>