<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';


    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['user_id'];

    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        // 파일 업로드가 있는 경우만 확장자 검사
        $filename = $_FILES['upload_file']['name'];//사용자가 올린 파일 이름 (ex: hello.jpg)
        $tmp_path = $_FILES['upload_file']['tmp_name'];//서버가 저장하는 임시경로
        $error = $_FILES['upload_file']['error'];//파일업로드 시 에러코드

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));//확장자 소문자로 변환
        $allow_ext = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'txt', 'pdf', 'doc', 'hwp', 'ppt'];//허용된 확장자

        // 허용되지 않은 확장자 알림
        if (!in_array($ext, $allow_ext)) {
            echo "<script>alert('허용되지 않는 파일 형식입니다.'); history.back();</script>";
            exit;
        }
        
        $unique_idx = uniqid('', true);//랜덤값
        $unique_filename = $unique_idx . '.' . $ext;//랜덤값+확장자
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/board/uploads/" . $unique_filename;//저장할 파일 경로

        if ($error === UPLOAD_ERR_OK) {
            if (move_uploaded_file($tmp_path, $file_path)) {
                // 파일과 함께 저장
                $sql = "INSERT INTO board (title, author, content, post_date, file, views, likes) VALUES (?, ?, ?, now(), ?, 0, 0)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ssss", $title, $author, $content, $unique_filename);
            }
            else {
                // 파일 이동 실패 → 파일 없이 저장
                $sql = "INSERT INTO board (title, author, content, post_date, views, likes) VALUES (?, ?, ?, now(), 0, 0)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sss", $title, $author, $content);
            }
        }else if ($error === UPLOAD_ERR_INI_SIZE) {
            echo "<script>alert('파일 사이즈가 너무 큽니다.'); history.back();</script>";
            exit;
        }
    }else {
        // 파일이 첨부되지 않은 경우
        $sql = "INSERT INTO board (title, author, content, post_date, views, likes) VALUES (?, ?, ?, now(), 0, 0)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $title, $author, $content);
    }

    $result = $stmt->execute();
        if ($result) {
            echo "<script>
                    alert('게시글이 작성되었습니다.');
                    location.href='/board/board.php';
                </script>";
        } else {
            echo "관리자에게 문의하세요";
        }
?>