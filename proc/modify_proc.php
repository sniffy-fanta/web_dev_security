<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';

    //수정값 변수에 할당
    $user_id = $_POST['user_id'];
    $user_pw = $_POST['user_pw'];
    $name = $_POST['name'];
    $address = $_POST['address'];

    //기존 사용자 정보 가져오기
    $session_user_id = $_SESSION['user_id'];
    $sql = "SELECT userid,userpw,name,address from users WHERE userid='$session_user_id'";
    $result=$mysqli->query($sql);
    $row = $result->fetch_assoc();

    // 아이디가 바뀌었으면 중복확인 했는지 체크
    if ($user_id !== $row['userid']) {
        if (!isset($_SESSION['temp_user_id']) || $_SESSION['temp_user_id'] !== $user_id) {
            echo "
                <script>
                    alert('아이디를 변경하려면 중복확인을 먼저 해주세요.');
                    history.back();
                </script>";
            exit;
        }
    }
    
    //수정된 내용만 배열에 넣기
    $update_fields=[];
    if($user_id !== $row['userid']){
        $update_fields[]="userid='$user_id'";
    }
    if($user_pw !== $row['userpw']){
        $update_fields[]="userpw='$user_pw'";
    }
    if($name !== $row['name']){
        $update_fields[]="name='$name'";
    }
    if($address !== $row['address']){
        $update_fields[]="address='$address'";
    }

    if(count($update_fields) > 0){
        $update_sql = "UPDATE users SET " . implode(',',$update_fields) . "WHERE userid='$session_user_id'";
        $update_result = $mysqli->query($update_sql);

        if($update_result){
            if($user_id !== $row['userid']){
                $_SESSION['user_id'] = $user_id;
                unset($_SESSION['temp_user_id']);
            }
            echo "
                <script>
                    alert('수정이 완료되었습니다.'); 
                    location.href='/pages/mypage.php';
                </script>";
        }else{
            unset($_SESSION['temp_user_id']);
            echo "
                <script>
                    alert('수정에 실패했습니다.'); 
                    history.back();
                </script>";
        }
    }else{
        unset($_SESSION['temp_user_id']);
        echo "
            <script>
                alert('변경사항이 없습니다.');
                location.href='/pages/mypage.php';
            </script>";
    }