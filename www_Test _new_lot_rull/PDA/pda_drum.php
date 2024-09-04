<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>無標題文件</title>
<style type="text/css">
body,td,th {
	font-size: 20px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
</head>
<?php
session_start();
include("../connections/conn.php");
include ("../lib/fun.php");
datepick();
if($_SESSION['uid']==''){jumpto("login.php");}
 $dat=getdate(mon)."/".getdate(mday)."/".getdate(year);
?>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <p>
    <label for="lot_no"></label>
<a href="fill.php"><img src="../pics/TYS.jpg" alt="" width="50" height="28" /></a>人員：<?php echo $_SESSION['uname']?>
  <p>
  DRUM 出貨檢查作業.
  <br>
   出荷日期: <input type="text" name="toto_no" id="toto_no" style="font-size:20px" value="<?PHP echo date("Ymd") ?>"/ readonly>
  <br>
出荷決定書NO.: <input type="text" name="nono" id="nono" autocomplete="off" style="font-size:20px" value="<?PHP echo $_SESSION['nono'];  ?>" onchange="set_date_session(this.name,this.value)"/>
<BR />			
        

<?php 
if(isset($_POST['enter']))
{	$_SESSION['PDD']='';
	$_SESSION['QTY']='';
	$_SESSION['steps']=1;
		$_SESSION['nono']=$_POST['nono'] ;
		$chksql="SELECT          CUSTOMER_DATA.CTD_CUST_NAME, CUSTOMER_DATA.CTD_CUST_NO, PRODUCT_DATA.PDD_PROD_NAME, 
                            PRODUCT_DATA.PDD_PROD_NO, OUT_PRODUCT.OPD_LOT_NO
FROM              OUT_DECISION INNER JOIN
                            OUT_PRODUCT ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
WHERE          OUT_DECISION.OTD_NO ='".$_SESSION['nono']."'";
//  echo $chksql;
	$resultsql = mssql_query($chksql);
	$numRowssql = mssql_num_rows($resultsql);
	
	$selectlot='<select name="lot_id" style="font-size:20px" autofocus="autofocus">';
	while($rowsql=mssql_fetch_array($resultsql)){
		$_SESSION['cust_id']=$rowsql['CTD_CUST_NO'];
		$_SESSION['cidcname']=$rowsql['CTD_CUST_NO'].":".$rowsql['CTD_CUST_NAME'];

		$selectlot=$selectlot.'<option value="'.$rowsql['OPD_LOT_NO'].",".$rowsql['PDD_PROD_NO'].'">'.$rowsql['OPD_LOT_NO'].'</option>';
	}
	$selectlot=$selectlot.'</select>';
	
	$selectlot='<input type="text" name="lot_id" style="font-size:20px"  autofocus="autofocus"/>';
	
	$query="select PD.PDD_TYPE,OP.*,PD.PDD_PROD_NAME,CD.CTD_CUST_SHORT_NAME 
		FROM              OUT_PRODUCT AS OP INNER JOIN
                            PRODUCT_DATA AS PD ON PD.PDD_PROD_NO = OP.PDD_PROD_NO INNER JOIN
                            OUT_DECISION AS OD ON OP.OPM_ORDER_NO = OD.OPM_ORDER_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CD ON OD.CTD_CUST_NO = CD.CTD_CUST_NO

	 where OD.OTD_NO='".$_SESSION['nono']."'";
//	echo $query."<BR>";	 
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$chdrum=$chdrum."/".$row['PDD_TYPE'];
		$chdrum=substr($chdrum,1);
		//echo $chdrum;
		$strc=explode("/",$chdrum);
		for($i=0;$i<count($strc);$i++)
		{	
			if(trim($strc[$i])=="LY")
			{	
				$BOOM=1;
			}
		}
	
	
			$_SESSION['CUST']=$CUST=$row['CTD_CUST_SHORT_NAME'];
			$_SESSION['PDD']=$_SESSION['PDD'].",".$row['PDD_PROD_NO'];
			//$_SESSION['PDD']=substr($_SESSION['PDD'],0,-1);
			$_SESSION['PDD']=substr($_SESSION['PDD'],1);
			$PDD=$PDD."/".$row['PDD_PROD_NAME'];
			$OPDLOT=($OPDLOT."/".$row['OPD_LOT_NO']);
			
			$L=$L."   ".$row['OAF_PACKAGE'];
			$KG=$row['OPD_ACC_UNIT'];
			$QTY=$QTY."   ".$row['OPD_QTY_DRUM'];
			$_SESSION['QTY']=$_SESSION['QTY']+$row['OPD_QTY_DRUM'];
		}
		if($numRowssql==0 or $BOOM==1){echo "輸入錯誤或不屬於DRUM的OTD NO";}	
		if($numRowssql>=1 and $BOOM!=1)
		{	$OPD=array();
			$_SESSION['LOT']=$OPDLOT;
			echo '<br>';
			echo  "客戶 : ".$_SESSION['cidcname'].')<br>';
			echo  "批號 : ".$selectlot.'<br>';	
			echo '<input type="submit" name="next1"  style="font-size:20px" id="next1" value="下一步" />'.'</br>';

	}

}

/*
if((isset($_POST['next1'])) and ($_POST['chk1']!=1 or $_POST['chk2']!=2 or $_POST['chk3']!=3))
{
	
	echo '<br>';
			echo  "客戶 : ".$_SESSION['cidcname'].')<br>';
			echo  "品名 : ".$_SESSION['pidcname'].')<br>';
			echo  "批號 : ".$selectlot.'<br>';
			echo '<br>';
			echo '<input type="checkbox" name="chk1" value="1">'."容器荷姿:(".$L.$KG.") 與容器對照是否無錯誤.".'</br>';
			echo '<input type="checkbox" name="chk2" value="2">'."數量".$QTY."與容器對照是否無錯誤".'</br>';
			echo '<input type="checkbox" name="chk3" value="3">'."附帶條件有無?".'</br>';
			echo '<input type="submit" name="next1" style="font-size:20px" id="next1" value="下一步" />'.'</br>';
	echo "請確認輸入無誤";
}
*/
if(isset($_POST['next1']))
{	
	$query="SELECT          CUSTOMER_PRODUCTS.CTP_CUSTBAR1, CUSTOMER_PRODUCTS.CTP_CUSTBAR1_CHECK, OP.OPD_LOT_NO, 
                            OP.OPD_QTY_DRUM, CUSTOMER_PRODUCTS.CTP_CUSTBAR1_start_char AS sc, OP.PDD_PROD_NO, 
                            PRODUCT_DATA.PDD_PROD_NAME
FROM              OUT_PRODUCT AS OP INNER JOIN
                            OUT_DECISION ON OP.OPM_ORDER_NO = OUT_DECISION.OPM_ORDER_NO INNER JOIN
                            CUSTOMER_PRODUCTS ON OP.PDD_PROD_NO = CUSTOMER_PRODUCTS.PDD_PROD_NO AND 
                            OUT_DECISION.CTD_CUST_NO = CUSTOMER_PRODUCTS.CTD_CUST_NO INNER JOIN
                            PRODUCT_DATA ON OP.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
	WHERE          (OP.OPD_LOT_NO = '".trim($_POST['lot_id'])."') AND (OP.OTD_NO = '".$_POST['nono']."')";
//	echo $query."<BR>";
	$result=mssql_query($query);
	$numrow1=mssql_num_rows($result);
	if($numrow1==0){
		my_msg("批號錯誤");
	}
	else{
		$row=mssql_fetch_row($result);
		$drum_qty=trim($row[3]);
		if($row[1]==1){
			if($row[4]==1){
				$custbar1=$_SESSION['custbar1']=trim($row[0])." ";
			}
			else{
				$custbar1=$_SESSION['custbar1']=trim($row[0]);
			}
			$custbar1=$_SESSION['custbar1']=trim($row[0]);
			$start_char=$_SESSION['start_char']=$row[4];
			$ck1=1;
			
		}
		else{
			$ck1=0;
			$start_char=$_SESSION['start_char']=0;
		}
	}
	$_SESSION['pidcname']=$row[6].":".$row[5];
	$_SESSION['QTY']=$row[3];
	$_SESSION['steps']=2;
	$_SESSION['aa']=$aa=explode(",",$_POST['lot_id']);
	$_SESSION['prod_id']=$aa[1];
	$_SESSION['lot_id']=$_POST['lot_id'];
	$QTY1=$_SESSION['QTY'];
	echo  "客戶 : ".$_SESSION['cidcname'].')<br>';
	echo  "品名 : ".$_SESSION['pidcname'].')<br>';
	echo "LOT NO:".'<input type="text" name="no" autocomplete="off" id="no" style="font-size:20px" value="'.$_SESSION['lot_id'].'"  readonly="readonly" /></br>';
	$query1="select  * from OUT_CHECK_DRUM_DETAIL where (OTD_NO='".$_POST['nono']."' and OCD_LOT_NO = '".trim($_POST['lot_id'])."')";
//	echo $query1."<BR>";
	$result1 = mssql_query($query1);
	$numRows1 = mssql_num_rows($result1);
		$_SESSION['numrow']=$numRows1;
		if($QTY1==$numRows1 or $drum_qty==$numRows1){
				$check_finished=1;
			my_msg($_POST['lot_id']."檢查已完成");
		}
		else{
			$check_finished=0;
			if($ck1==1){
//				echo "A<BR>";
				echo "客戶料號:".'<input type="text" name="cust_prod_no" autocomplete="off" id="cust_prod_no" style="font-size:20px" value="" autofocus="autofocus" /></br>';
				echo '<input type="hidden" name="custbar1" id="custbar1" value="'.$custbar1.'">';
				echo '<input type="hidden" name="check_custbar1" id="check_custbar1" value="'.$ck1.'">';
				echo '<input type="hidden" name="nono" id="nono" value="'.$_POST['nono'].'">';
				echo '<input type="hidden" name="sc" id="sc" value="'.$start_char.'">';
				
				echo "桶  號  :&nbsp; &nbsp; &nbsp;".'<input type="text" name="no1" autocomplete="off" id="no1" autofocus="autofocus" style="font-size:20px" value="'.$_SESSION['dm_no'].'"/></br>';
				echo "棧板編號:".'<input type="text" name="no2" autocomplete="off" id="no2" style="font-size:20px" value="'.$_SESSION['pa_no'].'"/></br>';
				echo '<input type="submit" name="next2" style="font-size:20px" id="next2" value="下一桶/儲存" />';
				echo "      ".'<input type="submit" name="next3" style="font-size:20px" id="next3" value="結束刷桶" />'.'</br>';
			}
			else{
//				echo "B<BR>";
				echo '<input type="hidden" name="custbar1" id="custbar1" value="'.$custbar1.'">';
				echo '<input type="hidden" name="check_custbar1" id="check_custbar1" value="'.$ck1.'">';
				echo '<input type="hidden" name="nono" id="nono" value="'.$_POST['nono'].'">';
				echo '<input type="hidden" name="sc" id="sc" value="'.$start_char.'">';
				echo "桶  號  :&nbsp;&nbsp; &nbsp; ".'<input type="text" name="no1" autocomplete="off" id="no1" autofocus="autofocus" style="font-size:20px" value="'.$_SESSION['dm_no'].'"/></br>';
				echo "棧板編號:".'<input type="text" name="no2" autocomplete="off" id="no2" style="font-size:20px" value="'.$_SESSION['pa_no'].'"/></br>';
				echo '<input type="submit" name="next2" style="font-size:20px" id="next2" value="下一桶/儲存" />';
				echo "      ".'<input type="submit" name="next3" style="font-size:20px" id="next3" value="結束刷桶" />'.'</br>';
			}
			echo "共".$_SESSION['QTY']."桶 , 已完成".$_SESSION['numrow']."桶<BR>";
		}
	$query="SELECT          OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO, OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO, 
                            OUT_CHECK_DRUM_DETAIL.OCD_CUST_BAR, OUT_CHECK_DRUM_DETAIL.OCD_PLT_NO, 
                            OUT_CHECK_DRUM_DETAIL.OCD_CUSTBAR1, OUT_CHECK_DRUM_DETAIL.OCD_CUSTBAR2, 
                            OUT_CHECK_DRUM.OTD_NO AS Expr1, OUT_CHECK_DRUM_DETAIL.OTD_NO
FROM              OUT_CHECK_DRUM_DETAIL INNER JOIN
                            OUT_CHECK_DRUM ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_CHECK_DRUM.OTD_NO
WHERE          (OUT_CHECK_DRUM_DETAIL.OTD_NO = '".$_POST['nono']."' and OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO='".$_POST['lot_id']."')";

	$result=mssql_query($query);
	$all=mssql_num_rows($result);
}
if(isset($_POST['next2']) and $_POST['no1']<>'' and $_POST['no2']<>'' )
{	
	$_SESSION['pa_no']=$_POST['no2'];
	$_SESSION['dm_no']=$_POST['no1'];
	$_SESSION['cust_prod_no']=$_POST['cust_prod_no'];
//	echo  "客戶 : ".$_SESSION['cidcname'].')<br>';
//	echo  "品名 : ".$_SESSION['pidcname'].')<br>';
	echo '<input type="hidden" name="no" autocomplete="off" id="no" style="font-size:20px" value="'.$_SESSION['lot_id'].'"  readonly="readonly" /></br>';
	
	$ck1=$_POST['check_custbar1'];
//	
	if($ck1==1){  // 檢查客戶料號
			$_SESSION['check_custbar1']=1;
			$longstring= trim($_POST['cust_prod_no']);
			
			if($_POST['sc']==1){
				$shortString= trim($_POST['custbar1']);
				$cpsl=strlen($shortString);  //客戶料號長度
				$start_from=$_POST['sc'];
				$str1=substr($longstring,$start_from,$cpsl);
				$str2=$shortString;
			}
			else{
				$shortString= trim($_POST['custbar1']);
				$str1=($_POST['cust_prod_no']);
				$str2=$shortString;
			}
			
			
//			echo "CK1:##".$longstring."##<BR>".$shortString;
			if ($str1<>$str2) {
		//		echo "#".$str1.":".$str2."#<BR>";
				 my_msg("輸入的客戶料號錯誤");
			} 
		}	
	$_SESSION['steps']=3;
	$_SESSION['pa_no']=$_POST['no2'];
	if($_SESSION['QTY']>$_SESSION['numrow'])
	{
		$OK=$OK+1;
	}
	$str1=explode("/",$_SESSION['LOT']);
	for($i=0;$i<count($str1);$i++)
	{	
		if(trim($_POST['no'])==trim($str1[$i]))
		{
			$OK=$OK+1;
			$alarm=1;
		}
	}
	if($alarm<>1)
	{
			echo "LOTNO 錯誤".'<br>';
	}
	$drum="select * from DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST['no1']."'";
	$resultd = mssql_query($drum);
	$numrowss=mssql_num_rows($resultd);
//	echo "##".$numrowss."##<BR>";
	if($numrowss==0)
	{
		create_drum($_POST['no1'],$_SESSION['prod_id'],$_POST['no']);
	}
	$drum1="select * from OUT_CHECK_DRUM_DETAIL where OCD_DRUM_NO='".$_POST['no1']."' and OTD_NO='".$_POST['nono']."'";
//	echo $drum1."<BR>";
	$resultd1 = mssql_query($drum1);
	$numRowsd1 = mssql_num_rows($resultd1);
	if($numRowsd1 >0 ){
		$_SESSION['duplicate']=1;
		my_msg("桶號重覆");
	}
	$drum="select * from DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST['no1']."'";
//	echo $drum."<BR>";
	$resultd = mssql_query($drum);
		$numRowsd = mssql_num_rows($resultd);
				while($rowd = mssql_fetch_array($resultd))
				{
					$used=$rowd['DHN_USED_COUNT']; 
					$disc=trim($rowd['DHN_DISCARD_DATE']);
					$pd=trim($rowd['PDD_PROD_NO1']);
				}
				$str2=explode(",",$_SESSION['PDD']);
				for($i=0;$i<count($str2);$i++)
				{	
					
						if($numRowsd>0 and  $disc == NULL and $pd==trim($str2[$i]) and $numRowsd1==0)
						{
							$OK=$OK+1;
							$alarm1=1;
						}
				
				}
		
		if($alarm1<>1){// echo "桶號錯誤或藥品不符".'<br>';
			}

	$bord="select * from BOARD where BOD_NO='".$_SESSION['pa_no']."'";
	$resultb = mssql_query($bord);
		$numRowsb = mssql_num_rows($resultb);
		 while($rowb = mssql_fetch_array($resultb))
		 {
		 	
			 $query="SELECT  TOP (1) BOA_TIMES + 1 AS cnt, BOA_OUT_DATE FROM BOARD_ALL WHERE (BOA_NO = '".$_SESSION['pa_no']."') ORDER BY BOA_TIMES DESC";
//			 echo "<BR>".$query."<BR>";
					$result=mssql_query($query);
					$row=mssql_fetch_row($result);
					$cnt=$row[0];
			if($cnt == NULL){
				
				// 新增棧板
			}
			elseif($row[1]<>date("Ymd")){
			$query="INSERT INTO BOARD_ALL (BOA_NO, BOA_TIMES, BOA_BEGIN_DATE, BOA_OUT_LOC, BOA_OUT_DATE) VALUES  ('".$_SESSION['pa_no']."', ".$cnt.",'".$rowb['BOD_BEGIN_DATE']."',  '".$_SESSION['cust_id']."', '".date("Ymd")."')";
//				echo "<BR>".$query."<BR>";
				$result=mssql_query($query);
			}
					
					
			 if($rowb['BOD_OUT_DATE']==date("Ymd"))
			 {
				 $BC=2;
			 }
			 if(trim($rowb['BOD_OUT_DATE'])=='')
			 {
				 $BC=1;
			 }
		 }
		 if($BC==2)
		 {
			 $bord2="select * from OUT_CHECK_DRUM_DETAIL where OTD_NO='".$_POST['nono']."' and OCD_PLT_NO='".trim($_SESSION['pa_no'])."' ";

			 $resultb2 = mssql_query($bord2);
			 $numRowsb2 = mssql_num_rows($resultb2);
			 if($numRowsb2 < 4)
			 {
			  	$BC=1;
			 }
		 }
		
		if($BC==1)
		{
			$OK=$OK+1;
		}
		if($BC!=1){ //echo "棧板編號錯誤".'<br>';
			}
			$query2="SELECT OP.OPM_ORDER_NO, OP.OPD_SERIAL_NO, OD.OTD_NO, OP.OTN_NO, OP.OTNP_SERIAL_NO, OP.PDD_PROD_NO, 
                            OP.OAF_PACKAGE, OP.OPD_LOT_NO, OP.PRA_SERIAL_NO, OP.OPD_TERM_DATE, OP.OPD_VALIDATE, 
                            OP.OPD_QTY_DRUM, OP.OPD_ACC_UNIT, OP.OPD_QTY_LITER, OP.OPD_QTY_KG, OP.OPD_SIGN_RECEIPT, 
                            OP.OPD_COA_RECEIPT, OP.OPD_MEMO, OP.OPD_REAL_QTY_KG, OP.OPD_INWARD_DATE, OP.OPD_COA_NO, 
                            OP.COA_INDEX, OP.OAF_ACC_ID, OP.OPD_SMP_DATETIME, OP.OPD_COA_DATETIME, OD.CTD_CUST_NO
FROM              OUT_PRODUCT AS OP INNER JOIN
                            OUT_DECISION AS OD ON OP.OPM_ORDER_NO = OD.OPM_ORDER_NO 
			 where OD.OTD_NO='".$_POST['nono']."' and REPLACE(OP.OPD_LOT_NO,' ','')='".trim($_POST['no'])."'";
//			echo "<BR>".$query2."<BR>";
			$result2 = mssql_query($query2);
			$numRows2 = mssql_num_rows($result2);
			 while($row2 = mssql_fetch_array($result2))
				{
					
				if(trim($_POST['no1'])<>'' and trim($_SESSION['pa_no'])<>''){
					$inser="insert into OUT_CHECK_DRUM_DETAIL (OTD_NO,OCD_LOT_NO,OCD_DRUM_NO,OCD_PLT_NO,OCD_CUSTBAR1) 
					VALUES ('".trim($_POST['nono'])."','".trim($_POST['no'])."','".trim($_POST['no1'])."','".trim($_SESSION['pa_no'])."', '".$_POST['cust_prod_no']."')";
			//		echo $inser."<BR>";
			//		$_SESSION['insert']=$inser;
					$resultin = mssql_query($inser);
					if($used==0){$used=1;}
					$up1="UPDATE DRUM_HISTORY_NORMAL set CTD_CUST_NO".$used."='".$row2['CTD_CUST_NO']."',
					PDD_PROD_NO".$used."='".$row2['PDD_PROD_NO']."',DHN_LOT_NO".$used."='".$_POST['no']."',
					DHN_OUT_DATE".$used."='".date("Ymdhis")."' 
					where DHN_DRUM_NO='".$_POST['no1']."'";
		
					$resultup1 = mssql_query($up1);
		//			$_SESSION['code2']=$up1;
					$up2="UPDATE BOARD set BOD_OUT_LOC='".$row2['CTD_CUST_NO']."',BOD_OUT_DATE='".date("Ymd")."' 
					where BOD_NO='".$_SESSION['pa_no']."'";
					$resultup2 = mssql_query($up2);
					echo  "客戶 : ".$_SESSION['cidcname'].')<br>';
					echo  "品名 : ".$_SESSION['pidcname'].')<br>';
					echo "LOT NO:".'<input type="text" autocomplete="off" size="10" name="no" id="no" style="font-size:20px" value="'.$_SESSION['aa'][0].'" readonly=" readonly"/><br>';
					$query3="SELECT  OUT_PRODUCT.OPD_QTY_DRUM FROM OUT_CHECK_DRUM_DETAIL INNER JOIN OUT_PRODUCT ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_PRODUCT.OTD_NO AND OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO = OUT_PRODUCT.OPD_LOT_NO WHERE 
					(OUT_CHECK_DRUM_DETAIL.OTD_NO = '".trim($_POST['nono'])."') AND (OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO = '".trim($_POST['no'])."')";
//				echo $query3."<BR>";
					$result3=mssql_query($query3);
					$row3=mssql_num_rows($result3);
					$row4=mssql_fetch_row($result3);
					if($row3 >= $row4[0]){
						$check_finished=1;
					}
					else{	
						echo '第'. ($row3+1).'/'.$row4[0].'桶';
						echo '</br>';
//						echo "C<BR>";
						if($_POST['check_custbar1']==1){
							echo "客戶料號:".'<input type="text" name="cust_prod_no" autocomplete="off" id="cust_prod_no" style="font-size:20px" value="" autofocus="autofocus" /></br>';
						}
						echo '<input type="hidden" name="custbar1" id="custbar1" value="'.$_POST['custbar1'].'">';
						echo '<input type="hidden" name="check_custbar1" id="check_custbar1" value="'.$ck1.'">';
						echo '<input type="hidden" name="sc" id="sc" value="'.$_POST['sc'].'">';
						echo "桶  號  : &nbsp;&nbsp;&nbsp; ".'<input type="text" autocomplete="off" name="no1" autofocus="autofocus" id="no1" style="font-size:20px" value="'.$_SESSION['dm_no'].'"/></br>';
						echo "棧板編號:".'<input type="text" autocomplete="off" name="no2" id="no2" style="font-size:20px" value="'.$_SESSION['pa_no'].'"/></br>';
						echo '<input type="submit" name="next2" style="font-size:20px" id="next2" value="下一桶/儲存" />';
						echo "      ".'<input type="submit" style="font-size:20px" name="next3" id="next3" value="結束刷桶" />'.'</br>';	
					}
					
				}
				}
				echo "桶號:".$_POST['no1']."已寫入".'<br>';
}//end next2
elseif(isset($_POST['next2']) and ($_POST['no1']=='' or $_POST['no2']=='')){
	$_SESSION['pa_no']=$_POST['no2'];
	$_SESSION['dm_no']=$_POST['no1'];
	$_SESSION['cust_prod_no']=$_POST['cust_prod_no'];
//	echo  "客戶 : ".$_SESSION['cidcname'].')<br>';
//	echo  "品名 : ".$_SESSION['pidcname'].')<br>';
	echo '<input type="hidden" name="no" autocomplete="off" id="no" style="font-size:20px" value="'.$_SESSION['lot_id'].'"  readonly="readonly" /></br>';
	$ck1=$_POST["check_custbar1"];
				if($ck1==1){
//				echo "A<BR>";
				echo "客戶料號:".'<input type="text" name="cust_prod_no" autocomplete="off" id="cust_prod_no" style="font-size:20px" value="'.$_SESSION['cust_prod_no'].'" autofocus="autofocus" /></br>';
				echo '<input type="hidden" name="custbar1" id="custbar1" value="'.$_POST['custbar1'].'">';
				echo '<input type="hidden" name="check_custbar1" id="check_custbar1" value="'.$_POST['check_custbar1'].'">';
				echo '<input type="hidden" name="nono" id="nono" value="'.$_POST['nono'].'">';
				echo '<input type="hidden" name="sc" id="sc" value="'.$_POST['sc'].'">';
				
				echo "桶  號  :&nbsp; &nbsp; &nbsp;".'<input type="text" name="no1" autocomplete="off" id="no1" autofocus="autofocus" style="font-size:20px" value="'.$_SESSION['dm_no'].'"/></br>';
				echo "棧板編號:".'<input type="text" name="no2" autocomplete="off" id="no2" style="font-size:20px" value="'.$_SESSION['pa_no'].'"/></br>';
				echo '<input type="submit" name="next2" style="font-size:20px" id="next2" value="下一桶/儲存" />';
				echo "      ".'<input type="submit" name="next3" style="font-size:20px" id="next3" value="結束刷桶" />'.'</br>';
			}
			else{
//				echo "B<BR>";
				echo '<input type="hidden" name="custbar1" id="custbar1" value="'.$_POST['custbar1'].'">';
				echo '<input type="hidden" name="check_custbar1" id="check_custbar1" value="'.$_POST['check_custbar1'].'">';
				echo '<input type="hidden" name="nono" id="nono" value="'.$_POST['nono'].'">';
				echo '<input type="hidden" name="sc" id="sc" value="'.$_POST['sc'].'">';
				echo "桶  號  :&nbsp;&nbsp; &nbsp; ".'<input type="text" name="no1" autocomplete="off" id="no1" autofocus="autofocus" style="font-size:20px" value="'.$_SESSION['dm_no'].'"/></br>';
				echo "棧板編號:".'<input type="text" name="no2" autocomplete="off" id="no2" style="font-size:20px" value="'.$_SESSION['pa_no'].'"/></br>';
				echo '<input type="submit" name="next2" style="font-size:20px" id="next2" value="下一桶/儲存" />';
				echo "      ".'<input type="submit" name="next3" style="font-size:20px" id="next3" value="結束刷桶" />'.'</br>';
			}
			echo "共".$_SESSION['QTY']."桶 , 已完成".$_SESSION['numrow']."桶<BR>";
}


if(isset($_POST['next3']) or $check_finished==1)
{
	echo '<input type="hidden" name="nono" id="nono" value="'.$_POST['nono'].'">';
	echo '<input type="checkbox" name="chk1" checked="checked"  value="1">'."捆包檢查確認".'</br>';
	echo '<input type="checkbox" name="chk2"  checked="checked" value="2">'."外觀檢查、髒汙、洩漏、傷痕、凹凸等".'</br>';
	echo "棧板:".'<br>';
	
	echo '<input type="radio" checked="checked" name="chose1" value="1" style="font-size:20px" id="RadioGroup1_0" />木頭
		<input type="radio" name="chose1" value="2" id="RadioGroup1_1" style="font-size:20px" />塑膠
		<input type="radio" name="chose1" value="3" id="RadioGroup1_1" style="font-size:20px" />其他<br>';
	  
	echo "櫃內荷姿:".'<br>';
		echo '<input type="radio" name="chose11" value="1" id="RadioGroup1_0" style="font-size:20px" />木架
		<input type="radio" name="chose11" value="2" id="RadioGroup1_1" style="font-size:20px" />消毒
		<input type="radio" name="chose11" value="3" id="RadioGroup1_1" style="font-size:20px"/>其他
		<input type="radio" checked="checked" name="chose11" value="4" id="RadioGroup1_1" style="font-size:20px"/>NA<BR>';
	echo '<input type="submit" name="next4" style="font-size:20px" id="next4" value="確定並結束" />';
		
}
if((isset($_POST['next4'])) and $_POST['chk1']<>1 and $_POST['chk2']<>2)
{

	echo '<input type="hidden" name="nono" id="nono" value="'.$_POST['nono'].'">';
	echo '<input type="checkbox" name="chk1" checked="checked"  value="1" style="font-size:20px">'."捆包檢查確認".'</br>';
	echo '<input type="checkbox" name="chk2"  checked="checked" value="2" style="font-size:20px">'."外觀檢查、髒汙、洩漏、傷痕、凹凸等".'</br>';
	echo "棧板:".'<br>';
	
	echo '<input type="radio" checked="checked" name="chose1" value="1" id="RadioGroup1_0" style="font-size:20px"/>木頭
		<input type="radio" name="chose1" value="2" id="RadioGroup1_1" style="font-size:20px"/>塑膠
		<input type="radio" name="chose1" value="3" id="RadioGroup1_1" style="font-size:20px"/>其他<br>';
	  
	echo "櫃內荷姿:".'<br>';
		echo '<input type="radio" name="chose11" value="1" id="RadioGroup1_0" style="font-size:20px"/>木架
		<input type="radio" name="chose11" value="2" id="RadioGroup1_1" style="font-size:20px"/>消毒
		<input type="radio" name="chose11" value="3" id="RadioGroup1_1" style="font-size:20px"/>其他
		<input type="radio" checked="checked" name="chose11" value="4" id="RadioGroup1_1" style="font-size:20px"/>NA<BR>';
	echo '<input type="submit" checked="checked" name="next4" id="next4" value="確定並結束" style="font-size:20px"/>';
	echo "請確認輸入無誤";
}
if((isset($_POST['next4'])) and $_POST['chk1']==1 and $_POST['chk2']==2)
{
	if($_POST['chose1']==1){$zhi="木頭";}
	if($_POST['chose1']==2){$zhi="塑膠";}
	if($_POST['chose1']==3){$zhi="其他";}
	if($_POST['chose11']==1){$hoz="木架";}
	if($_POST['chose11']==2){$hoz="消毒";}
	if($_POST['chose11']==3){$hoz="其他";}
	if($_POST['chose11']==4){$hoz="NA";}
    $last="select OTD_NO from OUT_CHECK_DRUM where OTD_NO='".$_SESSION['nono']."' ";
	$resultlast = mssql_query($last);
	$numRowslast = mssql_num_rows($resultlast);
	if($numRowslast>0  and trim($_SESSION['uid'])<>'')
	{
		$up3="update OUT_CHECK_DRUM set OCM_CHK_DATE='".date("Ymd")."',OCM_CHK_STYLE='Y',OCM_CHK_ADDITION='Y',OCM_CHK_SURFACE='Y',
		OCM_PLT_NAME='".$zhi."',OCM_PLT_STYLE='".$hoz."',OCM_CF_MAN='".$_SESSION['uid']."'
		where OTD_NO='".$_SESSION['nono']."'";
//		echo $up3;
		$resultup3 = mssql_query($up3);
		$_SESSION['pda']=$up3;
	}
	elseif($numRowslast==0 and trim($_SESSION['uid'])<>'')
	{
		$inser1="insert into OUT_CHECK_DRUM (OTD_NO,OCM_CHK_DATE,OCM_CHK_STYLE,
		OCM_CHK_ADDITION,OCM_CHK_SURFACE,OCM_PLT_NAME,OCM_PLT_STYLE,OCM_CF_MAN) VALUES ('".$_SESSION['nono']."','".date("Ymd")."','Y','Y','Y',
			'".$zhi."',	'".$hoz."','".$_SESSION['uid']."')";
			$resultins1 = mssql_query($inser1);

	}
	elseif(trim($_SESSION['uid'])==''){
		my_msg("請重新登入","login.php");
	}
//	jumpto("index.php");
}
?>
<input type="submit" name="enter" style="font-size:20px" id="enter" value="送出" />  

</form>
<a href="index.php"><strong>取消/離開</strong></a><br>