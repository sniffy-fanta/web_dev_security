<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_GET['idx'];
    $sql = "SELECT title,author,content,post_date FROM board WHERE idx=$post_id";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/board.css">
    <title>게시글</title>
</head>
<body>
    <header class="board_title">
        <h1>Read</h1>
        <div class="nav">
            <a href="board.php">회원게시판</a>
            <a href="/pages/main.php">메인페이지</a>
            <a href="/pages/mypage.php">마이페이지</a>
        </div>
        <hr>
    </header>
    <div class="read_wrapper">
        <div class="read_title">
            <p><?=$row['title'] ?>
            <div class="post_meta">
                작성자: <?=$row['author']?> | 작성일: <?=$row['post_date']?>
            </div>
        </div>
        <div class="read_content">
            <p><?=nl2br($row['content'])?></p>
        </div>
        <div class="btn3">
            <form action="/board/board_modify.php">
                <button type="submit">수정하기</button>
            </form>
            <form action="/board/board_delete.php">
                <button type="submit">삭제하기</button>
            </form>
        </div>
    </div>
</body>
</html>