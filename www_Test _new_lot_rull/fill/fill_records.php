<?php   ///last edit by jtsai 2018-03-09 
session_start();
unset($_SESSION['urln1']);
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
require_once "../PHPEXCEL/Classes/PHPExcel.php";
require_once "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
datepick();
$path_root=$_SERVER['HTTP_HOST'];


?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form action="" method="post" name="form1" id="form1">
  <table width="1240"  border="0">
    <tr>充填生產作業記錄總表  &nbsp;&nbsp;&nbsp;&nbsp; </tr>
    <tr>
      <td width="450" bgcolor="#FFFFFF" class="d1">充填日期 :&nbsp;
        <input name="datepicker1" type="text" id="datepicker1" size="8" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}else{echo $_SESSION['datepicker1']=date("m/d/Y");}?>" onChange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="8" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}else{echo $_SESSION['datepicker2']=date("m/d/Y");}?>" onChange="set_date_session(this.name,this.value)">
        Lot NO：&nbsp;
        <input type="text" name="lid" id="lid" onchange="set_date_session(this.name,this.value)" value="<?php echo $_SESSION['lid'];?>" size="10"/></td>
      <td width="400" class="d1">藥品：
        <input type="button" name="X" id="X" value="X" onclick="window.open('../erase_prod.php ', '_self');" />
        <input name="pid" type="text" id="pid" size="10" value="<?php echo $_SESSION['pid']?>" readonly="readonly" />
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onclick="window.open('../pdd_prod_no.php ', '_self');" />
      <input name="prod_name" type="text" id="prod_name" size="14" value="<?php echo $_SESSION['prod_name']?>" readonly="readonly" /></td>
      <td width="400" class="centerutton"><span class="d1">
        <input name="submit" type="submit" class="centerutton" id="submit" value="查    詢" />
        <input type="submit" name="excel" id="excel2" value="excel" />
      </span></td>
    </tr>
    <tr>
      <td width="250" class="d1">出荷預定日:
        <input name="datepicker3" type="text" id="datepicker3" size="10" value="<?php echo $_SESSION['datepicker3'];?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker4" type="text" id="datepicker4" size="10" value="<?php echo $_SESSION['datepicker4'];?>" onChange="set_date_session(this.name,this.value)">
隱藏客戶欄位:
        <input name="cust" type="radio" value="1"></td>
      <td width="5" class="d1">客戶：
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_customer.php ', '_self');" />
        <input name="cid" type="text" id="cid" size="10" value="<?php echo $_SESSION['cid']?>" readonly="readonly" />
        <input type="button" name="pdd_no2" id="pdd_no2" value="查詢客戶" onclick="window.open('../cust_no.php?sup=N ', '_self');" />
        <input name="cust_name" type="text" id="cust_name" size="14" value="<?php echo $_SESSION['cust_name']?>" /></td>
      <td width="100" class="centerutton"><span class="d1">
      	<input name="webexcel" type="submit" class="centerutton" id="webexcel" value="產生頁面Excel檔案" />
        <input name="ax_submit" type="submit" class="centerutton" id="ax_submit" value="產生 AX 匯出檔" />
        <input name="ax_submit2" type="submit" class="centerutton" id="ax_submit2" value="匯出到 AX " />
      </span></td>
    </tr>
  </table>
<script type="text/javascript" src="/css/jquery.min.js"></script>
    <script type="text/javascript" src="/css/jquery-ui.min.js"></script>
    <script src="/css/datepicker-zh-TW.js"></script>
    <script type="text/javascript" src="/css3menu/GridViewScroll/gridviewScroll.min.js"></script>
    <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
        <script type="text/javascript">
	    $(document).ready(function () {
	        gridviewScroll();
	    });
	
	    function gridviewScroll() {
	        gridView1 = $('#GridView1').gridviewScroll({
                width: 1260,
                height: 520,
                varrowtopimg: "/css3menu/GridViewScroll/Images/arrowvt.png",
                varrowbottomimg: "/css3menu/GridViewScroll/Images/arrowvb.png",
                harrowleftimg: "/css3menu/GridViewScroll/Images/arrowhl.png",
                harrowrightimg: "/css3menu/GridViewScroll/Images/arrowhr.png",
            });
	    }
</script>
<?php
if($_POST['cust']!='1'){$CUST='<td>客戶名</td>';}
echo '<table id="GridView1" border="1" width="2200">';
echo '<tr class="GridviewScrollHeader"><td>藥品名</td>'.$CUST.'<td>荷姿 NO</td><td>Idle天數</td><td>LOT NO</td><td>SN</td><td>指示日期</td><td>充填日期</td><td>開始時間</td><td>結束時間</td><td>實際出庫量</td><td><input type="submit" size="4" name="remnant"  style="background-color:white" value="充填殘單"></td><td><input type="submit" size="4"  name="tys_qty"  style="background-color:#FF0" value="充填滿單"></td><td>充填來源</td><td>充填路徑</td><td>來源LOT NO</td><td>充填手</td><td>保壓測試</td><td>流量</td><td>取樣數</td><td>送出數</td>
		<td>送出時間</td><td>取得時間</td><td>取得數量</td><td>完成時間</td><td>判定</td><td>預計出荷日期</td><td>實際出荷時間</td><td>回廠時間</td></tr>';
	
	$query="SELECT DISTINCT 
                            FOD.PDD_PROD_NO AS PNO, PDD.PDD_TYPE AS PTYPE, PDD.PDD_PROD_NAME AS F1, 
                            CTD.CTD_CUST_SHORT_NAME AS F2, FOD.FDM_LY_NO AS F3, FOD.FDM_LOT_NO AS F4, 
                            LEFT(FOD.FDM_EXPECT_DATE, 8) AS F5, LEFT(FID.FID_FILL_BEGIN_DATE, 8) AS F6, 
                            RIGHT(FID.FID_FILL_BEGIN_DATE, 6) AS F7, RIGHT(FID.FID_FILL_END_DATE, 6) AS F8, 
                            LFC.LFC_B_REMAIN_QTY AS F9, FOD.FDM_QTY AS F10, '< 1.9kg/cm2 10分鐘' AS F11, QC.TestDate AS F12, 
                            LEFT(FOD.FDM_OUT_DATE, 8) AS F13, FOD.FDM_REAL_OUT_TIME AS F14, FOD.FDM_REAL_RETURN_TIME AS F15, 
                            CTD.CTD_CUST_NO AS CUSTACCOUNT, FID.FID_FILL_BEGIN_DATE, PDD.PDD_UNIT AS UNITP, 
                            PDD.PDD_LITER_KG AS LKG, FOD.LY_SN AS lysn, FID.FID_OPERATOR AS fill_operator1, FILLREC_AX.fill_left as AL, 
                            FILLREC_AX.fill_full as AF, LFC.LFC_F_FLOW as FF, LFC.LFC_FILLER AS fill_operator
FROM              FILLPLAN_OUT_DECIDE AS FOD LEFT OUTER JOIN
                            FILLREC_AX ON FOD.FDM_LOT_NO = FILLREC_AX.LotNo LEFT OUTER JOIN
                            PRODUCT_DATA AS PDD ON FOD.PDD_PROD_NO = PDD.PDD_PROD_NO LEFT OUTER JOIN
                            CUSTOMER_DATA AS CTD ON FOD.CTD_CUST_NO = CTD.CTD_CUST_NO LEFT OUTER JOIN
                            FILL_INDICATE AS FID ON FOD.FDM_LOT_NO = FID.FDM_LOT_NO LEFT OUTER JOIN
                            LORRY_FILL_CHECK AS LFC ON FOD.FDM_LOT_NO = LFC.FDM_LOT_NO LEFT OUTER JOIN
                                (SELECT          LotNo, MAX(TestDate) AS TestDate
                                  FROM               QC_LotData
                                  GROUP BY    LotNo) AS QC ON FOD.FDM_LOT_NO = QC.LotNo  
			WHERE          (FOD.FDM_LOT_NO <> '' )	";
			
	if($_SESSION['lid']<>''){$query.= "AND (FOD.FDM_LOT_NO like '%".$_SESSION['lid']."%') ";}	
	else{
		if($_SESSION['datepicker1']<>''){$query.= "AND (LEFT(FOD.FDM_EXPECT_DATE, 8) >= '".dod($_SESSION['datepicker1'])."') ";}
		if($_SESSION['datepicker2']<>''){$query.= "AND (LEFT(FOD.FDM_EXPECT_DATE, 8) <= '".dod($_SESSION['datepicker2'])."') ";}
		if($_SESSION['datepicker3']<>''){$query.= "AND (LEFT(FOD.FDM_REAL_OUT_TIME, 8) >= '".dod($_SESSION['datepicker3'])."') ";}
		if($_SESSION['datepicker4']<>''){$query.= "AND (LEFT(FOD.FDM_REAL_OUT_TIME, 8) <= '".dod($_SESSION['datepicker4'])."') ";}
		if($_SESSION['pid']<>''){$query.= "AND (FOD.PDD_PROD_NO = '".$_SESSION['pid']."') ";}
		if($_SESSION['cid']<>''){$query.= "AND (FOD.CTD_CUST_NO = '".$_SESSION['cid']."') ";}	
	}
	$query.="order by PTYPE, PNO ";
//	echo $query."<BR>";
	$j=0;
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{   
//		echo "AL=". $row['AL']; 
		if(trim($row['fill_operator'])==''){
			$row['fill_operator']=$row['fill_operator1'];
		}
		$lst=new last_LEL;
		$lst->lotno=trim($row['F4']);
		$lst->run();
		$row['TYSQTY1']=$lst->tys_rem;
		$row['TYSQTY']=$lst->tysqty;
		if($row['TYSQTY1']=='' and $row['TYSQTY']=='' and (trim($row['AL'])!='' or trim($row['AF'])!='')){$row['TYSQTY1']=$row['AL'];$row['TYSQTY']=$row['AF'];}
		$pra_out_count=$lst->out_count;
		$source_lot=chk_source($row['F4']);
		if($row['lysn']<>''){
			$tysqty1='<input type="text" size="5" name="remnant'.$j.'" value="0" readonly="readonly">';
			$row['TYSQTY1']=0;
			$row['TYSQTY']=$row['AF']-$row['AL'];
			$tysqty='<input size="5" type="text" name="tysqty'.$j.'"value="'.$row['TYSQTY'].'" readonly="readonly">';
		}
		else{
			$tysqty1='<input type="text" size="5" name="remnant'.$j.'" value="'.$row['TYSQTY1'].'">';
			if($row['AF']<>''){$tysqty='<input size="5" type="text" name="tysqty'.$j.'"value="'.$row['TYSQTY'].'">';}
			else{ $tysqty='<input type="text" size="5" name="tysqty'.$j.'" value="'.$row['TYSQTY'].'">';}
		}
//		$tysqty1='<input type="text" size="5" name="remnant'.$j.'" value="'.$row['TYSQTY1'].'">';
//		if($row['AF']<>''){$tysqty='<input size="5" type="text" name="tysqty'.$j.'"value="'.$row['TYSQTY'].'">';}
//		else{ $tysqty='<input type="text" size="5" name="tysqty'.$j.'" value="'.$row['TYSQTY'].'">';}
		echo '<input type="text" name="lot_no'.$j.'" hidden="hidden" value="'.$row['F4'].'">';
		
		if($row['PTYPE']<>'LY'){$tysqty=$tysqty1='';}
		/*
		$tysqty1='<input type="text" size="5" name="remnant'.$j.'" value="'.$row['F9'].'" disabled="disabled">';
		$tysqty='<input type="text" size="5" name="tysqty'.$j.'" value="'.LEL_remnant($row['F4']).'"  disabled="disabled">';
		if(LEL_remnant($row['F4'])!=''){
		$row['TYSQTY']=LEL_remnant($row['F4']);}
		else{$row['TYSQTY']=0;}
		*/
		if($row['F1']=='CAL3'){
			$tysqty1='<input type="text" size="5" name="remnant'.$j.'" value="0">';
			$tysqty='<input type="text" size="5" name="tysqty'.$j.'" value="'.round($row['F10'],0).'">';
			$row['TYSQTY']=round($row['F10'],0);
		}
		
		$Value1=$_POST['remnant'.$j];
		$Value2=$_POST['tysqty'.$j];
		$web[$j][1]=$row['PNO'];
		$web[$j][2]=$row['F1']; //品名
		$web[$j][3]=$row['F2']; //客戶
		$web[$j][4]=$row['F3'];// 荷滋
		$web[$j][5]=$row['F4']; //LOTNO
		$web[$j][6]=$row['lysn'];// SN
		$web[$j][0]=stab($row['F5']); //只是日期
		$web[$j][9]=stab($row['F6']);//充填日期
		$web[$j][10]=substr($row['F7'],0,2).":".substr($row['F7'],2,2).":".substr($row['F7'],4,2); //開始時間
		$web[$j][11]=substr($row['F8'],0,2).":".substr($row['F8'],2,2).":".substr($row['F8'],4,2);//結束時間
		$web[$j][12]=($row['AF']-$row['F9']); //實際出庫輛
		$web[$j][7]=$row['TYSQTY1'];// 充填殘單
		$web[$j][8]=$row['TYSQTY'];//充填滿單
		$web[$j][13]=$row['充填作業.充填來源TANK']; //充填來源
		$web[$j][14]=fill_flow1($row['F4']); //充填路徑
		$web[$j][15]=chk_source($row['F4']); //來源LOTNO
		$web[$j][16]=get_uname($row['fill_operator']); //充填手
		$web[$j][17]=$row['F11']; //保壓測試
		$web[$j][18]=$row['取樣作業.取樣數']; //取樣數
		$web[$j][19]=$row['取樣作業.送出數']; //送出數
		$web[$j][20]=$row['取樣作業.送出時間'];//送出時間
		$web[$j][21]=$row['分析作業.取得時間'];//取得時間
		$web[$j][22]=$row['分析作業.取得數量'];//取得數量
		$web[$j][23]=$row['F12'];//完成時間
		$web[$j][24]=$row['分析作業.判定'];//判定
		$web[$j][25]=stab($row['F13']);//預計出和日期
		$web[$j][26]=stab($row['F14']);//時祭出和時間
		$web[$j][27]=$row['F15'];//回廠時間
		$web[$j][28]=$row['FF'];//流量
		
		// 檢驗TSMC  LORRY是否超過30日
	//	echo 	"F5:".ddd($row['F5'])."<BR>";
	$lorryno=$row['F3'];
	if(trim($row['F3'])<>""){
		$dirdate=trim($row['F5']);
		if(trim($row['F6'])==""){
			$isfilled=0;
		}
		else{
			$isfilled=1;
		}
		if (strpos(($row['F2']), "台積") !== false) {
			$aa=diff_days($row['F4'],$lorryno);
		}
		else{
			$aa="";
		}
	//	$aa=diff_days($dirdate,$lorryno,$isfilled);
		if($aa>30){
			$aa='<font color="red">>'.$aa.'天</font>';
		}
	}
	else{
		$aa="";
	}
	//echo $aa;
		
		if($_POST['cust']!='1'){$CUST1='<td>'.$row['F2'].'</td>';}
		echo '<tr class="GridviewScrollItem">
		<td>'.$row['F1'].'</td>
		'.$CUST1.'
		<td>'.$row['F3'].'</td>
		<td>'.$aa.'</td>
		<td>'.$row['F4'].'</td>
		<td>'.$row['lysn'].'</td>
		<td>'.$row['F5'].'</td>
		<td>'.$row['F6'].'</td>
		<td>'.substr($row['F7'],0,2).":".substr($row['F7'],2,2).":".substr($row['F7'],4,2).'</td>
		<td>'.substr($row['F8'],0,2).":".substr($row['F8'],2,2).":".substr($row['F8'],4,2).'</td>
		<td>'.round($row['TYSQTY']-$row['TYSQTY1']).'</td>
		<td align="center">'.$tysqty1.'</td>
		<td align="center">'.$tysqty.'</td>
		<td>'.$row['充填作業.充填來源TANK'].'</td>
		<td>'.fill_flow($row['F4']).'</td>
		<td>'.fill_source($row['F4']).'</td>
		<td>'.get_uname($row['fill_operator']).'</td>
		<td>'.$row['F11'].'</td>
		<td>'.$row['FF'].'</td>
		<td>'.$row['取樣作業.取樣數'].'</td>
		<td>'.$row['取樣作業.送出數'].'</td>
		<td>'.$row['取樣作業.送出時間'].'</td>
		<td>'.$row['分析作業.取得時間'].'</td>
		<td>'.$row['分析作業.取得數量'].'</td>
		<td>'.$row['F12'].'</td>
		<td>'.$row['分析作業.判定'].'</td>
		<td>'.$row['F13'].'</td>
		<td>'.$row['F14'].'</td>
		<td>'.$row['F15'].'</td>
		</tr>';
		
		if(trim($row['TYSQTY1'])<>'' and trim($row['TYSQTY'])<>''){$qty=$row['TYSQTY']-$row['TYSQTY1'];}
		elseif(trim($row['TYSQTY1'])=='' and trim($row['TYSQTY'])<>''){$qty=$row['TYSQTY']-$row['TYSQTY1'];}
		elseif(trim($row['TYSQTY1'])==0 and trim($row['TYSQTY'])<>''){$qty=$row['TYSQTY']-$row['TYSQTY1'];}
		if(trim($row['TYSQTY1'])=='' and trim($row['TYSQTY'])=='' and trim($row['AL'])=='' and trim($row['AF'])==''){$qty=0;}
		
		if($row['PTYPE']=='DM' or $row['PTYPE']=='BTL' or $row['PTYPE']=='TOTO'){$qty=$row['F10'];}
		echo '<input type="hidden" name="CUSTACCOUNT'.$j.'" value="'.$row['CUSTACCOUNT'].'"/>';
		echo '<input type="hidden" name="INVENTBATCHID'.$j.'" value="'.$row['F4'].'"/>';
		echo '<input type="hidden" name="ITEMID'.$j.'" value="'.$row['PNO'].'"/>';
		echo '<input type="hidden" name="LORRYNO'.$j.'" value="'.$row['F3'].'"/>';
		echo '<input type="hidden" name="QTYSCHED'.$j.'" value="'.$qty.'"/>';
		echo '<input type="hidden" name="SCHEDEND'.$j.'" value="'.stabb($row['F5']).'"/>';
		echo '<input type="hidden" name="UNIT01'.$j.'" value="'.$row['UNITP'].'"/>';
		echo '<input type="hidden" name="PTYPE'.$j.'" value="'.$row['PTYPE'].'"/>';
		echo '<input type="hidden" name="qtyfill'.$j.'" value="'.round($row['F10'],2).'"/>';
		echo '<input type="hidden" name="lkg'.$j.'" value="'.$row['LKG'].'"/>';
		echo '<input type="hidden" name="lysn'.$j.'" value="'.$row['lysn'].'"/>';
		echo '<input type="hidden" name="pra_out_count'.$j.'" value="'.$pra_out_count.'"/>';
		$PDD=new PDD_MADE_FROM;
		$PDD->pid_target=$row['PNO'];
		$PDD->cx();
//		$source_lot=chk_source($row['F4']);
		echo '<input type="hidden" name="BOMINVENTBATCHID'.$j.'" value="'.trim($source_lot).'"/>';
		echo '<input type="hidden" name="WEIGHTS'.$j.'" value="'.trim($PDD->weight).'"/>';
		echo '<input type="hidden" name="BOMITEMID'.$j.'" value="'.trim($PDD->pid_from).'"/>';
		$j++;
	}
echo '<input type="text" hidden="hidden" name="CNT" value="'.$j.'"> ';
echo '</table>';
echo '</form>';
if(isset($_POST['webexcel'])){
	$objPHPExcel = new PHPExcel();
	$fl="webexcel.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$obj=$objPHPExcel->setActiveSheetIndex(0);
	$XX= $_POST['CNT'];$I=2;
	for($n=0;$n < $XX;$n++)		
	{
		$ENG='A';
		for($i=0;$i<=26;$i++){
		
		if($ENG=='I' and trim($web[$n][3])=='')
		{
			$query="SELECT * FROM FILLPLAN_OUT_DECIDE FD left join PRODUCT_DATA PA on FD.PDD_PROD_NO=PA.PDD_PROD_NO where FDM_LOT_NO='".($web[$n][5])."'";
			$result=mssql_query($query);
			while($row=mssql_fetch_array($result))
			{
				$qty=trim($row['FDM_QTY']);
				$LITER=trim($row['PDD_LITER_KG']);
				if($row['FDM_QTY_UNIT']=='L'){
				$qty=$qty/$LITER;}
				$qty=round(trim($qty));
			}
		}
		if($qty!='' and $ENG=='I')
		{
			$obj->setCellValue($ENG.$I,iconv('big5','utf-8',trim($qty)));

		}
		else{
		$obj->setCellValue($ENG.$I,iconv('big5','utf-8',trim($web[$n][$i])));}
		$qty='';
		$ENG++;
		}
		$I++;
	}
	$f2='webexcel_1';
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($f2.".xlsx");
	echo '<script>document.location.href="http://'.$path_root."/fill/".$f2.'.xlsx";</script>';		
}
if(isset($_POST['ax_submit2'])){
	$XX= $_POST['CNT'];
	for($n=0;$n<$XX;$n++){
		include("../connections/ax_connections.php");
		$query="select MAX(RECID) as nummax from TYS_PRODTABLETEMP";
		$_SESSION['step']=1;
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$nummax=$row[0]+1;
		if($_POST['PTYPE'.$n]=='DM' or $_POST['PTYPE'.$n]=='BTL'){$qtyfill=$_POST['qtyfill'.$n];$_SESSION['step']=2;}
		else{$qtyfill=$_POST['QTYSCHED'.$n];$_SESSION['step']=3;}

		if($_POST['UNIT01'.$n]=='L'){   /// 藥品單位為公升的
			$qtyfill=round($qtyfill/$_POST['lkg'.$n],0);
		}
//		echo "LKG:".$_POST['lkg'.$n]."<BR>";
		if($_POST['QTYSCHED'.$n]<>0 or $_POST['PTYPE'.$n]=='DM' or $_POST['PTYPE'.$n]=='BTL'){
			$_POST['QTYSCHED'.$n]=$qtyfill;		
			if($_POST['QTYSCHED'.$n]>0  or $_POST['PTYPE'.$n]=='DM' or $_POST['PTYPE'.$n]=='BTL'){
				$query="select count(*) from TYS_PRODTABLETEMP where INVENTBATCHID='".substr(trim($_POST['INVENTBATCHID'.$n]),0,-4)."'";	
				$result=mssql_query($query);
				$row=mssql_fetch_row($result);
				if(trim($_POST['WEIGHTS'.$n])==''){$_POST['WEIGHTS'.$n]=0;}
				if($row[0]>0){  // update 
					$query="update TYS_PRODTABLETEMP set BOMINVENTBATCHID='".$_POST['BOMINVENTBATCHID'.$n]."' , BOMITEMID='".$_POST['BOMITEMID'.$n]."',WEIGHTS=".$_POST['WEIGHTS'.$n].",CUSTACCOUNT='".$_POST['CUSTACCOUNT'.$n]."', INVENTBATCHID='".substr(trim($_POST['INVENTBATCHID'.$n]),0,-4)."', ITEMID='".$_POST['ITEMID'.$n]."',LORRYNO='".$_POST['LORRYNO'.$n]."',QTYSCHED='".$qtyfill."',SCHEDEND='".$_POST['SCHEDEND'.$n]."',UNIT01='".$_POST['UNIT01'.$n]."' where INVENTBATCHID='".substr(trim($_POST['INVENTBATCHID'.$n]),0,-4)."'";
//				 	echo $query."<BR>";
					echo "更新  ".$_POST['INVENTBATCHID'.$n]."<BR>";
					$result=mssql_query($query);
				}
				else{
					$query="insert into  TYS_PRODTABLETEMP (BOMINVENTBATCHID, BOMITEMID, WEIGHTS, DATAAREAID, RECID, CUSTACCOUNT,INVENTBATCHID,ITEMID,LORRYNO,QTYSCHED,SCHEDEND,UNIT01) values ('".$_POST['BOMINVENTBATCHID'.$n]."', '".$_POST['BOMITEMID'.$n]."',".$_POST['WEIGHTS'.$n].",'tys', ".$nummax.",'".$_POST['CUSTACCOUNT'.$n]."', '".substr(trim($_POST['INVENTBATCHID'.$n]),0,-4)."', '".$_POST['ITEMID'.$n]."','".$_POST['LORRYNO'.$n]."','".$_POST['QTYSCHED'.$n]."','".$_POST['SCHEDEND'.$n]."','".$_POST['UNIT01'.$n]."') ";
//			 		echo $query."<BR>";
					echo "新增  ".$_POST['INVENTBATCHID'.$n]."<BR>";
					$result=mssql_query($query);
				}
			}
		}
	}
	break;
}




if(isset($_POST['ax_submit'])){
	$objPHPExcel = new PHPExcel();
	$fl="daily_list.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$obj=$objPHPExcel->setActiveSheetIndex(0);
	$obj
							->setCellValue("d5",iconv('big5','utf-8',$date_duration))
							->setCellValue("h3",iconv('big5','utf-8',$h3));
							
	$i=2;
	$XX= $_POST['CNT'];
	for($n=0;$n<$XX;$n++)		
	{   
		$PDD1=new PDD_MADE_FROM;
		$PDD1->pid_target=$_POST['ITEMID'.$n];
		$PDD1->cx();
		$source_lot=chk_source($_POST['INVENTBATCHID'.$n]);
//		$source_lot=chk_source($row['F4']);
		echo '<input type="hidden" name="BOMINVENTBATCHID'.$j.'" value="'.trim($source_lot).'"/>';
		echo '<input type="hidden" name="WEIGHTS'.$j.'" value="'.trim($PDD->weight).'"/>';
		echo '<input type="hidden" name="BOMITEMID'.$j.'" value="'.trim($PDD->pid_from).'"/>';
		$c1=$_POST['ITEMID'.$n];
		
		$qtyfill=$_POST['QTYSCHED'.$n];
		
		$pdd_drum=fill_drum($c1);
		if($_POST['UNIT01'.$n]=='L')/// 藥品單位為公升的
		{   
			echo $_POST['QTYSCHED'.$n].":".$_POST['lkg'.$n]."<BR>";
				$qtyfill=round($_POST['QTYSCHED'.$n]/$_POST['lkg'.$n]);			
		}
		
		if(($_POST['PTYPE'.$n])<>'LY'){
			$c2=substr(trim($_POST['INVENTBATCHID'.$n]),0,-4);
		}
		else{
			$c2=$_POST['INVENTBATCHID'.$n];
		}
			$c5=round($qtyfill,0);
			$c3=$_POST['LORRYNO'.$n];
			//if($pdd_drum!='' or $c1=='UP003-000' or $c1=='P002-000'){$c6='L';}else{$c6='KG';}
			$c6=$_POST['UNIT01'.$n];
			$c9=$_POST['CUSTACCOUNT'.$n];
			$dd=$_POST['SCHEDEND'.$n];
/*		echo "LOTNO:".$c2."<BR>";
		echo "QTY:".$qtyfill."<BR>";
		echo "QQ:".$c6."<BR>";
		echo "LKG:".$_POST['lkg'.$n]."<BR><BR>";
*/
		//		echo $row['LEL_TYS_QTY'],$row['LEL_TYS_REMNANT_QTY'], $c5;
		
		if(trim($_POST['PTYPE'.$n])=='LY'){
			$obj
								->setCellValue("A".$i,iconv('big5','utf-8',$c1))
								->setCellValue("B".$i,iconv('big5','utf-8',$c2))
								->setCellValue("C".$i,iconv('big5','utf-8',$c3))
								->setCellValue("D".$i,iconv('big5','utf-8',$dd))
								->setCellValue("E".$i,iconv('big5','utf-8',$c5))
								->setCellValue("F".$i,iconv('big5','utf-8',$c6))
								->setCellValue("G".$i,iconv('big5','utf-8',$PDD1->pid_from))
								->setCellValue("H".$i,iconv('big5','utf-8',$source_lot))
								->setCellValue("I".$i,iconv('big5','utf-8',$c9))
								->setCellValue("J".$i,iconv('big5','utf-8',$PDD1->weight))
			;
			$i=$i+1;
		}
		
		elseif((trim($_POST['PTYPE'.$n])=='DM' or trim($_POST['PTYPE'.$n])=='BTL')){
			$c5=$qtyfill;
			$dd=$_POST['SCHEDEND'.$n];
			$obj
								->setCellValue("A".$i,iconv('big5','utf-8',$c1))
								->setCellValue("B".$i,iconv('big5','utf-8',$c2))
								->setCellValue("C".$i,iconv('big5','utf-8',$c3))
								->setCellValue("D".$i,iconv('big5','utf-8',$dd))
								->setCellValue("E".$i,iconv('big5','utf-8',$c5))
								->setCellValue("F".$i,iconv('big5','utf-8',$c6))
								->setCellValue("G".$i,iconv('big5','utf-8',$PDD1->pid_from))
								->setCellValue("H".$i,iconv('big5','utf-8',$source_lot))
								->setCellValue("I".$i,iconv('big5','utf-8',$c9))
								->setCellValue("J".$i,iconv('big5','utf-8',$PDD1->weight))
			;
			$i=$i+1;
		}
		else{
			
		}
/*
			$dd=substr($row['F6'],0,4)."-".substr($row['F6'],4,2)."-".substr($row['F6'],6,2);
			$obj
								->setCellValue("A".$i,iconv('big5','utf-8',$c1))
								->setCellValue("B".$i,iconv('big5','utf-8',$c2))
								->setCellValue("C".$i,iconv('big5','utf-8',$c3))
								->setCellValue("D".$i,iconv('big5','utf-8',$dd))
								->setCellValue("E".$i,iconv('big5','utf-8',$c5))
								->setCellValue("F".$i,iconv('big5','utf-8',$c6))
								->setCellValue("G".$i,iconv('big5','utf-8',''))
								->setCellValue("H".$i,iconv('big5','utf-8',''))
								->setCellValue("I".$i,iconv('big5','utf-8',$c9))
			;
		$i=$i+1;
*/
	}
	$fileurl="./formlist/tmp1/tmp9";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
	echo '<script>document.location.href="http://'.$path_root."/fill/".$fileurl.'.xlsx";</script>';			
}





if(isset($_POST['excel']))
{
	$objPHPExcel = new PHPExcel();
	$fl="./formlist/fill_records.xlsx";
	$objPHPExcel = PHPExcel_IOFactory::load($fl);
	$obj=$objPHPExcel->setActiveSheetIndex(0);
	$obj
							->setCellValue("d5",iconv('big5','utf-8',$date_duration))
							->setCellValue("h3",iconv('big5','utf-8',$h3));
							
	$i=2;
	$query="SELECT DISTINCT PDD.PDD_PROD_NAME AS F1, CTD.CTD_CUST_SHORT_NAME AS [F2], 
                   FOD.FDM_LY_NO AS [F3], FOD.FDM_LOT_NO AS [F4], 
                   LEFT(FOD.FDM_EXPECT_DATE, 8) AS [F5], LEFT(FID.FID_FILL_BEGIN_DATE, 8) 
                   AS [F6], RIGHT(FID.FID_FILL_BEGIN_DATE, 6) AS [F7], RIGHT(FID.FID_FILL_END_DATE, 
                   6) AS [F8], EMP1.EMP_NAME AS [充填作業.作業者], LFC.LFC_B_REMAIN_QTY AS [F9], FOD.FDM_QTY AS [F10],
                   TNK.TANK_DESC AS [充填作業.充填來源TANK], '< 1.9kg/cm2 10分鐘' AS [F11], 
                   [AND].AND_TOTAL AS [取樣作業.取樣數], [AND].AND_OUT_QTY AS [取樣作業.送出數], 
                   [AND].AND_OUT_DATETIME AS [取樣作業.送出時間], [AND].AND_GET_DATETIME AS [分析作業.取得時間], 
                   [AND].AND_GET_QTY AS [分析作業.取得數量], QC.TestDate AS [F12], 
                   [AND].AND_RESULT AS [分析作業.判定], LEFT(FOD.FDM_OUT_DATE, 8) AS [F13], 
                   FOD.FDM_REAL_OUT_TIME AS [F14], FOD.FDM_REAL_RETURN_TIME AS [F15],
				   LEL.LEL_TYS_QTY AS TYSQTY, LEL.LEL_TYS_REMNANT_QTY AS TYSQTY1, PDD.PDD_TYPE AS PTYPE , LFC.LFC_F_FLOW as FF 
FROM      FILLPLAN_OUT_DECIDE AS FOD LEFT OUTER JOIN
                   PRODUCT_DATA AS PDD ON FOD.PDD_PROD_NO = PDD.PDD_PROD_NO LEFT OUTER JOIN
                   CUSTOMER_DATA AS CTD ON FOD.CTD_CUST_NO = CTD.CTD_CUST_NO LEFT OUTER JOIN
                   FILL_INDICATE AS FID ON FOD.FDM_LOT_NO = FID.FDM_LOT_NO LEFT OUTER JOIN
                   EMPLOYEE_DATA AS EMP1 ON FID.FID_OPERATOR = EMP1.EMP_NO LEFT OUTER JOIN
                   LORRY_FILL_CHECK AS LFC ON FOD.FDM_LOT_NO = LFC.FDM_LOT_NO LEFT OUTER JOIN
                   TANK_DATA AS TNK ON TNK.PDD_PROD_NO = FOD.PDD_PROD_NO AND 
                   TNK.TANK_KEY = LFC.LFC_TANK LEFT OUTER JOIN
                   LORRY_EXAMINE_LIST AS LEL ON FOD.FDM_LOT_NO = LEL.LEL_LOT_NO LEFT OUTER JOIN
                   AnalyzeDesign AS [AND] ON FOD.FDM_LOT_NO = [AND].AND_LOT_NO LEFT OUTER JOIN
                       (SELECT  LotNo, MAX(TestDate) AS TestDate
                        FROM       QC_LotData
                        GROUP BY LotNo) AS QC ON FOD.FDM_LOT_NO = QC.LotNo 
			WHERE          (LEL.PRA_OUT_COUNT IS NULL or LEL.PRA_OUT_COUNT<2)	";
	if($_POST['lid']<>''){$query.= "AND (FOD.FDM_LOT_NO like '%".$_POST['lid']."%') ";}	
	else{
	if($_POST['datepicker1']<>''){$query.= "AND ((LEFT(FID.FID_FILL_BEGIN_DATE, 8) >= '".dod($_POST['datepicker1'])."') or (LEFT(FOD.FDM_EXPECT_DATE, 8) >= '".dod($_POST['datepicker1'])."')) ";}
	if($_POST['datepicker2']<>''){$query.= "AND ((LEFT(FID.FID_FILL_BEGIN_DATE, 8) <= '".dod($_POST['datepicker2'])."') or (LEFT(FOD.FDM_EXPECT_DATE, 8) <= '".dod($_POST['datepicker2'])."')) ";}
	if($_POST['datepicker3']<>''){$query.= "AND (LEFT(FOD.FDM_REAL_OUT_TIME, 8) >= '".dod($_POST['datepicker3'])."')  ";}
	if($_POST['datepicker4']<>''){$query.= "AND (LEFT(FOD.FDM_REAL_OUT_TIME, 8) <= '".dod($_POST['datepicker4'])."') ";}
	if($_POST['pid']<>''){$query.= "AND (FOD.PDD_PROD_NO = '".$_POST['pid']."') ";}
	if($_POST['cid']<>''){$query.= "AND (FOD.CTD_CUST_NO = '".$_POST['cid']."') ";}	
	}
	$query.=" ORDER BY   [F4]";
	
//	$_SESSION['record']= $query.'<br>';
	
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
	while($row = mssql_fetch_array($result))
	{   
		if($row['PTYPE']<>'LY'){$lotno=substr(trim($row['F4']),0,-4);}
		else{$lotno=$row['F4'];}
		$obj
							->setCellValue("A".$i,iconv('big5','utf-8',$row['F1']))
							->setCellValue("B".$i,iconv('big5','utf-8',$row['F2']))
							->setCellValue("C".$i,iconv('big5','utf-8',$row['F3']))
							->setCellValue("D".$i,iconv('big5','utf-8',$lotno))
							->setCellValue("E".$i,iconv('big5','utf-8',substr($row['F5'],0,4)."-".substr($row['F5'],4,2)."-".substr($row['F5'],6,2)))
							->setCellValue("F".$i,iconv('big5','utf-8',substr($row['F6'],0,4)."-".substr($row['F6'],4,2)."-".substr($row['F6'],6,2)))
							->setCellValue("G".$i,iconv('big5','utf-8',substr($row['F7'],0,2).":".substr($row['F7'],2,2).":".substr($row['F7'],4,2)))
							->setCellValue("H".$i,iconv('big5','utf-8',substr($row['F8'],0,2).":".substr($row['F8'],2,2).":".substr($row['F8'],4,2)))
							->setCellValue("I".$i,iconv('big5','utf-8',$row['充填作業.作業者']))
							->setCellValue("j".$i,iconv('big5','utf-8',$row['F9']))
							->setCellValue("k".$i,iconv('big5','utf-8',$row['F10']))
							->setCellValue("l".$i,iconv('big5','utf-8',LEL_remnant($row['F4'])))
							->setCellValue("m".$i,iconv('big5','utf-8',$row['充填作業.充填來源TANK']))
							->setCellValue("n".$i,iconv('big5','utf-8',fill_flow1($row['F4'])))
							->setCellValue("o".$i,iconv('big5','utf-8',$row['F11']))
							->setCellValue("P".$i,iconv('big5','utf-8',$row['FF']))
							->setCellValue("q".$i,iconv('big5','utf-8',$row['取樣作業.取樣數']))
							->setCellValue("r".$i,iconv('big5','utf-8',$row['取樣作業.送出數']))
							->setCellValue("s".$i,iconv('big5','utf-8',$row['取樣作業.送出時間']))
							->setCellValue("x".$i,iconv('big5','utf-8',$row['分析作業.取得時間']))
							->setCellValue("u".$i,iconv('big5','utf-8',$row['分析作業.取得數量']))
							->setCellValue("v".$i,iconv('big5','utf-8',$row['F12']))
							->setCellValue("w".$i,iconv('big5','utf-8',$row['分析作業.判定']))
							->setCellValue("x".$i,iconv('big5','utf-8',$row['F13']))
							->setCellValue("y".$i,iconv('big5','utf-8',$row['F14']))
							->setCellValue("z".$i,iconv('big5','utf-8',$row['F15']))
							;
		$i=$i+1;
	}
	$fileurl="./formlist/tmp1/tmp1";
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$objWriter->save($fileurl.".xlsx");
	echo '<script>document.location.href="http://'.$path_root."/fill/".$fileurl.'.xlsx";</script>';			
}
?>
</body>
</html>
<?php
function fill_flow($lotno){
	$query="SELECT	Fill_Flow_Chart.flow FROM Fill_Flow_Chart WHERE (Lot_No = N'".$lotno."') order by date desc";
	$url="edit_flow.php?lot_no=".$lotno;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if(trim($row[0])==''){$a='編輯';}else{$a=$row[0];}
	return '<a target="_blank" href="'.$url.'">'.$a.'</a>';	
}

function fill_source($lotno){
	$query="SELECT  FID_SOURCE_LOT FROM FILL_INDICATE WHERE (FDM_LOT_NO = '".$lotno."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	$url="edit_fill_source.php?lot_no=".$lotno.'&fid_source='.$row[0];
	if(trim($row[0])==''){$a='編輯';}else{$a=$row[0];}
	return '<a target="_blank" href="'.$url.'">'.$a.'</a>';	
}


function fill_flow1($lotno){
	$query="SELECT top(1)	Fill_Flow_Chart.flow FROM Fill_Flow_Chart WHERE (Lot_No = N'".$lotno."') order by date desc";
//	echo "<BR>".$query."<BR>";
	$url="edit_flow.php?lot_no=".$lotno;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];	
}

if(isset($_POST['tys_qty']) or isset($_POST['remnant'])){
	$num=$_POST['CNT'];
	for($i=0;$i<$num;$i++)
	{
		$lotnox="lot_no".$i;
		$tysqtyx="tysqty".$i;
		$pra_out_cnt="pra_out_count".$i;
		if($_POST[$tysqtyx]<>''){ //數量非0才更新
		
			$query="INSERT INTO LEL_CHANGE_LOG (lid, uid, item, value, datetime) VALUES  (N'".$_POST[$lotnox]."', N'".$_SESSION['uid']."', N'LEL_TYS_QTY', N'".$_POST[$tysqtyx]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102))";
			$result1=mssql_query($query);
			
			$query="UPDATE  LORRY_EXAMINE_LIST SET LEL_TYS_QTY = ".$_POST[$tysqtyx].", LEL_TYS_QTY_MAN='".$_SESSION['uid']."' 
					WHERE LEL_LOT_NO='".$_POST[$lotnox]."' and PRA_OUT_COUNT=".$_POST[$pra_out_cnt];
			echo "<BR>0:".$query."<BR>";
			$result=mssql_query($query);
			
		}
	}	
	
	for($i=0;$i<$num;$i++)
	{
		$remnantx="remnant".$i;
		$lotnox="lot_no".$i;
		$pra_out_cnt="pra_out_count".$i;
		if($_POST[$remnantx]<>''){ //數量非0才更新
			$query="INSERT INTO LEL_CHANGE_LOG (lid, uid, item, value, datetime) VALUES  (N'".$_POST[$lotnox]."', N'".$_SESSION['uid']."', N'LEL_TYS_REMNANT_QTY', N'".$_POST[$remnantx]."', CONVERT(DATETIME, '".date("Y-m-d H:i:s")."', 102))";
			echo "<BR>1:".$query."<BR>";
			$result1=mssql_query($query);
			
			$query="UPDATE  LORRY_EXAMINE_LIST SET LEL_TYS_REMNANT_QTY = ".$_POST[$remnantx].",LEL_TYS_REMNANT_QTY_MAN='".$_SESSION['uid']."' 
					WHERE LEL_LOT_NO='".$_POST[$lotnox]."' and PRA_OUT_COUNT=".$_POST[$pra_out_cnt];
			echo "<BR>2:".$query."<BR>";
			$result=mssql_query($query);
		}
	}
  refresh();
}


function LEL_remnant($lot){
	$query="SELECT  LEL_FAKE_QTY - LEL_REMNANT_QTY AS Expr1
			FROM      LORRY_EXAMINE_LIST
			WHERE   (LEL_LOT_NO = '".trim($lot)."') order by PRA_OUT_COUNT desc ";
//	echo $query;
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];	
}

function chk_source($lid){
	$query="SELECT FID_SOURCE_LOT from FILL_INDICATE where FDM_LOT_NO='".$lid."'";	
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
	
}

function diff_days($lotno,$lorryno){

		$query="SELECT TOP (2)
    LEFT(OUT_DECISION.OPM_ETA_DATE, 8) AS 出荷日期,
    OUT_PRODUCT.OPD_LOT_NO
FROM
    CUSTOMER_DATA
    INNER JOIN OUT_DECISION
        ON CUSTOMER_DATA.CTD_CUST_NO = OUT_DECISION.CTD_CUST_NO
    INNER JOIN OUT_PRODUCT
        ON OUT_DECISION.OPM_ORDER_NO = OUT_PRODUCT.OPM_ORDER_NO
    INNER JOIN PRODUCT_DATA
        ON OUT_PRODUCT.PDD_PROD_NO = PRODUCT_DATA.PDD_PROD_NO
    LEFT OUTER JOIN (
        CUSTOMER_PRODUCTS
        INNER JOIN LORRY_EXAMINE_LIST
            ON CUSTOMER_PRODUCTS.PDD_PROD_NO = LORRY_EXAMINE_LIST.PDD_PROD_NO
    )
        ON OUT_DECISION.CTD_CUST_NO = CUSTOMER_PRODUCTS.CTD_CUST_NO
        AND OUT_PRODUCT.OPD_LOT_NO = LORRY_EXAMINE_LIST.LEL_LOT_NO
WHERE
    PRODUCT_DATA.PDD_TYPE <> 'DM'
    AND PRODUCT_DATA.PDD_PROD_SHORT_NAME + SUBSTRING(OUT_PRODUCT.OPD_LOT_NO, 8, 4) = '".$lorryno."'
    AND OUT_PRODUCT.OPD_LOT_NO <= '".$lotno."'
    AND LEFT(OUT_DECISION.OPM_ETA_DATE, 8) <= (
        SELECT TOP 1 LEFT(D2.OPM_ETA_DATE, 8)
        FROM OUT_DECISION D2
        INNER JOIN OUT_PRODUCT P2 ON D2.OPM_ORDER_NO = P2.OPM_ORDER_NO
        INNER JOIN PRODUCT_DATA PD2 ON P2.PDD_PROD_NO = PD2.PDD_PROD_NO
        WHERE
            PD2.PDD_TYPE <> 'DM'
            AND PD2.PDD_PROD_SHORT_NAME + SUBSTRING(P2.OPD_LOT_NO, 8, 4) = '".$lorryno."'
            AND P2.OPD_LOT_NO = '".$lotno."'
    )
ORDER BY 出荷日期 DESC ";
// echo $query."<BR>";
	$result=mssql_query($query);
	$k=1;
	while($row=mssql_fetch_array($result)){
		$aa[$k]=$row['出荷日期'];
		$k=$k+1;
	}
	$d1 = floor(abs(strtotime(ddd($aa[1])) - strtotime(ddd($aa[2]))) / 86400);
	return $d1;
}
function fod($lotno){
	$query="SELECT FOD_YEAR_MONTH, FOD_DAY, FDM_LY_NO FROM FILLPLAN_OUT_DECIDE WHERE (FDM_LOT_NO = '".$lotno."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return array($row[0],$row[1],$row[2]);
}

class last_LEL{
	public $out_count,$fake_out,$rem_qty,$lotno,$real_out,$tysqty,$tys_rem;	
	function run()
	{
		$query="SELECT top(1)  
                   PRA_OUT_COUNT , LEL_CUST_QTY, LEL_CUST_UNIT, LEL_CUST_QTY AS custqty, 
                   LEL_TYS_QTY AS tys_qty, LEL_TYS_REMNANT_QTY AS tys_rem, LEL_REMNANT_QTY AS rem_qty, 
                   LEL_FAKE_QTY AS fake_qty FROM      LORRY_EXAMINE_LIST WHERE   (LEL_LOT_NO  = '".$this->lotno."') ORDER BY PRA_OUT_COUNT desc";	
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
		$rows=mssql_fetch_row($result);
		$this->out_count=$rows[0];
		$this->fake_out=$rows[7];
		$this->rem_qty=$rows[6];
		$this->real_out=$rows[7]-$rows[6];
		$this->tysqty=$rows[4];
		$this->tys_rem=$rows[5];
	}
}