<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';


    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['user_id'];
    $upload_file = $_FILES['upload_file'];
    
    //DB 데이터 저장
    $sql = "INSERT INTO board (title,author,content,post_date,file,views,likes) VALUES ('$title','$author','$content',now(),0,0)";
    $result = $mysqli->query($sql);

    if($result){
        echo "<script>
                alert('게시글이 작성되었습니다.');
                location.href='/board/board.php';
            </script>";
    }
    else{
        echo "관리자에게 문의하세요";
    }
?>