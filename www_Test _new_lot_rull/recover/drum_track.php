<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
datepick();
lasturl();
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
<table border="0"><tr><td>
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
        
<BR />品名:
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
        <input type="button" name="X" id="X" value="X" onclick="window.open('./erase_prod.php ', '_self');" /> </td><td align="center">
        出荷 / 回收日期<BR>
    <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)">
<input type="button" name="X3" id="X3" value="X" onclick="window.open('./erase_time.php ', '_self');" /></td><td>
<input name="submit" type="submit" class="center button" id="submit" value="  出荷日查詢  "><BR>
<input name="submit2" type="submit" class="center button" id="submit2" value="  回收日查詢  " /> 已回收的紀錄 </td><td>
<input name="submit3" type="submit" class="center button" id="submit3" value="產生報告 ">
</td>
</form>
        
<?php 
if(isset($_POST['submit']))
{
			$_SESSION['submit']='out';
//          include("../connections/conn.php");
			$rr=1;
			 echo '<table id="GridView1" width="" border="1" align="left">
  <tr class="GridviewScrollHeader">';
  		echo '<td>count</td><td>LOT NO</td><td>LOTNO</td><td>D/M NO</td><td>客戶編號</td><td>用戶</td><td>料號</td><td>
		 品名</td><td>'."LOTNO".'</td><td>'."出荷日".'</td><td>'."出荷檢查日".'</td>';
			   echo '</tr>';	
			   
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);			
			$query="SELECT DISTINCT 
                            OUT_DECISION.OTD_NO, OUT_DECISION.OPM_ETA_DATE, OUT_DECISION.CTD_CUST_NO, 
                            OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO, OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO, 
                            OUT_CHECK_DRUM.OCM_CHK_DATE, CUSTOMER_DATA.CTD_CUST_SHORT_NAME, 
                            OUT_PRODUCT.PDD_PROD_NO, PRODUCT_DATA.PDD_PROD_NAME
FROM              OUT_CHECK_DRUM_DETAIL INNER JOIN
                            OUT_CHECK_DRUM ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_CHECK_DRUM.OTD_NO INNER JOIN
                            OUT_DECISION ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_DECISION.OTD_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            OUT_PRODUCT ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_PRODUCT.OTD_NO AND 
                            OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO = OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO ";
			if($_POST['cust']=='' and $_POST['pdd_chemical1']==''){
				$query.=" WHERE   
                (OUT_DECISION.OPM_ETA_DATE >= '".$date1."000000' AND OUT_DECISION.OPM_ETA_DATE <= '".$date2."235959') ";
			}
			if($_POST['cust']<>'' or $_POST['pdd_chemical1']<>''){   
				$query.=" WHERE   
				(OUT_DECISION.OPM_ETA_DATE >= '".$date1."000000' AND OUT_DECISION.OPM_ETA_DATE <= '".$date2."235959' and OUT_PRODUCT.PDD_PROD_NO  like '".$_POST['pdd_chemical1']."%' and OUT_DECISION.CTD_CUST_NO like '".$_POST['cust']."%' )";
			}
			$query.=" ORDER BY   OUT_DECISION.OPM_ETA_DATE, OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO ";
//			echo $query."<BR>";
			$result=mssql_query($query);
			while($row=mssql_fetch_array($result))
			{		
				if(substr(trim($row['OCD_LOT_NO']),-4,1)=='D' or substr(trim($row['OCD_LOT_NO']),-4,1)=='B' ){$lotno=substr(trim($row['OCD_LOT_NO']),0,-4);}else{$lotno=trim($row['OCD_LOT_NO']);}
	//			  echo "EAT:" .substr($row['OPM_ETA_DATE'],0,8) . "RCV:".$row['DHN_TYS_RCV_DATE1']."RCV: ".$rcv."<BR>";
					echo '<tr class="GridviewScrollItem">';
					echo  '<td>'.$rr.'</td>';
					echo  '<td>'.$row['OCD_LOT_NO'].'</td>';
					echo  '<td>'.$lotno.'</td>';
			   		echo  '<td>'.$row['OCD_DRUM_NO'].'</td>';			   
			   		echo  '<td>'.$row['CTD_CUST_NO'].'</td>';
			  		echo  '<td>'.$row['CTD_CUST_SHORT_NAME'].'</td>'; 
			  		echo  '<td>'.$row['PDD_PROD_NO'].'</td>';
			  		echo  '<td>'.$row['PDD_PROD_NAME'].'</td>';
			 			echo  '<td>'.$lotno.'</td>';
			   		echo  '<td>'.std($row['OPM_ETA_DATE']).'</td>';		   
			  		echo  '<td>'.std($row['OCM_CHK_DATE']).'</td>';
					echo '</tr>';					
				$rr++;	
		}//ifpostsubmit
		 echo '</table>'; 		
}

if(isset($_POST['submit2']))
{
			$_SESSION['submit']='return';
//          include("../connections/conn.php");
			$rr=1;
			 echo '<table id="GridView1" width="" border="1" align="left">
  <tr class="GridviewScrollHeader">';
  		echo '<td>count</td><td>LOT NO</td><td>LOTNO</td><td>D/M NO</td><td>客戶編號</td><td>用戶</td><td>料號</td><td>
		 品名</td><td>'."出荷日".'</td><td>'."出荷檢查日".'</td>';
			   echo  '<td>'."TYS回收日期".'</td>';
			  echo  '<td>已回收</td>';
			   echo  '<td>'."D/M廢棄出廠日".'</td>';
			   echo  '<td>'."D/M廢棄再確認日".'</td>';
			   echo '</tr>';	
			   
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);			
			$query="SELECT DISTINCT DHN_DRUM_NO 
FROM              DRUM_HISTORY_NORMAL ";
			if($_POST['cust']=='' and $_POST['pdd_chemical1']==''){
				$query.=" WHERE   
				(DHN_TYS_RCV_DATE1 >= '".$date1."' AND DHN_TYS_RCV_DATE1 <= '".$date2."') ";
			}
			if($_POST['cust']<>'' or $_POST['pdd_chemical1']<>''){   
				$query.=" WHERE   
				(DHN_TYS_RCV_DATE1 >= '".$date1."' AND DHN_TYS_RCV_DATE1 <= '".$date2."' and PDD_PROD_NO1 like '".$_POST['pdd_chemical1']."%' and CTD_CUST_NO1 like '".$_POST['cust']."%' )";
			}
		 	$query.=" ORDER BY DHN_DRUM_NO";

			$result=mssql_query($query);
			
//			echo "numrow:".$numrowss=mssql_num_rows($result);
//			echo "SS";
			while($row=mssql_fetch_array($result))
			{						
				$aa=rcv_drum($row['DHN_DRUM_NO']);
					$lot_no=$aa[3];
					$cust_no=$aa[1];
					$prod_no=$aa[2];
					$tys_rcv_date=$aa[5];
					$outdate=substr($aa[4],0,8);	
					$ret='Y';
				

				if($aa[6]<>''){$discard_date=$aa[6];}

				if(substr(trim($lot_no),-4,1)=='D' or substr(trim($lot_no),-4,1)=='B' ){$lotno=substr(trim($lot_no),0,-4);}else{$lotno=trim($lot_no);}
					if($dm->aa[$i][5]-substr(trim($row['OPM_ETA_DATE']),0,8)>0){$rcv='Y';}else{$rcv='N';}
					echo '<tr class="GridviewScrollItem">';
					echo  '<td>'.$rr.'</td>';
					echo  '<td>'.$lot_no.'</td>';
					echo  '<td>'.$lotno.'</td>';
			   		echo  '<td>'.$row['DHN_DRUM_NO'].'</td>';			   
			   		echo  '<td>'.$cust_no.'</td>';
			  		echo  '<td>'.get_cust_name($cust_no).'</td>'; 
			  		echo  '<td>'.$prod_no.'</td>';
			  		echo  '<td>'.get_prod_name($prod_no).'</td>';
			   		echo  '<td>'.std($outdate).'</td>';		   
			  		echo  '<td>'.'</td>';
			  		echo  '<td>'.std($tys_rcv_date).'</td>';      
			   		echo  '<td>'.$ret.'</td>';      
			  		echo  '<td>'.std($discard_date).'</td>';      
			    	echo  '<td>'.std($discard_date).'</td>';
					echo '</tr>';					
				$rr++;								
		//	}
		}//ifpostsubmit
		 echo '</table>'; 		
}

function rcv_drum($drumno){
	$query="SELECT          TOP (1) DHN_DRUM_NO, CTD_CUST_NO1, PDD_PROD_NO1, DHN_LOT_NO1, DHN_OUT_DATE1, 
                            DHN_TYS_RCV_DATE1, DHN_DISCARD_DATE
FROM              DRUM_HISTORY_NORMAL
WHERE          (DHN_DRUM_NO = '".$drumno."')
ORDER BY   [index] DESC ";

	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $aa=array($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6]);
}

if(isset($_POST['submit3']))		
		{	
				include_once("../PHPEXCEL/Classes/PHPExcel.php");
				include_once("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
				$objPHPExcel = PHPExcel_IOFactory::load("drumlist.xlsx");
				$objPHPExcel->setActiveSheetIndex(0);
				$p=2;
				$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","NO"));
				$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","LOT NO"));
				$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","LOTNO"));
				$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","桶號"));
				$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","客戶編號"));
				$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","客戶名稱"));
				$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","料號"));
				$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","品名"));
				$objPHPExcel->getActiveSheet()->setCellValue("I".$p, iconv("big5","utf-8","出荷日"));
				$objPHPExcel->getActiveSheet()->setCellValue("J".$p, iconv("big5","utf-8","出荷檢查日"));

				$p++;
		if($_SESSION['submit']=='out'){	
			$objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","drum追蹤一覽表(出荷日期查詢)"));
			$rr=1;
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);			
			$query="SELECT DISTINCT 
                            OUT_DECISION.OTD_NO, OUT_DECISION.OPM_ETA_DATE, OUT_DECISION.CTD_CUST_NO, 
                            OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO, OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO, 
                            OUT_CHECK_DRUM.OCM_CHK_DATE, CUSTOMER_DATA.CTD_CUST_SHORT_NAME, 
                            OUT_PRODUCT.PDD_PROD_NO, PRODUCT_DATA.PDD_PROD_NAME
FROM              OUT_CHECK_DRUM_DETAIL INNER JOIN
                            OUT_CHECK_DRUM ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_CHECK_DRUM.OTD_NO INNER JOIN
                            OUT_DECISION ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_DECISION.OTD_NO INNER JOIN
                            CUSTOMER_DATA ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
                            OUT_PRODUCT ON OUT_CHECK_DRUM_DETAIL.OTD_NO = OUT_PRODUCT.OTD_NO AND 
                            OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO = OUT_PRODUCT.OPD_LOT_NO INNER JOIN
                            PRODUCT_DATA ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO ";
			if($_POST['cust']=='' and $_POST['pdd_chemical1']==''){
				$query.=" WHERE   
                (OUT_DECISION.OPM_ETA_DATE >= '".$date1."000000' AND OUT_DECISION.OPM_ETA_DATE <= '".$date2."235959') ";
			}
			if($_POST['cust']<>'' or $_POST['pdd_chemical1']<>''){   
				$query.=" WHERE   
				(OUT_DECISION.OPM_ETA_DATE >= '".$date1."000000' AND OUT_DECISION.OPM_ETA_DATE <= '".$date2."235959' and OUT_PRODUCT.PDD_PROD_NO  like '".$_POST['pdd_chemical1']."%' and OUT_DECISION.CTD_CUST_NO like '".$_POST['cust']."%' )";
			}
			$query.=" ORDER BY   OUT_DECISION.OPM_ETA_DATE,OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO";
//			echo $query."<BR>";
			$result=mssql_query($query);	
			while($row1=mssql_fetch_array($result))
			{			
				if(substr(trim($row1['OCD_LOT_NO']),-4,1)=='D' or substr(trim($row1['OCD_LOT_NO']),-4,1)=='B' ){$lotno=substr(trim($row1['OCD_LOT_NO']),0,-4);}else{$lotno=trim($row1['OCD_LOT_NO']);}

			//	echo $row['prodno']."<BR>";
				$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rr);
				$objPHPExcel->getActiveSheet()->setCellValue("B".$p,trim($row1['OCD_LOT_NO']));
				$objPHPExcel->getActiveSheet()->setCellValue("C".$p,$lotno);		
				$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$row1['OCD_DRUM_NO']);		
				$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8",$row1['CTD_CUST_NO']));
				$objPHPExcel->getActiveSheet()->setCellValue("F".$p,iconv("big5","utf-8",$row1['CTD_CUST_SHORT_NAME']));
				$objPHPExcel->getActiveSheet()->setCellValue("G".$p,$row1['PDD_PROD_NO']);
				$objPHPExcel->getActiveSheet()->setCellValue("H".$p,iconv("big5","utf-8",$row1['PDD_PROD_NAME']));
				$objPHPExcel->getActiveSheet()->setCellValue("I".$p,std($row1['OPM_ETA_DATE']));
				$objPHPExcel->getActiveSheet()->setCellValue("J".$p,std($row1['OCM_CHK_DATE']));
				$p++;
				$rr++;
			
			}
		}
		
		if($_SESSION['submit']=='return')
		{				
			$objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","drum追蹤一覽表(日期查詢)"));  
			$date1=dod($_SESSION['datepicker1']);
			$date2=dod($_SESSION['datepicker2']);			
			$query="SELECT DISTINCT DHN_DRUM_NO 
FROM              DRUM_HISTORY_NORMAL ";
			if($_POST['cust']=='' and $_POST['pdd_chemical1']==''){
				$query.=" WHERE   
				(DHN_TYS_RCV_DATE1 >= '".$date1."' AND DHN_TYS_RCV_DATE1 <= '".$date2."') ";
			}
			if($_POST['cust']<>'' or $_POST['pdd_chemical1']<>''){   
				$query.=" WHERE   
				(DHN_TYS_RCV_DATE1 >= '".$date1."' AND DHN_TYS_RCV_DATE1 <= '".$date2."' and PDD_PROD_NO1 like '".$_POST['pdd_chemical1']."%' and CTD_CUST_NO1 like '".$_POST['cust']."%' )";
			}
		 	$query.=" ORDER BY DHN_DRUM_NO";

			$result=mssql_query($query);
			
			$rr=1;
			while($row=mssql_fetch_array($result))
			{						
				$aa=rcv_drum($row['DHN_DRUM_NO']);
					$lot_no=$aa[3];
					$cust_no=$aa[1];
					$prod_no=$aa[2];
					$tys_rcv_date=$aa[5];
					$outdate=substr($aa[4],0,8);	
					if($tys_rcv_date-$outdate>>0){$ret='Y';}
					else{$ret='N';}
				

				if($aa[6]<>''){$discard_date=$aa[6];}
				if(substr(trim($lot_no),-4,1)=='D' or substr(trim($lot_no),-4,1)=='B' ){$lotno=substr(trim($lot_no),0,-4);}else{$lotno=trim($lot_no);}
				$rcv='Y';
				$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rr);
				$objPHPExcel->getActiveSheet()->setCellValue("B".$p,$lot_no);
				$objPHPExcel->getActiveSheet()->setCellValue("C".$p,$lotno);
				$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$row['DHN_DRUM_NO']);		
				$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8",$cust_no));
				$objPHPExcel->getActiveSheet()->setCellValue("F".$p,iconv("big5","utf-8",get_cust_name($cust_no)));
				$objPHPExcel->getActiveSheet()->setCellValue("G".$p,$prod_no);
				$objPHPExcel->getActiveSheet()->setCellValue("H".$p,iconv("big5","utf-8",get_prod_name($prod_no)));
				$objPHPExcel->getActiveSheet()->setCellValue("I".$p,std($outdate));
				$objPHPExcel->getActiveSheet()->setCellValue("J".$p,std(substr($dm->aa[$i][4],0,8)));
				$objPHPExcel->getActiveSheet()->setCellValue("K".$p,std($tys_rcv_date));
				$objPHPExcel->getActiveSheet()->setCellValue("L".$p,$ret);
				$objPHPExcel->getActiveSheet()->setCellValue("M".$p,std($discard_date));
				$objPHPExcel->getActiveSheet()->setCellValue("N".$p,std($discard_date));
				$p++;
				$rr++;								
			}
		}
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('report.xlsx');  
			$path_root=$_SERVER['HTTP_HOST'];		
			echo '<script>document.location.href="http://'.$path_root.'/recover/report.xlsx";</script>';
		}	
		
class drum_no{
	public $otd_no,$drum_no,$numrows,$aa,$lotno,$wash_date,$fill_date,$outdate,$return_date,$discard_date   ;	
	function list_drun(){		
		$i=0;
		$query="SELECT	distinct	OUT_CHECK_DRUM_DETAIL.OCD_LOT_NO, OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO, 
                            DRUM_HISTORY_NORMAL.DHN_WASHED_DATE1, DRUM_HISTORY_NORMAL.DHN_FILLED_DATE1, 
                            DRUM_HISTORY_NORMAL.DHN_OUT_DATE1, DRUM_HISTORY_NORMAL.DHN_TYS_RCV_DATE1, 
                            DRUM_HISTORY_NORMAL.DHN_DISCARD_DATE, DRUM_HISTORY_NORMAL.DHN_CF_DISCARD_DATE, 
                            DRUM_HISTORY_NORMAL.DHN_DISCARD_W_DATE, DRUM_HISTORY_NORMAL.DHN_DISCARD_MAN
FROM              OUT_CHECK_DRUM_DETAIL INNER JOIN 
                            DRUM_HISTORY_NORMAL ON 
                            OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO = DRUM_HISTORY_NORMAL.DHN_DRUM_NO 
 WHERE DRUM_HISTORY_NORMAL.disable <> 1 and (OTD_NO = '".$this->otd_no."') AND (OCD_LOT_NO = '".$this->lotno."') 
                            order by OUT_CHECK_DRUM_DETAIL.OCD_DRUM_NO "; 
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
		$this->numrows=mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			if($row['DHN_TYS_RCV_DATE1']=='' and $row['DHN_DISCARD_DATE']<>''){$row['DHN_TYS_RCV_DATE1']=$row['DHN_DISCARD_DATE'];}
			$this->aa[$i][0]=$row['OCD_LOT_NO'];
			$this->aa[$i][1]=$row['OCD_DRUM_NO'];
			$this->aa[$i][2]=$row['DHN_WASHED_DATE1'];
			$this->aa[$i][3]=$row['DHN_FILLED_DATE1'];
			$this->aa[$i][4]=$row['DHN_OUT_DATE1'];
			$this->aa[$i][5]=$row['DHN_TYS_RCV_DATE1'];
			$this->aa[$i][6]=$row['DHN_DISCARD_DATE'];
			$this->aa[$i][7]=$row['DHN_CF_DISCARD_DATE'];
			$this->aa[$i][8]=$row['DHN_DISCARD_W_DATE'];
			$i++;
		}
		
	}	
}
?>
		