<?php

// connection parameters
$host     = '';
$user     = '';
$password = '';
$database = '';

// connecting to mysql
@mysql_connect($host, $user, $password) or die('Error: MySQL Connection Problem');
@mysql_select_db($database) or die('Error: Database Connection Problem');

// defining grid functions and actions
require_once('grid.php');