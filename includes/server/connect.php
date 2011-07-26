<?php

/*DB CONFIG*/


$dbname="sg000161_bureau";
$server="localhost";
$username="sg000161_bureau";
$password="Fede1926";
$dblink = mysql_connect($server,$username,$password);
mysql_query("use $dbname");


?>