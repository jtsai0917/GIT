<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form action="" method="post">
  Date
	<input type="text" name="date" id="date" />
    </br>
     Lot
	<input type="text" name="lot" id="lot" />
    </br>
  	1.
  	<label for="first"></label>
  	<input type="text" name="first" id="first" /> 
  	2.
    <input type="text" name="second" id="first2" />
    3.
    <input type="text" name="third" id="first3" />
    數量.
    <input type="text" name="cnt" id="first3" />
  <input type="submit" name="submit" id="submit" value="送出" />
</form>
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
if(isset($_POST['submit'])){
	$j=1;
		for($i=$_POST['second'];$i<($_POST['second']+$_POST['cnt']);$i++){
			if($i<10){$n='000'.$i;}	
			elseif($i<100){$n='00'.$i;}	
			elseif($i<1000){$n='0'.$i;}	
			$dmno= $_POST['first'].$n.$_POST['third'];
			$query="INSERT INTO EL_WASH_DRUM
                            (FDM_LOT_NO, EWD_DRUM, EWD_USED_COUNT, EWD_SERIAL_NO, EWD_CHK_SURFACE, EWD_CHK_LABEL, 
                            EWD_CHK_DRUM_IN, EWD_CHK_FILL_LINK, EWD_WASH_TIME, EWD_CLEAR, EWD_CHK_WASHER)
VALUES          ('".$_POST['lot']."', '".$dmno."', 1, ".$j.", 'Y', 'Y', 'Y', 'Y', '5', 'Y', 'Y')";

			$result=mssql_query($query);
			$j++;
			
		}
	}