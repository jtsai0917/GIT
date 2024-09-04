<?php
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include("../lib/print_ani.php");
$lot_no=$_GET['lot_no'];
$_SESSION['anigroup']=$_GET['ani_groupname'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>列印</title>
<style type="text/css">
.big24 {
	font-size: 24px;
}
normal {
	font-size: 14px;
}
tr td {
	font-size: 14px;
}
</style>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>"><span class="big24">
  製品檢查作業表 <?php //echo $_GET['lot_no'];?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
	if(($_GET['pdd_chemical']=='H2O') and (($_GET['ani_groupname']='M13') or ($_GET['ani_groupname']='M21'))){
		echo '<input type="submit" name="print_metal" value=" 金屬成分(全項目) " />';	
	}
	if(strpos($_GET['ani_groupname'],"TT")===FALSE)
	{}
	else
	{
		echo '<input type="submit" name="print_TT" value=" TT列印(全項目) " />';	
 	}
	if($_GET['ani_groupname']=='ACID')
	{
		echo '<input type="submit" name="print_ACID" value=" ACID列印 " />';	
	}
?>
  <input type="submit" name="print" id="print" value=" 列 印 " />
  </span><span class="big24">
  <input type="submit" name="leave" id="leave" value=" 離 開 " />
  </span>
</form>
<?php 

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_POST["print"]))
{
	echo "Printing...";
	$oo=new get_from_lot_no;
	$oo->lid=$_GET['lot_no'];
	$oo->cid();
	$oo->ani();
	$tb=new tble;
	$tb->pdd_prod_name=$oo->pdd_prod_name;
	$tb->tbname=$_GET['table'];
	$tb->pdd_box=$oo->pdd_type;
	$tb->pdd_prod_short_name=$oo->pdd_prod_short_name;
	$tb->ps=$_SESSION['ps'];
	$tb->pdd_type=lotnoTotype($lot_no);
	$tb->ani_groupname=$_SESSION['anigroup'];
	$tb->pdd_chemical=$_GET['pdd_chemical'];
	$tb->lot_no=$_GET['lot_no'];
	$tb->AnalyzeTime=$_GET['AnalyzeTime'];
	$tb->prod_no=$_GET['pdd_prod_no'];
	$tb->pdd_style=$oo->prodstyle;
	$tb->print_table();
}

if (isset($_POST["leave"])) 
{
	rm_dir("../tmp2/");
	jumpto("../close_pag.php");
}

if (isset($_POST["print_metal"])) 
{
	$oo=new get_from_lot_no;
	$oo->lid=$_GET['lot_no'];
	$oo->cid();
	$oo->ani();
	$tb=new tble;
	$tb->pdd_prod_name=$oo->pdd_prod_name;
	$tb->pdd_type=lotnoTotype($lot_no);
	$tb->pdd_chemical=$_GET['pdd_chemical'];
	$tb->lot_no=$_GET['lot_no'];
	$tb->AnalyzeTime=$_GET['AnalyzeTime'];
	$tb->print_metal();
}
if (isset($_POST["print_ACID"])) 
{
	$findAC="select * from TLIQA9100342 where lotno='".$_GET['lot_no']."' ";
	$resultAC = mssql_query($findAC);
	$numRowsAC=mssql_num_rows($resultAC);
	while ($rowAC = mssql_fetch_array($resultAC))
	{
		$PH=$rowAC['PH'];$mole=$rowAC['Test3'];
	}
	$findACID="select * from ".$_GET['table']." where lotno='".$_GET['lot_no']."' ";
	$resultACID = mssql_query($findACID);
	$numRowsACID=mssql_num_rows($resultACID);
	while ($rowACID = mssql_fetch_array($resultACID))
	{
		$Operator=$rowACID['Operator'];
		$SampleNo=$rowACID['SampleNo'];
		$Tester=$rowACID['Tester'];
		$TestQty=$rowACID['TestQty'];
		$FacterQty=$rowACID['FacterQty'];
		$LostQty=$rowACID['LostQty'];
		$Value0=$rowACID['Value0'];
		$Value1=$rowACID['Value1'];
		$Value2=$rowACID['Value2'];
		$Value3=$rowACID['Value3'];
		$dat0="西曆 ".substr($rowACID['TestDate'],5,4)." 年 ".substr($rowACID['TestDate'],0,2)." 月 ".substr($rowACID['TestDate'],3,2)." 日";	

		
		

	}
	$tname='TLIQA9100317';
	require_once ('../PHPWord/PHPWord.php');
		$PHPWord = new PHPWord();
	$document = $PHPWord->loadTemplate('../FormList/PrintAnalyze/'.$_GET['pdd_chemical'].'/'.$tname.'.docx');
	$document->setValue("TestDate0",$dat0);
	$document->setValue("Operator0",$Operator);
	$document->setValue("SampleNo0",$SampleNo);	
	$document->setValue("Tester0",$Tester);	
	$document->setValue("LotNo0", $_GET['lot_no']);
	$document->setValue("pdd_prod_name", $_GET['pdd_chemical']);
	$document->setValue("PH", $PH);
	$document->setValue("pdd_type0", "製品");
	$document->setValue("LostQty0", $LostQty);
	$document->setValue("Test3", $mole);
	$document->setValue("TestQty0", $TestQty);
	$document->setValue("FacterQty0", $FacterQty);
	$document->setValue("Value10", $Value1);
	$document->setValue("Value20", $Value2);
	$document->setValue("Value30", $Value3);

	
	$document->save('../tmp2/'.$tname.'.docx');


echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp2/'.$tname.'.docx";</script>';

}
if (isset($_POST["print_TT"])) 
{
	$TT1=array();$TT2=array();
	$findTT="select * from AnalyzeItem where ani_groupname like 'TT%'";
	$resultTT = mssql_query($findTT);
	$numRowsTT=mssql_num_rows($resultTT);
	while ($rowTT = mssql_fetch_array($resultTT))
	{
		array_push($TT1,trim($rowTT['ANI_INDEX']));
	}
	//print_r($TT1);
	$query="select top 1 * from AnalyzeDesign where and_lot_no='".$_GET['lot_no']."' ";
	//echo $query;
	$result= mssql_query($query);
	$numRows=mssql_num_rows($result);
	while ($row = mssql_fetch_array($result))
	{	
		//echo $row['AND_ITEM'];
		for($i=0;$i<$numRowsTT;$i++)
		{	
			$ID=strpos($row['AND_ITEM'],$TT1[$i]);
			//echo $ID;
			if($ID>0){array_push($TT2,$TT1[$i]);	}
			else
			{
				//echo "aaaa";
			}
		}
		for($j=0;$j<count($TT2);$j++)
		{
			$groupname="select * from AnalyzeItem where ANI_INDEX='".$TT2[$j]."' ";
			//echo $groupname;
			$resultg= mssql_query($groupname);
			$numRowsg=mssql_num_rows($resultg);
			while ($rowg = mssql_fetch_array($resultg))
			{
				$tab=get_table($rowg['ANI_GROUPNAME'],$_GET['pdd_chemical']);
				$query2="select top 1 * from ".$tab." where lotno='".$_GET['lot_no']."' order by analyzetime desc ";
				$result2= mssql_query($query2);
				$numRows2=mssql_num_rows($result2);
				while ($row2 = mssql_fetch_array($result2))
				{
					if($row2['AnalyzeTime']<>''){
					$value1="西曆 ".substr(trim($row2['AnalyzeTime']),0,4)." 年 ".substr(trim($row2['AnalyzeTime']),4,2)." 月 ".substr(trim($row2['AnalyzeTime']),6,2)." 日";}
					$value2=trim($row2['AnalyzeTime']);
					if(trim($row2['Operator'])<>''){$Operator=$row2['Operator'];}
					if(trim($row2['Tester'])<>''){$Tester=$row2['Tester'];}
					if(trim($row2['SampleNo'])<>''){$SampleNo=$row2['SampleNo'];}
					if(trim($row2['B'])<>''){$B=trim($row2['B']);}
					if(trim($row2['In'])<>''){$In=trim($row2['In']);}
					if(trim($row2['Pt'])<>''){$Pt=trim($row2['Pt']);}
					if(trim($row2['Au'])<>''){$Au=trim($row2['Au']);}
					if(trim($row2['Si'])<>''){$Si=trim($row2['Si']);}
					if(trim($row2['Se'])<>''){$Se=trim($row2['Se']);}
					if(trim($row2['Hg'])<>''){$Hg=trim($row2['Hg']);}
					if(trim($row2['Pd'])<>''){$Pd=trim($row2['Pd']);}
					if(trim($row2['P'])<>''){$P=trim($row2['P']);}

				}//while
				
			}//group while
		}//for j
	}//while lot
	$tname='TLEQA9110101A';
	require_once ('../PHPWord/PHPWord.php');
	$PHPWord = new PHPWord();
	$document = $PHPWord->loadTemplate('../FormList/PrintAnalyze/'.$_GET['pdd_chemical'].'/'.$tname.'.docx');
	$document->setValue("TestDate0",$value1);
	$document->setValue("Operator0",$Operator);
	$document->setValue("SampleNo0",$SampleNo);	
	$document->setValue("Tester0",$Tester);	
	$document->setValue("LotNo0", $_GET['lot_no']);
	$document->setValue("pdd_prod_name", $_GET['pdd_chemical']);
	$document->setValue("B0", $B);
	$document->setValue("In0", $In);
	$document->setValue("Pt0", $Pt);
	$document->setValue("Au0", $Au);
	$document->setValue("Si0", $Si);
	$document->setValue("Se0", $Se);
	$document->setValue("Hg0", $Hg);
	$document->setValue("Pd0", $Pd);
	$document->setValue("P0", $P);
	$na=new analyze_first;
	$na->lot_no=$_GET['lot_no'];
	$na->create_time=$value2;
	$na->get();
	
	$document->setValue("first0",get_uname($na->first));



//	$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

	$document->save('../tmp2/'.$tname.'.docx');


echo '<script>document.location.href="http://'.$_SERVER['HTTP_HOST'].'/tmp2/'.$tname.'.docx";</script>';
}


?>
