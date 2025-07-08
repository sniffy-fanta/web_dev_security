<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';

    $post_id = $_GET['idx'];
    $sql = "SELECT * FROM board WHERE idx=$post_id";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<script>
                alert('오류가 발생했습니다.');
                history.back();
            </script>";
            exit;
    }

    if($_SESSION['user_id'] != $row['author']){
        echo "<script>
                alert('권한이 없습니다');
                history.back();
            </script>";
            exit;
    }
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
        <h1>Modify</h1>
        <div class="nav">
            <a href="board.php">회원게시판</a>
            <a href="/pages/main.php">메인페이지</a>
            <a href="/pages/mypage.php">마이페이지</a>
        </div>
        <hr>
    </header>
    <div class="write_wrapper">
        <form action="/board/board_modify_proc.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" value="<?=htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8')?>" required>
            <textarea name="content"><?=htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8')?></textarea>
            <?php if (!empty($row['file'])): ?>
                <input type="file" name="upload_file" id="file_btn">
                <div class="write_btn2">
                    현재 파일: <?=htmlspecialchars($row['file'], ENT_QUOTES, 'UTF-8')?>
                    <br>
                    파일 삭제 <input type="checkbox" name="delete_file" value="1">
                    <input type="hidden" name="idx" value="<?=htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8')?>">
                    <button type="submit">수정하기</button>
                </div>
            <?php else: ?>
                <input type="file" name="upload_file" id="file_btn">
                <div class="write_btn2">
                    <input type="hidden" name="idx" value="<?=htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8')?>">
                    <button type="submit">수정하기</button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>