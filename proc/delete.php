<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    if(!$user_id){
        echo "<script>
            alert('다시 로그인해주세요.');
            location.href='/pages/login.php';
            </script>";
            exit;
    }

    // users 테이블에서 삭제
    $stmt = $mysqli->prepare("DELETE FROM users WHERE userid=?");
    $stmt->bind_param("s", $user_id);
    $result1 = $stmt->execute();
    $stmt->close();

    // post_likes 테이블에서 삭제
    $stmt = $mysqli->prepare("DELETE FROM post_likes WHERE user_id=?");
    $stmt->bind_param("s", $user_id);
    $result2 = $stmt->execute();
    $stmt->close();

    // board 테이블에서 삭제
    $stmt = $mysqli->prepare("DELETE FROM board WHERE author=?");
    $stmt->bind_param("s", $user_id);
    $result3 = $stmt->execute();
    $stmt->close();

    //sql이 실행이 됐다면
    if($result){
        session_unset(); //변수삭제
        session_destroy(); //세션초기화

        echo "<script>
            alert('탈퇴 되었습니다.');
            location.replace('/pages/login.php');
            </script>";
            exit;
    }
    else{
        echo "<script>
            alert('탈퇴중 오류가 발생했습니다. 나중에 다시 시도해주세요.');
            history.back();
            </script>";
            exit;
    }
?>