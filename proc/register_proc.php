<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/validation.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';


    //버튼확인
    $action =$_POST['action'];
    $user_id = trim($_POST['user_id']); //check,register 둘 다 쓰여서 전역변수로 선언


    if($action === 'check'){
        //중복확인
        $check_sql= "SELECT userid FROM users WHERE userid=?";
        $stmt = $mysqli->prepare($check_sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result_id = $stmt->get_result();
        
        if($result_id && $result_id->num_rows > 0){
            //아이디가 존재
             echo "
             <script>
                alert('중복된 아이디입니다.');
                history.back();
             </script>";
             exit;
        }
        else{
            echo "
            <script>
                alert('사용 가능한 아이디입니다.');
                history.back();
            </script>";
            exit;
        }
    }

    if($action === 'register'){
         //post로 전달된 입력값 받기
         $user_pw = trim($_POST['user_pw']);

         //비밀번호 유효성 검사
         $pw_num = preg_match('/[0-9]/', $user_pw);
         $pw_alpha = preg_match('/[A-Za-z]/', $user_pw);
         $pw_special = preg_match('/[!@#$%^&*()_+\-=~]/', $user_pw);

         $pw_count = $pw_num + $pw_alpha + $pw_special;

         if(strlen($user_pw) < 10 || $pw_count < 2){
            echo "<script>alert('비밀번호는 영문, 숫자, 특수문자를 조합하여 10자 이상으로 설정하세요.'); history.back();</script>";
            exit;
         }


         $name = trim($_POST['name']);
         $address = trim($_POST['address']);
        
         $error = validation_input($user_id,$user_pw,$name,$address);
            if($error !== ''){
                echo "<script>alert('$error');
                history.back();
                </script>";
                exit;
            }

         $sql = "INSERT INTO users (userid, userpw, name, address) VALUES (?, ?, ?, ?)";
         $stmt = $mysqli->prepare($sql);
         
         //비밀번호 해시 처리
         $hashed_pw = password_hash($user_pw, PASSWORD_DEFAULT);

         $stmt->bind_param("ssss", $user_id, $hashed_pw, $name, $address);
         $result = $stmt->execute();

         if($result){
            echo "
            <script>
                alert('회원가입 성공');
                location.href='/pages/login.php';
            </script>";
            exit;
         }
         else {
            echo "
            <script>
                alert('회원가입 실패');
                history.back();
            </script>";
            exit;
        }
    }   
?>