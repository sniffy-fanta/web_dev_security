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

    //데이터베이스ID와 세션유저ID값이 같다면 해당 행 삭제
    $sql = "DELETE FROM users WHERE userid='$user_id'";
    $result = $mysqli->query($sql);
    $sql = "DELETE FROM post_likes WHERE userid='$user_id'";
    $result = $mysqli->query($sql);
    $sql = "DELETE FROM board WHERE userid='$user_id'";
    $result = $mysqli->query($sql);

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