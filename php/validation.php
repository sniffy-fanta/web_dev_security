<?php
    function validation_input($user_id, $name,$address){

    if (empty($user_id)) return '아이디를 입력해주세요.';
    if (!preg_match('/^[a-z0-9]+$/', $user_id)) return '아이디는 소문자와 숫자만 사용 가능합니다.';
    if (empty($name)) return '이름을 입력해주세요.';
    if (empty($address)) return '주소를 입력해주세요.';

    return '';  // 에러 없음
    }
?>