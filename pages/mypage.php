<?php
    //DB시작
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
    //세션시작 및 만료시간 설정
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    
    if (!isset($_SESSION['user_id'])) {
        echo "<script>
            alert('로그인이 필요합니다.');
            location.href='/pages/login.php';
            </script>";
            exit;
        }
        
    $user_id = $_SESSION['user_id'];

    //아이디,이름,주소 DB에서 조회
    $sql = "SELECT userid, name, address FROM users WHERE userid=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
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
        <div class="nav">
            <a href="/board/board.php">회원게시판</a>
            <a href="/pages/main.php">메인페이지</a>
            <a href="/pages/mypage.php">마이페이지</a>
        </div>
        <hr>
    </header>
    <div class="table_wrapper">
        <div class="mypage_container">
            <table class="mypage_table">
                <tr>
                    <th>아이디</th>
                    <!--DB에서 아이디 가져온 값-->
                    <td><?= htmlspecialchars($row['userid'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <tr>
                    <th>이름</th>
                    <!--DB에서 이름 가져온 값-->
                    <td><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <tr>
                    <th>주소</th>
                    <!--DB에서 주소 가져온 값-->
                    <td><?= htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            </table>
            <div class="btn">
                <button onclick="location.href='/pages/modify.php'">수정하기</button> 
                <form action="/proc/delete.php" method="POST" onclick="return confirm('정말 탈퇴하시겠습니까?')">
                    <button type="submit">탈퇴하기</button>
                </form>
            </div>
        </div>
    </div>
    <script src="/JS/session.js"></script>
</body>
</html>