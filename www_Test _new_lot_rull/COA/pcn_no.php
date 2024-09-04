<?php 
session_start();
include("../lib/fun.php");
include("../checkuser.php");
include("../lib/jtsai.php");
include("../connections/conn.php");
lasturl();
datepick();
 include("../lib/user_right.php");
$a1=array_user_group($_SESSION['uid'],'PCN');
if($a1==0){ $disable1=' disabled="disabled" ';}else{ $disable1='';}
if($_SESSION['datepicker1']==''){$_SESSION['datepicker1']=date("m/d/Y")   ;}
if($_SESSION['datepicker2']==''){$_SESSION['datepicker2']=date("m/d/Y")   ;}
?>
<form name="form1" method="post" action="<?php echo $loginFormAction; ?>">
  <label for="textfield"></label>

  <table width="1240" border="1">
    <tr>
      <td width="480">要求日期 
        <input name="datepicker1" type="text" id="datepicker1" size="14" value="<?php echo $_SESSION['datepicker1'];?>" onchange="set_date_session(this.name,this.value)">
        ~
        <input name="datepicker2" type="text" id="datepicker2" size="14" value="<?php echo $_SESSION['datepicker2'];?>" onchange="set_date_session(this.name,this.value)">

        
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;品名：<span class="d1">
        <input type="button" name="X" id="X" value="X" onClick="window.open('../erase_prod.php ', '_self');">
        <input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
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
        <input type="button" name="pdd_no" id="pdd_no" value="查詢品名" onClick="window.open('../pdd_prod_no.php ', '_self');" >
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
		?>" >
    <BR>
    Lot No：
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('../erase_lot.php ', '_self');" />
<input name="lid" type="text" id="lid" size="10" value="<?php 
	if($_SESSION['lid']){
		echo $_SESSION['lid'];
	}
	else{
		echo '';
	}
		?>"  onchange="set_date_session(this.name,this.value)">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="search" id="search" value="    搜  尋   " />
				&nbsp;&nbsp;&nbsp;<input type="button" name="exit" id="exit" value="離開" />
          <input type="hidden" name="mm_insert" id="mm_insert" value="form1" />
        </span></td>
    </tr>
  </table>
<?php
	echo '<table width="800" border="1"><tr bgcolor="#CCCCCC"><td width="120" align="center">日期</td><td width="130" align="center">批號</td><td align="center">料號
	</td><td align="center">客戶編號</td><td align="center">客戶名稱</td><td colspan="2" align="center">PCN &nbsp;&nbsp;';
	if($a1==1){ echo '<input type="submit" name="submit" value="確定">&nbsp;&nbsp;&nbsp;<input type="submit" name="delete" value="刪除">';}
	echo '</td></tr>';
	$query="SELECT AnalyzeDesign.*, CUSTOMER_DATA.CTD_CUST_NAME FROM AnalyzeDesign INNER JOIN CUSTOMER_DATA ON AnalyzeDesign.CTD_CUST_NO = CUSTOMER_DATA.CTD_CUST_NO
	 WHERE (AND_NEED_NO < 150) ";
	if($_POST['lid']==''){
		$query.=" AND AND_SMP_DATETIME >='".dod($_SESSION['datepicker1'])."0000' AND AND_SMP_DATETIME <='".dod($_SESSION['datepicker2'])."2359'";
	}else{
		$query.=" AND AND_LOT_NO='".$_POST['lid']."' ";
	}	
	if($_POST['pdd_chemical1']<>''){
		$query.=" AND AND_GOODS ='".$_POST['pdd_chemical1']."' ";		
	}
	$query=$query." order by AND_LOT_NO";
//	echo $query."<BR>";
	$result=mssql_query($query);
	$i=0;
	while($row=mssql_fetch_array($result)){
	/*
		if($_SESSION['pcn_no'.$i]!=''){
			$_SESSION['pcn_no'.$i]=$row['PCN_NO'];
		}
	*/
			if($row['PCN']==1){
				$pcn='<input type="checkbox" name="chkbox[]" checked value="'.$_SESSION['pcn_no'.$i].','.$row['AND_LOT_NO'].','.$row['PCN'].'">
				</td><td align="center"><input type="text" name="pcn_no[]'.'" value="'.trim($row['PCN_NO']).'" size="5" >是';
			}
			else{
				$pcn='<input type="checkbox" name="chkbox[]" value="'.$_SESSION['pcn_no'.$i].','.$row['AND_LOT_NO'].','.$row['PCN'].'">
				</td><td align="center"><input type="text" name="pcn_no[]'.'" value="'.trim($row['PCN_NO']).'" size="5" >否';
			}		
		echo '<tr>';
		echo '<td  align="center">'.$row['AND_SMP_DATETIME'].'</td><td align="center">'.$row['AND_LOT_NO'].'</td><td align="center">'.$row['AND_GOODS'].'</td><td align="center">'.$row['CTD_CUST_NO'].'</td><td align="center">'.$row['CTD_CUST_NAME'].'</td><td align="center">'.$pcn.'</td>';
		echo '</tr>';
		$i++;
	}
	echo '</table></form>';
	echo "<BR>";
	if(isset($_POST['submit'])){
		$ck=$_POST['chkbox'];
		$cl=$_POST['pcn_no'];
		$num=count($ck);
		for($i=0;$i<$num;$i++){
			$aa=explode(",",$ck[$i]);
			$cc=$cl[$i];
			echo "CC".$aa[2].":".$aa[1].":".$cc."<BR>";
			write_pcn($aa[2],$aa[1],$cc);
		}
	refresh();
	}
	
		if(isset($_POST['delete'])){
		$ck=$_POST['chkbox'];
		$num=count($ck);
		for($i=0;$i<$num;$i++){
			$aa=explode(",",$ck[$i]);
			delete_item($aa[1]);
		}
		refresh();
	}
function delete_item($lotno){
	$query="update AnalyzeDesign set PCN=0, PCN_NO='' where AND_LOT_NO='".$lotno."'";
	$result=mssql_query($query);
}	
function write_pcn($pcn,$lotno,$pcn_no){
	if($pcn==1){
		$query="update AnalyzeDesign set PCN=0, PCN_NO='".$pcn_no."' where AND_LOT_NO='".$lotno."'";
		echo $query."<BR>";
	}
	else{
		$query="update AnalyzeDesign set PCN=1, PCN_NO='".$pcn_no."' where AND_LOT_NO='".$lotno."'";
		echo $query."<BR>";
	}
	$result=mssql_query($query);
}
?>