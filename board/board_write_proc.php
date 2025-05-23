<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/session_guard.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';


    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['user_id'];

    $filename = $_FILES['upload_file']['name'];
    $tmp_filename = $_FILES['upload_file']['tmp_name'];
    $file_dir = $_SERVER['DOCUMENT_ROOT']."/board/uploads/";
    $file_path = $_SERVER['DOCUMENT_ROOT']."/board/uploads/".$filename;

    if(move_uploaded_file($tmp_filename,$file_path)){}
        //DB 데이터 저장
        $sql = "INSERT INTO board (title,author,content,post_date,file,views,likes) VALUES ('$title','$author','$content',now(),$file_path,0,0)";
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