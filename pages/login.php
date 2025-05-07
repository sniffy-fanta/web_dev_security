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
        <form action="../proc/login_proc.php" method="POST">
            <div class="input_field">
                <input type="text" name="user_id" placeholder="아이디" required>
                <input type="password" name="user_pw" placeholder="비밀번호" required>
            </div>
            <a href="../pages/register.php" id="register_link">회원가입</a>
            <button type="submit">로그인</button>
        </form>
    </div>
</body>
</html>