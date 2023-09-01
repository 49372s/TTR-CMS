<?php

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
?>