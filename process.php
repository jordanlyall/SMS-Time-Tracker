<?php 

// connect to db
include ( 'functions/dbconn.php' ); 

// make the date readable
$date = mysql_real_escape_string( $_POST[ 'year' ] . "-" . $_POST[ 'month' ] .
	"-" . $_POST[ 'day' ] );

// get the company name
$company = mysql_real_escape_string( $_POST[ 'company' ] );

// query the db for the last batch number, increase it by one for the next import
$last_batch = mysql_fetch_row( mysql_query( "SELECT max(batch) FROM time" ) );
$next_batch = mysql_real_escape_string( $last_batch[ 0 ] + '1' );

// gets all _x inputs (x is integer) 
for ( $i = 1; $i <= count( $_POST ); $i++ ) {
	
	  //Random 4 char string
	  $characters = array(
	  "A","B","C","D","E","F","G","H","J","K","L","M",
	  "N","P","Q","R","S","T","U","V","W","X","Y","Z",
	  "1","2","3","4","5","6","7","8","9");

	  $keys = array();
	  
	  //first count of $keys is empty so "1", remaining count is 1-6 = total 7 times
	  while(count($keys) < 4) {
		  //"0" because we use this to FIND ARRAY KEYS which has a 0 value
		  //"-1" because were only concerned of number of keys which is 32 not 33
		  //count($characters) = 33
		  $x = mt_rand(0, count($characters)-1);
		  if(!in_array($x, $keys)) {
			 $keys[] = $x;
		  }
	  }
	  
	  foreach($keys as $key){
		 $random_chars .= $characters[$key];
	  }
	
	$name = '';
	$title = '';
	$phone = '';
	$start_time = '';
	
	
	// checks each form row to make sure there's data
	if ( isset ( $_POST[ "name_${i}" ] ) && $_POST[ "name_${i}" ] != '' ) {
		$name = mysql_real_escape_string( $_POST[ "name_${i}" ] );
	}

	if ( isset ( $_POST[ "title_${i}" ] ) && $_POST[ "title_${i}" ] != '' ) {
		$title = mysql_real_escape_string( $_POST[ "title_${i}" ] );
	}

	if ( isset ( $_POST[ "phone_${i}" ] ) && $_POST[ "phone_${i}" ] != '' ) {
		$phone = mysql_real_escape_string( $_POST[ "phone_${i}" ] );
	}

	if ( isset ( $_POST[ "start_time_${i}" ] ) &&
		$_POST[ "start_time_${i}" ] != '' ) {

		$start_time = mysql_real_escape_string( $_POST[ "start_time_${i}" ] );
	}

	if (
		(
			( isset ( $name ) && $name != '' ) ||
			( isset ( $title ) && $title != '' ) ||
			( isset ( $phone ) && $phone != '' ) ||
			( isset ( $start_time ) && $start_time != '' )
		) && isset ( $company ) && isset ( $date )
	) {
		

		// sets up the value data for each new row
		$values = "'${next_batch}','${company}','${date}','${name}','${title}','${phone}',"
			. "'sent_start','${start_time}', '$random_chars'";

		// run query that inserts to MySQL
		$db_query = "INSERT INTO time
(batch,company_name,date,name,title,phone,status,start_time_1,random)
			VALUES(${values})";

		mysql_query( $db_query );
		
	}
}

// now run send_1.php which sends the SMS texts and displays sent confirmation
include 'send_1.php';

?>