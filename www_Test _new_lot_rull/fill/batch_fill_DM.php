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
			$query="INSERT INTO EL_FILLDATA_DRUM
                            (FDM_LOT_NO, EFD_DRUM_NO, EFD_SERIAL_NO, EFD_OPERATOR, EFD_EMPTY_SCALES, EFD_QTY, 
                            EFD_CHK_CAP, EFD_CHK_CAP_CLR, EFD_SAMPLING)
VALUES          ('".$_POST['lot']."', '".$dmno."', ".$j.", 'E264', 17.5, 330, 'Y', 'Y', 'N')";

			echo $query;
			echo "<BR>";

			$result=mssql_query($query);
			$j++;
			
		}
	}