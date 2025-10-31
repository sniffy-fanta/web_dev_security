<?php
    session_start();
    $msg = $_SESSION['login_error'] ?? '';
    $locked = !empty($_SESSION['login_switch']);
    unset($_SESSION['login_error']);
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
        <?php if ($msg): ?>
            <div class="alert"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if($locked): ?>
            <!-- 잠금 로그인 뷰 -->
            <p></p>
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