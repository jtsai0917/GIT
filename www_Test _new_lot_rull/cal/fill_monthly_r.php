<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
include("../connections/conn.php");
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
lasturl();
datepick();
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
                width: 1260,
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
    <style type="text/css">
    	BODY,TD
		{
		    font-family: 微軟正黑體, Tahoma, Arial, Verdana;
		    font-weight: normal;
		    font-size: 9px;
		    color: #333333;
		}
    </style>
  <form name="form1" method="post" action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data">
  <table width="1240" height="40" border="1">
    <tr>
      <td height="34"><table width="1240" border="0">
        <tr>
          <td width="824" border="0"><font size="+1">月充填計畫表</font>
            <p><span class="d1"><font size="+1">藥品名:</font>
                <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
                <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="8" value="<?php echo $_SESSION['prod_no']?>" readonly="readonly" />
                <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../main.php?url=pdd_prod_no ', '_self');" />
                <input name="pdd_chemical2" type="text" id="pdd_chemical2" size="10" value="<?php echo $_SESSION['prod_name']?>" readonly="readonly" />
            <font size="+1">客戶：</font>
            <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
            <input name="pdd_chemical3" type="text" id="pdd_chemical3" size="8" value="<?php echo $_SESSION['cust_no']?>" readonly="readonly" />
            <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onclick="window.open('../main.php?url=cust_no&sup=N ', '_self');" />
            <input name="pdd_chemical4" type="text" id="pdd_chemical4" size="10" value="<?php echo get_cust_name($_SESSION['cust_no']);?>" />
            </p>
            <p>
              <input type="checkbox" name="hy" id="hy"/>
              <font size="+1">顯示半年未出貨的客戶
              充填月份：</font>
              <input type="text" name="ym" id="ym" value="<?php echo $_SESSION['ym'];?>" onchange="set_date_session(this.name,this.value)"> 
              <font size="+1">( 年月相連 EX：201508) </font>
              <input type="submit" name="submit" id="submit" value="  確定  ">
            </p></td>
          <td width="400" align="center" border="0"><p class="left">&nbsp;</p>
            <p class="left"><font size="+1">匯入EXCEL </font>
              <input type="file" name="file" id="file" />
              <label for="disc"></label>
              <input type="submit" name="upload" id="upload" value="上傳" />
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
  echo '<table id="GridView1" width="1240" border="1" align="left">
  <tr class="GridviewScrollHeader">
    <td width="100">客戶</td>
    <td width="100">藥品名</td>
    <td width="30">1</td>
    <td width="30">2</td>
    <td width="30">3</td>
    <td width="30">4</td>
    <td width="30">5</td>
    <td width="30">6</td>
    <td width="30">7</td>
    <td width="30">8</td>
    <td width="30">9</td>
    <td width="30">10</td>
    <td width="30">11</td>
    <td width="30">12</td>
    <td width="30">13</td>
    <td width="30">14</td>
    <td width="30">15</td>
    <td width="30">16</td>
    <td width="30">17</td>
    <td width="30">18</td>
    <td width="30">19</td>
    <td width="30">20</td>
    <td width="30">21</td>
    <td width="30">22</td>
    <td width="30">23</td>
    <td width="30">24</td>
    <td width="30">25</td>
    <td width="30">26</td>
    <td width="30">27</td>
    <td width="30">28</td>
    <td width="30">29</td>
    <td width="30">30</td>
    <td width="30">31</td>
  </tr>
';
	echo $_POST['ym'].' 充填計畫表</br>';
	if($_GET['ym']<>"" and $_POST['ym']==""){ $_POST['ym']=$_GET['ym'];}
	else{$_SESSION['ym']=$_POST['ym'];}
//	$d=strtotime("-6 Months"); $d= date("Ym",$d);
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
			WHERE          (dbo.CUSTOMER_DATA.CTD_SUPPLIER = 'N') AND (dbo.PRODUCT_DATA.PDD_STYLE = 'LY') AND (FLM_YEAR_MONTH='".$_POST['ym']."')";
	if($_SESSION['pid']<>''){$query.=" AND (dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO = '".$_SESSION['pid']."')";}
	if($_SESSION['cid']<>''){$query.=" AND (dbo.CUSTOMER_DATA.CTD_CUST_NO = '".$_SESSION['cid']."')  ORDER BY  dbo.CUSTOMER_PRODUCTS.PDD_PROD_NO, dbo.CUSTOMER_DATA.CTD_CUST_NO";}
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	 while($row = mssql_fetch_array($result))
	 {
		$row['FLM_YEAR_MONTH']=$_POST['ym'];
		$flm=new FLM;
		$flm->pid=$row['PDD_PROD_NO'];
		$flm->ym=$_POST['ym'];
		$flm->cid=$row['CTD_CUST_NO'];

		echo '<tr class="GridviewScrollItem">';
		echo '<td>'.get_cust_name($row['CTD_CUST_NO']).'</td>';
		echo '<td>'.$row['PDD_PROD_NAME'].'</td>';  
		for($i=1;$i<=31;$i++)
		{
			$i=sprintf("%02d", $i);
			if(trim($flm->lot('FLM_DAY'.$i))==''){$s="___";}
			else{$s=$flm->lot('FLM_DAY'.$i);}
			echo '<td><a target="_self" href="index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day='.$i.'">'.$s.'</a></td>';
//			echo 'index.php?url=edit_FLMYM&ym='.$row['FLM_YEAR_MONTH'].'&cn='.$row['CTD_CUST_NO'].'&pn='.$row['PDD_PROD_NO'].'&day='.$i;
//			echo "</br>";
		}
		echo '</tr>';
	  }
  echo '</table>';

  
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
	jumpto("index.php?url=echo");
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
	
	public $ft,$url;
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
		$v = null;//FILLPLAN_LORRY_MONTH
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
							//$aa[$x][$c]=$this->value =date("Y-m-d",$t);
							$dat[$x][$c]=$this->value =date("Ym",$t);
							$day[$x][$c]=$this->value =date("d",$t);
							
							}
							elseif($c==2){$cn[$x]=$this->value;}
							
							elseif($c==3){$pn[$x]=$this->value;}
							elseif($c==4){$lo[$x]=$this->value;}
							$cn[$x]=strtoupper($cn[$x]);
							$pn[$x]=strtoupper($pn[$x]);
							$lo[$x]=strtoupper($lo[$x]);
							
							
							
							
							
						}
																					
				}
				for($x=0;$x<$highestRows-1;$x++)
				{
    				for ($c = 0; $c < $highestColumns; $c++) 
						{
							$this->value=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());
							//$this->aa[$x][$c]=	trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());
							$aa[$x][$c]=trim($sheet->getCellByColumnAndRow($c,$x+2)->getValue());	
						if(($c==1) or ($c==5))
							{
								$t = PHPExcel_Shared_Date::ExcelToPHP($aa[$x][$c]);
							
							//$aa[$x][$c]=$this->value =date("Y-m-d",$t);
								if($aa[$x][$c]<>''){
									$aa[$x][$c] = date("Y-m-d",$t);}
								else{
								$aa[$x][$c]='';
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
/*/
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