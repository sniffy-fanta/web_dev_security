<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/register.css">
</head>
<body>
    <div class="register_box">
        <h2>회원가입</h2>
        <form action="../proc/register_proc.php" method="POST">
            <div class="input_field">
                <input type="text" name="user_id" placeholder="아이디" required>
                <button type="submit" name="action" value="check" id="check_id">중복확인</button>

                <input type="password" name="user_pw" placeholder="비밀번호" required>
                <input type="text" name="name" placeholder="이름" required>
                <input type="text" name="address" placeholder="주소" required>
                <button type="submit" name="action" value="register" id="register_btn">가입하기</button>
            </div>
        </form>
    </div>
    <script src="/JS/register.js"></script>
</body>
</html>