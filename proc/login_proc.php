<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

//입력값 변수에 저장하기
$user_id = $_POST['user_id'];
$user_pw = $_POST['user_pw'];
//입력받은 아이디의 비밀번호를 DB에서 조회
$sql = "SELECT userpw FROM users WHERE userid= ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

//$result 는 쿼리가 성공하기만 하면 true이기 때문에 결과가 0개여도 true를 반환
//$result-> num_rows > 0 은 1개이상의 결과가 있어야 하는데 result가 false라면 실행조차 x
if($result && $result->num_rows >0){
    $row = $result->fetch_assoc();

    if($row['userpw']===$user_pw){
        //DB비밀번호와 입력한 비밀번호가 같을 시 세션저장
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user_id;
        header("Location:/pages/main.php");
        exit;
    }
    else{
        header("Location:/proc/login_fail.php");
        exit;
    }
}
else{
    header("Location:/proc/login_fail.php");
    exit;
}
?>
