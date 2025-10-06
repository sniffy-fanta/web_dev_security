<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    session_start();

    //로그인 체크
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('로그인이 필요합니다.'); location.href='/pages/login.php';</script>";
        exit;
    }

    $post_id = $_POST['idx'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // 기존 파일명 및 작성자 조회
    $stmt = $mysqli->prepare("SELECT file,author FROM board WHERE idx = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_file = $row ? $row['file'] : null;

    //작성자 확인
    if($_SESSION['user_id'] != $row['author']){
        echo "<script>
                alert('권한이 없습니다');
                history.back();
            </script>";
            exit;
    }

    // 파일 삭제 체크 시
    if (isset($_POST['delete_file']) && $_POST['delete_file'] == '1') {
        // 실제 파일 삭제 (파일이 존재할 때만)
        if ($existing_file && file_exists($_SERVER['DOCUMENT_ROOT']."/board/uploads/".$existing_file)) {
            unlink($_SERVER['DOCUMENT_ROOT']."/board/uploads/".$existing_file);
        }
        // DB에서 파일 정보 삭제
        $stmt = $mysqli->prepare("UPDATE board SET title=?, content=?, file=NULL WHERE idx=?");
        $stmt->bind_param("ssi", $title, $content, $post_id);
    }

    // 파일 업로드 처리
    else if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $filename = $_FILES['upload_file']['name'];
        $tmp_path = $_FILES['upload_file']['tmp_name'];
        $error = $_FILES['upload_file']['error'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allow_ext = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'txt', 'pdf', 'doc', 'hwp', 'ppt'];

        if (!in_array($ext, $allow_ext)) {
            echo "<script>alert('허용되지 않는 파일 형식입니다.'); history.back();</script>";
            exit;
        }
        $unique_idx = uniqid('', true);
        $unique_filename = $unique_idx . '.' . $ext;
        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/board/uploads/" . $unique_filename;

        if ($error === UPLOAD_ERR_OK) {
            if (move_uploaded_file($tmp_path, $file_path)) {
                // 기존 파일 삭제
                if ($existing_file && file_exists($_SERVER['DOCUMENT_ROOT']."/board/uploads/".$existing_file)) {
                    unlink($_SERVER['DOCUMENT_ROOT']."/board/uploads/".$existing_file);
                }
                $stmt = $mysqli->prepare("UPDATE board SET title=?, content=?, file=? WHERE idx=?");
                $stmt->bind_param("sssi", $title, $content, $unique_filename, $post_id);
            } else {
                $stmt = $mysqli->prepare("UPDATE board SET title=?, content=? WHERE idx=?");
                $stmt->bind_param("ssi", $title, $content, $post_id);
            }
        } else if ($error === UPLOAD_ERR_INI_SIZE) {
            echo "<script>alert('파일 사이즈가 너무 큽니다.'); history.back();</script>";
            exit;
        }
    }
    // 파일 변경 없이 텍스트만 수정
    else {
        $stmt = $mysqli->prepare("UPDATE board SET title=?, content=? WHERE idx=?");
        $stmt->bind_param("ssi", $title, $content, $post_id);
    }

    // 마지막에 한 번만 쿼리 실행
    if ($stmt && $stmt->execute()) {
        echo "<script>
                alert('게시글이 수정되었습니다.');
                location.href='/board/board.php';
            </script>";
    } else {
        echo "<script>
                alert('DB 오류가 발생했습니다. 관리자에게 문의하세요.');
                history.back();
              </script>";
    }
    exit;
?>
