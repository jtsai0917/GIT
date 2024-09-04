<?php
session_start();
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	auth('2-11',$_SESSION['aut']);
	lasturl();
	datepick();
?>

<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
<table width="1240" border="1"><tr><td>訂單單號:<input name="order_no" type="text" id="orderno" size="15" value="<?php echo $_SESSION['order_no'];?>"  onchange="set_date_session(this.name,this.value)"/>
  預定交期：
      		<input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php echo $_SESSION['datepicker1'] ; ?>"  onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php echo $_SESSION['datepicker2'] ; ?>"  onchange="set_date_session(this.name,this.value)"></td></tr>
<tr>
  <td><label for="dec_no1"></label>
      <span class="d1">
        交貨客戶：
        <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
        <input name="cid" type="text" id="cid" size="16" value="<?php echo $_SESSION['cust_no'];?>" readonly="readonly" />
        <input type="button" name="pdd_no" id="pdd_no" value="查詢" onclick="window.open('../main.php?url=cust_no&amp;sup=N ', '_self');" />
        <input name="pdd_chemical" type="text" id="pdd_chemical2" size="20" value="<?php echo $_SESSION['cust_name'];?>" />
		<input name="search" type="submit" class="ui-button-text-icons" id="search" value="	查	詢	  "  />
        <input name="input" type="submit" class="ui-button-text-icons" id="input" value="	匯  入	  "  />
      </span></td></tr></table>
<?php
	$loginFormAction = $_SERVER['PHP_SELF'];
	if($_POST['select_all'])
	{
		if($_SESSION['select_all']==''){$_SESSION['select_all']='checked="checked"';}
		elseif($_SESSION['select_all']=='checked="checked"'){$_SESSION['select_all']='';}
	}	
	if(count($_POST)>0){ foreach($_POST as $k=>$v);}
	if(isset($_POST['search']) or isset($_POST['select_all']) or isset($_POST['input']))
	{
		include_once("../connections/ax_connections.php");
		$i=1;
		$_SESSION['cust_no']=$_POST['cid'];
		$_SESSION['dec_no1']=$_POST['dec_no1'];
		$_SESSION['dec_no2']=$_POST['dec_no2'];
		$_SESSION['order_no']=$_POST['order_no'];
		$_SESSION['datepicker1']=$_POST['datepicker1'];
		$_SESSION['datepicker2']=$_POST['datepicker2'];
		$query="SELECT  COPCONFIRM.*, CONVERT(varchar(100), TYSORDERDATE, 101) as odate,CONVERT(varchar(100), RECEIPTDATEREQUESTED, 101) as rdate FROM COPCONFIRM where SALESID<>'' ";
		if($_POST['order_no']<>''){ $query.="and SALESID='".$_POST['order_no']."' ";}
		else{
			if($_POST['datepicker1']<>''){$query.="and RECEIPTDATEREQUESTED >='".ddo($_POST['datepicker1'])."'";}
			if($_POST['datepicker2']<>''){$query.="and RECEIPTDATEREQUESTED <='".ddo($_POST['datepicker2'])."'";}
			if(trim($_POST['cid'])<>''){$query.=" and CUSTACCOUNT='".$_POST['cid']."'";}
		}
//		echo $query;
	echo '<table width="1240" border="1"><tr align="center"><td width="10">Items</td><td width="30"><input type="submit" name="select_all" value="√"></td><td>訂貨日</td><td>決定書號碼</td><td>決定書序號</td><td>訂單號碼</td><td>送貨客戶. </td><td>銷帳客戶. </td><td>客戶訂單號碼</td><td>料號</td><td>預交量</td><td>單位</td><td>預交日</td><td>摘要</td><td>台南倉暫出</td><td>備註</td><td>Lot_No</td></tr>';
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{
		$aa[$i][1]=$row['odate'];
		$aa[$i][2]=$row['SALESID'];
		$aa[$i][3]=$row['CUSTACCOUNT'];
		$aa[$i][4]=$row['INVOICEACCOUNT'];
		$aa[$i][5]=$row['TYSCUSPO'];
		$aa[$i][6]=$row['ITEMID'];
		$aa[$i][7]=($row['SALESQTY']);
		$aa[$i][8]=$row['SALESUNIT'];
		$aa[$i][9]=$row['rdate'];
		$aa[$i][10]=$row['TYSMEMO'];
		$aa[$i][11]=$row['INVENTLOCATIONID'];
		$aa[$i][12]=$row['TYSNOTE'];
		$aa[$i][13]=$row['LOTNO'];
		$aa[$i][14]=$row['CREATEDBY'];
		$aa[$i][15]=$row['CREATEDDATETIME'];
		$aa[$i][16]=$row['COPCONFIRMNO'];
		$aa[$i][17]=$row['DATAAREAID'];
		$aa[$i][18]=$row['ITEMID'];
		$aa[$i][19]=$row['COPCONFIRMSERIES'];
		$aa[$i][20]=$row['COPCONFIRMSERIES'];
		$i++;
	}
	include("../connections/conn.php");
	
		for($j=1;$j<$i;$j++){			
			$pdd=new get_pdd_literkg;
			$pdd->pid=$aa[$j][6];
			$pdd->liter_kg();
			$pdtype=$pdd->class;
			if(trim($aa[$j][11])==11){$location='台南倉';$location_name="TAINAN";}
			if(trim($aa[$j][11])==1){$location='商品倉';$location_name="TYS";}
			if(trim($aa[$j][11])==5){$location='成品倉';$location_name="TYS";}
			if(substr(trim($aa[$j][6]),-3)<>'000' and substr(trim($aa[$j][6]),-3,1)<>'0' and $pdtype=='成品'){

				$lot=trim($aa[$j][13])."D".substr(trim($aa[$j][6]),-3);
			}
			elseif(substr(trim($aa[$j][6]),-3)<>'000' and substr(trim($aa[$j][6]),-3,1)=='0' and $pdtype=='成品'){
				$lot=trim($aa[$j][13])."B".substr(trim($aa[$j][6]),-3);

			}
			else{
				$lot=trim($aa[$j][13]);

			}
			echo '<tr align="left"><td align="center">'.$j.'</td><td align="center"><input type="checkbox" name="chkbox[]" value="'.$j.'" '.$_SESSION['select_all'].'/></td><td>'.$aa[$j][1].'</td><td>'.$aa[$j][16].'</td><td>'.$aa[$j][19].'</td><td align="center">'.$aa[$j][2].'</td><td>'.$aa[$j][3]."<BR>".get_cust_sname($aa[$j][3]).'</td><td>'.$aa[$j][4]."<BR>".get_cust_sname($aa[$j][4]).'</td><td>'.$aa[$j][5].'</td><td>'.$aa[$j][6].'</td><td>'.round($aa[$j][7],0).'</td><td>'.$aa[$j][8].'</td><td>'.$aa[$j][9].'</td><td>'.$aa[$j][10].'</td><td>'.$location.'</td><td>'.$aa[$j][12].'</td><td>'.$lot.'</td></tr>';
		}
		echo '</form></table></br>';
	}
	
	if(($k=='otd_no') and ($v<>'')){
		echo '<script>document.location.href="./index.php?url=out_decision_2&order_no='.$v.'";</script>';
	}	
	
	if($_POST['print'])
	{
		include_once("../lib/print.php");
		$prt=new print_outdecision;
		$prt->aa=$_POST['chkbox'];
		echo "TOTAL ：".count($prt->aa)." 筆</br>";
		$prt->print_out_decision();
	}
	
function prod($pono){
	$query="SELECT distinct PDD_PROD_NO FROM OUT_PRODUCT WHERE (OPM_ORDER_NO = '".$pono."')";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result)){
		$rtn=$rtn.$row['PDD_PROD_NO']."<br>";	
	}
	return $rtn;
}

if(isset($_POST['input'])){
	include("../connections/conn.php");
	$cc=$_POST['chkbox'];
	$n=count($cc);
	$aa[$i][16];
	$o=0;
	for($x=0;$x<$n;$x++)
	{
		if(trim($aa[$cc[$x]][13])<>'')
		{	
			if(trim($aa[$cc[$x]][11])==11){$tn='Y';$tc='N';$location_name="TAINAN";}
			else{$tn='N';$tc='N';$location_name="TYS";}
			$nn=substr($aa[$cc[$x]][19],-2,2);
			$query=" select max(SAG_NO) as sagmax from SIGN_AGREE";	
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			$sagmax=$row[0]+1;
			$query="INSERT INTO OUT_DECISION 
					   (OTD_NO, OPM_PO_NO, OPM_ORDER_NO, CTD_CUST_NO, OTD_CREATE_DATE, OPM_ETA_DATE, 
					   OPM_STOCK, OPM_TAICHUNG_CACHE, OPM_TAINAN_CACHE, SAG_NO)
					VALUES  ('".$aa[$cc[$x]][16]."','".$aa[$cc[$x]][5]."','".$aa[$cc[$x]][2]."','".$aa[$cc[$x]][3]."','".date("YmdHis")."','".dod($aa[$cc[$x]][9])."160000',
					'".$location_name."','".$tc."','".$tn."',".$sagmax.")";	
			echo  $query."<BR><BR><BR>";
			$acc=substr($aa[$cc[$x]][19],-2,2);
			$result=mssql_query($query);
			
			$query="select count(*) as tn from OUT_PLAN where OPM_ORDER_NO=".$aa[$cc[$x]][2];
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			if($row[0]==0){			
				$query="INSERT INTO OUT_PLAN (OPM_PO_NO, OPM_STOCK, OPM_ORDER_NO, OPM_ETA_DATE, CTD_CUST_NO, OPM_TAINAN_CACHE) VALUES ('".$aa[$cc[$x]][5]."','".$location_name."','".$aa[$cc[$x]][2]."', '".gtm($aa[$cc[$x]][9])."', '".$aa[$cc[$x]][3]."','".$tn."')";
				echo $query."<BR><BR><BR>";
				$result=mssql_query($query);
			}
			$pdd=new get_pdd_literkg;
			$pdd->pid=$aa[$cc[$x]][6];
			$pdd->liter_kg();
			$lkg=$pdd->lkg;
			$skg=$aa[$cc[$x]][7]*$lkg;
			if(strtoupper($aa[$cc[$x]][8])=='KG')
			{
				$OPD_QTY_KG=$OPD_REAL_QTY_KG=$aa[$cc[$x]][7];
				$OPD_QTY_LITER=$aa[$cc[$x]][7]/$lkg;
			}
			if(strtoupper(trim($aa[$cc[$x]][8]))=='L')
			{
				$OPD_QTY_KG=$OPD_REAL_QTY_KG=$skg;
				$OPD_QTY_LITER=$aa[$cc[$x]][7];
			}
			if($pdd->type=='DM' or $pdd->type=='BTL')
			{
				$query="select * from PRODUCT_DATA where PDD_PROD_NO='".$aa[$cc[$x]][18]."'";
				$result=mssql_query($query);
				$row=mssql_fetch_row($result);
				$package=trim($row[4]);
			}
			else
			{
				$package='LY-'.substr($aa[$cc[$x]][13],-3);
			}
			$query="select count(*) as tn from OUT_PLAN_FROM_FILE where OPM_ORDER_NO='".trim($aa[$cc[$x]][2])."'";
//			echo $query."<BR>";
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			if($row[0]==0){	
				/////package沒有帶入包裝型態->導致coa拉出來的包裝型態是空的		
				$query="INSERT INTO OUT_PLAN_FROM_FILE (OAF_CUST_ORDER_NO, OAF_DEP_NO , OAF_PROD_NAME, OPM_ORDER_NO,  OAF_SERIAL_NO, OAF_ETA_DATE, PDD_PROD_NO, 
				OAF_DELI_CUST_NO, OAF_DELI_CUST_NAME, OAF_ORDER_CUST_NO, OAF_ORDER_CUST_NAME, OAF_PACKAGE_DESC) VALUES 
				('".$aa[$cc[$x]][5]."',2,'".get_prod_name($aa[$cc[$x]][3])."','".$aa[$cc[$x]][2]."', 1, '".gtm($aa[$cc[$x]][9])."', '".$aa[$cc[$x]][6]."', 
				'".$aa[$cc[$x]][3]."', '".get_cust_sname($aa[$cc[$x]][3])."', '".$aa[$cc[$x]][4]."', '".get_cust_sname($aa[$cc[$x]][4])."', '".$package."')";
 				echo $query."<BR><BR><BR>";
				$result=mssql_query($query);
			}
			
			$query="UPDATE  PRODUCT_RUNNING_ACCOUNT SET PRA_OUT_COUNT=".$acc." , OTD_NO = '".$aa[$cc[$x]][16]."' WHERE   (PRA_LOT_NO = '".$aa[$cc[$x]][13]."')";	
			$result=mssql_query($query);
	/*		
			echo "UNIT:".$aa[$cc[$x]][8]."<BR>";
			echo "RW:".$aa[$cc[$x]][7]."<BR>";
			echo "TYPE:".$pdd->type."<BR>";
			echo "LKG:".$lkg=$pdd->lkg."<BR>";
			echo "QKG:".$aa[$cc[$x]][7]."<BR>";
			echo "SKG:".$skg."<BR>";
			echo "OPD_QTY_KG:".$OPD_QTY_KG."<BR>";
			echo "OPD_QTY_LITER:".$OPD_QTY_LITER."<BR>";
	*/
			
			/////$pdd->drum_liter應該要換成Excel表的包裝註解
			if($pdd->type=='DM' or $pdd->type=='BTL')
			{
				$drum_liter=$pdd->drum_liter;
				if($pdd->drum_liter==''){$drum_liter=$pdd->drum_kg;}
				$OPD_QTY_DRUM=$OPD_QTY_LITER/($drum_liter);
			}
			else{$OPD_QTY_DRUM=1;}
			
			
			$end0=substr(trim($aa[$cc[$x]][6]),(strpos(trim($aa[$cc[$x]][6]),"-")+1)); //檢查藥品
			$end=substr(trim($aa[$cc[$x]][6]),(strpos(trim($aa[$cc[$x]][6]),"-")+1),3);
			$end_=substr(trim($aa[$cc[$x]][6]),(strpos(trim($aa[$cc[$x]][6]),"-")+1),1);
			
	 //		echo "3D:".$aa[$cc[$x]][6]."<BR>";
//			echo "END:".$end."<BR>";
	//		echo "PDD:".trim($aa[$cc[$x]][11])."<BR>";
			
			///0: lorry 1:DRUM  2: BTL
			$pdd=new get_pdd_literkg;
			$pdd->pid=$aa[$cc[$x]][6];
			$pdd->liter_kg();
			$pdtype=$pdd->class;
			
			if(substr($end,0,1)<>'0'){$style=1;}
			elseif(substr($end,0,1)=='0' and substr($end,1,1)<>'0'){$style=2;}
			else{$style=0;}
			
			if($style==1 and $pdtype=='成品'){
				echo "LOT_NO:". $lot=trim($aa[$cc[$x]][13])."D".$end;
			}
			elseif($style==2 and $pdtype=='成品'){
				echo "LOT_NO:".	$lot=trim($aa[$cc[$x]][13])."B".$end;
			}
			else{
				$lot=trim($aa[$cc[$x]][13]);
			}
			echo "LOT_NO:".$lot." END:".$end." STYLE:".$pdtype."<BR>";
			if($aa[$cc[$x]][18]=='UP336-000' or $aa[$cc[$x]][18]=='UP317-000' or $aa[$cc[$x]][18]=='UP332-000' or $aa[$cc[$x]][18]=='UP323-000')
			{
				$query="select PDD_PROD_SHORT_NAME from PRODUCT_DATA where PDD_PROD_NO='".$aa[$cc[$x]][18]."'";
				$result=mssql_query($query);
				while($row=mssql_fetch_row($result)){
				$LY=trim($row[0]).substr($aa[$cc[$x]][13],-4);}
				$query="insert into LORRY_EXAMINE_LIST (LEL_LOT_NO, PRA_OUT_COUNT, PDD_PROD_NO, LEL_LY_NO) VALUES
				('".$aa[$cc[$x]][13]."',".$nn.",'".$aa[$cc[$x]][18]."','".$LY."')";
				//echo $query;
				$result=mssql_query($query);
				$row=mssql_fetch_row($result);
			}
			$query="INSERT INTO OUT_PRODUCT
					   (OTN_NO, COPCONFIRMSERIES, OPD_ACC_UNIT, OPM_ORDER_NO, OPD_SERIAL_NO, OTD_NO, PDD_PROD_NO, OPD_LOT_NO, OPD_QTY_DRUM, OPD_QTY_KG,OPD_QTY_LITER, OPD_COA_NO, OPD_MEMO, OPD_REAL_QTY_KG, OPD_INWARD_DATE, OAF_PACKAGE)	VALUES  
					   ('".$aa[$cc[$x]][5]."', '".$aa[$cc[$x]][19]."', '".strtoupper($aa[$cc[$x]][8])."', '".$aa[$cc[$x]][2]."',".$nn.",'".$aa[$cc[$x]][16]."','".$aa[$cc[$x]][18]."','".$lot."',".round($OPD_QTY_DRUM).",".round($OPD_QTY_KG).",".round($OPD_QTY_LITER).",'".$aa[$cc[$x]][10]."','".$aa[$cc[$x]][12]."','".round($aa[$cc[$x]][7],0)."','".date("Ymd")."','".$package."')";
//			echo $query."<BR>";
			$result=mssql_query($query);
	//		echo '<BR>';
	//		echo "決定書 : ". $aa[$cc[$x]][16]." 匯入..<br>";
			$o++;
		}
		else{
			echo "決定書 : ". $aa[$cc[$x]][16]." 未指定 Lot NO..</br>";
		}
	}
	echo "完成作業，共導入".$o."筆資料<BR>";
}

class get_pdd_literkg
{
	public $pid;
	public $type,$class;
	public $drum_kg,$drum_liter;
	public $lkg;
	public $unit;
	
	function liter_kg(){
		$query="SELECT  PDD_TYPE, PDD_DRUM_KG, PDD_LITER_KG, PDD_UNIT, PDD_DRUM_LITER, PDD_CLASS FROM PRODUCT_DATA WHERE (PDD_PROD_NO = '".$this->pid."') ";	
/*		echo "<BR>";
		echo $query;
		echo "<BR>";
*/
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->type=$row[0];
		$this->drum_kg=$row[1];
		$this->lkg=$row[2];
		$this->unit=$row[3];
		$this->drum_liter=$row[4];
		$this->class=$row[5];
		
	}
}
?>