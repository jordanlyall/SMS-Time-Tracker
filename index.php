<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>SMS Time Tracker App</title>


    <link href="http://max.jotfor.ms/min/g=formCss?3.0.3588" rel="stylesheet" type="text/css" />
    <link type="text/css" rel="stylesheet" href="css/form_style.css" />

    <link rel="stylesheet" href="css/multirow.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>


  </head>

<body>

 <form class="" action="process.php" method="post" name="" id="" accept-charset="utf-8">
 <input type="hidden" name="amount" value="5">
  <div class="form-all">
    <ul class="form-section">
      <li id="cid_1" class="form-input-wide">
        <div class="form-header-group">
          <h2 id="header_1" class="form-header">
            SMS Time Tracker App
          </h2>
          <div style="float:right; margin-top:-25px;"><!--<a href="./">new times</a> |--> <a href="reports.php">reports</a></div>
        </div>
      </li>
      <li class="form-line" id="id_3">
        <label class="form-label-left" id="label_3" for="input_3"> Company: </label>
        <div id="cid_3" class="form-input">
          <input type="text" class="form-textbox" id="input_3" name="company" size="27" />
        </div>
      </li>
      <li class="form-line" id="id_4">
        <label class="form-label-left" id="label_4" for="input_4"> Date: </label>
        <div id="cid_4" class="form-input">
        	<span class="form-sub-label-container">
            	<input class="form-textbox" id="month_4" name="month" type="tel" size="2" maxlength="2" value="<?=date("m");?>" />
                <span class="date-separate">&nbsp;/</span>
            	<label class="form-sub-label" for="month_4" id="sublabel_month"> Month </label>
            </span>
            <span class="form-sub-label-container">
            	<input class="form-textbox" id="day_4" name="day" type="tel" size="2" maxlength="2" value="<?=date("d");?>" />
                <span class="date-separate">&nbsp;/</span>
            	<label class="form-sub-label" for="day_4" id="sublabel_day"> Day </label>
            </span>
            <span class="form-sub-label-container">
            	<input class="form-textbox" id="year_4" name="year" type="tel" size="4" maxlength="4" value="<?=date("Y");?>" />
            	<label class="form-sub-label" for="year_4" id="sublabel_year"> Year </label></span><span class="form-sub-label-container">
            	<label class="form-sub-label" for="input_4_pick"> &nbsp;&nbsp;&nbsp; </label></span>
        </div>
      </li>
      <li class="form-line" id="id_7">
       
        <div id="cid_7" class="form-input-wide">
         
         	
            
           
            <div class="headerRow">

                <div class="headerItem" align="center">Name</div>

                <div class="headerItem" align="center">Job Title</div>

                <div class="headerItem" align="center">Phone #</div>

                <div class="headerItem" align="center">Call Time (Military)</div>
		    </div>
            

            <div class="multiRowInput">
            
            <div class="inputRow">

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="name_1"/></div>

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="title_1"/></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="phone_1" maxlength="10"  /></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="start_time_1" maxlength="4" /></div>
            </div>
            
           <div class="inputRow">
				
                <div class="inputField"><input class="form-textbox" type="text" size="23" name="name_2" /></div>

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="title_2" /></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="phone_2" maxlength="10"/></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="start_time_2" maxlength="4"/></div>


            </div>

            <div class="inputRow">

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="name_3" /></div>

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="title_3" /></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="phone_3" maxlength="10"/></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="start_time_3" maxlength="4"/></div>


            </div>
            
            <div class="inputRow">
				
                <div class="inputField"><input class="form-textbox" type="text" size="23" name="name_4" /></div>

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="title_4" /></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="phone_4" maxlength="10"/></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="start_time_4" maxlength="4"/></div>


            </div>
            
            <div class="inputRow">
				
                <div class="inputField"><input class="form-textbox" type="text" size="23" name="name_5" /></div>

                <div class="inputField"><input class="form-textbox" type="text" size="23" name="title_5" /></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="phone_5" maxlength="10"/></div>

                <div class="inputField"><input class="form-textbox" type="tel" size="23" name="start_time_5" maxlength="4"/></div>


            </div>          
            
            
            </div>
     
          
        </div>
      </li>
      <li class="form-line" id="id_2">
        <div id="cid_2" class="form-input-wide">
          <div style="margin-left:0; margin-top:50px;" class="form-buttons-wrapper">
            <button id="input_2" type="submit" class="form-submit-button" style="width:150px;  float:left;">
              Send Call Times
            </button>
            
          </div>
        </div>
      </li>
    </ul>
  </div>

</form>

</body>
</html>
