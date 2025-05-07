<?php
$mysqli = new mysqli('localhost','root','444444','mydb');
if($mysqli->connect_error){
    echo "관리자에게 문의하세요";
    exit;
}
?>