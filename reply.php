<?php
 
// connect to db
include('functions/dbconn.php'); 

// grabs the # from the incoming text, turns it back into 10 digits
$phone = substr($_REQUEST['From'], -10, 10);

//captures sms body and pulls out the 4 char code
$str = strrev($_POST['Body']);
$reverse = substr($str,0,4);
$code = strrev( $reverse );

// run query that gets everything from MySQL regarding the phone/code combo
	$sql = "SELECT * FROM time WHERE random LIKE '$code' AND phone LIKE $phone"; 
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result); // now just write $row['column_name'] to get data from that field


//if status is sent_start, then that means they want to confirm or update their start time

if ($row['status'] == 'sent_start') { 

		$id = $row['id'];
		$start1 = $row['start_time_1'];
		$body = $_POST['Body'];
		$name = $row['name'];
		$code = strtolower($code);
		
		//if $_POST['Body'] = "YES" then update start_time_2 and status and send thank you text
		if (strtolower(substr($body,0,3)) == 'yes') { 

		// add half-day, updating to next day, if needed */
		$start_date_time = strtotime( $row[ 'date' ] . ' ' . $start1 );
		$end_date_time = $start_date_time + ( 12 * 3600 ); // 12 hrs
		$d_new = date( 'Y-m-d', $end_date_time );
		$t_new = date( 'Hi', $end_date_time );

			//updates status, date (if nec), start_time_2, and end_time_1
			$query = "UPDATE `time`
				SET `status`='confirmed_start',
					`date`='${d_new}',
					`start_time_2`='${start1}',
					`end_time_1`='${t_new}'
				WHERE `id`='${id}'";


			  $result2 = mysql_query($query) or die(mysql_error());
			  
			  // Send a response text
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Hi <?=$name;?>, thanks for the confirmation! Your start time is confirmed at <?=$start1;?>.</Sms>
			  </Response>
			  <?
		
		//elseif they're changing the start time ($body =  ####), then add that # to start_time_ and update status
		} elseif (is_numeric(substr($body,0,1))) { 
			
			  $body = substr($body,0,4);
			  $start_date_time = strtotime( $row[ 'date' ] . ' ' . $body );
  			$end_date_time = $start_date_time + ( 12 * 3600 ); // 12 hrs
			$d_new = date( 'Y-m-d', $end_date_time );
			$t_new = date( 'Hi', $end_date_time );
			  
			  
			  	//updates status, date (if nec), start_time_2, and end_time_1
			  	$query = "UPDATE `time`
				SET `status`='fixed_start',
					`date`='${d_new}',
					`start_time_2`='$body',
					`end_time_1`='${t_new}'
				WHERE `id`='${id}'";
			  
			  $result2 = mysql_query($query) or die(mysql_error());
			  
			  // Send a response text
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Hi <?=$name;?>, thanks for the confirmation. Your start time is now set for <?=$body;?>.</Sms>
			  </Response>
			  <?
		
		//else something's wrong. Respond and ask them to reformat
		} else { 
		
			  // now greet the sender
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Please respond with either 'YES <?=$code;?>' or a new time in military code, like '0815 <?=$code;?>'.</Sms>
			  </Response>
			  <?
		
		}



//elseif status is sent_end, which means they want to confirm or update their end time
} elseif ($row['status'] == 'sent_end') { 


		$id = $row['id'];
		$end1 = $row['end_time_1'];
		$body = $_POST['Body'];
		$name = $row['name'];
		
		//if $_POST['Body'] = "YES" then update end_time_2 and status and send thank you text
		if (strtolower(substr($body,0,3)) == 'yes') { 
		
			  $query = "UPDATE time SET status = 'confirmed_end', end_time_2 = '$end1' WHERE id = '$id'"; 
			  $result2 = mysql_query($query) or die(mysql_error());
			  
			  // now greet the sender
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Hi <?=$name;?>, thanks for the confirmation! Your end time is confirmed at <?=$end1;?>.</Sms>
			  </Response>
			  <?
		
		//elseif $body =  ####, then add that # to to end time two and update status
		} elseif (is_numeric(substr($body,0,1))) {  
		
		$body = substr($body,0,4);
		
			  $query = "UPDATE time SET status = 'fixed_end', end_time_2 = '$body' WHERE id = '$id'"; 
			  $result2 = mysql_query($query) or die(mysql_error());
			  
			  // now greet the sender
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Hi <?=$name;?>, thanks for the confirmation. Your end time is now set for <?=$body;?>.</Sms>
			  </Response>
			  <?
		
		//else something's wrong respond and ask them to reformat
		} else { 
		
			  // now greet the sender
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Please respond with either 'YES <?=$code;?>' or a new time in military code, like '0815 <?=$code;?>'.</Sms>
			  </Response>
			  <?
		
		}


//else something's wrong, inform them to reformat, include the code, or call for help
} else { 

			  // now greet the sender
			  header("content-type: text/xml");
			  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
			  
			  ?>
			  <Response>
				  <Sms>Please add the appropriate 4 character code at the end of your text message. If issues persist please contact Mike Upton or Jordan Lyall.</Sms>
			  </Response>
			  <?

}







	
?>