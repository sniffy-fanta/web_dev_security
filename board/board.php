<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $sql = "SELECT idx,title,author,post_date,views,likes FROM board ORDER BY idx DESC";
    $result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/board.css">
</head>
<body>
    <header class="board_title">
        <h1>Board</h1>
        <div class="nav">
            <a href="/board/board.php">회원게시판</a>
            <a href="/pages/main.php">메인페이지</a>
            <a href="/pages/mypage.php">마이페이지</a>
        </div>
        <hr>
    </header>
    <div class="write_btn1">
        <a href="/board/board_write.php">글쓰기</a>
    </div>
    <table class="board_table">
        <thead>
            <tr>
                <th>POST ID</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>조회수</th>
                <th id="emot">💌</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($row = $result->fetch_assoc()){
        ?>
            <tr>
                <td><?= $row['idx']; ?></td>
                <td><?= $row['title']; ?></td>
                <td><?= $row['author']; ?></td>
                <td><?= $row['post_date']; ?></td>
                <td><?= $row['views']; ?></td>
                <td><?= $row['likes']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>