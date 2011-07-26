<?php
include_once("includes/connect.php");
include_once("includes/frame.php");   
include_once("classes/User.php");
session_start();
if ($_REQUEST[act] == "logout"){
    $_SESSION[id_user] = 0;
    session_destroy();
}

if (!$_SESSION[id_user]){
    $_REQUEST[act]= "showLogin";
}

if ($_SESSION[id_user] && !$_REQUEST[act]){
        $_REQUEST[act]= "showLoged";
}


if ($_POST[username] && $_POST[password]){
    $_REQUEST[act]= "doLogin";
}


    

switch($_REQUEST[act]){          
    case "changePass":          echo User::changePass($_GET[password],$_SESSION[id_user]);break;      
    case "partialSave":         echo User::partialSave($_REQUEST);break; 
    case "showLogin":           echo User::showLogin();break;
    case "doLogin":             echo User::doLogin($_POST[username],$_POST[password]);break;
    case "showLoged":           echo User::showLoged();break;
    case "index":               echo User::showLoged();break;        
} 


?>
