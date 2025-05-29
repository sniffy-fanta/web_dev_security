<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    $sql_check1 = "SELECT author FROM board WHERE idx=$post_id";
    $res_check1 = $mysqli->query($sql_check1);
    $row = $res_check1-> fetch_assoc();

    if($user_id === $row['author']){
        echo "<script>
                alert('자신의 게시글에는 좋아요를 누를 수 없습니다.');
                history.back();
            </script>";
        exit;
    }

    $sql_check2 = "SELECT 1 FROM post_likes WHERE post_id=$post_id and user_id='$user_id' LIMIT 1";
    $res_check2 = $mysqli->query($sql_check2);

    if($res_check2 && $res_check2->fetch_assoc()){
        echo "<script>alert('이미 좋아요를 눌렀습니다.');
                history.back();
            </script>";
        exit;
    }

    $sql = "UPDATE board SET likes = likes +1 WHERE idx=$post_id;
    INSERT INTO post_likes (post_id,user_id) VALUES ('$post_id','$user_id');";
    $result = $mysqli->multi_query($sql);

    echo "<script>
            location.href = '/board/board_read.php?idx=$post_id';
        </script>";
        exit;
?>