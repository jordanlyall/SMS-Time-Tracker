<?php

//this file sends an end_time text 12 hours after start_time_2. It is run via a cron job every 15 minutes.

// connect to db
include ( 'functions/dbconn.php' );

//set timezone
date_default_timezone_set( 'America/Los_Angeles' );
$now = strtotime( date( 'Y-m-d Hi' ) );

// get all rows where status is confirmed_start or fixed_start
$sql = "SELECT `id`,`date`,`end_time_1`,`phone`,`name`,`random`
	FROM `time`
	WHERE `status`='confirmed_start' OR `status`='fixed_start'";

$res = mysql_query( $sql ) or die( "Error selecting id's." ); # mysql_error()


    // Step 1: Download the Twilio-PHP library from twilio.com/docs/libraries, 
    // and move it into the folder containing this file.
    require "Services/Twilio.php";
 
    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
    $AccountSid = "AccountSid";
    $AuthToken = "AuthToken";
 
    // Step 3: instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // Step 4: make an array of people we know, to send them a message. 
    // Feel free to change/add your own phone number and name here.

	//create people array of everyone who should be sent an end time text
	$people = array();
	while ( $row = mysql_fetch_assoc( $res ) ) {
		$end_time_1 = strtotime( $row[ 'date' ] . ' ' . $row[ 'end_time_1' ] );

		// add them to the people array if their end time is now or in the last 15 minutes
		if ( ( $end_time_1 - $now ) < 1 && ( $end_time_1 - $now ) >= -900 ) {

			$people[] = array(
				'id'		=> $row[ 'id' ],
				'number'	=> $row[ 'phone' ],
				'name'		=> $row[ 'name' ],
				'time'		=> $row[ 'end_time_1' ],
				'code'		=> $row[ 'random' ]
			);
		}
	}
	unset( $row );


    // Step 5: Loop over all our friends. $number is a phone number above, and 
    // $name is the name next to it
    foreach ($people as $row) {
 		 
		$time = $row['time'];
		$time_bump = substr($time,0,-2);
		$time_bump .="15";
		$numbershort = $row['number'];
		$number .= "+1";
		$number = $row['number'];
		$code = $row['code'];
		
		
        $sms = $client->account->sms_messages->create(
 
        // Step 6: Change the 'From' number below to be a valid Twilio number 
        // that you've purchased, or the Sandbox number
            "310-683-0793", 
 
            // the number we are sending to - Any phone number
            $number,
 
            // the sms body
            "Hi ". $row['name'] .", your end time is $time. If correct, reply 'YES $code'. If incorrect, reply with adjusted time, like '$time_bump $code'."
        );

 		// update the db to show new status
		$query = "UPDATE `time` SET `status`='sent_end' WHERE `id`=${row[ 'id' ]}"; 

		$result2 = mysql_query($query) or die(mysql_error());
    }
?>