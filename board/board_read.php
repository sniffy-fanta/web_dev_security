<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
    session_start();

    $post_id = $_GET['idx'];
    $sql = "SELECT * FROM board WHERE idx=$post_id";
    $result = $mysqli->query($sql);
    $row = $result->fetch_assoc();

    $user_id = $_SESSION['user_id'];

    if($user_id != $row['author']){
	    $sql_views = "UPDATE board SET  views = views + 1 WHERE idx=$post_id";
	    $result_views = $mysqli->query($sql_views);
    }

    $likes = false;
    if($user_id){
        $like_sql = "SELECT 1 FROM post_likes WHERE post_id=$post_id AND user_id='$user_id' LIMIT 1";
        $like_result = $mysqli->query($like_sql);
        if($like_result && $like_result->fetch_assoc()){
            $likes =true;
        }
    }
?>



<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/board.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            <p><?=htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?>
            <div class="post_meta">
                작성자: <?=htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8')?> | 작성일: <?=htmlspecialchars($row['post_date'], ENT_QUOTES, 'UTF-8')?> | 조회수<?=htmlspecialchars($row['views'], ENT_QUOTES, 'UTF-8')?> 좋아요<?=htmlspecialchars($row['likes'], ENT_QUOTES, 'UTF-8')?>
                <?php if($user_id && $user_id !== $row['author']): ?>
                    <span class="like_btn">
                    <?php if($likes): ?>
                        <form action="/board/unlike.php" method="POST">
                            <input type="hidden" name="post_id" value=<?= htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8')?>>
                            <button type="submit" class="unlike"><i class="fa-solid fa-heart"></i></button>
                        </form>
                    <?php else: ?>
                        <form action="/board/like.php" method="POST">
                            <input type="hidden" name="post_id" value=<?= htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8')?>>
                            <button type="submit" class="like"><i class="fa-regular fa-heart"></i></button>
                        </form>
                    <?php endif; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="read_content">
            <p><?=nl2br(htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'))?></p>
        </div>
        <div class="download_file">
            <a href="/board/uploads/<?= htmlspecialchars($row['file'], ENT_QUOTES, 'UTF-8')?>"download><?= htmlspecialchars($row['file'], ENT_QUOTES, 'UTF-8')?></a>
        </div>
        <?php if($user_id === htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8')):?>
            <div class="btn3">
                <form action="/board/board_modify.php" method="GET">
                    <input type="hidden" name="idx" value="<?= htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8')?>">
                    <button type="submit">수정하기</button>
                </form>
                <form action="/board/board_delete.php" method="POST">
                    <input type="hidden" name="idx" value="<?= htmlspecialchars($row['idx'], ENT_QUOTES, 'UTF-8')?>">
                    <button type="submit">삭제하기</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>