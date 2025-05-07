<?php
    //DB시작
    require_once "../php/db.php";
    //세션시작 및 만료시간 설정
    require_once "../php/session_guard.php";
    $user_id = $_SESSION['user_id'];
    //아이디,이름,주소 DB에서 조회
    $sql = "SELECT userid,name,address FROM users WHERE userid='$user_id'";
    $result = $mysqli-> query($sql);
    $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이페이지</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/mypage.css">
</head>
<body>
    <header class="mypage_title">
        <h1>My Page</h1>
        <hr>
    </header>
    <div class="table_wrapper">
        <div class="mypage_container">
            <table class="mypage_table">
                <tr>
                    <th>아이디</th>
                    <!--DB에서 아이디 가져온 값-->
                    <td><?= $row['userid'] ?></td>
                </tr>
                <tr>
                    <th>이름</th>
                    <!--DB에서 이름 가져온 값-->
                    <td><?= $row['name'] ?></td>
                </tr>
                <tr>
                    <th>주소</th>
                    <!--DB에서 주소 가져온 값-->
                    <td><?= $row['address'] ?></td>
                </tr>
            </table>
            <div class="btn">
                <button onclick="location.href='../pages/modify.php'">수정하기</button> 
                <button onclick="location.href='../pages/main.php'">메인페이지</button>
                <form action="../proc/delete.php" method="POST" onclick="return confirm('정말 탈퇴하시겠습니까?')">
                    <button type="submit">탈퇴하기</button>
                </form>
            </div>
        </div>
    </div>
    <script src="/JS/session.js"></script>
</body>
</html>