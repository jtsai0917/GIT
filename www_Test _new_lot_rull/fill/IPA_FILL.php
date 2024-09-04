<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<script>
document.onkeydown = function() {
if (window.event)
if (event.keyCode == 13 && event.srcElement.nodeName != "TEXTAREA" && event.srcElement.type != "submit")
event.keyCode = 9;
}
</script><?php
	session_start();
	include("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	datepick();
	if($n1==''){$n1='start';}
	if(trim($_SESSION['til'])==''){$_SESSION['til']="開 始 此 Lot NO. 作 業";$n1="start";}
	if(trim($_SESSION['lot_no1'])<>''){$_SESSION['til']="結 束 此 Lot NO. 作 業";$n1="end";}
	if($_SESSION['d1']==''){$_SESSION['d1']="disabled";}
	if($_SESSION['d2']==''){$_SESSION['d2']="disabled";}
	if($_SESSION['d3']==''){$_SESSION['d3']="disabled";}
	if($_SESSION['d4']==''){$_SESSION['d4']="disabled";}
	$query="SELECT IDC_B_CHK_1 FROM IPA_DRUM_CHECKING WHERE FDM_LOT_NO = '".trim($_SESSION['lot_no1'])."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]=='Y'){
		$_SESSION['d3']='enabled';	
		$_SESSION['d4']='enabled';	
	}
if(isset($_POST['start'])){
	$_SESSION['lot_no1']=$_POST['lot_no1'];
	$_SESSION['d1']='enabled';	
	$_SESSION['d2']='enabled';	
	$n1="end";
	$_SESSION['wash_status']='off';
	refresh();
}

if(isset($_POST['wash'])){
	$_SESSION['wash_status']='on';	
	$_SESSION['fill_status']='off';	
}

if(isset($_POST['fill'])){
	$_SESSION['fill_status']='on';	
	$_SESSION['wash_status']='off';	
}


if(isset($_POST['end'])){
	
	$_SESSION['d1']=$_SESSION['d2']=$_SESSION['d3']=$_SESSION['d4']=$_SESSION['lot_no1']=$_SESSION['til']='';	
	$_SESSION['til']="開 始 此 Lot NO. 作 業";$n1="start";
	$_SESSION['wash_status']='off';
	refresh();
}
?>
<font color="#990066" size="+3">IPA 充填/洗淨 作業</font>
<form name="form1" method="post" action="" onkeydown="if(event.keyCode==13)return false;">
  <table width="800" border="0">
    <tr>
      <td width="250"><font size="+2">Lot No. </font>
      <input type="text" style="font-size:16px" size="14" name="lot_no1" id="lid" value="<?php echo $_SESSION['lot_no1'];?>" ></td>
      <td width=""><input type="submit" name="<?php echo $n1;?>" style="font-size:16px" value=" <?php echo $_SESSION['til'];?> "></td>

    </tr>
    <tr>
      <td><font size="+2">充填日: <?php echo date("Ymd");?> </font></td>
      <td>&nbsp;</td>

    </tr>
    <tr>
      <td><font size="+2">客戶名稱：</font></td>
      <td><input type="submit" name="mete" style="font-size:16px" value="物料數量確認" <?php echo $_SESSION['d1'];?>><input type="submit" name="b_fill" style="font-size:16px" value="充填前確認" <?php echo $_SESSION['d2'];?>></td>

    </tr>
    <tr>
      <td><font size="+2">
	  <?php 
	  if(trim($_SESSION['lot_no1'])<>''){
	  // cust_name (1~8)  
	  	$aa=array();
	  	$a=$n=0;$i=1;
		$query="SELECT  B.CTD_CUST_NAME as A1, A.FDMC_DRUM_COUNT as A2 
				FROM      FILLPLAN_DRUM_CUSTOMER AS A LEFT OUTER JOIN CUSTOMER_DATA AS B ON A.CTD_CUST_NO = B.CTD_CUST_NO
				WHERE   (A.FDM_LOT_NO = '".trim($_SESSION['lot_no1'])."') ORDER BY A.FDMC_SERIAL_NO";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			echo $row['A1']."：(".$i."~".($i+$row['A2']-1).")桶<BR>";
			$aa[$a][1]=$i;
			$aa[$a][2]=$i+$row['A2']-1;
			$aa[$a][3]=$row['A1'];
			$n=$n+$row['A2'];
			$i=$i+$row['A2'];
			$a=$a+1;
		}
	  }
	  ?> 
      </font></td>
      <td><input type="submit" name="wash" style="font-size:16px" value="洗淨作業" <?php echo $_SESSION['d3'];?> >&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="fill" style="font-size:16px" value="充填作業" <?php echo $_SESSION['d4'];?>><BR><font size="+2">共計 <?php echo $n;?> 桶</font></td>
    </tr> 
  </table>

<?php
if(isset($_POST['mete'])){
	$_SESSION['wash_status']='off';
}
if(isset($_POST['fill'])){
	$_SESSION['wash_status']='off';
}
if(isset($_POST['b_fill'])){
	$_SESSION['wash_status']='off';
}
if(isset($_POST['mete'])){
	echo '<BR><font size="+2" color="#990066">物料數量確認</font><table width="600" border="1"><tr bgcolor="#999999"><td width="200" align="center"><font size="+2">品名</font></td><td width="200" align="center"><font size="+2">管理Cord</font></td><td width="200" align="center"><font size="+2">受入數量</font></td></tr>';
	echo '<tr><td width="200">Drum</td><td width="200">PS-200-DW-S綠色</td><td width="200" align="center"><input type="text" name="IDC_DRUM" style="font-size:16px" value="'.$n.'" ></td></tr>';
	echo '<tr><td width="200">液 Plug 大</td><td width="200">KP4003</td><td width="200" align="center"><input type="text" name="IDC_PLUG_B" value="'.$n.'" style="font-size:16px" ></td></tr>';
	echo '<tr><td width="200">液 Plug 小</td><td width="200">KP3002</td><td width="200" align="center"><input type="text" name="IDC_PLUG_S" value="'.$n.'" style="font-size:16px"> </td></tr>';
	echo '<tr><td width="200">氣體 Plug </td><td width="200">KP4002</td><td width="200" align="center"><input type="text" name="IDC_PLUG_A" value="'.$n.'" style="font-size:16px"></td></tr>';
	echo '<tr><td width="200">Tube</td><td width="200">SA510 810Lx15φ</td><td width="200" align="center">新：<input type="text" name="IDC_TUB_N" size="4" value="0" style="font-size:16px">舊：<input type="text" name="IDC_TUB_O" size="4" value="0" style="font-size:16px"></td></tr>';
	echo '<tr><td width="200">O-Ring</td><td width="200">DS2006 (1.9g)</td><td width="200" align="center"><input type="text" name="IDC_ORING" value="'.$n.'" style="font-size:16px"></td></tr>';
	echo '<tr><td width="200">Packing</td><td width="200">MA-1001 (2.1g)</td><td width="200" align="center"><input type="text" name="IDC_PACK" value="'.$n.'" style="font-size:16px"></td></tr>';
	echo '<tr><td width="200">保護蓋 (PE)</td><td width="200">SA051</td><td width="200" align="center"><input type="text" name="IDC_CAP_PE" value="'.$n.'" style="font-size:16px"></td></tr>';
	echo '<tr><td width="200">保護蓋 (ST)</td><td width="200"></td><td width="200" align="center"><input type="text" name="IDC_CAP_ST" value="'.$n.'" style="font-size:16px"></td></tr></table>';
	echo '<table width="600"><tr bgcolor="#999999"><td width="600" align="center"><input type="submit" name="mete_store" style="font-size:16px" value="                       儲存物料數量                       "></td></tr></table>';
	
}

if(((isset($_POST['wash']) or isset($_POST['save2']) or isset($_POST['save1']) or isset($_POST['input_lot']) or isset($_POST['input_drum_no']))  and (trim($_SESSION['wash_status'])<>'off')) or (trim($_SESSION['wash_status'])=='on')){
	$query="select * from IPA_DRUM_FILLDATA where FDM_LOT_NO='".$_SESSION['lot_no1']."' and IDF_DRUM_NO='".$_SESSION['drum_no1']."'";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	for($n=0;$n<$a;$n++){
		if($row[2]>=$aa[$n][1] and $row[2]<=$aa[$n][2]){$cust_name=$aa[$n][3];}
	}
	if(trim($row[5])=='Y'){$v1=get_uname($row[13]);}
	else{$v1='<input type="submit" value="儲存" name="save1">';}
	
	if($row[2]==1 and $row[16] == NULL ){$v2='<input type="submit" value="儲存" name="save2">';}
	elseif(trim($row[16])=='Y'){$v2=get_uname($row[17]);}
	
	if($row[18] == NULL ){$v3='<input type="submit" value="儲存" name="save3">';}
	elseif(trim($row[18])=='Y'){$v3=get_uname($row[21]);}
	
	if($row[18] == NULL ){$v3='<input type="submit" value="儲存" name="save3">';}
	elseif(trim($row[18])=='Y'){$v3=get_uname($row[21]);}
	
	echo '<font size="+2" color="#990066">洗淨作業</font><table width="600" border="1"><tr><td width="200" align="left"><font size="+2">製造Lot：<input type="text" name="text_produce_lot" size="6" value="'.$row[4].'"><input type="submit" name="input_lot" value="輸入">桶號：<input type="text" name="input_drum_no" size="10" value="'.$row[1].'" onkeypress="return focusOnEnter(event)"><input type="submit" name="input_dmno" value="輸入"></font></td></tr><tr bgcolor="#CCCCCC"><td align="center"><font size="+2">桶號：'.$row[1].'&nbsp;&nbsp;&nbsp; 製造Lot：'.$row[4].'&nbsp;&nbsp;&nbsp;客戶：'.$cust_name.'&nbsp;&nbsp;</font></td></tr></table>';
	
	echo '<table width="600" border="1"><tr bgcolor="#66FFFF"><td width="450"  align="center">容器準備檢查項目</td><td width="150" align="center">擔當者</td></tr>';
	echo '<tr><td width="450">
	&nbsp;&nbsp;<input type="checkbox" name="e1" checked="checked">&nbsp;&nbsp;製造年月未滿 2 年<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e2" checked="checked">&nbsp;&nbsp;開始使用年月未滿 1 年且使用次數少於 3 次<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e3" checked="checked">&nbsp;&nbsp;外觀無汙穢、變形、損壞、變色、生鏽<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e4" checked="checked">&nbsp;&nbsp;桶內無異物、變形、變色、異臭、損壞<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e5" checked="checked">&nbsp;&nbsp;口部無變形<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e6" checked="checked">&nbsp;&nbsp;部品無汙穢、變形、損壞、變色<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e7" checked="checked">&nbsp;&nbsp;部品洗淨、組立<BR>
	&nbsp;&nbsp;<input type="checkbox" name="e8" checked="checked">&nbsp;&nbsp;Label 無脫落、破損<BR>
	</td><td width="150" align="center" ><BR><BR><BR><BR>'.$v1.'</td></tr>';
	
	echo '<tr bgcolor="#66FFFF"><td width="450"  align="center">洗淨用充填檢查項目</td><td width="150" align="center">擔當者</td></tr>';
	echo '<tr><td>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="f1" checked="checked">&nbsp;&nbsp;洗淨充填時有無洩漏、異常、過少充填、各種計時器異常<BR>';
	echo '&nbsp;&nbsp;充填量：<input type="text" name="text_fill_qty" value="'.$row[15].'" size="6" > KG<BR>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="f2" checked="checked">&nbsp;&nbsp;充填DRUM充填棚定位<BR>';
	echo '</td><td width="150" align="center" ><BR>'.$v2.'</td></tr>';
	
	echo '<tr bgcolor="#66FFFF"><td width="450"  align="center">桶洗淨檢查項目</td><td width="150" align="center">擔當者</td></tr>';
	echo '<tr><td>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="g1" checked="checked">&nbsp;&nbsp;空DRUM充填棚定位<BR>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="g2" checked="checked">&nbsp;&nbsp;洗淨 (移液) 狀態確認<BR>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="g3" checked="checked">&nbsp;&nbsp;洗淨完了確認<BR>';
	echo '</td><td width="150" align="center" ><BR>'.$v3.'</td></tr>';
	echo '</table>';
//	echo '<table width="600"><tr bgcolor="#999999"><td width="600" align="center"><input type="submit" name="mete_store" style="font-size:16px" value="                       儲存物料數量                       "></td></tr></table>';
	
	echo '<BR><table width="600" border="1"><tr bgcolor="#66FFFF"><td width="50" align="center"><font size="+1">序號</font></td><td width="90" align="center"><font size="+1">桶號</font></td><td width="50" align="center"><font size="+1">容器準備</font></td><td width="50" align="center"><font size="+1">洗淨用充填</font></td><td width="50" align="center"><font size="+1">桶洗淨</font></td></tr>';

	$query="SELECT  IDF_SERIAL_NO AS 序號, IDF_DRUM_NO AS 桶號, CASE WHEN IDF_PREPARE_CHECKER IS NOT NULL 
                   THEN 'OK' ELSE '' END AS 容器準備, CASE WHEN IDF_FILLWASH_CHECKER IS NOT NULL 
                   THEN 'OK' ELSE CASE WHEN IDF_SERIAL_NO = 1 THEN '' ELSE '－' END END AS 洗淨用充填, 
                   CASE WHEN IDF_WASH_CHECKER IS NOT NULL THEN 'OK' ELSE '' END AS 桶洗淨
			FROM      IPA_DRUM_FILLDATA
			WHERE   (FDM_LOT_NO = '".$_SESSION['lot_no1']."')
			ORDER BY 序號 ";
// 	echo "<BR>".$query."<BR>";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))	{
		if($_SESSION['drum_no1']==$row['桶號']){
			$bgcolor=  'bgcolor="yellow"';
			$_SESSION['IDF_SERIAL_NO']=$row['序號'];
			}
		else{$bgcolor='';}
		echo '<tr onClick="set_date_session('."'drum_no1'".','."'".$row['桶號']."'".')" '.$bgcolor.'>';
		
		echo '<td>'.$row['序號'].'</td><td>'.$row['桶號'].'</td><td>'.$row['容器準備'].'</td><td>'.$row['洗淨用充填'].'</td><td>'.$row['桶洗淨'].'</td></tr>';
		$n++;
	}
	echo '</table>';

}

if(isset($_POST['fill']) or $_SESSION['fill_status']=='on'){
	
	$query="select * from IPA_DRUM_FILLDATA where FDM_LOT_NO='".$_SESSION['lot_no1']."' and IDF_DRUM_NO='".$_SESSION['drum_no1']."'";
// 	echo "<BR>".$query."<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	for($n=0;$n<$a;$n++){
		if($row[2]>=$aa[$n][1] and $row[2]<=$aa[$n][2]){$cust_name=$aa[$n][3];}
	}
	
	if($row[27] == NULL ){$v4='<input type="submit" value="儲存" name="save4">';}
	elseif(trim($row[27])<>''){$v4=get_uname($row[27]);}
	if(trim($row[33])==''){$r33=$_SESSION['source_lot_no'];}
	else{$r33=trim($row[33]);}
	echo '<font size="+1" color="#990066">充填作業</font><table width="600" border="1"><tr><td width="200" align="left"><font size="+1">製造Lot：<input type="text" name="text_produce_lot" size="6" value="'.$row[4].'"><input type="submit" name="input_lot" value="輸入" style="font-size:16px">&nbsp;&nbsp;桶號：<input type="text" name="input_drum_no" size="6" value="'.$row[1].'"><input type="submit" name="input_dmno" value="輸入"  style="font-size:16px">&nbsp;&nbsp;來源LOT_NO：<input type="text" name="source_lot_no" size="8" value="'.$r33.'"><input type="submit" name="input_source" value="輸入"  style="font-size:16px"></font></td></tr><tr bgcolor="#CCCCCC"><td align="center"><font size="+2">桶號：'.$row[1].'&nbsp;&nbsp;&nbsp; 製造Lot：'.$row[4].'&nbsp;&nbsp;&nbsp;客戶：'.$cust_name.'&nbsp;&nbsp;</font></td></tr></table>';
	
	echo '<table width="600" border="1"><tr bgcolor="#66FFFF"><td width="450"  align="center">本充填檢查項目</td><td width="150" align="center">擔當者</td></tr>';
	echo '<tr><td width="450">';
	echo '&nbsp;&nbsp;空桶重量：&nbsp;&nbsp;<input type="text" name="te1" value="'.$_SESSION['te1'].'" size="6" onchange="set_date_session(this.name,this.value)"> KG<BR>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="h1" checked="checked">&nbsp;&nbsp;本充填時有無洩漏、異常、過少充填、各種計時器異常<BR>';
	echo '&nbsp;&nbsp;充填完成時重量：&nbsp;&nbsp;<input type="text" name="IDF_FILL_WEIGHT" value="'.$_SESSION['IDF_FILL_WEIGHT'].'" size="6"  onchange="set_date_session(this.name,this.value)"> KG<BR>';
	echo '&nbsp;&nbsp;充填重量：&nbsp;&nbsp;<input type="text" name="te2" readonly="readonly" value="'.($_SESSION['IDF_FILL_WEIGHT']-$_SESSION['te1']).'" size="6" > KG<BR>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="h2" checked="checked">&nbsp;&nbsp;液、氣測 check (大Plug 200kg、小Plug 100kg)<BR>';
	echo '&nbsp;&nbsp;<input type="checkbox" name="h3" checked="checked">&nbsp;&nbsp;桶上擦拭檢查有無汙穢、變形、損壞、變色、生鏽/有無插管<BR>';

		$url='drum_smp_rcv.php?lot='.trim($_SESSION['lot_no1']).'&sn='.$row[2];
		echo '&nbsp;&nbsp;<input type="checkbox" name="h4" checked="checked">&nbsp;&nbsp;桶內取樣  &nbsp;&nbsp;<a href="'.$url.'" target="_blank"><font  color="blue"> 取樣 </font></a><BR>';
		echo '<input type="hidden" name="smp" value="Y">' ;


	echo '</td><td width="150" align="center" ><BR><BR><BR><BR>'.$v4.'</td></tr>';

	echo '</table>';
//	echo '<table width="600"><tr bgcolor="#999999"><td width="600" align="center"><input type="submit" name="mete_store" style="font-size:16px" value="                       儲存物料數量                       "></td></tr></table>';
	
	echo '<BR><table width="600" border="1"><tr bgcolor="#66FFFF"><td width="60" align="center"><font size="+1">序號</font></td><td width="120" align="center"><font size="+1">桶號</font></td><td width="120" align="center"><font size="+1">來源LOT_NO</font></td><td width="120" align="center"><font size="+1">洗淨</font></td><td width="120" align="center"><font size="+1">充填</font></td><td width="120" align="center"><font size="+1">取樣</font></td></tr>';

	$query="SELECT  IDF_SERIAL_NO AS 序號, IDF_DRUM_NO AS 桶號, SOURCE_LOT_NO as source_lot_no,CASE WHEN IDF_WASH_CHECKER IS NOT NULL 
                   THEN 'OK' ELSE '' END AS 桶洗淨, CASE WHEN IDF_FILL_CHECKER IS NOT NULL THEN 'OK' ELSE '' END AS 充填, 
                   CASE WHEN IDF_CHK_SAMPLE = 'Y' THEN 'OK' ELSE '' END AS 取樣
FROM      IPA_DRUM_FILLDATA 
			WHERE   (FDM_LOT_NO = '".$_SESSION['lot_no1']."')
			ORDER BY 序號 ";
//	echo "<BR>".$query."<BR>";
	$result=mssql_query($query);
	while($row=mssql_fetch_array($result))	{
		if($_SESSION['drum_no1']==$row['桶號']){
			$bgcolor=  'bgcolor="yellow"';
			$_SESSION['IDF_SERIAL_NO']=$row['序號'];
			}
		else{$bgcolor='';}
		echo '<tr onClick="set_date_session('."'drum_no1'".','."'".$row['桶號']."'".')" '.$bgcolor.'>';
		
		echo '<td align="center">'.$row['序號'].'</td><td align="center">'.$row['桶號'].'</td><td align="center">'.$row['source_lot_no'].'</td><td align="center">'.$row['桶洗淨'].'</td><td align="center">'.$row['充填'].'</td><td align="center">'.$row['取樣'].'</td></tr>';
		$n++;
	}
	echo '</table>';
}


if(isset($_POST['b_fill'])){
	$_SESSION['wash_status']='off';
	echo '<BR><font size="+2" color="#990000">充填前確認</font><table width="900" border="1"><tr bgcolor="#999999"><td width="200" align="center"><font size="+2">檢查項目</font></td><td width="200" align="center"><font size="+2">檢查要領</font></td><td width="300" align="center"><font size="+2">檢查方式</font></td><td width="200" align="center"><font size="+2">CHECK 欄</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">氧氣濃度設定值確認</font></td><td width="200" align="center"><font size="+2">8 %</font></td><td width="200" align="center"><font size="+2">控制盤</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" checked="checked" name="c1"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">但氣壓力</font></td><td width="200" align="center"><font size="+2">0.12~0.14 Mpa</font></td><td width="200" align="center"><font size="+2">充填裝置內減壓閥</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c2"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">重量計 check</font></td><td width="200" align="center"><font size="+2">測試用重量容器 20kg</font></td><td width="200" align="center"><font size="+2">重量計表示 20kg ± 0.1kg </font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c3"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">重量設定值確認</font></td><td width="200" align="center"><font size="+2">150kg 基準</font></td><td width="200" align="center"><font size="+2">螢幕觸控設定畫面確認</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c4"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">空重量設定值確認</font></td><td width="200" align="center"><font size="+2">40kg</font></td><td width="200" align="center"><font size="+2">螢幕觸控設定畫面確認</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c5"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">CDA壓力 (Pump驅動用)</font></td><td width="200" align="center"><font size="+2">0.20~0.25 Mpa</font></td><td width="200" align="center"><font size="+2">控制盤內減壓閥</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c6"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">CDA壓力 (閥驅動用)</font></td><td width="200" align="center"><font size="+2">0.45~0.50 Mpa</font></td><td width="200" align="center"><font size="+2">控制盤內減壓閥</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c7"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">HEPA 防火風門確認</font></td><td width="200" align="center"><font size="+2">開放</font></td><td width="200" align="center"><font size="+2">目視</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c8"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">Vent 防火風門確認</font></td><td width="200" align="center"><font size="+2">開放</font></td><td width="200" align="center"><font size="+2">目視</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c9"> 確認</font></td></tr>';
	
	echo '<tr><td width="200" align="center"><font size="+2">強制室內排氣</font></td><td width="200" align="center"><font size="+2">開放</font></td><td width="200" align="center"><font size="+2">目視</font></td><td width="200" align="center"><font size="+2"><input type="checkbox" checked="checked" name="c10"> 確認</font></td></tr></table>';
	
	echo '<table width="900"><tr bgcolor="#999999"><td align="center"><input type="submit" name="b_fill_store" style="font-size:16px" value="                       儲 存 充 填 前 確 認 資 料                       "></td></tr></table>';
}
?>
</form>
<br><br><br>
<?php
if(isset($_POST['mete_store'])){
	$_SESSION['wash_status']='off';
	$query="SELECT  count(*) as NN FROM IPA_DRUM_CHECKING WHERE   (FDM_LOT_NO = '".$_POST['lot_no1']."') ";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	if($row[0]==0){
//		echo 
		$query="INSERT INTO IPA_DRUM_CHECKING
                   (FDM_LOT_NO, IDC_DRUM, IDC_PLUG_B, IDC_PLUG_S, IDC_PLUG_A, IDC_TUB_N, IDC_TUB_O, IDC_ORING, IDC_PACK, 
                   IDC_CAP_PE, IDC_CAP_ST)
		VALUES ('".trim($_POST['lot_no1'])."',".trim($_POST['IDC_DRUM']).",".trim($_POST['IDC_PLUG_B']).",".trim($_POST['IDC_PLUG_S']).",".trim($_POST['IDC_PLUG_A']).",".trim($_POST['IDC_TUB_N']).",".trim($_POST['IDC_TUB_O']).",".trim($_POST['IDC_ORING']).",".trim($_POST['IDC_PACK']).",".trim($_POST['IDC_CAP_PE']).",".trim($_POST['IDC_CAP_ST']).")";
		$result=mssql_query($query);
	}
}

if(isset($_POST['b_fill_store'])){
	$_SESSION['wash_status']='off';
	if($_POST['c1']=='on' and $_POST['c2']=='on' and $_POST['c3']=='on' and $_POST['c4']=='on' and $_POST['c5']=='on' and $_POST['c6']=='on' and $_POST['c7']=='on' and $_POST['c8']=='on' and $_POST['c9']=='on' and $_POST['c10']=='on'){
	
	confirm1("更新IPA充填前確認表","index.php?url=IPA_FILL");
			$query="UPDATE  IPA_DRUM_CHECKING
	SET           IDC_B_CHK_1 = 'Y', IDC_B_CHK_2 = 'Y', IDC_B_CHK_3 = 'Y', IDC_B_CHK_4 = 'Y', IDC_B_CHK_5 = 'Y', IDC_B_CHK_6 = 'Y', 
					   IDC_B_CHK_7 = 'Y', IDC_B_CHK_8 = 'Y', IDC_B_CHK_9 = 'Y', IDC_B_CHK_10 = 'Y', IDC_CHECKER = '".strtoupper(trim($_SESSION['uid']))."', 
					   IDC_FILLDATE = '".date("Ymd")."'
	WHERE   (FDM_LOT_NO = '".$_POST['lot_no1']."')";
			$result=mssql_query($query);
			if($result){echo "完成";}
			refresh();
	}
	else{
		my_msg("請重新確認檢查項目");	
	}
}

if(isset($_POST['save1']))
{
	$_SESSION['wash_status']='on';
	if($_POST['e1']=='on' and $_POST['e2']=='on' and $_POST['e3']=='on' and $_POST['e4']=='on' and $_POST['e5']=='on' and $_POST['e6']=='on' and $_POST['e7']=='on' and $_POST['e8']=='on'){
	$query="UPDATE IPA_DRUM_FILLDATA SET IDF_MAKE_LOT='".$_POST['text_produce_lot']."',IDF_CHK_MAKEDATE='Y',IDF_CHK_DOA='Y',IDF_CHK_SURFACE='Y',IDF_CHK_DRUM_IN='Y',IDF_CHK_FILL_LINK='Y',IDF_CHK_COMPONENT='Y',IDF_CHK_COMPONENT_W='Y',IDF_CHK_LABEL='Y',IDF_PREPARE_CHECKER='".trim($_SESSION['uid'])."' WHERE FDM_LOT_NO='".trim($_POST['lot_no1'])."' AND IDF_DRUM_NO='".$_SESSION['drum_no1']."' AND IDF_SERIAL_NO='".$_SESSION['IDF_SERIAL_NO']."' ";
	$result=mssql_query($query);
	}
	refresh();
}

if(isset($_POST['save2']))
{
	$_SESSION['wash_status']='on';
	if($_POST['f1']=='on' and $_POST['f2']=='on'){
	$query="UPDATE IPA_DRUM_FILLDATA SET IDF_CHK_WASH_ERROR='Y',IDF_CHK_MAKEDATE='Y',IDF_CHK_WASH_LOC='Y',IDF_WASH_WEIGHT='".$_POST['text_fill_qty']."',IDF_FILLWASH_CHECKER='".$_SESSION['uid']."' WHERE FDM_LOT_NO='".trim($_POST['lot_no1'])."' AND IDF_DRUM_NO='".$_SESSION['drum_no1']."' AND IDF_SERIAL_NO='".$_SESSION['IDF_SERIAL_NO']."' ";
	$result=mssql_query($query);
	}
	else
	{
		my_msg("請重新確認檢查項目");	
	}
	refresh();
}

if(isset($_POST['input_lot']))
{
	$query="update IPA_DRUM_FILLDATA SET IDF_MAKE_LOT='".$_POST['text_produce_lot']."' where IDF_SERIAL_NO='".$_SESSION['IDF_SERIAL_NO']."' and IDF_DRUM_NO='".$_SESSION['drum_no1']."' and FDM_LOT_NO='".trim($_POST['lot_no1'])."'";
	$result=mssql_query($query);
	refresh();
}

if(isset($_POST['input_source']))
{
	if(trim($_POST['source_lot_no'])<>''){
		$_SESSION['source_lot_no']=trim($_POST['source_lot_no']);
		
		$query="update IPA_DRUM_FILLDATA SET SOURCE_LOT_NO='".strtoupper($_SESSION['source_lot_no'])."' where IDF_SERIAL_NO='".$_SESSION['IDF_SERIAL_NO']."' and IDF_DRUM_NO='".$_SESSION['drum_no1']."' and FDM_LOT_NO='".trim($_POST['lot_no1'])."'";
		$result=mssql_query($query);
		
		$query="UPDATE  FILL_INDICATE SET FID_SOURCE_LOT = '".strtoupper($_SESSION['source_lot_no'])."' WHERE   (FDM_LOT_NO = '".trim($_POST['lot_no1'])."')";
		$result=mssql_query($query);
		refresh();		
	}
}
if(isset($_POST['input_dmno'])){
	$query="select * from IPA_DRUM_FILLDATA where FDM_LOT_NO='".trim($_POST['lot_no1'])."' and IDF_DRUM_NO='".$_POST['input_drum_no']."'";
	$result=mssql_query($query);
	$numrows=mssql_num_rows($result);
	if($numrows==0){
		$_SESSION['drum_no1']=$_POST['input_drum_no'];	
		$query="SELECT  DHN_USED_COUNT AS Expr1 FROM DRUM_HISTORY_NORMAL where DHN_DRUM_NO='".$_POST['input_drum_no']."'";
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$n1=$row[0]+1;
		
		$query="select MAX(IDF_SERIAL_NO) from IPA_DRUM_FILLDATA where FDM_LOT_NO='".trim($_POST['lot_no1'])."'";
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
		$rowx=mssql_fetch_row($result);
		$nn=$rowx[0]+1;
		
		$query="INSERT INTO IPA_DRUM_FILLDATA (IDF_MAKE_LOT, FDM_LOT_NO,IDF_DRUM_NO,IDF_SERIAL_NO,IDF_USED_COUNT) VALUES ('".$_POST['text_produce_lot']."', '".trim($_POST['lot_no1'])."','".$_POST['input_drum_no']."',".$nn.",".$n1.")";
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);
	}
 	refresh();
}

if(isset($_POST['save3'])){
	if($_POST['g1']=='on' and $_POST['g2']=='on' and $_POST['g3']=='on'){
		$query="UPDATE  IPA_DRUM_FILLDATA
SET           IDF_CHK_LOC = 'Y', IDF_CHK_WASH = 'Y', IDF_CHK_WASH_FINISH = 'Y', IDF_WASH_CHECKER = '".$_SESSION['uid']."'
WHERE   (FDM_LOT_NO = '".trim($_POST['lot_no1'])."') and IDF_DRUM_NO='".$_SESSION['drum_no1']."'";
		$result=mssql_query($query);
		
		$query="UPDATE DRUM_HISTORY_NORMAL SET DHN_USED_COUNT=@P1 WHERE DHN_DRUM_NO=@P2 AND DHN_USED_COUNT=@P3";
	
		$query="UPDATE FILL_INDICATE SET FID_WASHED_COUNT = FID_WASHED_COUNT + 1, FID_OPERATOR = 'E903', FID_FILL_END_DATE = '20180626084406' WHERE FDM_LOT_NO = 'TI08F19D190'";	
	
	}
	refresh();
}

if(isset($_POST['save4'])){
	if($_POST['h1']=='on' and $_POST['h2']=='on' and $_POST['h3']=='on'){
			$query="UPDATE  IPA_DRUM_FILLDATA SET IDF_CHK_SAMPLE='".$_POST['smp']."', IDF_CHK_FILL_ERROR = 'Y', IDF_CHK_GAS = 'Y', IDF_CHK_CLR = 'Y' , IDF_FILL_WEIGHT=".$_POST['IDF_FILL_WEIGHT'].", IDF_FILL_CHECKER = '".$_SESSION['uid']."' WHERE (FDM_LOT_NO = '".trim($_POST['lot_no1'])."') AND (IDF_DRUM_NO = '".$_SESSION['drum_no1']."')";
		$result=mssql_query($query);
		jumpto($url);
 		refresh();
	}
	else{
		my_msg("請勾選檢查項目");	
	}
 	refresh();
}

?>
