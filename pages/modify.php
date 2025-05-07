<?php
require_once "../php/db.php";
require_once "../php/session_guard.php";

$user_id = $_SESSION['user_id'];

// DB 조회
$sql = "SELECT userid, userpw, name, address FROM users WHERE userid='$user_id'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// 임시세션 temp_user_id 세팅
if (!isset($_SESSION['temp_user_id'])) {
    $_SESSION['temp_user_id'] = $row['userid'];
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>수정페이지</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/modify.css">
</head>
<body>
    <header class="modify_title">
        <h1>Check Info</h1>
        <hr>
    </header>

    <div class="table_wrapper">
        <div class="modify_container">
            <form action="../proc/modify_proc.php" method="POST">
                <table class="modify_table">
                    <tr>
                        <th>아이디</th>
                        <td>
                            <input type="text" name="user_id" 
                                value="<?= $_SESSION['temp_user_id'] ?>" readonly onclick="edit(this);">
                            <button type="submit" formaction="../pages/modify.php" name="action" value="check" id="check_id">중복확인</button>
                        </td>
                    </tr>
                    <tr>
                        <th>비밀번호</th>
                        <td>
                            <input type="password" name="user_pw" value="<?= $row['userpw'] ?>" readonly onclick="edit(this);">
                        </td>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <td>
                            <input type="text" name="name" value="<?= $row['name'] ?>" readonly onclick="edit(this);">
                        </td>
                    </tr>
                    <tr>
                        <th>주소</th>
                        <td>
                            <input type="text" name="address" value="<?= $row['address'] ?>" readonly onclick="edit(this);">
                        </td>
                    </tr>
                </table>
                <div class="btn">
                    <button type="submit">수정하기</button>
                </div>
            </form>
        </div>
    </div>

    <script src="/JS/session.js"></script>
    <script src="/JS/edit.js"></script>
</body>
</html>

<?php
// 중복확인 처리
if (isset($_POST['action']) && $_POST['action'] === 'check') {
    $check_user_id = trim($_POST['user_id']);

    // 아이디 유효성 검사
    if (!preg_match('/^[a-z0-9]+$/', $check_user_id)) {
        echo "<script>alert('아이디는 소문자와 숫자만 사용 가능합니다.'); history.back();</script>";
        exit;
    }

    // 중복검사
    $check_sql = "SELECT userid FROM users WHERE userid='$check_user_id'";
    $result_id = $mysqli->query($check_sql);

    if ($result_id && $result_id->num_rows > 0) {
        unset($_SESSION['temp_user_id']);
        echo "<script>alert('중복된 아이디입니다.'); history.back();</script>";
        exit;
    } else {
        $_SESSION['temp_user_id'] = $check_user_id;
        echo "<script>alert('사용 가능한 아이디입니다.'); history.back();</script>";
        exit;
    }
}
?>
