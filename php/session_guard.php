<?php
//세션 시작
session_start();
//아이디값 없을 시 로그인창으로 리다이렉션
if(!isset($_SESSION['user_id'])){
    header("Location: /pages/login.php");
    exit;
}
//만료시간 설정
$timeout = 900;
//만료시 세션삭제 및 로그인창으로 리다이렉션
if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout){
    session_unset();
    session_destroy();
    header("Location: /pages/login.php");
    exit;
}
//현재시간할당
 $_SESSION['last_activity'] = time();
?>