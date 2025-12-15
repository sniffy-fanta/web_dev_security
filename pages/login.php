<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php'; 

    $msg = $_SESSION['login_error'] ?? '';
    $locked = !empty($_SESSION['login_switch']);
    
    //잠금 해제 확인 및 처리
    if ($locked && !empty($_SESSION['last_failed_id'])) {
        $last_id = $_SESSION['last_failed_id'];

        //실패한 마지막 ID의 lock_until 값 조회
        $sql = "SELECT lock_until FROM users WHERE userid = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $last_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row['lock_until'] !== NULL) {
            $now = new DateTime('now', new DateTimeZone('UTC'));
            $lock_until = new DateTime($row['lock_until'], new DateTimeZone('UTC'));

            if ($lock_until <= $now) {
                //잠금 시간 경과 (잠금 해제)
                $update_sql = "UPDATE users SET failed_cnt = 0, lock_until = NULL WHERE userid = ?";
                $upStmt = $mysqli->prepare($update_sql);
                $upStmt->bind_param("s", $last_id);
                $upStmt->execute();

                // 세션 변수 해제
                unset($_SESSION['login_switch']); 
                unset($_SESSION['last_failed_id']); 
                unset($_SESSION['login_error']);

                $locked = false;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/login.css">
</head>
<body>
    <div class="login_box">
        <h2>로그인</h2>
        <?php if ($locked): ?>
            <!-- 잠금 로그인 뷰 -->
            <div class="alert"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php else: ?>
            <!-- 일반 로그인 폼 -->
            <form action="/proc/login_proc.php" method="POST">
                <div class="input_field">
                    <input type="text" name="user_id" placeholder="아이디" autocomplete="off" required>
                    <input type="password" name="user_pw" placeholder="비밀번호" autocomplete="off" required>
                </div>
                <a href="/pages/register.php" id="register_link">회원가입</a>
                <button type="submit">로그인</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>