<?php
$dbname="salutedonna";
$server="localhost";
$username="root";
$password="";
$db = mysql_connect($server,$username,$password);
mysql_query("use $dbname");
mysql_query("SET NAMES utf8");
?>