<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    $post_id = $_POST['idx'];

    $stmt = $mysqli->prepare("SELECT * FROM board WHERE idx = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        echo "<script>
                alert('게시글이 존재하지 않습니다.');
                history.back();
            </script>";
        exit;
    }

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
    $stmt = $mysqli->prepare("DELETE FROM post_likes WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
    //나머지 데이터 삭제
    $stmt = $mysqli->prepare("DELETE FROM board WHERE idx = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
            alert('게시글이 삭제되었습니다.');
            location.replace('/board/board.php');
        </script>";
        exit;
?>