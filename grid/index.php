<?php

error_reporting(1000);

// project starter
require_once('starter.php');

// defining post process function
function postProcessFunction($row){
	// add grid table to actions buttons
	// this column defined as "action" field in fields array
	$row['tweet_url'] = '<a href="../stats/'. $row['id'] .'">info</a> | <a href="'. $row['tweet_url'] .'">view</a>';
	$row['id'] = '<a href="stats/'. $row['id'] .'" class="info" title="Campaign Info">'. $row['id'] .'</a>';
	$row['timestamp'] = date("m/d/Y", strtotime($row['timestamp']));
	$row['email'] = '<a href="mailto:'. $row['email'] .'">'. $row['email'] .'</a>';
	$row['ref'] = '<a href="'. $row['ref'] .'">'. $row['ref'] .'</a>';
	
	// return new row content
	return $row;
}

// building our grid
$gridHTML = grid(array(
	'table'       => 'tpv_campaigns',
	'fields'      => array( /*
		 field     |        name        | width  | sortable  |  searchable  |  database
		-----------+--------------------+--------+-----------+--------------+---------------
		 defaults:                      | 100px  |  true     |   false      |  true
		-----------+--------------------+--------+-----------+--------------+-------------*/
		'id'      => array('#',            30,      true,        true),
		'timestamp'      => array('Date',     90,     true,        true),
		'email'  => array('Email',         130,    	true,        true),
		'tweet_text'   => array('Tweet',     300,     true,        true),
		'visits'   => array('Visits',         30,     true,        true),
		'views'   => array('Views',         30,     true,        true),
		'ref'   => array('Refering Site',        180,     true,        true),
		'tweet_url' => array('Actions',      70,     false,       false), 
	),
	'order'       => 'id',                   // default order (should be one of your field name)
	'sort'        => 'desc',                    // order direction (should be `asc` or `desc`)
	'where'       => 'id not like 0',      // filter (this filter will be applied always)
	'do'          => 'postProcessFunction',     // post processor for every row
	'limit'       => 20,
	'searchable'  => true
));


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Grid Example</title>
	<link rel="stylesheet" type="text/css" media="screen" href="style.css">
	<link rel="stylesheet" type="text/css" media="screen" href="grid.css">
	<style type="text/css" media="screen">
	    body {
        	padding: 20px;
        	margin: 0;
        	font: 12px "Lucida Grande", Lucida, Verdana, sans-serif;
        }
	
	    /* grid content specific styles */
		.cell.col_oturum, .cell.col_ip, .cell.col_zaman { font-size: 0.8em; }
		.col_zaman { text-align: center; }
		.cell.col_actions { font-size: 0.9em; }
		.cell.col_actions a.del { color: red; }
	</style>
</head>
<body>

	<div class="content">
		<h1>Data Grid</h1>

		<?php print $gridHTML; ?>
	</div>

</body>
</html>