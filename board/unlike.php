<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_POST['post_id'];

    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('로그인이 필요합니다.'); location.href='/pages/login.php';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("DELETE FROM post_likes WHERE post_id=? AND user_id=? LIMIT 1");
    $stmt->bind_param("is", $post_id, $user_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("UPDATE board SET likes = likes-1 WHERE idx=?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
            location.href = '/board/board_read.php?idx=$post_id';
        </script>";
        exit;
?>