<?php
session_start();

if ($_REQUEST[captcha]){
     require("includes/php-captcha.inc.php");
     if(PhpCaptcha::Validate($_REQUEST[captcha])){
         echo "true";
     }else
     echo "false";
}
?>