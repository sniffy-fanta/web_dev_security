<?php
session_start(); //세션시작
session_unset(); //세션 변수 삭제
session_destroy();//세션 초기화
echo "<script>
    alert('로그아웃 되었습니다.');
    location.href='../pages/login.php';
    </script>";
exit;
?>