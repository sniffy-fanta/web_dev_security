<?php
require_once "../php/session_guard.php";
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>메인페이지</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/main.css">
</head>
<body>
    <header class="main_title">
        <h1>MAIN</h1>
        <hr>
    </header>
    <div class="main_wrapper">
        <div class="main_container">
            <div class="user_header">
                <img src="https://static.nid.naver.com/images/web/user/default.png?type=s160">    
                <div class="user_info">
                    <h1><?= $user_id."님" ?></h1>
                    <span class="logout_btn" onclick="location.href='../proc/logout.php'">로그아웃</span>
                </div>
            </div>
            <div class="divider_line"></div>   
            <div class="nav_area">
                <div class="nav_btn">
                    <button onclick="location.href='../pages/mypage.php'">마이페이지</button>
                    <button onclick="location.href='../pages/board.php'">게시판</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/JS/session.js"></script>
</body>
</html>