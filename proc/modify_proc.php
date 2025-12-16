<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/validation.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';

    //CSRF 토큰 확인
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        session_unset();
        session_destroy(); 

        echo "
            <script>
                alert('보안 토큰이 유효하지 않습니다.');
                location.href='/pages/modify.php';
            </script>";
        exit;
    }

    //수정값 변수에 할당
    $user_id = $_POST['user_id'];
    $current_user_pw = $_POST['current_user_pw'];
    $user_pw = $_POST['user_pw'];
    $name = $_POST['name'];
    $address = $_POST['address'];

    //비밀번호 유효성 검사
    if(!empty($user_pw)){
        $pw_num = preg_match('/[0-9]/', $user_pw);
        $pw_alpha = preg_match('/[A-Za-z]/', $user_pw);
        $pw_special = preg_match('/[!@#$%^&*()_+\-=~]/', $user_pw);
        
        $pw_count = $pw_num + $pw_alpha + $pw_special;
        
        if(strlen($user_pw) < 10 || $pw_count < 2){
            echo "<script>alert('비밀번호는 영문, 숫자, 특수문자를 조합하여 10자 이상으로 설정하세요.'); history.back();</script>";
            exit;
        }
    }

    //입력 값 유효성 검사
    $error = validation_input($user_id, $user_pw, $name, $address);
    if ($error !== '') {
        echo "<script>alert('$error');
        history.back();
        </script>";
        exit;
    }


    //기존 사용자 정보 가져오기
    $session_user_id = $_SESSION['user_id'];
    $sql = "SELECT userid, userpw, name, address FROM users WHERE userid=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $session_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    //현재 비밀번호 확인
    if ($current_user_pw !== $row['userpw']) {
        echo "<script>alert('현재 비밀번호가 일치하지 않습니다.'); history.back();</script>";
        exit;
    }

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
    $update_fields = [];
    $params = [];
    $types = '';

    if($user_id !== $row['userid']){
        $update_fields[] = "userid=?";
        $params[] = $user_id;
        $types .= 's';
    }
    if($user_pw !== $row['userpw']){
        $update_fields[] = "userpw=?";
        $params[] = $user_pw;
        $types .= 's';
    }
    if($name !== $row['name']){
        $update_fields[] = "name=?";
        $params[] = $name;
        $types .= 's';
    }
    if($address !== $row['address']){
        $update_fields[] = "address=?";
        $params[] = $address;
        $types .= 's';
    }

    if(count($update_fields) > 0){
        $update_sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE userid=?";
        $params[] = $session_user_id;
        $types .= 's';

        $stmt = $mysqli->prepare($update_sql);

        $stmt->bind_param($types, ...$params);
        $update_result = $stmt->execute();
        $stmt->close();

        if($update_result){
            //수정 성공 시 csrf 토큰 삭제
            unset($_SESSION['csrf_token']);

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