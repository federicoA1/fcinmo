<?php

/*DB CONFIG*/


$dbname="poshpart_pictures";
$server="localhost";
$username="poshpart_fede";
$password="fede";
$db = mysql_connect($server,$username,$password);
mysql_query("use $dbname");

?>