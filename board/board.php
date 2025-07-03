<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/php/db.php';

    if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }

    $cate = $_GET['cate'];
    $search = $_GET['search'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    
    //게시글 개수체크
    if($search && $start_date && $end_date){
        //검색어+날짜 둘다
        $cnt_sql = "SELECT COUNT(*) as cnt FROM board WHERE $cate LIKE '%$search%' AND post_date BETWEEN '$start_date' AND '$end_date'";
    } else if($search){
        //검색어만
        $cnt_sql = "SELECT COUNT(*) as cnt FROM board WHERE $cate LIKE '%$search%'";
    } else if($start_date && $end_date){
        //날짜만
        $cnt_sql = "SELECT COUNT(*) as cnt FROM board WHERE post_date BETWEEN '$start_date' AND '$end_date'";
    } else {
        //아무것도 없을 때 전체글
        $cnt_sql = "SELECT COUNT(*) as cnt FROM board";
    }
    $cnt_res = $mysqli->query($cnt_sql);
    $cnt_row = $cnt_res->fetch_assoc();

    $total_post = $cnt_row['cnt'];//총 게시글 수
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
    <div class="search_wrapper">
        <form action="/board/board.php" method="GET" class="search_bar">
            <select name="cate" id="search_opt">
                <option value="title" <?= $cate=="title"?"selected":""; ?>>제목</option>
                <option value="author" <?= $cate=="author"?"selected":""; ?>>작성자</option>
                <option value="content" <?= $cate=="content"?"selected":""; ?>>내용</option>
            </select>
            <input type="text" name="search" value="<?= htmlspecialchars($search)?>"autocomplete="off" placeholder="검색어를 입력해주세요.">
            <input type="date" name="start_date" value="<?= htmlspecialchars($start_date)?>">
            ~
            <input type="date" name="end_date" value="<?= htmlspecialchars($end_date)?>">
            <button type="submit" class="btn_style">검색</button>
        </form>
        <a href="/board/board_write.php" class="btn_style write_btn">글쓰기</a>
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
        //게시글 목록 출력 체크
        if($search && $start_date && $end_date){
            //검색어+날짜 둘다
            $sql_page = "SELECT * FROM board WHERE $cate LIKE '%$search%' AND post_date BETWEEN '$start_date' AND '$end_date' ORDER BY idx DESC LIMIT $start, $per_post";
        } else if($search){
            //검색어만
            $sql_page = "SELECT * FROM board WHERE $cate LIKE '%$search%' ORDER BY idx DESC LIMIT $start, $per_post";
        } else if($start_date && $end_date){
            //날짜만
            $sql_page = "SELECT * FROM board WHERE post_date BETWEEN '$start_date' AND '$end_date' ORDER BY idx DESC LIMIT $start, $per_post";
        } else {
            //아무것도 없을 때 전체글
            $sql_page = "SELECT * FROM board ORDER BY idx DESC LIMIT $start, $per_post";
        }
        $res_page = $mysqli->query($sql_page);
        while($row = $res_page->fetch_assoc()){
        ?>
            <tr>
                <td><?= $row['idx']; ?></td>
                <td><a id="title_link" href="/board/board_read.php?idx=<?=$row['idx']?>"><?= $row['title']; ?></a></td>
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

        $query_string = "&cate=$cate&search=$search&start_date=$start_date&end_date=$end_date";
        //처음 블럭으로
        if($current_block>1){
            echo "<a href='/board/board.php?page=1$query_string'><<</a>";
        }

        //이전 블럭으로
        if($current_block>1){
            $pre_page = $start_block - 1;
            echo "<a href='/board/board.php?page=$pre_page$query_string'><</a>";
        }

        //블럭당 페이지 출력
        while($start_block<=$end_block){
            //현재페이지는 페이지 클릭 안되게 하기
            if($start_block==$page) {
                echo "<a href='/board/board.php?page=$start_block$query_string' class='active'>$start_block</a>";
            } else {
                echo "<a href='/board/board.php?page=$start_block$query_string'>$start_block</a>";
            }
            $start_block++;
        }
        
        //다음 블럭으로
        if($current_block<$total_block){
            $next_page=$end_block + 1;
            echo "<a href='/board/board.php?page=$next_page$query_string'>></a>";
        }

        //마지막 블럭으로
        if($current_block<$total_block){
            echo "<a href='/board/board.php?page=$total_page$query_string'>>></a>";
        }
        ?>
    </div>
</body>
</html>