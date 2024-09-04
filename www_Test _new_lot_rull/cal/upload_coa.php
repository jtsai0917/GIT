<?php 
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
lasturl();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>COA</title>
</head>

<body>

<form action="<?php echo $loginFormAction; ?>" method="post"
enctype="multipart/form-data">
  <p>廠商：
  <input type="button" name="X3" id="X3" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
    <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="10" value="<?php echo $_SESSION['cid'];?>" readonly="readonly" />
    <input type="button" name="pdd_no2" id="pdd_no2" value="查詢" onclick="window.open('../main.php?url=cust_no&sup=Y ', '_self');" />
    <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="16" value="<?php echo ($_SESSION['cname']);?>" />
    品名：
    <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
    <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />
    <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
    <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="16" value="<?php 
		if ($_GET['pname']){
			echo $_GET['pname'];
			$_SESSION['pname']=$_GET['pname'];
		}
		elseif($_SESSION['pname']){
			echo $_SESSION['pname'];
		}
		else{
		echo '';
		}
		?>" readonly="readonly" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" name="defitem" id="defitem" value="定義上傳欄位" onclick="window.open('../pdd_prod_no.php ', '_self');" />
  </p>

    
    檢驗報告上傳：
    <input type="file" name="file" id="file" />
    檔案說明：
    <input name="disc" type="text" id="disc" size="30" />
  </p>
  <p>輸入LOT NO: 
    <input type="text" name="lot_no" id="lot_no" />(如果此處有輸入，將不會讀取上傳設定裡的LotNo 設定位置)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" name="upload" id="upload" value="送出" />
    上傳檔名請不要用特殊字元 (EX: # @ ! % &amp; $)
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
</form>
</body>
</html>
<?php
$loginFormAction = $_SERVER['PHP_SELF'];
if(isset($_POST["upload"]))
{   
	$path="../reports/UPLOAD_COA/";
	$filename="tmp_".date("YmdHis").$_FILES["file"]["name"];
	$fullpath="/reports/UPLOAD_COA/".$filename;
	if (!file_exists($path)){
		mkdir($path);
		}
	$_SESSION['results']=move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
	$query="INSERT INTO dbo.FILE_REPORTS
           	(FILE_PATH, FILE_UPLOAD_TIME, ANI_GROUP, FILE_PID, FILE_UID, FILE_LOT_NO, FILE_CID, FILE_DISC, FILE_NAME, FORM_ID)
			VALUES          ('".$fullpath."', '".date("YmdHis")."', 'COA', '".$_POST['pdd_chemical1']."', '".$_SESSION['uid']."',
			 '".$_GET['lot_no']."', '".$_POST['pdd_chemical3']."', '".$_POST['disc']."', '".$_FILES["file"]["name"]."', 'COA')";
	$_SESSION['file']=$path.$filename;
	$result = mssql_query($query);

	$ss=new read_reportx;
	$ss->pid=$_POST['pdd_chemical1'];
	$pdd_chemical=get_pdd_chemicla_from_pid($ss->pid);
	$ss->url=$path.$filename;
	$ss->group=$ss->efm="COA";
	$ss->item='Lot_No';
	$ss->read();
	if($_POST['lot_no']==''){
		$n=strpos($ss->value,'(');
		echo $n;
		$lot_no=trim(substr($ss->value,0,$n));
	}else{
		$lot_no=$_POST['lot_no'];	
	}
	$query="update dbo.FILE_REPORTS set FILE_LOT_NO='".$lot_no."' where (FILE_PATH='".$fullpath."')";
	
	$result = mssql_query($query);
	echo "Lot No : ".$lot_no."</br>";
	$reg_items=list_ani_items_reg($_POST['pdd_chemical1']);
	$reg_items=substr($reg_items,0,-1);

	$d=strtotime("+2 Days"); $ordertime = date("YmdHi",$d);
	//
	$query="select * from dbo.AnalyzeDesign where AND_LOT_NO='".trim($lot_no)."'";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	if($numRows<1){
	//UPDATE 樣品瓶總表
	$query="UPDATE          Sample  
			SET                   Sample.SMP_TIMES = Sample.SMP_TIMES+1, SMP_LOT = '".trim($lot_no)."'
			WHERE          (SMP_ID = 'SCOA')";

	$result = mssql_query($query);
	//新增樣品瓶資料 
	$query="INSERT INTO Sample_All
                            (SMA_ID, SMA_TIMES, SMA_SERVICE, SMA_LOT, SMA_USER, SMA_SMP, SMA_DRUMNO)
			select         'SCOA',Sample.SMP_TIMES,1,'".trim($lot_no)."','".$_POST['cid']."','".$_SESSION['uid']."','1~8'
			from Sample WHERE          (SMP_ID = 'SCOA')";

	$result = mssql_query($query);		

	$query="INSERT INTO dbo.AnalyzeDesign
                            (AND_SMP_DATETIME, AND_OUT_DATETIME, AND_OUT_QTY, CTD_CUST_NO, AND_RESULT,AND_RESULT_DATETIME,AND_GET_DATETIME,AND_GET_QTY,AND_MARK,AND_VALUE,AND_ANA_ID,AND_APPLY_DATE, AND_NEED_NO, AND_LOT_NO, AND_GOODS, AND_BEFORE
							, AND_ITEM, AND_NOTE, AND_TOTAL, AND_REPORT_DATETIME, AND_PERSON, AND_TYPE)
  VALUES          ('".date("Ymd")."1000','".date("YmdHi")."','0','".$_POST['pdd_chemical3']."','','','',0,'0','','','".date("Ymd")."',277,'".$lot_no."','".$_SESSION['pid']."','N','".$reg_items."','".$_POST['remark']."',0,'".$ordertime."','".$_SESSION['uid']."',1)";}

  $resultx = mssql_query($query);
  if($resultx){
  	$str=$reg_items.",";
	$aa=explode(',',$str,-1);
	$query="SELECT DISTINCT ANI_GROUPNAME
			FROM              AnalyzeItem
			WHERE          (ANI_INDEX <>'') and ";
	for($i=0;$i<count($aa);$i++){
	if ($i<(count($aa)-1)){
	$query.="(ANI_INDEX =".$aa[$i].") OR ";}
	else {$query.="(ANI_INDEX =".$aa[$i].")";}	
	}
// echo '</br>'.$str.'</br>';
	$result = mssql_query($query);
	while($row = mssql_fetch_array($result)){
		$group.=$row['ANI_GROUPNAME'].",";
	}
	$aa=explode(',',$group,-1);
	for($i=0;$i<count($aa);$i++)
		{
			$query1=$q1=$q2='';
			$query="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='".get_table($aa[$i],$pdd_chemical)."')";
			$result = mssql_query($query);
			while($row = mssql_fetch_array($result))
			{
				if (($row['COLUMN_NAME']!='FacterDate') && ($row['COLUMN_NAME']!='LotNo') && ($row['COLUMN_NAME']!='CHK5') && ($row['COLUMN_NAME']!='CHK6') && ($row['COLUMN_NAME']!='CHK7') && 
($row['COLUMN_NAME']!='CHK8') && ($row['COLUMN_NAME']!='CHK1') && ($row['COLUMN_NAME']!='CHK2') && ($row['COLUMN_NAME']!='CHK3') && ($row['COLUMN_NAME']!='CHK4') && ($row['COLUMN_NAME']!='Ok') 
&& ($row['COLUMN_NAME']!='AnalyzeTime') && ($row['COLUMN_NAME']!='AnalyzeTime') && ($row['COLUMN_NAME']!='AnaManager') && ($row['COLUMN_NAME']!='Tester') && ($row['COLUMN_NAME']!='Operator') && 
($row['COLUMN_NAME']!='SampleNo') && ($row['COLUMN_NAME']!='SerialNo') && ($row['COLUMN_NAME']!='TestDate'))
				{	
					$clo=new ani_excel;
					$clo->table=get_table($aa[$i],$pdd_chemical);
					$clo->pid=$_SESSION['pid'];
					$clo->item=$row['COLUMN_NAME'];
					$clo->get();
					$disc=$clo->disc;
					if($clo->disc=='x'){$disc='X';}
					if($disc<>'X'){
					$ss=new read_reportx;
					$ss->efm=get_table($aa[$i],$pdd_chemical);
					$ss->item=$row['COLUMN_NAME'];
					$ss->url=$_SESSION['file'];
					$ss->pid=$_SESSION['pid'];
					$ss->group='COA';
					$ss->read();
					$q1.=$ss->item."],[";
					$q2.=$ss->value."','";		
				}
				} /// end X
			}
			 $q1="[".substr($q1,0,-2);
			 $q2="'".substr($q2,0,-2);
			 $query1="Insert into dbo.".get_table($aa[$i],$pdd_chemical)." ([SampleNo],[TestDate],[CHK1],[CHK2],[LotNo],[SerialNo],[Ok],[Tester],[Operator],[AnalyzeTime],
			 ".$q1.") VALUES ('SCOA','".date("Y-m-d")."','1','1','".$lot_no."',1,1,'".$_SESSION['uname']."','".$_SESSION['uname']."','".date("YmdHis")."',".$q2.")";

			 $result1 = mssql_query($query1);
			 if (!$result1) {
				print("SQL statement failed with error:\n");
				print("   ".mssql_get_last_message()."\n");
  			}
			else{echo $aa[$i]."....已輸入</br>";}
		}
	} 
}
if(isset($_POST["defitem"]))
{   
	$url="../system/index.php?url=defineaniitem_1&ani_group=COA&pid=".$_POST['pdd_chemical1'];
	jumpto($url);
}

class read_reportx
{
	public $url,$location,$item,$efm,$value,$float,$pid,$group,$x,$y,$yy,$ft,$smpid;
	function read()
	{
		session_start();
		$this->ft =strtoupper(substr($this->url,-4,4));

		if(($this->ft=='XLSX') or ($this->ft=='.XLS'))
		{	  ///read excel2007	
			session_start();
			require_once("../connections/conn.php");
			require_once "../PHPEXCEL/Classes/PHPExcel.php";
			require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
			$reader= PHPExcel_IOFactory::createReaderForFile($this->url);
			$reader->setReadDataOnly(true);
			$excel= $reader->load($this->url);	
			$sheetCount = $excel->getSheetCount();
			$sheetNames = $excel->getSheetNames();
			$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
			$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
			$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
			$highestRows = $sheet->getHighestRow(); //最高行數。從1開始
    		$sheet = $excel->getActiveSheet(0);
   			$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
    		$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
    		$highestRows = $sheet->getHighestRow(); //最高行數。從1開始    
    		$SheetName = $sheetNames[0];
    		$excelData[$s]['SheetName'] = $SheetName;
     		if(($this->group=='M13') or ($this->group=='M21'))
			{
    			$v = null;
				for($x=0;$x<4;$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) 
						{ // 0 = A欄
        					$v = trim($sheet->getCellByColumnAndRow($c,$x)->getValue());
							if($v=='Sample Name')
								{
									$lot=trim($sheet->getCellByColumnAndRow($c,$x+1)->getValue());
									$_SESSION['SampleNo']=brackets($lot);
									$_SESSION['get_lot_no']=brackets_1(trim($lot));
									if(trim($_SESSION['get_lot_no'])<>trim($_GET['lot_no']))
									{
										if($_SESSION['cnt']==0){
											$_SESSION['cnt']=$_SESSION['cnt']+1;
									//		my_msg("上傳文件的LOT NO 錯誤",$_SESSION['lasturl']);
											fun_confirm('Lot NO 錯誤...繼續上傳',$_SESSION['redir']);				
										}
									}
								}
							
							if(strpos($v," ".$this->item." "))
								{
									$this->value=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());
								}
							
						}
				}
			}
			else
			{
				$query="SELECT         excel
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->efm."') AND (item = '".$this->item."')";
				$query.=" AND (pid = '".$this->pid."') ";
				$result=mssql_query($query);
				$numrows=mssql_num_rows($result);
				if($numrows>0)
				{	
					while($row=mssql_fetch_array($result))
					{ 
						$this->location=$row['excel'];
						$this->value=$sheet->getCell(trim(strtoupper($row['excel'])))->getValue();	
					}
				}
				$this->value=number_format($this->value,6);
				if(trim($this->value)==''){$this->value='Null';}
				$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getValue();
				$ssp=substr($this->value,0,1);	
				$_SESSION[$this->value]=$ssp;
//				if($this->item=='Color'){$_SESSION['tmpsss']=$ssp;}	
				if($ssp=="=")
				{
					$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getCalculatedValue();
				}
				elseif(($ssp==">") or ($ssp=="<"))
				{
					$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getCalculatedValue();
					$this->value=0.000001;
				}
				else
				{
					$this->value=$sheet->getCell(trim(strtoupper($this->location)))->getValue();
				}
			}
			
		}

		if($this->ft=='.TXT' or $this->ft=='.txt')
		{
			if(substr($_GET['lot_no'],0,3)<>'TDL')
			{
				$myfile = fopen($this->url, "r") or die("Unable to open file!");
				while(!feof($myfile)) 
				{
					$str=fgets($myfile);
					$str1=trim(substr($str,1,9));
					$item_value=trim(substr($str,42,12));
			//		echo $str1.":".$item_value.":".$this->item."</br>";
					if($str1==($this->item))
					{
						$_SESSION[$this->item]=$this->value=$item_value;
					}
				}
			}
			else
			{
				$myfile = fopen($this->url, "r") or die("Unable to open file!");
				while(!feof($myfile)) 
				{
					$str=fgets($myfile);
					$str1=trim(substr($str,1,9));
					$item_value=trim(substr($str,25,12));
					$item_value=str_replace(",","",$item_value);
		//			echo $str1.":".$item_value.":".$this->item."</br>";
					if($str1==($this->item))
					{
						$_SESSION[$this->item]=$this->value=$item_value;
					}
				}
			}
			fclose($myfile);

		}
		
	}
	
	function excel5_(){
		$this->location=trim(strtoupper($this->location));
 		include_once("reader_excel5.php");
		$this->x=substr($this->location,0,1);
		$this->y=substr($this->location,1);
		$this->x=ord($this->x)-64;
		$x=substr($this->location,0,1);
		$y=substr($this->location,1);
			$excel = new Spreadsheet_Excel_Reader;		
			$excel->read($this->url);
			$this->value=$excel->sheets[0]['cells'][$this->y][$this->x];
			if(trim($this->value)==''){$this->value='Null';}
			$this->value=number_format($this->value,6);	
				return $this->value;			
	}
	
	function read_coa(){
		$query="";	
	}
function read_SampleNo(){	
	if($this->ft=='.XLS' or $this->ft<>'XLSX'){	
	session_start();
			require_once("../connections/conn.php");
				require_once "../PHPEXCEL/Classes/PHPExcel.php";
				require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
				$reader= PHPExcel_IOFactory::createReaderForFile($this->url);
				$reader->setReadDataOnly(true);
				$excel= $reader->load($this->url);	
				$sheetCount = $excel->getSheetCount();
				$sheetNames = $excel->getSheetNames();
				$sheet = $excel->getActiveSheet(0); //讀取第一個工作表(編號從 0 開始)
				$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
				$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
				$highestRows = $sheet->getHighestRow(); //最高行數。從1開始
    			$sheet = $excel->getActiveSheet(0);
   				$colString = $sheet->getHighestColumn(); //最大欄位的英文代號
    			$highestColumns = PHPExcel_Cell::columnIndexFromString($colString); //最大欄位的數字編號。A=0, B=1, C=2....
    			$highestRows = $sheet->getHighestRow(); //最高行數。從1開始    
    			$SheetName = $sheetNames[0];
    			$excelData[$s]['SheetName'] = $SheetName;	
				
				$query="SELECT         excel
				FROM             dbo.ani_excel_location
				WHERE         (efm = '".$this->efm."') AND (item = 'Lot_No')";
	if($_GET['pdd_prod_no']){$query.=" AND (pid = '".$_GET['pdd_prod_no']."') ";}
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$this->value= $row[0];	
	$lot=$this->value=$sheet->getCell(trim(strtoupper($row[0])))->getValue();	
	$_SESSION['SampleNoss']=$this->smpid=brackets($lot);	
	}
}

}
///end class
?>