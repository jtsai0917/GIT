<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 

session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
?>
<style type="text/css">
#right {
	text-align: right;
}
#center {
	text-align: center;
}
.left {
	text-align: left;
}
.left {
	text-align: left;
}
</style>

<form name="form1" method="post" action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data">
  <table width="1240" height="40" border="1">
    <tr>
      <td height="34"><table width="1240" border="0">
        <tr>
          <td width="824" border="0"><p>Lorry 月充填計畫表 </p>
            <p><span class="d1">藥品名:
                <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
                <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="8" value="<?php echo $_SESSION['prod_no']?>" readonly="readonly" />
                <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
                <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="10" value="<?php echo $_SESSION['prod_name']?>" readonly="readonly" />
            </span>  <span class="d1">客戶：
            <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
            <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="8" value="<?php echo $_SESSION['cust_no']?>" readonly="readonly" />
            <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onclick="window.open('../main.php?url=cust_no&sup=N ', '_self');" />
            <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="10" value="<?php echo get_cust_name($_SESSION['cust_no']);?>" />
            </span></p>
            <p>
              <input type="checkbox" name="hy" id="hy"/>
              顯示半年未出貨的客戶
              
              <label for="hy"></label>
              充填月份：
              <label for="ym"></label>
              <input type="text" name="ym" id="ym"> 
              ( 年月相連 EX：201508) 
              <input type="submit" name="submit" id="submit" value="  確定  ">
            </p></td>
          <td width="400" align="center" border="0"><p class="left">&nbsp;</p>
            <p class="left">匯入EXCEL 
              <input type="file" name="file" id="file" />
              <label for="disc"></label>
              <input type="submit" name="upload" id="upload" value="上傳" />
              <input type="button" name="after" id="after" value="看表單" onclick="window.open('echo.php','_self');" />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
            <p class="left">範例</p></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
  <?php 
  session_start();
  $loginFormAction = $_SERVER['PHP_SELF'];
  if ($_POST['ym']<>"" or $_GET['ym']<>""){
	  if($_GET['ym']<>"" and $_POST['ym']==""){ $_POST['ym']=$_GET['ym'];}
	include("../lib/monthly.php");
	include("../connections/conn.php");
	$d=strtotime("-6 Months"); $d= date("Ym",$d);
	$query="SELECT DISTINCT 
                          dbo.CUSTOMER_DATA.CTD_CUST_NO, 
                          dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO, 
                          dbo.CUSTOMER_DATA.CTD_SUPPLIER, 
                          dbo.PRODUCT_DATA.PDD_PROD_NAME, 
                          dbo.CUSTOMER_DATA.CTD_CUST_SHORT_NAME
FROM             dbo.CUSTOMER_DATA INNER JOIN
                          dbo.CUSTOMER_PRODUCTS ON 
                          dbo.CUSTOMER_DATA.CTD_CUST_NO = dbo.CUSTOMER_PRODUCTS.CTD_CUST_NO
                           INNER JOIN
                          dbo.PRODUCT_DATA ON 
                          dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO = dbo.PRODUCT_DATA.PDD_PROD_NO
                           INNER JOIN
                          dbo.FILLPLAN_LORRY_MONTH ON 
                          dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO = dbo.FILLPLAN_LORRY_MONTH.PDD_PROD_NO
WHERE          (dbo.CUSTOMER_DATA.CTD_SUPPLIER = 'N') AND (dbo.PRODUCT_DATA.PDD_STYLE = 'LY') AND 
                            (dbo.CUSTOMER_PRODUCTS.CTP_SEL_LY_TOTO <> '') ";
if($_SESSION['pid']<>''){$query.=" AND (dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO = '".$_SESSION['pid']."')";}
if($_SESSION['cid']<>''){$query.=" AND (dbo.CUSTOMER_DATA.CTD_CUST_NO = '".$_SESSION['cid']."')";}
if($_POST['hy']<>'on')
{
	$query=$query." AND (dbo.FILLPLAN_LORRY_MONTH.FLM_YEAR_MONTH > '".$d."') ORDER BY  dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO, dbo.CUSTOMER_DATA.CTD_CUST_NO";
}
//echo $_POST['hy']."</br>";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
$t1=0;
echo $_POST['ym'].' 充填計畫表';
	 while($row = mssql_fetch_array($result))
	 {
		 $row['FLM_YEAR_MONTH']=$_POST['ym'];
		$flm=new FLM;
		$flm->pid=$row['PDD_PROD_NO'];
		$flm->ym=$_POST['ym'];
		$flm->cid=$row['CTD_CUST_NO'];

echo '<tr class="_10">';
echo '<td width="120">'.$row['CTD_CUST_SHORT_NAME'].'</td>';
echo '<td width="120">'.$row['PDD_PROD_NAME'].'</td>';  
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=01'.'">_'.$flm->lot('FLM_DAY01').'_</a></td>';
if ($row['FLM_DAY01']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=02'.'">_'.$flm->lot('FLM_DAY02').'_</a></td>';
if ($row['FLM_DAY02']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=03'.'">_'.$flm->lot('FLM_DAY03').'_</a></td>';
if ($row['FLM_DAY03']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=04'.'">_'.$flm->lot('FLM_DAY04').'_</a></td>';
if ($row['FLM_DAY04']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=05'.'">_'.$flm->lot('FLM_DAY05').'_</a></td>';
if ($row['FLM_DAY05']<>''){$t1=$t1+1;}
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=06'.'">_'.$flm->lot('FLM_DAY06').'_</a></td>';
if ($row['FLM_DAY06']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=07'.'">_'.$flm->lot('FLM_DAY07').'_</a></td>';
if ($row['FLM_DAY07']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=08'.'">_'.$flm->lot('FLM_DAY08').'_</a></td>';
if ($row['FLM_DAY08']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=09'.'">_'.$flm->lot('FLM_DAY09').'_</a></td>';
if ($row['FLM_DAY09']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=10'.'">_'.$flm->lot('FLM_DAY10').'_</a></td>';
if ($row['FLM_DAY10']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=11'.'">_'.$flm->lot('FLM_DAY11').'_</a></td>';
if ($row['FLM_DAY11']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=12'.'">_'.$flm->lot('FLM_DAY12').'_</a></td>';
if ($row['FLM_DAY12']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=13'.'">_'.$flm->lot('FLM_DAY13').'_</a></td>';
if ($row['FLM_DAY13']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=14'.'">_'.$flm->lot('FLM_DAY14').'_</a></td>';
if ($row['FLM_DAY14']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=15'.'">_'.$flm->lot('FLM_DAY15').'_</a></td>';
if ($row['FLM_DAY15']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=16'.'">_'.$flm->lot('FLM_DAY16').'_</a></td>';
if ($row['FLM_DAY16']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=17'.'">_'.$flm->lot('FLM_DAY17').'_</a></td>';
if ($row['FLM_DAY17']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=18'.'">_'.$flm->lot('FLM_DAY18').'_</a></td>';
if ($row['FLM_DAY18']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=19'.'">_'.$flm->lot('FLM_DAY19').'_</a></td>';
if ($row['FLM_DAY19']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=20'.'">_'.$flm->lot('FLM_DAY20').'_</a></td>';
if ($row['FLM_DAY20']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=21'.'">_'.$flm->lot('FLM_DAY21').'_</a></td>';
if ($row['FLM_DAY21']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=22'.'">_'.$flm->lot('FLM_DAY22').'_</a></td>';
if ($row['FLM_DAY22']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=23'.'">_'.$flm->lot('FLM_DAY23').'_</a></td>';
if ($row['FLM_DAY23']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=24'.'">_'.$flm->lot('FLM_DAY24').'_</a></td>';
if ($row['FLM_DAY24']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=25'.'">_'.$flm->lot('FLM_DAY25').'_</a></td>';
if ($row['FLM_DAY25']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=26'.'">_'.$flm->lot('FLM_DAY26').'_</a></td>';
if ($row['FLM_DAY26']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=27'.'">_'.$flm->lot('FLM_DAY27').'_</a></td>';
if ($row['FLM_DAY27']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=28'.'">_'.$flm->lot('FLM_DAY28').'_</a></td>';
if ($row['FLM_DAY28']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=29'.'">_'.$flm->lot('FLM_DAY29').'_</a></td>';
if ($row['FLM_DAY29']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=30'.'">_'.$flm->lot('FLM_DAY30').'_</a></td>';
if ($row['FLM_DAY30']<>''){$t1=$t1+1;};
echo '<td width="38"><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day=31'.'">_'.$flm->lot('FLM_DAY31').'_</a></td>';
if ($row['FLM_DAY31']<>''){$t1=$t1+1;};

echo '<td width="40">'.$t1.'</td>';
echo '<td width="60">'.$row['FLM_MOD_COUNT'].'</td>';
echo '  </tr>';
$t1=0;
  }
  echo '</table>';
  }
  
if(isset($_POST["upload"]))
{   
	$path="../fill/upload/";
	echo "</br>";
	$filename="tmp_.xlsx";
	echo "</br>";
	$fullpath="/fill/upload/".$filename;
	if (!file_exists($path)){
		mkdir($path);
		}
	move_uploaded_file($_FILES["file"]["tmp_name"],$path.$filename);
	$tp=new read_fill_plan;
	$tp->url=$path.$filename;
	$tp->read();

	
	
	
	/*/echo '<table border="1" width="300">';
	for($i=0;$i<$_SESSION["x"];$i++)
	{
		echo '<tr height="25">';
		for($y=0;$y<$_SESSION["c"];$y++)
		{
			echo '<td>';
			echo $_SESSION["array1"][$i][$y];
			echo '</td>';
		}
		echo "</tr>";
	}
	echo '</table></br>';/*/
	
}

class read_fill_plan {
	
	public $ft,$url,$value;
	function read()
	{
	$this->ft =strtoupper(substr($this->url,-4,4));
	if(($this->ft=='XLSX') or ($this->ft=='.XLS') or ($this->ft=='.xls') or ($this->ft=='xlsx'))
		{	  ///read excel2007	
				session_start();
				require_once("../connections/conn.php");
				require_once ("../PHPEXCEL/Classes/PHPExcel.php");
				require_once ("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
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
				
		}
		$v = null;
				for($x=0;$x<$highestRows-1;$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) 
						{
							$aa[$x][$c]=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());		
							if(($c==1) or ($c==5))
							{
								$t = PHPExcel_Shared_Date::ExcelToPHP($aa[$x][$c]);
								if($aa[$x][$c]<>''){
									$aa[$x][$c] = date("Y-m-d",$t);
								}else{$aa[$x][$c]='';}		
							}
						}
					$_SESSION['array1']=$aa;												
				}
				$_SESSION["x"]=$x;
				$_SESSION["c"]=$c;
/*
				for($x=0;$x<$highestRows-1;$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) 
						{
							$this->value=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());
							$this->aa[$x][$c]=	trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());
							if(($c==1) or ($c==5))
							{
							$t = PHPExcel_Shared_Date::ExcelToPHP($this->aa[$x][$c]);
							// $this->aa[$x][$c] = date("Y-m-d",$t);
								if($t<>NULL){$aa[$x][$c]=$this->value =date("Y-m-d",$t);}
								else{
								$aa[$x][$c]=$this->value;
								}
							}
							else{
								$aa[$x][$c]=$this->value;}
						}
				}
				
				
				
				
				
				
				
				
				$_SESSION["lo"]=$lo;
				$_SESSION["cn"]=$cn;
				$_SESSION["pn"]=$pn;
				$_SESSION["array1"]=$aa;
				$_SESSION["dat"]=$dat;
				$_SESSION["day"]=$day;
				$_SESSION["x"]=$x;
				$_SESSION["c"]=$c;

				for($x=0;$x<($highestRows-1);$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) 
						{
							
							$this->value=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());	
							$this->aa[$x][$c]=	trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());									

							$aa[$x][$c]=$this->value;														
						}
				}
				
				for($x=0;$x<$highestRows-1;$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) {
						if(($c==1) or ($c==5)){
							$t = PHPExcel_Shared_Date::ExcelToPHP($this->aa[$x][$c]);
							//$this->aa[$x][$c] = date("Y/m-d",$t);
							//echo $a;
							//echo "&nbsp;&nbsp;&nbsp;&nbsp;";
							}
							else{
							//echo $this->aa[$x][$c];
							//echo "&nbsp;&nbsp;&nbsp;&nbsp;";
							}
						}
						$_SESSION["array1"]=$aa;
						$_SESSION["x"]=$x;
						$_SESSION["c"]=$c;
						echo "</br>";
				}
*/
	}
}

?>