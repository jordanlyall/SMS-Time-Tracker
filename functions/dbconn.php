<?php

// Important Configuration Option
// e.g. dbconn('localhost','your_database','your_login','your_pass');

$db = dbconn('localhost','your_database','your_login','your_pass');
	
function dbconn($server,$database,$user,$pass){ $db = mysql_connect($server,$user,$pass); $db_select = mysql_select_db($database,$db); return $db; }

?>
