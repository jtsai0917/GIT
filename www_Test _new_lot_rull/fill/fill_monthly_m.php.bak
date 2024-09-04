<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
auth('3-02',$_SESSION['aut']);
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
    <style type="text/css">
    	BODY,TD
		{
		    font-family: 微軟正黑體, Tahoma, Arial, Verdana;
		    font-weight: normal;
		    font-size: 12px;
		    color: #333333;
		}
    </style>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>" method="post" enctype="multipart/form-data">
  <table width="1230" height="40" border="1">
    <tr>
      <td height="34"><table width="1024" border="0">
        <tr>
          <td width="512" border="0"><p>Drum 月充填計畫表</p>
            <p>充填月份：
  <label for="ym"></label>
              <input type="text" name="ym" id="ym" value="<?php echo $_SESSION['ym'];?>" onchange="set_date_session(this.name,this.value)"> 
              ( 年月相連 EX：201508) 
              <input type="submit" name="submit" id="submit" value=" 送  出 ">
            </p></td>
          <td width="512" align="center" border="0">
            <p class="left"><font size="+1">匯入EXCEL </font>
              <input type="file" name="file" id="file" />
              <label for="disc"></label>
              <input type="submit" name="upload" id="upload" value="上傳" />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p>
              </td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
<?php 
  $loginFormAction = $_SERVER['PHP_SELF'];
  $query="SELECT          FILLPLAN_DRUM_MONTH.*
			FROM              FILLPLAN_DRUM_MONTH
			WHERE          (FDM_YEAR_MONTH = '".$_POST['ym']."')";
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if($numRows==0){
//	echo "NOT yet create tables!!";
//	echo "</br>";
//	echo $_POST['ym'];
	create_drum_fillplan($_POST['ym']);
	}

  
  if ($_POST['ym']<>"" or $_GET['ym']<>""){
	  if($_GET['ym']<>"" and $_POST['ym']==""){ $_POST['ym']=$_GET['ym'];}
    include("../lib/monthly_d.php");
	include("../connections/conn.php");
	$query = "SELECT  FDM.*,PDD.PDD_PROD_NO as PRODNO,PDD.PDD_PROD_NAME +'/'+ STR(PDD.PDD_DRUM_KG,3) as PRODNAME From PRODUCT_DATA PDD left outer join  
			FILLPLAN_DRUM_MONTH FDM ON PDD.PDD_PROD_NO = FDM.PDD_PROD_NO AND FDM.FDM_YEAR_MONTH = '".$_POST['ym']."' where not PDD.PDD_DRUM_KG is null  and PDD.PDD_PROD_NO IN 
			(select PDD_PROD_NO from  CUSTOMER_PRODUCTS  where CTP_FILL = 'Y' )  AND (PDD.PDD_CLASS = '成品')  ";
	$query.="order by PDD.PDD_PROD_NO";		
	echo $query."<BR>";
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	
// $cur=odbc_exec($conn,$query); 
//echo '<table width="2048" border="1" align="left">';
$t1=0;
echo $_POST['ym'].' 充填計畫表';
while($row = mssql_fetch_array($result))
{
echo '<tr class="GridviewScrollItem">';
echo '<td width="80">'.$row['PRODNO'].'</td>';
echo '<td width="120">'.$row['PRODNAME'].'</td>';  
for($i=1;$i<=31;$i++)
{
	if($i<10){$i="0".$i;}
	if($row['FDM_DAY'.$i]==''){$s="___";}
	else{$s=$row['FDM_DAY'.$i];}
echo '<td width="30"><a target="_blank" href="index.php?url=edit_FDMYM&ym='.$_POST['ym'].'&nu='.$row['FDM_WDAY'.$i].'&pn='.$row['PRODNO'].'&day='.$i.'">'.$s.'</a></td>';
if ($row['FDM_DAY'.$i]<>''){$t1=$t1+$row['FDM_DAY'.$i];};
}
echo '<td width="40">'.$t1.'</td>';
echo '<td width="60">'.$row['FLM_MOD_COUNT'].'</td>';
echo '</tr>';
$t1=0;
  }
  echo '</table>';
  echo '</tr>';
  }
  
function create_drum_fillplan($ym)
{
	include("../connections/conn.php");
	$query="select PDD_PROD_NO FROM PRODUCT_DATA where PDD_DRUM_KG <>''";
	$result = mssql_query($query);
	 while($row = mssql_fetch_array($result))
	 {
		$query1="INSERT INTO FILLPLAN_DRUM_MONTH
                            (CTD_CUST_NO, PDD_PROD_NO, FDM_SPECIFIC, FDM_YEAR_MONTH)
				VALUES          ('','".$row['PDD_PROD_NO']."','DM','".$ym."')";
		
		$result1 = mssql_query($query1);
	 }
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
	jumpto("index.php?url=echod");
	
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