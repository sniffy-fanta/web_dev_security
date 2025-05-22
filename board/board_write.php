<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글작성</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/board.css">
</head>
<body>
    <header class="board_title">
        <h1>Write</h1>
        <div class="nav">
            <a href="board.php">회원게시판</a>
            <a href="/pages/main.php">메인페이지</a>
            <a href="/pages/mypage.php">마이페이지</a>
        </div>
        <hr>
    </header>
    <div class="write_wrapper">
        <form action="/board/board_write_proc.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="제목" required>
            <textarea name="content" placeholder="내용을 입력하세요."></textarea>
            <input type="file" name="upload_file" id="file_btn">
            <div class="write_btn2">
                <button type="submit">작성하기</button>
            </div>
        </form>
    </div>
</body>
</html>