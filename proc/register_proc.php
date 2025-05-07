<?php
    require_once '../php/validation.php';
    require_once '../php/db.php';

    mysqli_report(MYSQLI_REPORT_OFF); //쿼리 실패시 FALSE반환

    //버튼확인
    $action =$_POST['action'];
    $user_id = trim($_POST['user_id']); //check,register 둘 다 쓰여서 전역변수로 선언

    if($action === 'check'){
        //중복확인
        $check_sql= "SELECT userid FROM users WHERE userid='$user_id'";
        $result_id= $mysqli->query($check_sql);
        
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
         $name = trim($_POST['name']);
         $address = trim($_POST['address']);

         $error = validation_input($user_id,$user_pw,$name,$address);
            if($error !== ''){
                echo "<script>alert('$error');
                history.back();
                </script>";
                exit;
            }

         $sql="INSERT INTO users (userid,userpw,name,address) VALUES ('$user_id','$user_pw','$name','$address')";
         $result=$mysqli->query($sql);

         if($result){
            echo "
            <script>
                alert('회원가입 성공');
                location.href='../pages/login.php';
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