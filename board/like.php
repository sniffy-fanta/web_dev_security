<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $sql_check1 = "SELECT author FROM board WHERE idx=?";
    $stmt_check1 = $mysqli->prepare($sql_check1);
    $stmt_check1->bind_param("i", $post_id);
    $stmt_check1->execute();
    $res_check1 = $stmt_check1->get_result();
    $row = $res_check1->fetch_assoc();

    if($user_id === $row['author']){
        echo "<script>
                alert('자신의 게시글에는 좋아요를 누를 수 없습니다.');
                history.back();
            </script>";
        exit;
    }

    $sql_check2 = "SELECT 1 FROM post_likes WHERE post_id=? and user_id=? LIMIT 1";
    $stmt_check2 = $mysqli->prepare($sql_check2);
    $stmt_check2->bind_param("is", $post_id, $user_id);
    $stmt_check2->execute();
    $res_check2 = $stmt_check2->get_result();

    if($res_check2 && $res_check2->fetch_assoc()){
        echo "<script>alert('이미 좋아요를 눌렀습니다.');
                history.back();
            </script>";
        exit;
    }

    $sql_update = "UPDATE board SET likes = likes + 1 WHERE idx=?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param("i", $post_id);
    $stmt_update->execute();

    $sql_insert = "INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)";
    $stmt_insert = $mysqli->prepare($sql_insert);
    $stmt_insert->bind_param("is", $post_id, $user_id);
    $stmt_insert->execute();

    echo "<script>
            location.href = '/board/board_read.php?idx=$post_id';
        </script>";
        exit;
?>