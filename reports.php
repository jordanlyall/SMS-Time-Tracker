<? 
// begin grid
	require_once('grid/starter.php'); // project starter
	
	// defining post process function
	function postProcessFunction($row){
		// add grid table to actions buttons
		// this column defined as "action" field in fields array
//		$row['tweet_url'] = '<a href="../stats/'. $row['id'] .'"><img src="images/info.png" ></a> &nbsp; <a href="../'. $row['id'] .'"><img src="images/arrow_right.png"></a>';
//		$row['id'] = '<a href="stats/'. $row['id'] .'" class="info" title="Campaign Info">'. $row['id'] .'</a>';
//		$row['timestamp'] = date("m/d/Y", strtotime($row['timestamp']));
//		$row['twitter_id'] = '<a href="http://twitter.com/'. $row['twitter_id'] .'">'. $row['twitter_id'] .'</a>';

		// return new row content
		return $row;
	}
	
	// building our grid
	$gridHTML = grid(array(
		'table'       => 'time',
		'fields'      => array( /*
			 field     |        name        | width  | sortable  |  searchable  |  database
			-----------+--------------------+--------+-----------+--------------+---------------
			 defaults:                      | 100px  |  true     |   false      |  true
			-----------+--------------------+--------+-----------+--------------+-------------*/
//			'id'      => array('#',            30,      true,        true),
			'company_name'      => array('Company',     160,     true,        true),
			'date'  => array('Date',         120,    	true,        true),
			'name'   => array('Name',     120,     true,        true),
			'phone'   => array('Phone',         100,     true,        true),
			'start_time_2'   => array('Start',         100,     true,        true),
			'end_time_2'   => array('End',        100,     true,        true),
			'status' => array('Status',      70,     true,       true), 
		),
		'order'       => 'date',                   // default order (should be one of your field name)
		'sort'        => 'desc',                    // order direction (should be `asc` or `desc`)
		'where'       => 'phone REGEXP "^-?[0-9]+$"',      // filter (this filter will be applied always)
		'do'          => 'postProcessFunction',     // post processor for every row
		'limit'       => 20,
		'searchable'  => false
	));
// end grid

?>

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
        <span>Reports:</span>
      </li>

      


       <li class="form-line" id="id_3">
        <?php print $gridHTML; ?>
      </li>





	   <li class="form-line" id="id_2">
        <div id="cid_2" class="form-input-wide" style="margin-top: 100px;">
          
          <div style="margin-left:0" class="form-buttons-wrapper">
                        
            <a href="./" style="text-decoration:none;">
            <div id="input_2" class="form-submit-button" style="width:140px; float:left; text-decoration:none;" align="center">
              New Call Times
            </div>
            </a>

          </div>
          
        </div>
      </li>

    </ul>
  </div>
  
  
</body>
</html>