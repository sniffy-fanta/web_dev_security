<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }
    
    $sql="SELECT * FROM board";
    $result = $mysqli->query($sql);

    $total_post = $result->num_rows;//총 게시글 수
    $per_post = 5;//페이지당 보여줄 게시글 수

    $start = ($page-1)*$per_post;//페이지당 보여줄 게시글 시작번호

    $total_page = ceil($total_post/$per_post);//총 페이지 수

    $per_block = 5;//한 블럭당 보여줄 페이지 수
    $total_block = ceil($total_page/$per_block);//총 블럭 수

    $current_block = ceil($page/$per_block);//현재 페이지가 속한 블럭
    $start_block = ($current_block-1)*$per_block+1;//지금 블럭의 시작 페이지 번호
    $end_block = min(($start_block + $per_block -1),$total_page);//지금 블럭의 마지막 페이지 번호

    
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시판</title>
    <link rel="stylesheet" href="/CSS/basic.css">
    <link rel="stylesheet" href="/CSS/board.css">
</head>
<body>
    <header class="board_title">
        <h1>Board</h1>
        <div class="nav">
            <a href="/board/board.php">회원게시판</a>
            <a href="/pages/main.php">메인페이지</a>
            <a href="/pages/mypage.php">마이페이지</a>
        </div>
        <hr>
    </header>
    <div class="write_btn1">
        <a href="/board/board_write.php">글쓰기</a>
    </div>
    <table class="board_table">
        <thead>
            <tr>
                <th>POST ID</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
                <th>조회수</th>
                <th id="emot">💌</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql_page = "SELECT * FROM board ORDER BY idx DESC LIMIT $start,$per_post";
        $res_page = $mysqli->query($sql_page);
        while($row = $res_page->fetch_assoc()){
        ?>
            <tr>
                <td><?= $row['idx']; ?></td>
                <td><?= $row['title']; ?></td>
                <td><?= $row['author']; ?></td>
                <td><?= $row['post_date']; ?></td>
                <td><?= $row['views']; ?></td>
                <td><?= $row['likes']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>   
    <div class="page_wrapper"> 
        <?php
        $page_num = 1;

        //처음 블럭으로
        if($current_block>1){
            echo "<a href='/board/board.php?page=1'><<</a>";
        }

        //이전 블럭으로
        if($current_block>1){
            $pre_page = $start_block - 1;
            echo "<a href='/board/board.php?page=$pre_page'><</a>";
        }

        //블럭당 페이지 출력
        while($start_block<=$end_block){
            //현재페이지는 페이지 클릭 안되게 하기
            if($start_block==$page) {
                echo "<a href='/board/board.php?page=$start_block' class='active'>$start_block</a>";
            } else {
                echo "<a href='/board/board.php?page=$start_block'>$start_block</a>";
            }
            $start_block++;
        }
        
        //다음 블럭으로
        if($current_block<$total_block){
            $next_page=$end_block + 1;
            echo "<a href='/board/board.php?page=$next_page'>></a>";
        }

        //마지막 블럭으로
        if($current_block<$total_block){
            echo "<a href='/board/board.php?page=$total_page'>>></a>";
        }
        ?>
    </div>
</body>
</html>