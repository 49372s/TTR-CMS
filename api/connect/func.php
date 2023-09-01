<?php
define('VS_DR',$_SERVER['DOCUMENT_ROOT']);

function cdb(){
    include($_SERVER['DOCUMENT_ROOT'].'/api/info/key.php');
    $pdo = new PDO("mysql:host=".$DATABASE_LOGIN_INFORMATION["host"].";dbname=".$DATABASE_LOGIN_INFORMATION["database"].";charset=utf-8",$DATABASE_LOGIN_INFORMATION['user'],$DATABASE_LOGIN_INFORMATION['password']);
    $DATABASE_LOGIN_INFORMATION = null;
    return $pdo;
}
function loginCheck(){
    if(empty($_COOKIE['session'])){
        return false;
    }
    $sql = "SELECT * from account";
    $pdo = cdb();
    $res = $pdo->query($sql);
    foreach($res as $val){
        if(md5($val[0].date('Ym'))==$_COOKIE['session']){
            return true;
        }
    }
    return false;
}

function loginRedirect($mode = false){
    if(loginCheck()){
        if($mode == true){
            http_response_code(401);
            header('Location: /dashboard/login.php');
            exit();
        }
    }else{
        if($mode == false){
            http_response_code(401);
            header('Location: /dashboard/login.php');
            exit();
        }
    }
}

function APIResponse($flug=false,$data=null){
    header('Content-Type: application/json;charset=UTF-8;');
    echo json_encode(array("result"=>$flug,"data"=>$data));
    exit();
}

function getSHA($str){
    return hash("sha3-512",$str);
}

function emptyCheck($keys,$mode="get"){
    foreach($keys as $key){
        if($mode=="get"){
            if(empty($_GET[$key])){
                APIResponse(false,"Bad request(GET).");
            }
        }else{
            if(empty($_POST[$key])){
                APIResponse(false,"Bad request(POST).".$key);
            }
        }
    }
}

function getCategory($mode=0,$q=null){
    if($mode==0){
        $pdo = cdb();
        $res = $pdo->query("SELECT * from category");
        foreach($res as $val){
            if($val[0] == $q || $val[1] == $q){
                return true;
            }
        }
        return false;
    }elseif($mode==1){
        $pdo = cdb();
        $res = $pdo->query("SELECT * from category");
        $html = "";
        foreach($res as $val){
            $html = $html . '<option value="'.$val[0].'">'.$val[1].'</option>'."\n";
        }
        APIResponse(true,$html);
    }
}

function searchCat($id){
    $pdo = cdb();
    $res = $pdo->query("SELECT * from category");
    $category = array();
    foreach($res as $val){
        if($id == $val[0]){
            return $val[1];
        }
    }
    return false;
}

function getArticle($id){
    $pdo = cdb();
    $sql = "SELECT * from article";
    $res = $pdo->query($sql);
    foreach($res as $val){
        if($val[0]==$id){
            $html = file_get_contents(VS_DR."/data/blog/$id.html");
            return array("title"=>$val[1],"author"=>$val[2],"category"=>$val[3],"lastUpdate"=>$val[4],"html"=>$html);
        }
    }
}
?>