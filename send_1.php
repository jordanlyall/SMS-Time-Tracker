<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SMS Time Tracker App</title>


<link href="http://max.jotfor.ms/min/g=formCss?3.0.3588" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="css/form_style.css" />

</head>

<body>

  <div class="form-all">
    <ul class="form-section">
      <li id="cid_1" class="form-input-wide">
        <div class="form-header-group">
          <h2 id="header_1" class="form-header">
            SMS Time Tracker App
          </h2>
        </div>
      </li>
      
       <li class="form-line" id="id_3">
        <span>Initial call times for <span style="color:#A3BAA6; font-weight:bold">
			<?=$_POST['month'] ."/". $_POST['day'] ."/". $_POST['year']?></span>
			have been sent.</span>
      </li>

      
      <li class="form-line" id="id_3">
        <span class="form-label-left" id="label_3" for="input_3">Results: </span>
      </li>


<?php

    // Step 1: Download the Twilio-PHP library from twilio.com/docs/libraries, 
    // and move it into the folder containing this file.
    require "Services/Twilio.php";
 
    // Step 2: set our AccountSid and AuthToken from www.twilio.com/user/account
    $AccountSid = "AccountSid";
    $AuthToken = "AuthToken";
 
    // Step 3: instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    // Step 4: make an array of people we know, to send them a message. 

	//Step 4.5: Creates a people array for the people that were submitted in the app
		$people = array();
		for ( $i = 1; $i <= count( $_POST ); $i++ ) {
			if (
				( isset ( $_POST[ "phone_${i}" ] ) && $_POST[ "phone_${i}" ] != '' ) ||
				( isset ( $_POST[ "name_${i}" ] ) && $_POST[ "name_${i}" ] != '' ) ||
				( isset ( $_POST[ "start_time_${i}" ] ) && $_POST[ "start_time_${i}" ] != '' )
			) {

				$people[] = array(
					'number' => $_POST[ "phone_${i}" ],
					'name' => $_POST[ "name_${i}" ],
					'time' => $_POST[ "start_time_${i}" ]
				);
			}
		}

 
    // Step 5: Loop over all our friends. $number is a phone number above, and 
    // $name is the name next to it
    foreach ($people as $row) {
 		 
		$time = $row['time']; //gets the start time
		$time_bump = substr($time,0,-2); 
		$time_bump .="15"; //suggests a time bump
		$number .= "+1";
		$number = $row['number']; // prefixes "+1" to the #
		$random_chars = substr($random_chars,0,4); 
		
		
        $sms = $client->account->sms_messages->create(
 
        // Step 6: Change the 'From' number below to be a valid Twilio number 
        // that you've purchased, or the Sandbox number
            "310-683-0793", 
 
            // the number we are sending to - Any phone number
            $number,
 
            // the sms body
            "Hi ". $row['name'] .", your start time is $time. If correct, reply 'YES $random_chars'. If incorrect, reply with adjusted time, like '$time_bump $random_chars'."
        );
 
        // Display a confirmation message on the screen
        echo "<li class=\"form-line\" id=\"id_3\">&raquo; Sent SMS to <span style=\"color:#A3BAA6; font-weight:bold\">". $row['name'] ."</span> at <span style=\"color:#A3BAA6; font-weight:bold\">". $row['number'] ."</span> that read:<br />&nbsp;&nbsp;&nbsp; Hi ". $row['name'] .", your start time is $time. If correct, reply 'YES $random_chars'. If incorrect, reply with adjusted time, like '$time_bump $random_chars'.</li>";
    }
	
?>

	   <li class="form-line" id="id_2">
        <div id="cid_2" class="form-input-wide" style="margin-top: 100px;">
          
          <div style="margin-left:0" class="form-buttons-wrapper">
                        
            <a href="./" style="text-decoration:none;">
            <div id="input_2" class="form-submit-button" style="width:140px; float:left; text-decoration:none;" align="center">
              New Call Times
            </div>
            </a>

            <a href="reports.php" style="text-decoration:none;">
            <div id="input_2" class="form-submit-button" style="width:140px; float:right; text-decoration:none;" align="center">
              View Reports
            </div>
            </a>

          </div>
          
        </div>
      </li>

    </ul>
  </div>
  
  
</body>
</html>