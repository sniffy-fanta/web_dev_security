<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $delete_sql = "DELETE FROM post_likes where post_id=$post_id AND user_id='$user_id' LIMIT 1;
    UPDATE board SET likes = likes-1 WHERE idx=$post_id;";
    $delete_res = $mysqli->multi_query($delete_sql);

    echo "<script>
            location.href = '/board/board_read.php?idx=$post_id';
        </script>";
        exit;
?>