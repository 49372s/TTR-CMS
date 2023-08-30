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
?>