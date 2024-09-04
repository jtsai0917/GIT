<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
include("../PHPEXCEL/Classes/PHPExcel.php");
include("../PHPEXCEL/Classes/PHPExcel/IOFactory.php");
lasturl();
datepick(); 
//echo "S";
?>
<form name="form1" method="post" enctype="multipart/form-data" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

		<table bgcolor="#CCCCCC" border="1">
        <tr><td>
 		®µ¿À§È¥¡ <span class="d1">
        <input name="datepicker7" type="text" id="datepicker7" size="10" value="<?php echo $_SESSION['datepicker7'] ?>" onchange="set_date_session(this.name,this.value)" >
        ~
        <input name="datepicker8" type="text" id="datepicker8" size="10" value="<?php echo $_SESSION['datepicker8'] ?>" onchange="set_date_session(this.name,this.value)" >
       
         ´~¶W°G<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pid" type="text" id="pid" size="10" onchange="set_date_session(this.name,this.value)" value="<?php 
		if ($_GET['pid']){
			echo $_GET['pid'];
			$_SESSION['pid']=$_GET['pid'];
		}
		elseif($_SESSION['pid']){
			echo $_SESSION['pid'];
		}
		else{
		echo '';
		}
		?>" >
        <input type="button" name="pdd_no" id="pdd_no" value="¨d∏ﬂ®µ¿À∂µ•ÿ" onClick="window.open('../PRODUCE/utt_tag_no.php ', '_self');" >
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
		?>" readonly>
        ®µ¿À™Ì°G
        <select name="form"  onchange="set_date_session(this.name,this.value)">
         <?php
		$query="select * from UTT_FORM_DATA order by Form";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			
		if(trim($row['index'])==$_SESSION['form']){$show1=' selected ';}else{$show1='';}
		echo '<option value="'.trim($row['index']).'" '.$show1.'>'.trim($row['Form']).'</option>';
		if($_SESSION['form']==''){$_SESSION['form']=trim($row['index']);}
		}
		$query="select * from UTT_FORM_DATA where [index]='".$_SESSION['form']."'";
		$result=mssql_query($query);
		$row=mssql_fetch_array($result);
		$_SESSION['way']=trim($row['way']);
		?>
        </select>

         ≤ƒ¥X¶∏®µ¿À°G
        <select name="sn"  onchange="set_date_session(this.name,this.value)">
         <?php
		 if($i==$_SESSION['sn']){$show=' selected ';}else{$show='';}
		echo '<option value="0" '.$show.'>•˛≥°</option>';
		 for($i=1;$i<7;$i++){
		if($i==$_SESSION['sn']){$show1=' selected ';}else{$show1='';}
		echo '<option value="'.$i.'" '.$show1.'>'.$i.'</option>';
		}
		?>
        </select>
        <input type="submit" name="search" id="search" value="∑j¥M">
        <input type="submit" name="print" id="print" value="¶C¶L">
        <input type="hidden" name="mm_insert" id="mm_insert" value="form1">
        <input type="submit" name="keyin" id="keyin" value="MCTW∏ÍÆ∆øÈ§J">
        <input type="submit" name="keyin_HIC" id="keyin_HIC" value="HIC∏ÍÆ∆øÈ§J">

        </span></td></table>

<?php
$loginFormAction = $_SERVER['PHP_SELF'];
$_SESSION['lasturl']=$_SERVER['REQUEST_URI'];
if(isset($_POST['keyin']) or isset($_POST['keyin_HIC']))
{
	if(isset($_POST['keyin_HIC'])){$_SESSION['HIC']=1;}else{unset($_SESSION['HIC']);}
	jumpto("/PRODUCE/UTT_check_input.php?form=".trim($_POST['form']));
}
if(isset($_POST['morning']))
{
	$_SESSION['time1']='0800';
	$_SESSION['time2']='1600';
}
if(isset($_POST['afternoon']))
{
	$_SESSION['time1']='1600';
	$_SESSION['time2']='2359';
}
if(isset($_POST['night']))
{
	$_SESSION['time1']='0000';
	$_SESSION['time2']='0800';
}
//echo $_SESSION['pass'];
if($_GET['remark']<>'' and ($_SESSION['pass']==1 or $_SESSION['pass']==2) or isset($_POST['cancel1']) or isset($_POST['cancel2']))  //ßÂ¶∏√±Æ÷ªP®˙Æ¯™∫≥°§¿
{
	$remark=$_GET['remark'];
	if($remark=='empty'){$remark='';}
	for($i=0;$i<count($_SESSION['SAG_NO']);$i++){
	$query="select distinct form1 from UTT_TAGNO_DATA  WHERE form1=".$_SESSION['form'];
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$Numrows=mssql_num_rows($result);
	if($Numrows==0)
	{
		$query1="select distinct form1 from UTT_TAGNO_DATA  WHERE form=".$_SESSION['form'];
		$result1=mssql_query($query1);
		$row1=mssql_fetch_array($result1);
		$form1=trim($row1[0]);
	}
	else
	{
		$form1=trim($row[0]);
	}	
	if($_SESSION['pass']==1)
	{
		$query1="select SIGN1 from UTT_DATA_SIGN where SAG_NO='".$form1."_".$_SESSION['SAG_NO'][$i]."'";
		$result1=mssql_query($query1);
		$row1=mssql_fetch_array($result1);
		if(trim($row1[0])==''){
		$query="update UTT_DATA_SIGN set SIGN1='".$_SESSION['uid']."',Remark1='".$remark."',sign_time1='".date(YmdHis)."',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$form1."_".$_SESSION['SAG_NO'][$i]."'";
		}
	}
	elseif($_SESSION['pass']==2)
	{
		$query1="select SIGN2 from UTT_DATA_SIGN where SAG_NO='".$form1."_".$_SESSION['SAG_NO'][$i]."'";
		//echo $query1;
		$result1=mssql_query($query1);
		$row1=mssql_fetch_array($result1);
		if(trim($row1[0])==''){
		$query="update UTT_DATA_SIGN set SIGN2='".$_SESSION['uid']."',Remark2='".$remark."',sign_time2='".date(YmdHis)."',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$form1."_".$_SESSION['SAG_NO'][$i]."'";
		}
	}
	elseif(isset($_POST['cancel1']))
	{
		$query="update UTT_DATA_SIGN set SIGN1='',Remark1='',sign_time1='',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$form1."_".$_SESSION['SAG_NO'][$i]."'";
	}
	elseif(isset($_POST['cancel2']))
	{
		$query="update UTT_DATA_SIGN set SIGN2='',Remark2='',sign_time2='',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$form1."_".$_SESSION['SAG_NO'][$i]."'";
	}
	$result=mssql_query($query);
	//echo $query.'<br>';
	}
	$url='/PRODUCE/index.php?url=UTT_check';
	my_msg('√±Æ÷ßπ¶®!',$url);
}

	if($_SESSION['sign']<>'')////≥Ê¶∏√±Æ÷™∫≥°§¿
	{
		$PASS=substr($_SESSION['sign'],0,3);
		$A=substr($_SESSION['sign'],3,1);
		$sign=substr($_SESSION['sign'],4);
		//echo $PASS.','.$sign.','.$A;
		if($PASS=='sig')
		{
			if($A=='A'){	
			$query="update UTT_DATA_SIGN set SIGN1='".$_SESSION['uid']."',Remark1='".$remark."',sign_time1='".date(YmdHis)."',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$sign."'";
			}
			else
			{
			$query="update UTT_DATA_SIGN set SIGN2='".$_SESSION['uid']."',Remark2='".$remark."',sign_time2='".date(YmdHis)."',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$sign."'";
			}
		}
		else
		{
			if($A=='A'){
			$query="update UTT_DATA_SIGN set SIGN1='',Remark1='',sign_time1='',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$sign."'";
			}
			else
			{
			$query="update UTT_DATA_SIGN set SIGN2='',Remark2='',sign_time2='',saveuid='".$_SESSION['uid']."',savetime='".date(YmdHis)."' where SAG_NO='".$sign."'";
			}
		}
		//echo $query;
		mssql_query($query);
		unset($_SESSION['sign']);
		$url='/PRODUCE/index.php?url=UTT_check';
		my_msg('√±Æ÷ßπ¶®!',$url);
	}
	for($i=0;$i<count($_SESSION['SIGN']);$i++)
	{ 
		if(isset($_POST[$_SESSION['SIGN'][$i]]))
		{
			$PASS=substr($_SESSION['SIGN'][$i],0,3);
			$_SESSION['sign']=$_SESSION['SIGN'][$i];
			unset($_SESSION['SIGN']);
			if($PASS=='sig'){
			remark();}
			else
			{
				refresh();
			}
		}
		$_SESSION['pass']=1;
	}
	
if(isset($_POST['search']) or isset($_POST['morning']) or isset($_POST['afternoon']) or isset($_POST['night']) or $_SESSION['pass']<>'')
{
	if(isset($_POST['search'])){unset($_SESSION['SIGN'],$_SESSION['pass']);}
	if($_SESSION['pass1']<>''){unset($_SESSION['SIGN'],$_SESSION['pass']);break;}
	
	if($_SESSION['pass']==''){
	$_SESSION['form']=$_POST['form'];}
	unset($_SESSION['SAG_NO'],$_SESSION['pass']);
	if($_SESSION['pid']<>'')
	{
		echo '<table width="1000" border="1" align="left">';
		echo '<tr bgcolor="#CCCCCC"><td>TAG_NO</td><td>∂µ•ÿ¶W∫Ÿ</td><td>º∆≠»</td><td>SPEC</td><td>®µ¿À§È¥¡Æ…∂°</td><td>®µ¿À§H≠˚</td><td>¶XÆÊ/§£¶XÆÊ</td></tr>';
		$query="select US.*,UA.project,UA.spec from UTT_record_spec US left join UTT_TAGNO_DATA UA ON US.TAG_NO=UA.TAG_NO where US.TAG_NO='".$_SESSION['pid']."' and Date >=  '".dateform1($_SESSION['datepicker7'])."0000' and Date<='".dateform1($_SESSION['datepicker8'])."9999' order by date";
//		echo $query."<BR>";
		$result=mssql_query($query);$Num=2;
		while($row1=mssql_fetch_array($result))
		{
		$pass=trim($row1['pass']);
					if($pass=='O'){$pass='¶XÆÊ';$BG='';}
					elseif($pass=='°µ'){$pass='';$BG='';}
					else{$pass='§£¶XÆÊ';$BG='bgcolor="#FFFF66"';}
					echo '<tr '.$BG.'><td>'.$row1['TAG_NO'].'</td><td>'.$row1['project'].'</td><td>'.$row1['Data'].'</td><td>'.$row1['spec'].'</td><td>'.$row1['Date'].'</td><td>'.getusername($row1['saveuid']).'</td><td>'.$pass.'</td></tr>';	


		}
		echo '</table>';
		
		
	}
	else
	{
		$num=preg_replace('/[^\d]/','',$timetitle);
		$eng=ereg_replace("[0-9]","",$timetitle);	
		$num1=preg_replace('/[^\d]/','',$timetitle1);
		$eng1=ereg_replace("[0-9]","",$timetitle1);	
		if($_SESSION['way']=='A'){$form='form1';}else{$form='form';}
		if($_SESSION['time1']<>'' and $_SESSION['time2']<>'')
		{
			$time1=$_SESSION['time1'];$time2=$_SESSION['time2'];
		}
		else
		{
			$time1='0000';$time2='9999';
		}
		$Datetime="substring(date,1,8)>= '".dateform1($_SESSION['datepicker7'])."' and substring(date,1,8)<='".dateform1($_SESSION['datepicker8'])."' and substring(date,9,4)>='".$time1."' and substring(date,9,4)<='".$time2."'";
		$query2="SELECT distinct Date from UTT_CHECK_DATA UD left join UTT_TAGNO_DATA UN ON UD.TAG_NO=UN.TAG_NO where ".$Datetime." and ".$form."=".$_SESSION['form']." order by date";
	//	echo $query."<BR>";
		$result2=mssql_query($query2);
		$row2=mssql_fetch_array($result2);
		$Numrows=mssql_num_rows($result2);$PASS=0;$i=0;$count=0;
		if($Numrows==0){my_msg('¨dµL∏ÍÆ∆');}
		$sign1=hr_duteies($_SESSION['uid']);   ////ßÂ¶∏√±Æ÷≥°§¿
		
		/////////ßÂ¶∏®˙Æ¯≥°§¿
		$query1="select distinct way from UTT_FORM_DATA FD LEFT JOIN UTT_TAGNO_DATA TD ON   FD.[index]=TD.".$form." left join UTT_record_spec rs on  TD.TAG_NO=rs.TAG_NO  where FD.[index]='".$_SESSION['form']."' AND Date ='".$row2[0]."' and way='A'";
		//echo $query1;
		$result1=mssql_query($query1);
		$Numrows=mssql_num_rows($result1);
		if($Numrows==0){
		$query3="select distinct form1 from UTT_TAGNO_DATA where form='".$_SESSION['form']."'";
		$result3=mssql_query($query3);
		$row3=mssql_fetch_array($result3);
		$SIGN1=trim($row3[0]);
		}
		else{$SIGN1=$_SESSION['form'];}
		$reverse="reverse(substring(reverse(SAG_NO),1,charindex('_',reverse(SAG_NO)) - 1))";
		$query4="select * from UTT_DATA_SIGN where SUBSTRING(SAG_NO,1,CHARINDEX('_',SAG_NO)-1)='".$SIGN1."' and  SUBSTRING(".$reverse.",1,8)>=  '".dateform1($_SESSION['datepicker7'])."' and substring(".$reverse.",1,8)<='".dateform1($_SESSION['datepicker8'])."' and SUBSTRING(".$reverse.",9,4)>='".$time1."' and SUBSTRING(".$reverse.",9,4)<='".$time2."'"; 
		//echo $query4;
		$result4=mssql_query($query4);
		$Numrow4=mssql_num_rows($result4);$signcount=0;$signcount1=0;
		while($row4=mssql_fetch_array($result4))
		{
			if(trim($row4['SIGN1'])<>''){$signcount++;}	
			if(trim($row4['SIGN2'])<>''){$signcount1++;}	
		}
		//echo $signcount1.','.$signcount.','.$Numrow4;
		if(substr($_SESSION['uid']=='E903' or $_SESSION['uid'],1)=='319' or substr($_SESSION['uid'],1)=='029' or substr($_SESSION['uid'],1)=='525'  or substr($_SESSION['uid'],1)=='903' or substr($_SESSION['uid'],1)=='066' or substr($_SESSION['uid'],1)=='1048' or substr($_SESSION['uid'],1)=='1263' or substr($_SESSION['uid'],1)=='900' or substr($_SESSION['uid'],1)=='1038' or substr($_SESSION['uid'],1)=='1044' or substr($_SESSION['uid'],1)=='1103' or substr($_SESSION['uid'],1)=='1100' or substr($_SESSION['uid'],1)=='1065')
		{
			if($signcount==$Numrow4){
				if($signcount1<$Numrow4 and $signcount1!=0){$sign1[0]='';}
				elseif($signcount1==0){$sign1[0]='<input type="submit" name="cancel1" value="ßÂ¶∏®˙Æ¯" style="background-color:red;">';}
				else{
				$sign1[0]='<input type="submit" name="cancel1" value="ßÂ¶∏®˙Æ¯" style="background-color:red;">';}
			}
			if($signcount1==$Numrow4){$sign1[1]='<input type="submit" name="cancel2" value="ßÂ¶∏®˙Æ¯" style="background-color:red;">';$sign1[0]='';}
		}
		///////////////
		
		$morning='<input type="submit" name="morning" value="¶≠">';
		$afternoon='<input type="submit" name="afternoon" value="§§">';
		$night='<input type="submit" name="night" value="±ﬂ">';
		echo '<table width="1200" border="1" align="left">';
		//echo $sing1[0].','.$sing[1];
		/*
		if($sing1[0]==''){$sign1='';}else{$sign2=$sign1[1];}
		if($sing1[1]==''){$sign1=$sign1[0];}else{$sign2='';}
		*/
		echo '<tr  bgcolor="#CCCCCC"><td colspan="4"  align="center" valign="center"><strong>√±Æ÷ß@∑~</strong></td><td align="center">'.$morning.' '.$afternoon.' '.$night.'</td><td colspan="2"><td align="center">'.$sign1[0].'</td><td align="center">'.$sign1[1].'</td></tr>';
		$query2="SELECT distinct Date from UTT_CHECK_DATA UD left join UTT_TAGNO_DATA UN ON UD.TAG_NO=UN.TAG_NO where ".$Datetime." and ".$form."=".$_SESSION['form']." order by date";
		$result2=mssql_query($query2);
		while($row2=mssql_fetch_array($result2))
	{
		$_SESSION['SAG_NO'][$i]=trim($row2[0]);
		$i++;
		$query="select FD.Form,FD.filename,FD.link,FD.sn,FD.sn1,FD.way,TD.TAG_NO,TD.project,TD.spec,rs.Data,rs.pass,rs.Date,rs.saveuid from UTT_FORM_DATA FD LEFT JOIN UTT_TAGNO_DATA TD ON   FD.[index]=TD.".$form." left join UTT_record_spec rs on  TD.TAG_NO=rs.TAG_NO  where FD.[index]='".$_SESSION['form']."' AND Date ='".$row2[0]."'";
		//echo $query;
		$result=mssql_query($query);$title=1;
		$query1="select distinct way from UTT_FORM_DATA FD LEFT JOIN UTT_TAGNO_DATA TD ON   FD.[index]=TD.".$form." left join UTT_record_spec rs on  TD.TAG_NO=rs.TAG_NO  where FD.[index]='".$_SESSION['form']."' AND Date ='".$row2[0]."' and way='A'";
		$result1=mssql_query($query1);
		$Numrows=mssql_num_rows($result1);
		if($Numrows==''){
		$query3="select distinct form1 from UTT_TAGNO_DATA where form='".$_SESSION['form']."'";
		$result3=mssql_query($query3);
		$row3=mssql_fetch_array($result3);
		$sign1=trim($row3[0]);
		}
		else{$sign1=$_SESSION['form'];}
		
		$query1="select * from UTT_DATA_SIGN where SAG_NO='".trim($sign1)."_".trim($row2[0])."'";
		//echo $query1.'<br>';
		$result1=mssql_query($query1);
		$row1=mssql_fetch_array($result1);
		
		if($_SESSION['uid']=='E903' or $_SESSION['uid']=='H1071' or $_SESSION['uid']=='H1172' or $_SESSION['uid']=='H1199'){$director=1;}
		elseif($_SESSION['uid']=='H1005' or $_SESSION['uid']=='H1041' or $_SESSION['uid']=='H1100' or $_SESSION['uid']=='H1103'){$director=2;}
		else{$director='';}
		if($_SESSION['director']==1 or $_SESSION['director']==2 or $director<>'')
		{
			if(trim($row1['SIGN1'])=='' and trim($row1['SIGN2'])=='')
			{
				$SIGN1='<input type="submit" value="√±Æ÷" name="sigA'.$sign1.'_'.trim($row2[0]).'" style="background-color:green;">';
				$SIGN2='';
				//echo $count.','.trim($row2[0]).'<br>';
				$_SESSION['SIGN'][$count]='sigA'.$sign1.'_'.trim($row2[0]);
				$count++;
			}
			if(trim($row1['SIGN1'])<>'' and trim($row1['SIGN2'])=='')
			{
				if($_SESSION['uid']==trim($row1['SIGN1']) or $_SESSION['director']==1 or $director==1){
				$SIGN1='<input type="submit" value="®˙Æ¯√±Æ÷" name="canA'.$sign1.'_'.trim($row2[0]).'" style="background-color:red;">';
				$_SESSION['SIGN'][$count]='canA'.$sign1.'_'.trim($row2[0]);
				$count++;
				}
				if($_SESSION['director']==1 or $director==1){
				$SIGN2='<input type="submit" value="√±Æ÷" name="sigB'.$sign1.'_'.trim($row2[0]).'" style="background-color:green;">';
				$_SESSION['SIGN'][$count]='sigB'.$sign1.'_'.trim($row2[0]);
				$count++;
				}
			}
			if(trim($row1['SIGN1'])<>'' and trim($row1['SIGN2'])<>'')
			{
				if($_SESSION['director']==1 or $director==1){
				$SIGN1='';
				}
				$SIGN2='<input type="submit"  value="®˙Æ¯√±Æ÷" name="canB'.$sign1.'_'.trim($row2[0]).'" style="background-color:red;">';
				$_SESSION['SIGN'][$count]='canB'.$sign1.'_'.trim($row2[0]);
				$count++;
			}
			//echo $count.','.$_SESSION['SIGN'][$count].'<br>';
			$director1=trim($row1['SIGN1']);
			$director2=trim($row1['SIGN2']);
		}
		else
		{
			$director1=trim($row1['SIGN1']);
			$director2=trim($row1['SIGN2']);

		}
		echo '<tr bgcolor="#CCCCCC"><td>TAG_NO</td><td>∂µ•ÿ¶W∫Ÿ</td><td>º∆≠»</td><td>SPEC</td><td>®µ¿À§È¥¡Æ…∂°</td><td>®µ¿À§H≠˚</td><td>¶XÆÊ/§£¶XÆÊ</td><td>•D•Ù√±Æ÷:'.$SIGN1.'</td><td>•D∫ﬁ√±Æ÷:'.$SIGN2.'</td></tr>';
		while($row1=mssql_fetch_array($result))
		{
					$pass=trim($row1['pass']);
					if($pass=='O'){$pass='¶XÆÊ';$BG='';}
					elseif($pass=='°µ'){$pass='';$BG='';}
					else{$pass='§£¶XÆÊ';$BG='bgcolor="#FFFF66"';}
					echo '<tr '.$BG.'><td>'.$row1['TAG_NO'].'</td><td>'.$row1['project'].'</td><td>'.$row1['Data'].'</td><td>'.$row1['spec'].'</td><td>'.$row1['Date'].'</td><td>'.getusername($row1['saveuid']).'</td><td>'.$pass.'</td><td bgcolor="#FAFAFA" align="center">'.getusername($director1).'</td><td  bgcolor="#FAFAFA" align="center">'.getusername($director2).'</td></tr>';
					$COLUMN=$column[trim($row1['TAG_NO'])];
					//echo trim($row1['TAG_NO']).'='.$COLUMN.'='.trim($row1['Data']).'<br>';
		}
	}
}
	echo '</table>';
} ///$_POST['search']
if(isset($_POST['sign1']))
{
$_SESSION['pass']='1';
remark();
}
if(isset($_POST['sign2']))
{
$_SESSION['pass']='2';
remark();
}
if(isset($_POST['cancel1']))
{
$_SESSION['pass']='A';
}
if(isset($_POST['cancel2']))
{
$_SESSION['pass']='B';
}
if(isset($_POST['print']))
{
	$objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("../PRODUCE/tag_no_spec.xlsx");
	$objPHPExcel-> setActiveSheetIndex(0);
			$styleThinBlackBorderOutline = array(  
					   'borders' => array (  
							 'outline' => array (  
								   'style' => PHPExcel_Style_Border::BORDER_THIN,   //?∏mborder?¶° 
								   //'style' => PHPExcel_Style_Border::BORDER_THICK,  •t§@œ˙?¶°  
							),  
					  ),  
				);
	if($_SESSION['pid']<>'')  // ´¸©wTAG_NO
	{
		//echo '<table width="1000" border="1" align="left">';
		//echo '<tr bgcolor="#CCCCCC"><td>TAG_NO</td><td>∂µ•ÿ¶W∫Ÿ</td><td>º∆≠»</td><td>SPEC</td><td>®µ¿À§È¥¡Æ…∂°</td><td>®µ¿À§H≠˚</td><td>¶XÆÊ/§£¶XÆÊ</td></tr>';
		$query="select US.*,UA.project,UA.spec from UTT_record_spec US left join UTT_TAGNO_DATA UA ON US.TAG_NO=UA.TAG_NO where US.TAG_NO='".$_SESSION['pid']."' and Date >=  '".dateform1($_SESSION['datepicker7'])."0000' and Date<='".dateform1($_SESSION['datepicker8'])."9999' order by date";
		$result=mssql_query($query);$Num=2;
		while($row1=mssql_fetch_array($result))
		{
		$ENG='A';

		$pass=trim($row1['pass']);
					if($pass=='O'){$pass='¶XÆÊ';$BG='';}
					elseif($pass=='°µ'){$pass='';$BG='';}
					else{$pass='§£¶XÆÊ';$BG='bgcolor="#FFFF66"';}
				//	echo '<tr '.$BG.'><td>'.$row1['TAG_NO'].'</td><td>'.$row1['project'].'</td><td>'.$row1['Data'].'</td><td>'.$row1['spec'].'</td><td>'.$row1['Date'].'</td><td>'.getusername($row1['saveuid']).'</td><td>'.$pass.'</td></tr>';	
		if($pass=='§£¶XÆÊ')
		{
		$objPHPExcel->getActiveSheet()->getStyle($ENG.$Num)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>'FFFF30'))); 
		}
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$Num, iconv("big5","utf-8",trim($row1['TAG_NO'])));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$Num)->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$Num, iconv("big5","utf-8",trim($row1['project'])));
		$objPHPExcel->getActiveSheet()->getStyle('B'.$Num)->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
		$objPHPExcel->getActiveSheet()->getStyle('B'.$Num)->getAlignment()->setWrapText(true);//¶€∞ ¥´¶Ê
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$Num, iconv("big5","utf-8",trim($row1['Data'])));
		$objPHPExcel->getActiveSheet()->getStyle('C'.$Num)->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$Num, iconv("big5","utf-8",trim($row1['spec'])));
		$objPHPExcel->getActiveSheet()->getStyle('D'.$Num)->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$Num, iconv("big5","utf-8","'".trim($row1['Date'])));
		$objPHPExcel->getActiveSheet()->getStyle('E'.$Num)->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$Num, iconv("big5","utf-8",getusername(trim($row1['saveuid']))));
		$objPHPExcel->getActiveSheet()->getStyle('F'.$Num)->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
		
		$Num++;
		}
	//	echo '</table>';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
		$objWriter->save('..//PRODUCE/Download/'.$_SESSION['pid'].'_'.des($_SESSION['datepicker7']).'.xlsx');
		
	}
	else   //§£´¸©wTAG_NO
{
		 //Ëµ∑Â??
			$query2="select * from UTT_FORM_DATA where [index]='".$_POST['form']."'";
		//	echo $query2."<BR>";
			$result2=mssql_query($query2);
			$row2=mssql_fetch_row($result2);
			$link=trim($row2[3]);
			$sn=trim($row2[4]);  //B3
			$col_start_=substr($sn,0,1);
			
			
			//PHPExcel_Cell::columnIndexFromString  
			//PHPExcel_Cell::stringFromColumnIndex  
			
			$col_start=PHPExcel_Cell::columnIndexFromString(substr($sn,0,1));  // EXCEL??ãÂ
			$col_write_start=$col_start+3;    
			
			$col_spec=PHPExcel_Cell::stringFromColumnIndex($col_start+1);
		//	echo "Start_column:".$col_write_start."<BR>";  
		
		// A ??ÑASCII code = 65 ??ØÁî® chr(65) Ë°®Á§∫
		
			$row_start=substr($sn,1);  // EXCEL??ãÂ??ËÆÄ??ñÂ?? ‰æãÂ?? 3
			$sn1=trim($row2[5]);
			$way=trim($row2[6]);
			
		//	$reader = PHPExcel_IOFactory::createReader('Excel2007'); 
			$reader= PHPExcel_IOFactory::createReaderForFile("..".$link);
			$reader->setReadDataOnly(true);
			$PHPExcel = $reader->load("..".$link);
			$sheet = $PHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow ();// ??≤Â????∂È????ÄÂ§ßË?????
			$highestColumn = $sheet->getHighestColumn ();//??ÄÂ§ßÊ??‰ΩçÁ????±Ê??‰ª????	
			echo "Form_ID: ".$_POST['form']."<BR>";
			
			
			for($i=0;$i <= ($highestRow);$i++){
				$tagno[$i]=trim($sheet->getCell($col_start_.($i+$row_start))->getValue());
				$spec[$i]=trim($sheet->getCell($col_spec.($i+$row_start))->getValue());
			}
			unset($sheet,$PHPExcel,$reader);  //
			unset($result2,$row2);
			
			//??•Ê????????  array days ex: 20240101-20240131 ; ??ÇÈ????•Êï∏???:count($time_chr[20240518])   ??ÇÈ??: $time_chr[20240518][$i] 
			$query="SELECT DISTINCT LEFT(LTRIM(UTT_CHECK_DATA.[date]), 8) AS t1
							FROM              UTT_FORM_DATA INNER JOIN
                            UTT_TAGNO_DATA ON UTT_FORM_DATA.[index] = UTT_TAGNO_DATA.form1 INNER JOIN
                            UTT_CHECK_DATA ON UTT_TAGNO_DATA.TAG_NO = UTT_CHECK_DATA.TAG_NO
							WHERE          (UTT_FORM_DATA.[index] = ".$_POST['form'].") AND (LEFT(LTRIM(UTT_CHECK_DATA.[date]), 8) >= '".dateform1($_POST['datepicker7'])."') AND 
                            (LEFT(LTRIM(UTT_CHECK_DATA.[date]), 8) <= '".dateform1($_POST['datepicker8'])."')
							ORDER BY   t1";

			$result=mssql_query($query);
			$i=0; $time_chr=$times=array();
			while($row=mssql_fetch_array($result)){
				$x=$days[$i]=$row['t1'];
				$times=times_($row['t1'],$_POST['form']);
				$y=count($times);

				for($o=0;$o< $y;$o++){
					$time_chr[$x][$o]=$times[$o];
				}
				$i++;
			}
			unset($result,$row);
			
			

		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("..".$link);
		$objPHPExcel-> setActiveSheetIndex(0);
	echo "..".$link;
		
		//ÂØ´ÂÖ•EXCEL $col_write_start(??±Ê??).$row_start(??∏Â??) ??óÂ????±F3 ??ãÂ??
		for ($j=0;$j < $i ; $j++)
		{
			$DD= $days[$j];  // ??•Ê?? EX: 20241130
			$cnt_times=count($time_chr[$DD]);
		//	echo "DD:".$DD."cnt_times:".$cnt_times." NN  :";

			for ($m=0;$m < $cnt_times;$m++){
				$value_datetime=$DD.$time_chr[$DD][$m];
				$datetime_excel=$DD."\n".$time_chr[$DD][$m];
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_write_start,$row_start-1,$datetime_excel);  // ÂØ´ÂÖ•
				$ddk=PHPExcel_Cell::stringFromColumnIndex($col_write_start);
				$objPHPExcel->getActiveSheet()->getStyle($ddk.($row_start-1))->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
			//	echo $value_datetime."<BR>";
				$query="SELECT UTT_CHECK_DATA.Data as data, UTT_CHECK_DATA.TAG_NO
								FROM              UTT_FORM_DATA INNER JOIN	UTT_TAGNO_DATA ON UTT_FORM_DATA.[index] = UTT_TAGNO_DATA.form1 INNER JOIN UTT_CHECK_DATA ON UTT_TAGNO_DATA.TAG_NO = UTT_CHECK_DATA.TAG_NO
								WHERE          (UTT_FORM_DATA.[index] = ".$_POST['form'].") AND (UTT_CHECK_DATA.[date] = '".$value_datetime."')";
			
				$result=mssql_query($query);
				while($row=mssql_fetch_array($result)){
					$tid=trim($row['TAG_NO']);
					$value=trim($row['data']);
					for($ii=0;$ii <= ($highestRow-$row_start);$ii++){
						if($tid==$tagno[$ii]){
							$rtn_ok=check_value($spec[$ii],$value);
							$BG='bgcolor="#FFFF66"';
							if($rtn_ok == 0 ){
								$color='FFFF30';
			//					echo $spec[$ii].":".$value."<BR>";
							}
						else{$color='FAFAFA';}
						$objPHPExcel->getActiveSheet()->getStyle($ddk.($row_start+$ii))->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' =>$color))); 	
						
							
						//	echo $rtn_ok."<BR>";
							$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_write_start,($row_start+$ii),$value);  // Á¥Ä???
							//PHPExcel_Cell::columnIndexFromString  
							//PHPExcel_Cell::stringFromColumnIndex  
							$ddk=PHPExcel_Cell::stringFromColumnIndex($col_write_start);
							$objPHPExcel->getActiveSheet()->getStyle($ddk.($row_start+$ii))->applyFromArray($styleThinBlackBorderOutline); ///µe¿x¶sÆÊ•~Æÿ
						}
					}
				}
			
				$saveusername=saveid($_POST['form'],$value_datetime);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_write_start,$highestRow+1,iconv("big5","utf-8",$saveusername));
				
				$str=$_POST['form']."_".$value_datetime;
				
				$users=sign_user_name($str);
				
				$usersingle=explode(",",$users);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_write_start,$highestRow+2,iconv("big5","utf-8",$usersingle[0]));  // •D•ÙÖ•
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_write_start,$highestRow+4,iconv("big5","utf-8",$usersingle[1]));  // •D∫ﬁÖ•

				$col_write_start++;
			}
			
			$EE=$row_start-1;
		
			$symbo=PHPExcel_Cell::stringFromColumnIndex($col_write_start);
			$objPHPExcel->getActiveSheet()->getStyle("A".$EE.":".$symbo.$EE)->getNumberFormat()->setFormatCode('########');
		}
		//		ÂØ´ÂÖ•Á∞ΩÊ†∏title
			
			
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($col_start+3-1),($highestRow+1),iconv("big5","utf-8","®µ¿À§H≠˚"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($col_start+3-1),($highestRow+2),iconv("big5","utf-8","•D•Ù√±Æ÷"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($col_start+3-1),($highestRow+3),iconv("big5","utf-8","•D•Ù≥∆µ˘"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($col_start+3-1),($highestRow+4),iconv("big5","utf-8","•D∫ﬁ√±Æ÷"));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(($col_start+3-1),($highestRow+5),iconv("big5","utf-8","•D∫ﬁ≥∆µ˘"));
			
			
			
			$rc=($col_write_start-1);
			$str_end=PHPExcel_Cell::stringFromColumnIndex($rc);
			$sm=PHPExcel_Cell::stringFromColumnIndex($rc).$highestRow;

			//PHPExcel_Cell::columnIndexFromString  
			//PHPExcel_Cell::stringFromColumnIndex  
			
			$KS=$col_start_.($row_start-1).":".$str_end.($row_start-1);
			
//			echo $col_start_.($row_start-1).":".$sm;
			
			$objPHPExcel->getActiveSheet()->getStyle($KS)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle($sn.":".$sm)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle($sn.":".$sm)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);			
	}
	//echo '</table>';
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
	$A=explode("(",formname($_SESSION['form']));
	$_SESSION['filename']=$A[0].'('.des($_SESSION['datepicker7']).').xlsx';
	$objWriter->save('../PRODUCE/Download/Form'.$_POST['form'].'_'.des($_SESSION['datepicker7']).'.xlsx');
	
	$A=explode("(",formname($_SESSION['form']));
	$path_root=$_SERVER['HTTP_HOST'];
	if($_SESSION['pid']=='')
	{
		echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/Download/Form'.$_POST['form'].'_'.des($_SESSION['datepicker7']).'.xlsx";</script>';
		if($_SESSION['way']=='A'){$form='form1';}else{$form='form';}
		$query="SELECT distinct UD.TAG_NO from UTT_CHECK_DATA UD left join UTT_TAGNO_DATA UN ON UD.TAG_NO=UN.TAG_NO where ".$form."=".$_SESSION['form']."";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			unset($_SESSION[trim($row[0])]);
		}
	}
	else
	{
		echo '<script>document.location.href="http://'.$path_root.'/PRODUCE/Download/'.$_SESSION['pid'].'_'.des($_SESSION['datepicker7']).'.xlsx";</script>';
	}
	unset($_SESSION['time1'],$_SESSION['time2']);
}

function saveid($form,$datetime){
	$query="SELECT DISTINCT EMPLOYEE_DATA.EMP_NAME
FROM              UTT_FORM_DATA INNER JOIN
                            UTT_TAGNO_DATA ON UTT_FORM_DATA.[index] = UTT_TAGNO_DATA.form1 INNER JOIN
                            UTT_CHECK_DATA ON UTT_TAGNO_DATA.TAG_NO = UTT_CHECK_DATA.TAG_NO INNER JOIN
                            EMPLOYEE_DATA ON UTT_CHECK_DATA.saveuid = EMPLOYEE_DATA.EMP_NO 
WHERE          (UTT_FORM_DATA.[index] = ".$form.") AND (UTT_CHECK_DATA.[date] = '".$datetime."') and   (UTT_CHECK_DATA.saveuid <> N'')";

	try{
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$savername=$row[0];
		unset($result,$row);
		return $savername;
	} catch (Exception $e) {
		return "";
	}
}

function check_value($spec1,$value){
	$spec1=trim($spec1);
	$spec_dis=trim(substr($spec1,0,1));
	$spec_value=trim(substr($spec1,1));
	if($spec1==''){
		return 1;
	}
	elseif (strpos($spec1,"~") !== false){
		$aa=explode("~",$spec1);
		$left=trim($aa[0]);
		$right=trim($aa[1]);
		if($value >= $left and $value <= $right){
			return 1;
		}
		else{
			return 0;
		}
	}
	elseif($spec_dis=='>' or $spec_dis=='<' or $spec_dis=='='){
	//	echo "Value:".$value."spec_dis:".$spec_dis."spec_value:".$spec_value."<BR>";
		if($spec_dis=='='){
			if($value = $spec_value){return 1;}
			else{return 0;}
		}
		elseif($spec_dis=='<'){
			if($value <= $spec_value){return 1;}
			else{return 0;}			
		}
		elseif($spec_dis=='>'){
//			echo "Value:".$value."spec_dis:".$spec_dis."spec_value:".$spec_value."<BR>";
			if($value >= $spec_value){return 1;}
			else{return 0;}
		}
	}
	else{
		return 1;
	}
}
	
function sign_user_name($sigid){
	$query="SELECT   EMPLOYEE_DATA.EMP_NAME AS sig1, EMPLOYEE_DATA_1.EMP_NAME AS sig2
FROM              UTT_DATA_SIGN LEFT OUTER JOIN
                            EMPLOYEE_DATA ON UTT_DATA_SIGN.SIGN1 = EMPLOYEE_DATA.EMP_NO LEFT OUTER JOIN
                            EMPLOYEE_DATA AS EMPLOYEE_DATA_1 ON UTT_DATA_SIGN.SIGN2 = EMPLOYEE_DATA_1.EMP_NO
WHERE          (RTRIM(UTT_DATA_SIGN.SAG_NO) = N'".$sigid."') AND (UTT_DATA_SIGN.SIGN1 <> N'')";

	try{
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$sd=$row[0].",".$row[1];
		unset($result,$row);
		return $sd;
	}catch (Exception $e) {
		return ' , ';
	}
}

function times_($a,$form){
	$query="SELECT DISTINCT RIGHT(RTRIM(UTT_CHECK_DATA.[date]), 4) AS a1
FROM              UTT_FORM_DATA INNER JOIN
                            UTT_TAGNO_DATA ON UTT_FORM_DATA.[index] = UTT_TAGNO_DATA.form1 INNER JOIN
                            UTT_CHECK_DATA ON UTT_TAGNO_DATA.TAG_NO = UTT_CHECK_DATA.TAG_NO
WHERE          (UTT_FORM_DATA.[index] = ".$form.") AND (LEFT(LTRIM(UTT_CHECK_DATA.[date]), 8) = '".$a."')
ORDER BY   a1";
	$aa=array();
 	$result=mssql_query($query);
 	while($row=mssql_fetch_array($result)){
	array_push($aa,$row[0]);
 	}
 	unset($result,$row);
 	return $aa;
}

function rt_data($form_id,$datetime,$tag_no){
	$query="SELECT          TOP (1) UTT_CHECK_DATA.Data
FROM              UTT_FORM_DATA INNER JOIN
                            UTT_TAGNO_DATA ON UTT_FORM_DATA.[index] = UTT_TAGNO_DATA.form1 INNER JOIN
                            UTT_CHECK_DATA ON UTT_TAGNO_DATA.TAG_NO = UTT_CHECK_DATA.TAG_NO
WHERE          (UTT_FORM_DATA.[index] = ".$form_id.") AND (UTT_CHECK_DATA.[date] = '".$datetime."') AND 
                            (UTT_CHECK_DATA.TAG_NO = '".$tag_no."')";
  $result=mssql_query($query);
  $row=mssql_fetch_row($result);
  $SSDDS=$row[0];
  unset($result,$row);
  return $SSDDS;
}

function remark()
{
	echo '<script>
	var name=prompt("≥∆µ˘(¶pµL∂∑∂Ò§J™Ω±µ´ˆΩT©w)",""); 
	if(name==""){location.href="index.php?url=UTT_check&remark=empty";}else{location.href="index.php?url=UTT_check&remark=" + name;}
	</script>';
}
function dateform1($d1)  //2017/01/01 => 20170101
{	
	$rt=str_replace("/","",$d1);
	return $rt;
}
function formname($d1)  //2017/01/01 => 20170101
{	
	$query="select * from UTT_FORM_DATA where [index]='".$d1."'";
	$result=mssql_query($query);
	$row=mssql_fetch_array($result);
	$rt=trim($row['Form']);
	return $rt;
}
function hr_duteies($val)
{
	require_once("../connections/conn_hr.php");
	$query2="select duties from EMPLOYEE_DATA where empno='".$val."'";
	$result2=mssql_query($query2);
	$row2=mssql_fetch_array($result2);
	$sign1=array();
	if(trim($row2[0])>=7 and trim($row2[0])<17 or substr($_SESSION['uid'],1)=='589'){$_SESSION['director']=2;$sign1[0]='<input type="submit" name="sign1" value="ßÂ¶∏√±Æ÷" style="background-color:green;">';}
	if(substr($_SESSION['uid'],1)=='319' or substr($_SESSION['uid'],1)=='029' or substr($_SESSION['uid'],1)=='066' or substr($_SESSION['uid'],1)=='1048' or substr($_SESSION['uid'],1)=='1303' or substr($_SESSION['uid'],1)=='1263' or substr($_SESSION['uid'],1)=='1103' or substr($_SESSION['uid'],1)=='1044' or substr($_SESSION['uid'],1)=='1041' or substr($_SESSION['uid'],1)=='1100' or substr($_SESSION['uid'],1)=='1065'){$_SESSION['director']=1;$sign1[1]='<input type="submit" name="sign2" value="ßÂ¶∏√±Æ÷" style="background-color:green;">';
	$sign1[0]='<input type="submit" name="sign1" value="ßÂ¶∏√±Æ÷" style="background-color:green;">';
	}
	// include("../connections/conn.php");
	return $sign1;
	
}
?>
</table>
</br>