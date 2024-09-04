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
?>
<form name="form1" method="post" action="">
COA製作進度查詢表&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
系統時間:<?PHP echo date("Y/m/d h:i:sa")?><br /><br />

查詢時間:<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>




        品名：<span class="d1">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php		echo $_SESSION['prod_no'];	?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('./all/pdd_prod1.php ', '_self');" >
        <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php		echo $_SESSION['prod_name'];?>" readonly>        <input type="button" name="X" id="X" value="X" onClick="window.open('./all/erase_prod.php ', '_self');">

        <br>

         客戶：<span class="d1">
       
        <input name="pdd_cust1" type="text" id="pdd_cust1" size="10" value="<?php		echo $_SESSION['cust_no'];	?>" readonly>
        <input type="button" name="pdd_cust" id="pdd_cust" value="查詢客戶" onClick="window.open('./all/cust_no.php ', '_self');" >
        <input name="pdd_cust2" type="text" id="pdd_cust2" size="16" value="<?php		echo $_SESSION['cust_name'];?>" readonly> 
        <input type="button" name="X1" id="X1" value="X" onClick="window.open('./all/erase_customer.php ', '_self');">
        
        


        LOT NO:
        <input name="LOT" type="text" id="LOT" size="16" value="<?PHP echo $_SESSION['LOT'] ?>" onChange="set_date_session(this.name,this.value)">
         <input type="submit" name="search" id="search" value="搜尋"></form>
       <form name="form2" method="post" action="coa_run.php">

         <input type="submit" name="print_mctw" id="print_mctw" value="列印MCTW" formtarget="_new">
     
          



         <table width="1300" border="1">
  <tr bgcolor="#CCCCCC">
  <td width="30">選擇</td>  
    <td width="80">出貨日期</td>
    <td width="140">客戶</td>
    <td width="120">品名</td>
    <td width="120">OTN_NO</td>
    <td width="100">LotNO</td>
    <td width="100">SN</td>
    <td width="60">數量/單位</td>  
    <td width="670"><p>分析項目:<font color="#0000FF">藍字</font>表示已有報告及結果，<font color="#FF0000">紅字</font>表示沒有報告及結果，<font color="green">綠字</font>表示有報告但是無檢驗結果，</p>
    <p><font color="purple">紫色</font>表示沒有報告但是有檢驗結果‧<span style="background-color: red"><font color="white">紅底白字</font></span>表示檢驗未通過‧</p></td>

  </tr>
        
        
<?PHP 
$loginFormAction = $_SERVER['PHP_SELF'];
//OPF.OAF_ORDER_CUST_NAME,OPF.OAF_PROD_NAME,OPF.OAF_PACKAGE_DESC,inner join OUT_PLAN_FROM_FILE as OPF on OD.OPM_ORDER_NO=OPF.OPM_ORDER_NO

if(isset($_POST['search']))
{
$query="select * ,OD.CTD_CUST_NO as cus,OP.PDD_PROD_NO as PORDNO,OP.OPD_QTY_LITER as LITTER,OP.OPD_QTY_KG as KG,OP.OPD_ACC_UNIT as UNIT
	,OD.OPM_ORDER_NO as ORDERNO,PD.PDD_TYPE as TYPE1,OD.OPM_ETA_DATE as DATE1
	from OUT_DECISION as OD 
	left join OUT_PRODUCT as OP on OD.OTD_NO=OP.OTD_NO
	left join AnalyzeDesign as AD on AD.AND_LOT_NO=OP.OPD_LOT_NO
	left join OUT_PLAN_FROM_FILE as OPF on OP.OAF_ACC_ID=OPF.OAF_ACC_ID
	left JOIN PRODUCT_DATA  as PD ON AD.AND_GOODS = PD.PDD_PROD_NO 
	left JOIN EMPLOYEE_DATA as ED ON AD.AND_PERSON = ED.EMP_NO
	where OD.OTD_NO!='' ";
	if($_SESSION['datepicker1']<>'')
	{
		$query.=" and OD.OPM_ETA_DATE>'".dod($_SESSION['datepicker1'])."000000' and OD.OPM_ETA_DATE<'".dod($_SESSION['datepicker2'])."999999' ";
	}
	if($_SESSION['prod_no']<>'')
	{
		$query.=" and OP.PDD_PROD_NO='".$_SESSION['prod_no']."' ";
	}
	if($_SESSION['cust_no']<>'')
	{
		$query.=" and OD.CTD_CUST_NO='".$_SESSION['cust_no']."' ";
	}
	if($_SESSION['LOT']<>'')
	{
$query="select * ,OD.CTD_CUST_NO as cus,OP.PDD_PROD_NO as PORDNO,OP.OPD_QTY_LITER as LITTER,OP.OPD_QTY_KG as KG,OP.OPD_ACC_UNIT as UNIT
	,OD.OPM_ORDER_NO as ORDERNO,PD.PDD_TYPE as TYPE1,OD.OPM_ETA_DATE as DATE1
	from OUT_DECISION as OD 
	left join OUT_PRODUCT as OP on OD.OTD_NO=OP.OTD_NO
	left join AnalyzeDesign as AD on AD.AND_LOT_NO=OP.OPD_LOT_NO
	left join OUT_PLAN_FROM_FILE as OPF on OP.OAF_ACC_ID=OPF.OAF_ACC_ID
	left JOIN PRODUCT_DATA  as PD ON AD.AND_GOODS = PD.PDD_PROD_NO 
	left JOIN EMPLOYEE_DATA as ED ON AD.AND_PERSON = ED.EMP_NO
	where AD.AND_LOT_NO like '%".$_POST['LOT']."%'";
	}
	/*PD.PDD_TYPE,OD.OTD_NO,OD.OPM_ORDER_NO,OD.CTD_CUST_NO, OD.OPM_ORDER_NO,OD.OPM_ETA_DATE,
				AD.AND_LOT_NO,AD.AND_OUT_DATETIME,AD.AND_ITEM,
				OP.PDD_PROD_NO,OP.OPD_QTY_DRUM,OP.OPD_QTY_LITER,OP.OPD_ACC_UNIT,OP.OPD_QTY_DRUM
				,OPF.OAF_ORDER_CUST_NAME,OPF.OAF_PROD_NAME,OPF.OAF_PACKAGE_DESC,OPF.OAF_PACKAGE,OPF.OAF_ACC_ID
				,ED.EMP_NAME,PD.PDD_CHEMICAL,OP.OPD_QTY_KG*/
/*OD.OTD_NO,OD.OPM_ORDER_NO,OD.CTD_CUST_NO, OD.OPM_ORDER_NO,OD.OPM_ETA_DATE,
				AD.AND_LOT_NO,AD.AND_OUT_DATETIME,AD.AND_ITEM,
				OP.PDD_PROD_NO,OP.OPD_QTY_DRUM,OP.OPD_QTY_LITER,OP.OPD_ACC_UNIT,OP.OPD_QTY_DRUM
				,OPF.OAF_ORDER_CUST_NAME,OPF.OAF_PROD_NAME,OPF.OAF_PACKAGE_DESC,OPF.OAF_PACKAGE,OPF.OAF_ACC_ID
				,ED.EMP_NAME,PD.PDD_CHEMICAL,OP.OPD_QTY_KG*/
$query.= "order by OD.OPM_ORDER_NO";
 
//echo "<BR>".$query."<BR>";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$_SESSION['NB']=$numRows;
$i=0;
while($row = mssql_fetch_array($result))
{   

	echo '<tr>';
	
	echo '<td>'
	.'<input type ="checkbox" name ="chk[]" value ="'.$row['ORDERNO'].",".$row['OPD_LOT_NO'].",".$row['DATE1'].",".$row['cus'].",".$row['TYPE1'].",".$row['OPD_SERIAL_NO'].'" >'.'</td>';
	/*echo '<td>'.'<a target="_blank" href=index.php?url=print_from&chk='.$row['ORDERNO'].",".$row['OPD_LOT_NO'].",".$row['DATE1'].",".$row['cus'].",".$row['TYPE1'].'>'."列印".'</td>';*/
	$i++;
    echo '<td>'.ttd($row['OPM_ETA_DATE']).'</td>';
	
	echo '<td>'.get_cust_name($row['cus']).'</td>';
    echo '<td>'.get_prod_name($row['PORDNO']).'</td>';    
    echo '<td>'.($row['OTN_NO']).'</td>';    
    echo '<td><a target="_blank" href=../COA/index.php?url=coa_printout&lid='.$row['OPD_LOT_NO'].'&cid='.$row['cus'].'&pi>'.$row['OPD_LOT_NO'].'</a></td>';
    echo '<td>'.($row['OPD_SERIAL_NO']).'</td>'; 
	if(trim($row['UNIT'])=='L')
	{
		echo '<td>'.$row['LITTER'].$row['UNIT'].'</td>';
	}
	elseif(trim($row['UNIT'])=='KG')
	{
		echo '<td>'.$row['KG'].$row['UNIT'].'</td>';
	}// echo '<td>'.$rpd.'</td>';
	else{echo  '<td>'. '</td>';}
	if(substr($row['AND_ITEM'],-1)==','){$row['AND_ITEM']=substr($row['AND_ITEM'],0,-1);}
	analyzereport($row['AND_ITEM'],trim($row['OPD_LOT_NO']),$row['EMP_NAME'],$row['PDD_CHEMICAL'],$row['PORDNO'],$row['AND_REPORT_DATETIME']);
	
	echo '</tr>';
}
}

if(isset($_POST['print'])){$str='./all/TYS.docx';echo "TYS";}
if(isset($_POST['print_mctw'])){$str='./all/MCTW_COA.docx';echo "MCTW";}

if(isset($_POST['print']) or isset($_POST['print_mctw']))
{
	require_once ('../PHPWord/PHPWord.php');
	$PHPWord = new PHPWord();
	$document = $PHPWord->loadTemplate($str);
	$num=0;
	$i=0;
//	if(trim($_SESSION['cust_no'])=='')
//	{echo "請選擇客戶!";}
//	if($_SESSION['cust_no']<>'')
	//echo $_SESSION['NB'];
		for($T=0;$T<=$_SESSION['NB'];$T++)
		{	
			if(trim($_POST['chk'.$i.''])!='')
			{	
				//echo $T;
				//echo $_POST['chk4'].'<br>';
				//$_SESSION['chkkk']=$_POST['chk'.$i.''];
				$chose[$i]=explode(",",$_POST['chk'.$i.'']);	
				//print_r($chose[$i]);
				$document->setValue("CUST",get_cust_name($chose[$i][3]));
				$date3[$i]=dat1($chose[$i][2]);
				//echo $date3.$T;
				$document->setValue("DAT",$date3[$i]);
				$prints="select OP.OPD_QTY_DRUM,PD.PDD_STYLE,PD.PDD_PROD_NAME,PD.PDD_TYPE,OP.OPD_LOT_NO,OP.OAF_PACKAGE
		,OP.OPD_QTY_KG,OP.OPD_ACC_UNIT
		from OUT_PRODUCT as OP 
		inner join PRODUCT_DATA as PD on OP.PDD_PROD_NO=PD.PDD_PROD_NO
		
		inner join OUT_DECISION as OD on OD.OTD_NO=OP.OTD_NO 

		where OP.OPM_ORDER_NO='".$chose[$i][0]."'   and  OD.OPM_ETA_DATE='".$chose[$i][2]."' and  CTD_CUST_NO='".$chose[$i][3]."' and 
		OPD_LOT_NO='".$chose[$i][1]."'";
		$result = mssql_query($prints);
	while($row = mssql_fetch_array($result))
	{	
		if(trim($row['PDD_TYPE'])!="LY")
		{
		$document->setValue("NAME".$num,$row['PDD_PROD_NAME']);
		$document->setValue("STYLE".$num,$row['PDD_STYLE']);
		$document->setValue("QTY".$num,$row['PDD_STYLE']."*".$row['OPD_QTY_DRUM']);
		if(substr($row['OPD_LOT_NO'],0,1)=="T")
		{
			$LOTNO=substr($row['OPD_LOT_NO'],0,7);
		}
		else
		{
			$LOTNO=$row['OPD_LOT_NO'];
		}
		$document->setValue("LOT".$num,$LOTNO);
		$num++;
		}
		if(trim($row['PDD_TYPE'])=="LY")
		{
		$document->setValue("NAME".$num,$row['PDD_PROD_NAME']);
		$document->setValue("STYLE".$num,$row['OAF_PACKAGE']);
		$document->setValue("QTY".$num,floor($row['OPD_QTY_KG'])." ".$row['OPD_ACC_UNIT']);
		if(substr($row['OPD_LOT_NO'],0,1)=="T")
		{
			$LOTNO=substr($row['OPD_LOT_NO'],0,7);
		}
		else
		{
			$LOTNO=$row['OPD_LOT_NO'];
		}
		$document->setValue("LOT".$num,$LOTNO);
		$num++;
		}
	}
	
	
			}//if
			$i++;
		}///for
		
	for($i=0;$i<10;$i++)
	{
		$document->setValue("NAME".$num," ");
		$document->setValue("STYLE".$num," ");
		$document->setValue("QTY".$num," ");
		$document->setValue("LOT".$num," ");
		
		$num++;
	}
	$document->save('./all/TYS1.docx');
			
$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';
			echo "請儲存後列印";
		echo '<script>document.location.href="http://'.$path_root.'/cal/all/TYS1.docx";</script>';	
}

function dat1($ds){   //day-style  201509020102=>09/02/2015
		$dat=substr($ds,0,+4)."/".substr($ds,4,+2)."/".substr($ds,6,+2);
	return $dat;
}
?>