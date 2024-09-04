<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
datepick();
datepick1();
?>
<script type="text/javascript" src="/css/jquery.min.js"></script>
    <script type="text/javascript" src="/css/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/css3menu/GridViewScroll/gridviewScroll.min.js"></script>
    <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
        <script type="text/javascript">
	    $(document).ready(function () {
	        gridviewScroll();
	    });
	
	    function gridviewScroll() {
	        gridView1 = $('#GridView1').gridviewScroll({
                width: 1300,
                height: 550,
                railcolor: "#F0F0F0",
                barcolor: "#CDCDCD",
                barhovercolor: "#606060",
                bgcolor: "#F0F0F0",
                freezesize: 1,
                arrowsize: 30,
                varrowtopimg: "/css3menu/GridViewScroll/Images/arrowvt.png",
                varrowbottomimg: "/css3menu/GridViewScroll/Images/arrowvb.png",
                harrowleftimg: "/css3menu/GridViewScroll/Images/arrowhl.png",
                harrowrightimg: "/css3menu/GridViewScroll/Images/arrowhr.png",
                headerrowcount: 1,
                railsize: 16,
                barsize: 8
            });
	    }
	</script>
查詢 </br><form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">

用戶:
<input name="cust" type="text" id="csut" size="10" value="<?php 
		if ($_GET['cust_no']){
			echo $_GET['cust_no'];
			$_SESSION['cust_no']=$_GET['cust_no'];
		}
		elseif($_SESSION['cust_no']){
			echo $_SESSION['cust_no'];
		}
		else{
		echo '';
		}?>">
   
        
		<input name="textfield4" type="text" id="textfield4" size="10" value="<?php 
if ($_GET['name']){
			echo $_GET['cust_name'];
			$_SESSION['cust_name']=$_GET['cust_name'];
		}
		elseif($_SESSION['cust_name']){
			echo $_SESSION['cust_name'];
		}
		else{
		echo '';
		}
		 
		
		?>" readonly>
        <input type="button" name="cust" id="cust" value="選擇客戶" onClick="window.open('./cust_no.php ', '_self');" >
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('./erase_customer.php ', '_self');" />
        
        品名:
<input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['prod_no']){
			echo $_GET['prod_no'];
			$_SESSION['prod_no']=$_GET['prod_no'];
		}
		elseif($_SESSION['prod_no']){
			echo $_SESSION['prod_no'];
		}
		else{
		echo '';
		}?>">
   
        
		<input name="textfield1" type="text" id="textfield1" size="10" value="<?php 
if ($_GET['name']){
			echo $_GET['prod_name'];
			$_SESSION['prod_name']=$_GET['prod_name'];
		}
		elseif($_SESSION['prod_name']){
			echo $_SESSION['prod_name'];
		}
		else{
		echo '';
		}
		 
		
		?>" readonly>
        <input type="button" name="pdd" id="pdd" value="選擇藥品" onClick="window.open('./pdd_prod.php ', '_self');" >
        <input type="button" name="X" id="X" value="X" onclick="window.open('./erase_prod.php ', '_self');" /> 
        目前次數: <input name="nownum" type="text" id="nownum" size="10"  />
        D/M NO: <input name="dmno" type="text" id="dmno" size="10"  />
        LOT NO: <input name="lotno" type="text" id="lotno" size="10"  />
        
       
        
        </br>
        </br>
出荷日期： <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
<input type="button" name="X3" id="X3" value="X" onclick="window.open('./erase_time.php ', '_self');" />

華立回收日期： <input name="datepicker3" type="text" id="datepicker3" size="10" value="<?php if($_SESSION['datepicker3']){echo $_SESSION['datepicker3'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker4" type="text" id="datepicker4" size="10" value="<?php if($_SESSION['datepicker4']){echo $_SESSION['datepicker4'];}?>" onChange="set_date_session(this.name,this.value)"></td>
TYS回收日期： <input name="datepicker5" type="text" id="datepicker5" size="10" value="<?php if($_SESSION['datepicker5']){echo $_SESSION['datepicker5'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker6" type="text" id="datepicker6" size="10" value="<?php if($_SESSION['datepicker6']){echo $_SESSION['datepicker6'];}?>" onChange="set_date_session(this.name,this.value)"></td>
抽殘液回收再判斷(Y/N): <input name="chk2" type="text" id="chk2" size="10"  />

		</br>
        </br>
D/M廢棄出廠日： <input name="datepicker7" type="text" id="datepicker7" size="10" value="<?php if($_SESSION['datepicker7']){echo $_SESSION['datepicker7'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker8" type="text" id="datepicker8" size="10" value="<?php if($_SESSION['datepicker8']){echo $_SESSION['datepicker8'];}?>" onChange="set_date_session(this.name,this.value)"></td>

D/M廢棄再確認日： <input name="datepicker9" type="text" id="datepicker9" size="10" value="<?php if($_SESSION['datepicker9']){echo $_SESSION['datepicker9'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker10" type="text" id="datepicker10" size="10" value="<?php if($_SESSION['datepicker10']){echo $_SESSION['datepicker10'];}?>" onChange="set_date_session(this.name,this.value)"></td>
轉用日 : <input name="datepicker11" type="text" id="datepicker11" size="10" value="<?php if($_SESSION['datepicker11']){echo $_SESSION['datepicker11'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker12" type="text" id="datepicker12" size="10" value="<?php if($_SESSION['datepicker12']){echo $_SESSION['datepicker12'];}?>" onChange="set_date_session(this.name,this.value)"></td>
回收判斷(Y/N): <input name="chk1" type="text" id="chk1" size="10"  />



        
        
        <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
         <input name="submit3" type="submit" class="center button" id="submit3" value=" 下載報告 ">
        
        <?php 
		if(isset($_POST['submit']))
		{
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);
			$date3=dod($_SESSION['datepicker3']);
			$date4=dod($_SESSION['datepicker4']);
			$date5=dod($_SESSION['datepicker5']);
			$date6=dod($_SESSION['datepicker6']);
			$date7=dod($_SESSION['datepicker7']);
			$date8=dod($_SESSION['datepicker8']);
			$date9=dod($_SESSION['datepicker9']);
			$date10=dod($_SESSION['datepicker10']);
			$date11=dod($_SESSION['datepicker11']);
			$date12=dod($_SESSION['datepicker12']);
			$p=2;
			$objPHPExcel = new PHPExcel();

			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","drum追蹤一覽表"));
			
			
			$query="SELECT  DHN.DHN_USED_COUNT AS TIME, DHN.DHN_MAKE_DATE AS P_D, CD.CTD_CUST_SHORT_NAME AS 用戶1, 
                   DHN.PDD_PROD_NO1 AS 料號1, PD.PDD_PROD_NAME AS 品名1, DHN.DHN_DRUM_NO AS [DMNO], 
                   DHN.DHN_WASHED_DATE1 AS 洗淨日1, DHN.DHN_FILLED_DATE1 AS 充填日1, DHN.DHN_LOT_NO1 AS [LOT NO1], 
                   DHN.DHN_OUT_DATE1 AS 出荷日1, DHN.DHN_VALIDATE1 AS 藥品有效期限1, 
                   DHN.DHN_WL_RCV_DATE1 AS 華立回收日期1, DHN.DHN_TYS_RCV_DATE1 AS TYS回收日期1, 
                   DHN.DHN_RCV_DECIDE1 AS 回收判斷1, DHN.DHN_REMAIN_DATE1 AS 抽殘液日期1, 
                   DHN.DHN_REMAIN_DECIDE1 AS 抽殘液回收再判斷1, CD2.CTD_CUST_SHORT_NAME AS 用戶2, 
                   DHN.PDD_PROD_NO2 AS 料號2, PD2.PDD_PROD_NAME AS 品名2, DHN.DHN_WASHED_DATE2 AS 洗淨日2, 
                   DHN.DHN_FILLED_DATE2 AS 充填日2, DHN.DHN_LOT_NO2 AS [LOT NO2], DHN.DHN_OUT_DATE2 AS 出荷日2, 
                   DHN.DHN_VALIDATE2 AS 藥品有效期限2, DHN.DHN_WL_RCV_DATE2 AS 華立回收日期2, 
                   DHN.DHN_TYS_RCV_DATE2 AS TYS回收日期2, DHN.DHN_RCV_DECIDE2 AS 回收判斷2, 
                   DHN.DHN_REMAIN_DATE2 AS 抽殘液日期2, DHN.DHN_REMAIN_DECIDE2 AS 抽殘液回收再判斷2, 
                   CD3.CTD_CUST_SHORT_NAME AS 用戶3, DHN.PDD_PROD_NO3 AS 料號3, PD3.PDD_PROD_NAME AS 品名3, 
                   DHN.DHN_WASHED_DATE3 AS 洗淨日3, DHN.DHN_FILLED_DATE3 AS 充填日3, DHN.DHN_LOT_NO3 AS [LOT NO3], 
                   DHN.DHN_OUT_DATE3 AS 出荷日3, DHN.DHN_VALIDATE3 AS 藥品有效期限3, 
                   DHN.DHN_WL_RCV_DATE3 AS 華立回收日期3, DHN.DHN_TYS_RCV_DATE3 AS TYS回收日期3, 
                   DHN.DHN_RCV_DECIDE2 AS 回收判斷3, DHN.DHN_REMAIN_DATE3 AS 抽殘液日期3, 
                   DHN.DHN_REMAIN_DECIDE2 AS 抽殘液回收再判斷3, LEFT(DHN.DHN_DISCARD_DATE, 8) AS [D/M廢棄出廠日], 
                   DHN.DHN_DISCARD_MAN AS 出廠作業人員, DHN.DHN_TRANS_DATE AS 轉用日, LEFT(DHN.DHN_CF_DISCARD_DATE, 8) 
                   AS [D/M廢棄再確認日], DHN.DHN_MEMO AS 備註, LEFT(DHN.DHN_DISCARD_W_DATE, 8) AS 廢棄洗桶日, 
                   DHN.CTD_CUST_NO1, DHN.CTD_CUST_NO2, DHN.CTD_CUST_NO3, DHN.PDD_PROD_NO1, DHN.PDD_PROD_NO2, 
                   DHN.PDD_PROD_NO3
FROM      DRUM_HISTORY_NORMAL AS DHN LEFT OUTER JOIN
                   PRODUCT_DATA AS PD ON DHN.PDD_PROD_NO1 = PD.PDD_PROD_NO LEFT OUTER JOIN
                   PRODUCT_DATA AS PD2 ON DHN.PDD_PROD_NO2 = PD2.PDD_PROD_NO LEFT OUTER JOIN
                   PRODUCT_DATA AS PD3 ON DHN.PDD_PROD_NO3 = PD3.PDD_PROD_NO LEFT OUTER JOIN
                   CUSTOMER_DATA AS CD ON DHN.CTD_CUST_NO1 = CD.CTD_CUST_NO LEFT OUTER JOIN
                   CUSTOMER_DATA AS CD2 ON DHN.CTD_CUST_NO2 = CD2.CTD_CUST_NO LEFT OUTER JOIN
                   CUSTOMER_DATA AS CD3 ON DHN.CTD_CUST_NO3 = CD3.CTD_CUST_NO
WHERE   DHN.DHN_DRUM_NO<>'' ";
				   
			if($_POST['lotno']<>''){$query=$query."and (DHN_LOT_NO1='".$_POST['lotno']."' or DHN_LOT_NO2='".$_POST['lotno']."' or DHN_LOT_NO3='".$_POST['lotno']."')";}
			else{
				if($_SESSION['datepicker1']<>''){
					$query=$query." and  ((DHN_OUT_DATE1>='".$date1."000000' and DHN_OUT_DATE1<='".$date2."235959') or (DHN_OUT_DATE2>='".$date1."000000' and DHN_OUT_DATE2<='".$date2."235959') or (DHN_OUT_DATE3>='".$date1."000000' and DHN_OUT_DATE3<='".$date2."235959') )";
					}
				if($_POST['cust']<>''){$query=$query."and CTD_CUST_NO1='".$_POST['cust']."'";}
				if($_POST['pdd_chemical1']<>''){$query=$query."and PDD_PROD_NO1='".$_POST['pdd_chemical1']."'";}
				if($_POST['nownum']<>''){$query=$query."and DHN_USED_COUNT='".$_POST['nownum']."'";}
				if($_POST['dmno']<>''){$query=$query."and DHN_DRUM_NO='".$_POST['dmno']."'";}
				if($_POST['lotno']<>''){$query=$query."and DHN_LOT_NO1='".$_POST['lotno']."'";}
				if($_SESSION['datepicker3']<>''){$query=$query." and DHN_WL_RCV_DATE1>='".$date3."'
																 and DHN_WL_RCV_DATE1<='".$date4."' ";}
				if($_SESSION['datepicker5']<>''){$query=$query." and DHN_TYS_RCV_DATE1>='".$date5."'
																 and DHN_TYS_RCV_DATE1<='".$date6."'";}
				if($_POST['chk1']<>''){$query=$query."and DHN_RCV_DECIDE1='".$_POST['chk1']."'";}
				if($_POST['chk2']<>''){$query=$query."and DHN_REMAIN_DECIDE1='".$_POST['chk2']."'";}
				if($_SESSION['datepicker7']<>''){$query=$query." and DHN_DISCARD_DATE>='".$date7."'
																 and DHN_DISCARD_DATE<='".$date8."'";}
				if($_SESSION['datepicker9']<>''){$query=$query." and DHN_DISCARD_W_DATE>='".$date9."'
																 and DHN_DISCARD_W_DATE<='".$date10."'";}																											
				if($_SESSION['datepicker11']<>''){$query=$query." and DHN_TRANS_DATE>='".$date11."000000"."'
			 												 and DHN_TRANS_DATE<='".$date12."999999"."'";}
			}
			$query=$query." ORDER BY 品名1, SUBSTRING(DHN.DHN_DRUM_NO, 3, 1) DESC, SUBSTRING(DHN.DHN_DRUM_NO, 7, 2) DESC, 
                   SUBSTRING(DHN.DHN_DRUM_NO, 4, 3)	";
//			echo "<BR>".$query."<BR>";
			$result = mssql_query($query);
		$numrows = mssql_num_rows($result);
		 echo '<table id="GridView1" width="" border="1" align="left">
  <tr class="GridviewScrollHeader">';
  				echo  '<td>'."count".'</td>';
			 echo  '<td>'."目前次數".'</td>';
			  echo  '<td>'."製造日期".'</td>';
			   echo  '<td>'."D/M NO".'</td>';
			   $objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","目前次數"));
			$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","製造日期"));
			$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","D/M NO"));
			
			   
			   echo  '<td>'."用戶1".'</td>';
			    echo  '<td>'."品名1".'</td>';
			  echo  '<td>'."洗淨日1".'</td>';
			 echo  '<td>'."充填日1".'</td>';
			  echo  '<td>'."LOTNO1".'</td>';
			   echo  '<td>'."出荷日1".'</td>'; 
			 echo  '<td>'."藥品有效期限1".'</td>';
			  echo  '<td>'."華立回收日期1".'</td>';
			   echo  '<td>'."TYS回收日期1".'</td>';
			   echo  '<td>'."回收判斷1".'</td>';
			    echo  '<td>'."抽殘液日期1".'</td>';
			  echo  '<td>'."抽殘液回收再判斷1".'</td>';
			  $objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","用戶1"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","品名1"));
			$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","洗淨日1"));
			$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","充填日1"));
			$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","LOTNO1"));
			$objPHPExcel->getActiveSheet()->setCellValue("I".$p, iconv("big5","utf-8","出荷日1"));
			$objPHPExcel->getActiveSheet()->setCellValue("J".$p, iconv("big5","utf-8","藥品有效期限1"));
			$objPHPExcel->getActiveSheet()->setCellValue("K".$p, iconv("big5","utf-8","華立回收日期1"));
			$objPHPExcel->getActiveSheet()->setCellValue("L".$p, iconv("big5","utf-8","TYS回收日期1"));
			$objPHPExcel->getActiveSheet()->setCellValue("M".$p, iconv("big5","utf-8","回收判斷1"));
			$objPHPExcel->getActiveSheet()->setCellValue("N".$p, iconv("big5","utf-8","抽殘液日期1"));
			$objPHPExcel->getActiveSheet()->setCellValue("O".$p, iconv("big5","utf-8","抽殘液回收再判斷1"));			  
			  echo  '<td>'."用戶2".'</td>';
			    echo  '<td>'."品名2".'</td>';
			  echo  '<td>'."洗淨日2".'</td>';
			 echo  '<td>'."充填日2".'</td>';
			  echo  '<td>'."LOTNO2".'</td>';
			   echo  '<td>'."出荷日2".'</td>'; 
			 echo  '<td>'."藥品有效期限2".'</td>';
			  echo  '<td>'."華立回收日期2".'</td>';
			   echo  '<td>'."TYS回收日期2".'</td>';
			   echo  '<td>'."回收判斷2".'</td>';
			    echo  '<td>'."抽殘液日期2".'</td>';
			  echo  '<td>'."抽殘液回收再判斷2".'</td>';
			   $objPHPExcel->getActiveSheet()->setCellValue("P".$p, iconv("big5","utf-8","用戶2"));
			$objPHPExcel->getActiveSheet()->setCellValue("Q".$p, iconv("big5","utf-8","品名2"));
			$objPHPExcel->getActiveSheet()->setCellValue("R".$p, iconv("big5","utf-8","洗淨日2"));
			$objPHPExcel->getActiveSheet()->setCellValue("S".$p, iconv("big5","utf-8","充填日2"));
			$objPHPExcel->getActiveSheet()->setCellValue("T".$p, iconv("big5","utf-8","LOTNO2"));
			$objPHPExcel->getActiveSheet()->setCellValue("U".$p, iconv("big5","utf-8","出荷日2"));
			$objPHPExcel->getActiveSheet()->setCellValue("V".$p, iconv("big5","utf-8","藥品有效期限2"));
			$objPHPExcel->getActiveSheet()->setCellValue("W".$p, iconv("big5","utf-8","華立回收日期2"));
			$objPHPExcel->getActiveSheet()->setCellValue("X".$p, iconv("big5","utf-8","TYS回收日期2"));
			$objPHPExcel->getActiveSheet()->setCellValue("Y".$p, iconv("big5","utf-8","回收判斷2"));
			$objPHPExcel->getActiveSheet()->setCellValue("Z".$p, iconv("big5","utf-8","抽殘液日期2"));
			$objPHPExcel->getActiveSheet()->setCellValue("AA".$p, iconv("big5","utf-8","抽殘液回收再判斷2"));
			   echo  '<td>'."用戶3".'</td>';
			    echo  '<td>'."品名3".'</td>';
			  echo  '<td>'."洗淨日3".'</td>';
			 echo  '<td>'."充填日3".'</td>';
			  echo  '<td>'."LOTNO3".'</td>';
			   echo  '<td>'."出荷日3".'</td>'; 
			 echo  '<td>'."藥品有效期限3".'</td>';
			  echo  '<td>'."華立回收日期3".'</td>';
			   echo  '<td>'."TYS回收日期3".'</td>';
			   echo  '<td>'."回收判斷3".'</td>';
			    echo  '<td>'."抽殘液日期3".'</td>';
				 echo  '<td>'."抽殘液回收再判斷3".'</td>';
				 $objPHPExcel->getActiveSheet()->setCellValue("AB".$p, iconv("big5","utf-8","用戶3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AC".$p, iconv("big5","utf-8","品名3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AD".$p, iconv("big5","utf-8","洗淨日3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AE".$p, iconv("big5","utf-8","充填日3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AF".$p, iconv("big5","utf-8","LOTNO3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AG".$p, iconv("big5","utf-8","出荷日3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AH".$p, iconv("big5","utf-8","藥品有效期限3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AI".$p, iconv("big5","utf-8","華立回收日期3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AJ".$p, iconv("big5","utf-8","TYS回收日期3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AK".$p, iconv("big5","utf-8","回收判斷3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AL".$p, iconv("big5","utf-8","抽殘液日期3"));
			$objPHPExcel->getActiveSheet()->setCellValue("AM".$p, iconv("big5","utf-8","抽殘液回收再判斷3"));
			  echo  '<td>'."廢棄洗桶日".'</td>';
			   echo  '<td>'."D/M廢棄出廠日".'</td>';
			  echo  '<td>'."出場作業人員".'</td>';
			   echo  '<td>'."轉用日".'</td>';
			   echo  '<td>'."D/M廢棄再確認日".'</td>';
			   echo '</tr>';
			   $objPHPExcel->getActiveSheet()->setCellValue("AN".$p, iconv("big5","utf-8","廢氣洗桶日"));
			$objPHPExcel->getActiveSheet()->setCellValue("AO".$p, iconv("big5","utf-8","D/M廢棄出廠日"));
			$objPHPExcel->getActiveSheet()->setCellValue("AP".$p, iconv("big5","utf-8","出場作業人員"));
			$objPHPExcel->getActiveSheet()->setCellValue("AQ".$p, iconv("big5","utf-8","轉用日"));
			$objPHPExcel->getActiveSheet()->setCellValue("AR".$p, iconv("big5","utf-8","D/M廢棄再確認日"));
			$p++;
			
			  
			 $rr=1;
			while($row=mssql_fetch_array($result))
			{
				   echo '<tr class="GridviewScrollItem">';
				echo  '<td>'.$rr.'</td>';
				echo  '<td>'.$row['TIME'].'</td>';
			  echo  '<td>'.$row['P_D'].'</td>';
			   echo  '<td>'.$row['DMNO'].'</td>';
			   $objPHPExcel->getActiveSheet()->setCellValue("A".$p,$row['TIME']);
			   $objPHPExcel->getActiveSheet()->setCellValue("B".$p,$row['P_D']);
			   $objPHPExcel->getActiveSheet()->setCellValue("C".$p,$row['DMNO']);
			   
			   
			  echo  '<td>'.$row['用戶1'].'</td>';
			  echo  '<td>'.$row['品名1'].'</td>';
			   echo  '<td>'.ttd($row['洗淨日1']).'</td>';
			 echo  '<td>'.ttd($row['充填日1']).'</td>';
			  echo  '<td>'.$row['LOT NO1'].'</td>';
			   echo  '<td>'.ttd($row['出荷日1']).'</td>';		   
			  echo  '<td>'.$row['藥品有效期限1'].'</td>';
			  echo  '<td>'.ttd($row['華立回收日期1']).'</td>';
			  echo  '<td>'.ttd($row['TYS回收日期1']).'</td>';      
			   echo  '<td>'.$row['回收判斷1'].'</td>';
			   echo  '<td>'.ttd($row['抽殘液日期1']).'</td>';
			   echo  '<td>'.$row['抽殘液回收再判斷1'].'</td>';
			    $objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8",$row['用戶1']));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$p,iconv("big5","utf-8",$row['品名1']));
			$objPHPExcel->getActiveSheet()->setCellValue("F".$p,ttd($row['洗淨日1']));
			$objPHPExcel->getActiveSheet()->setCellValue("G".$p,ttd($row['充填日1']));
			$objPHPExcel->getActiveSheet()->setCellValue("H".$p,$row['LOT NO1']);
			$objPHPExcel->getActiveSheet()->setCellValue("I".$p,$row['出荷日1']);
			$objPHPExcel->getActiveSheet()->setCellValue("J".$p,$row['藥品有效期限1']);
			$objPHPExcel->getActiveSheet()->setCellValue("K".$p,ttd($row['華立回收日期1']));
			$objPHPExcel->getActiveSheet()->setCellValue("L".$p,ttd($row['TYS回收日期1']));
			$objPHPExcel->getActiveSheet()->setCellValue("M".$p,$row['回收判斷1']);
			$objPHPExcel->getActiveSheet()->setCellValue("N".$p,ttd($row['抽殘液日期1']));
			$objPHPExcel->getActiveSheet()->setCellValue("O".$p,$row['抽殘液回收再判斷1']);
			  
			   echo  '<td>'.$row['用戶2'].'</td>';
			  echo  '<td>'.$row['品名2'].'</td>';
			   echo  '<td>'.ttd($row['洗淨日2']).'</td>';
			 echo  '<td>'.ttd($row['充填日2']).'</td>';
			  echo  '<td>'.$row['LOT NO2'].'</td>';
			   echo  '<td>'.ttd($row['出荷日2']).'</td>';		   
			  echo  '<td>'.$row['藥品有效期限2'].'</td>';
			  echo  '<td>'.ttd($row['華立回收日期2']).'</td>';
			  echo  '<td>'.ttd($row['TYS回收日期2']).'</td>';      
			   echo  '<td>'.$row['回收判斷2'].'</td>';
			   echo  '<td>'.ttd($row['抽殘液日期2']).'</td>';
			   echo  '<td>'.$row['抽殘液回收再判斷2'].'</td>';
			     $objPHPExcel->getActiveSheet()->setCellValue("P".$p, iconv("big5","utf-8",$row['用戶2']));
			$objPHPExcel->getActiveSheet()->setCellValue("Q".$p,iconv("big5","utf-8",$row['品名2']));
			$objPHPExcel->getActiveSheet()->setCellValue("R".$p,ttd($row['洗淨日2']));
			$objPHPExcel->getActiveSheet()->setCellValue("S".$p,ttd($row['充填日2']));
			$objPHPExcel->getActiveSheet()->setCellValue("T".$p,$row['LOT NO2']);
			$objPHPExcel->getActiveSheet()->setCellValue("U".$p,ttd($row['出荷日2']));
			$objPHPExcel->getActiveSheet()->setCellValue("V".$p,ttd($row['藥品有效期限2']));
			$objPHPExcel->getActiveSheet()->setCellValue("W".$p,$row['華立回收日期2']);
			$objPHPExcel->getActiveSheet()->setCellValue("X".$p,ttd($row['TYS回收日期2']));
			$objPHPExcel->getActiveSheet()->setCellValue("Y".$p,$row['回收判斷2']);
			$objPHPExcel->getActiveSheet()->setCellValue("Z".$p,ttd($row['抽殘液日期2']));
			$objPHPExcel->getActiveSheet()->setCellValue("AA".$p,$row['抽殘液回收再判斷2']);
			   
			    echo  '<td>'.$row['用戶3'].'</td>';
			  echo  '<td>'.$row['品名3'].'</td>';
			   echo  '<td>'.ttd($row['洗淨日3']).'</td>';
			 echo  '<td>'.ttd($row['充填日3']).'</td>';
			  echo  '<td>'.$row['LOT NO3'].'</td>';
			   echo  '<td>'.$row['出荷日3'].'</td>';		   
			  echo  '<td>'.$row['藥品有效期限3'].'</td>';
			  echo  '<td>'.ttd($row['華立回收日期3']).'</td>';
			  echo  '<td>'.ttd($row['TYS回收日期3']).'</td>';      
			   echo  '<td>'.$row['回收判斷3'].'</td>';
			   echo  '<td>'.ttd($row['抽殘液日期3']).'</td>';
			   echo  '<td>'.$row['抽殘液回收再判斷3'].'</td>';
			   
			   $objPHPExcel->getActiveSheet()->setCellValue("AB".$p, iconv("big5","utf-8",$row['用戶3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AC".$p,iconv("big5","utf-8",$row['品名3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AD".$p,ttd($row['洗淨日3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AE".$p,ttd($row['充填日3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AF".$p,$row['LOT NO3']);
			$objPHPExcel->getActiveSheet()->setCellValue("AG".$p,ttd($row['出荷日3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AH".$p,ttd($row['藥品有效期限3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AI".$p,ttd($row['華立回收日期3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AJ".$p,ttd($row['TYS回收日期3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AK".$p,$row['回收判斷3']);
			$objPHPExcel->getActiveSheet()->setCellValue("AL".$p,ttd($row['抽殘液日期3']));
			$objPHPExcel->getActiveSheet()->setCellValue("AM".$p,ttd($row['抽殘液回收再判斷3']));
			  
			   echo  '<td>'.$row['廢棄洗桶日'].'</td>';
			  echo  '<td>'.$row['D/M廢棄出廠日'].'</td>';
			  echo  '<td>'.$row['出廠作業人員'].'</td>';
			   echo  '<td>'.$row['轉用日'].'</td>';
			    echo  '<td>'.$row['D/M廢棄再確認日'].'</td>';
				echo '</tr>';
				$objPHPExcel->getActiveSheet()->setCellValue("AN".$p,$row['廢棄洗桶日']);
			$objPHPExcel->getActiveSheet()->setCellValue("AO".$p,$row['D/M廢棄出廠日']);
			$objPHPExcel->getActiveSheet()->setCellValue("AP".$p,$row['出廠作業人員']);
			$objPHPExcel->getActiveSheet()->setCellValue("AQ".$p,$row['轉用日']);
			$objPHPExcel->getActiveSheet()->setCellValue("AR".$p,$row['D/M廢棄再確認日']);
			$p++;
			$rr++;
			
			}
			$ENG=A;
			for($E=0;$E<43;$E++){
				$objPHPExcel->getActiveSheet()->getColumnDimension($ENG)->setAutoSize(true);
	 		 	 $ENG++;	   
			}
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('report.xlsx');
			
		
		
		
		}//ifpostsubmit
		  echo '</table>';
		if(isset($_POST['submit3']))		
		{			
			$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';			
		echo '<script>document.location.href="http://'.$path_root.'/recover/report.xlsx";</script>';
		}
			

		function datepick1(){
echo '
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
  <link rel="stylesheet" href="/css/jquery-ui.css">
  <link rel="stylesheet" href="/css/style.css">
  <script src="/css/jquery-1.12.4.js"></script>
  <script src="/css/jquery-ui.js"></script>
  <script src="/css/datepicker-zh-TW.js"></script>
  <script src="/css3menu/GridViewScroll/gridviewScroll.js"></script>
  <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
  <script type="text/javascript">
    $(function() {
    $( "#datepicker5" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker6" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker7" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker8" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker9" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker10" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicke11" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker12" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker13" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	$( "#datepicker14" ).datepicker({
      changeMonth: true,
      changeYear: true
    });
	
  });
  function set_date_session(nam,val){
	window.open("../backend.php?name="+nam+"&value="+val)  
  }
</script>
</head>';
}
		?>
		