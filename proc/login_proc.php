<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

//입력값 변수에 저장하기
$user_id = $_POST['user_id'] ?? '';
$user_pw = $_POST['user_pw'] ?? '';

//로그인 실패 시 마지막으로 시도한 ID 저장
$_SESSION['last_failed_id'] = $user_id;

//로그인 일반 실패 함수
function common_failed_login($msg){
    echo "<script>
            alert('{$msg}');
            history.back();
        </script>";
        exit;
}

//로그인 잠금 처리 함수
function locked_login($msg){
    $_SESSION['login_error'] = $msg;
    $_SESSION['login_switch'] = 1;
    header("Location:/pages/login.php");
    exit;
}

//입력받은 아이디의, 비밀번호, 실패 카운트, 잠금 해제 시간 DB에서 조회
$sql = "SELECT userpw, failed_cnt, lock_until FROM users WHERE userid= ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

//결과가 없거나 0개라면 실패
if(!$result || $result->num_rows === 0){
    common_failed_login("아이디 또는 비밀번호가 틀렸습니다.");
}
$row = $result->fetch_assoc();

//잠금 상태 확인
if(($row['lock_until']) !== NULL){
    $now = new DateTime('now', new DateTimeZone('UTC')); //현재시간
    $lock_until = new DateTime($row['lock_until'], new DateTimeZone('UTC')); //잠금 해제 시간

    if($lock_until > $now){
        locked_login("잠금 된 상태입니다. 10분 후 다시 시도해 주세요.");
    } else{
        $update_sql = "UPDATE users SET failed_cnt = 0, lock_until = NULL WHERE userid = ?";
        $upStmt = $mysqli -> prepare($update_sql);
        $upStmt -> bind_param("s", $user_id);
        $upStmt -> execute(); 
        
        unset($_SESSION['login_switch']); 
        $row['failed_cnt'] = 0; 
        $row['lock_until'] = NULL;
    }
}

//비밀번호 비교
if($row['userpw'] === $user_pw) {
    //로그인 성공 (실패 횟수 초기화)
    $success_sql = "UPDATE users SET failed_cnt = 0, lock_until = NULL WHERE userid = ?";
    $suStmt = $mysqli -> prepare($success_sql);
    $suStmt -> bind_param("s", $user_id);
    $suStmt -> execute();

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
    header("Location:/pages/main.php");
    exit;
} else {
    //로그인 실패
    $failed_cnt = $row['failed_cnt'] + 1;

    if($failed_cnt >= 5){
        // 로그인 5회 이상 실패 (잠금)
        $locked_sql = "UPDATE users SET failed_cnt = 0, lock_until = (UTC_TIMESTAMP() + INTERVAL 10 MINUTE) WHERE userid = ?";
        $lockStmt = $mysqli -> prepare($locked_sql);
        $lockStmt -> bind_param("s", $user_id);
        $lockStmt -> execute();

        locked_login("5회 이상 실패하여 10분간 로그인이 잠금되었습니다.");
    }else {
        //실패횟수 누적
        $update2_sql = "UPDATE users SET failed_cnt = ? WHERE userid = ?";
        $up2Stmt = $mysqli -> prepare($update2_sql);
        $up2Stmt -> bind_param("is", $failed_cnt, $user_id);
        $up2Stmt -> execute();

        $count = 5 - $failed_cnt;
        common_failed_login("로그인 실패, 남은 시도 횟수: {$count}회");
    }
}
?>