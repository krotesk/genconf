<?php
$db = mysql_connect("localhost","asteriskuser","asteriskpass");
mysql_select_db("asterisk",$db);
mysql_query("set names utf8",$db);
?>
