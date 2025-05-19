<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/board/dummy_data.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';

$author = $_SESSION['user_id'];

for ($i = 1; $i <= 50; $i++) {
    $title = "테스트글 $i";
    $content = "이것은 테스트용 게시글입니다.";
    $post_date = date("Y-m-d H:i:s");
    $views = 0;
    $likes = 0;

    $sql = "
        INSERT INTO board (title, content, author, post_date, views, likes)
        VALUES ('$title', '$content', '$author', '$post_date', $views, $likes)
    ";

    $mysqli->query($sql);
}

echo "더미 게시글 50개 삽입 끝";
?>
