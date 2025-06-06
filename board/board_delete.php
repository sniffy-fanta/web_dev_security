<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_POST['idx'];
    $result = $mysqli->query("SELECT * FROM board WHERE idx=$post_id");
    $row = $result->fetch_assoc();

    //작성자 확인
    if($_SESSION['user_id'] != $row['author']){
        echo "<script>
                alert('권한이 없습니다');
                history.back();
            </script>";
            exit;
    }
    //파일 있을 때 경로삭제
    if($row['file']){
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/board/uploads/'.$row['file'];
        if(file_exists($file_path)){
            unlink($file_path);
        }
    }
    //좋아요 테이블 데이터 삭제
    $mysqli->query("DELETE FROM post_likes WHERE post_id=$post_id");
    //나머지 데이터 삭제
    $mysqli->query("DELETE FROM board WHERE idx=$post_id");

    echo "<script>
            alert('게시글이 삭제되었습니다.');
            location.replace('/board/board.php');
        </script>";
        exit;
?>