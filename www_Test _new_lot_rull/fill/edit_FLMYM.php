<?php
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
remurl("flmym");	
datepick();
$t=0;
$day="FLM_DAY".$_GET['day'];
$query="SELECT DISTINCT 
                   CUSTOMER_PRODUCTS.CTP_SEL_LY_TOTO, FILLPLAN_LORRY_MONTH.".$day." as dayx
FROM      CUSTOMER_PRODUCTS INNER JOIN
                   FILLPLAN_LORRY_MONTH ON CUSTOMER_PRODUCTS.CTD_CUST_NO = FILLPLAN_LORRY_MONTH.CTD_CUST_NO AND
                    CUSTOMER_PRODUCTS.PDD_PROD_NO = FILLPLAN_LORRY_MONTH.PDD_PROD_NO INNER JOIN
                   FILLPLAN_OUT_DECIDE ON FILLPLAN_LORRY_MONTH.CTD_CUST_NO = FILLPLAN_OUT_DECIDE.CTD_CUST_NO AND 
                   FILLPLAN_LORRY_MONTH.PDD_PROD_NO = FILLPLAN_OUT_DECIDE.PDD_PROD_NO AND 
                   FILLPLAN_LORRY_MONTH.FLM_YEAR_MONTH = FILLPLAN_OUT_DECIDE.FOD_YEAR_MONTH 
WHERE          (FILLPLAN_LORRY_MONTH.FLM_YEAR_MONTH = '".$_GET['ym']."') AND (FILLPLAN_LORRY_MONTH.CTD_CUST_NO = '".$_GET['cn']."') AND 
                            (FILLPLAN_LORRY_MONTH.PDD_PROD_NO = '".$_GET['pn']."') AND (FILLPLAN_OUT_DECIDE.FOD_O_DAY = '".$_GET['day']."')";		
// echo "<BR>".$query."<BR>";	
$result = mssql_query($query);
$numRows = mssql_num_rows($result);
if(!$numRows){
	$query="SELECT          CTD_CUST_NO, PDD_PROD_NO, CTP_SEL_LY_TOTO
	FROM              dbo.CUSTOMER_PRODUCTS
	WHERE          (CTD_CUST_NO = '".$_GET['cn']."') AND (PDD_PROD_NO = '".$_GET['pn']."')";	
	$result = mssql_query($query);
	$numRows = mssql_num_rows($result);
}
// echo "<BR>".$query."<BR>";	
while($row = mssql_fetch_array($result)){
?>
<table width="250" border="1" align="left">
<form id="form1" name="form1" method="post" action="">
<tr width="200" align="center">
<?php
$str=$row['CTP_SEL_LY_TOTO'];
$str2=$row[$day];
$new="";
               $array=explode(";",$str);
			   if(count($array)!=0)
			   {
				   foreach($array as $value)
				   {
					   $data=explode(":",$value);
                       if (preg_match("/".$data[0]."/i",$str2)){$sw=0;}
					   else {$sw=1;}
?>


						<td width="10" align="center"><?php echo $data[0];?>
                        <input type="hidden" name="ym" value="<?php echo $_GET['ym'];?>" />
                        <input type="hidden" name="cn" value="<?php echo $_GET['cn'];?>" />
                        <input type="hidden" name="day" value="<?php echo $_GET['day'];?>" />
                        <input type="hidden" name="pn" value="<?php echo $_GET['pn'];?>" />
                        <input type="hidden" name="new" value="<?=$new;?>" />
                        <?php
						
						$sn=csn($_GET['ym'],$_GET['day'],$data[0]);
						if(($_GET['pn']=='UP004-000' and ($_GET['cn']=='C13116' or $_GET['cn']=='C13142' or $_GET['cn']=='C13146' or $_GET['cn']=='C13150')) or $_GET['pn']=='P001-000')
						{
							if($_SESSION['select'.$t]=='' and $sn=='NULL'){$s0='selected="selected"';$s1=$s2=$s3=$s4=$s5=$s6='';}
						
							elseif($_SESSION['select'.$t]=='' and $sn<>'')
							{
								
										if($sn=='1'){$s1='selected="selected"'; $s0=$s2=$s3=$s4=$s5=$s6='';}
								elseif($sn=='2'){$s2='selected="selected"'; $s1=$s0=$s3=$s4=$s5=$s6='';}
								elseif($sn=='3'){$s3='selected="selected"'; $s1=$s2=$s0=$s4=$s5=$s6='';}
								elseif($sn=='4'){$s4='selected="selected"'; $s1=$s2=$s3=$s0=$s5=$s6='';}
								elseif($sn=='5'){$s5='selected="selected"'; $s1=$s2=$s3=$s4=$s0=$s6='';}
								elseif($sn=='6'){$s6='selected="selected"'; $s1=$s2=$s3=$s4=$s5=$s6='';}
							}
							
							elseif(isset($_SESSION['select'.$t]))
							{
							//	echo "#".$_SESSION['select'.$t]."#";
							
										if($_SESSION['select'.$t]=='1' ){$s1='selected="selected"'; $s0=$s2=$s3=$s4=$s5=$s6='';}
								elseif($_SESSION['select'.$t]=='2' ){$s2='selected="selected"'; $s1=$s0=$s3=$s4=$s5=$s6='';}
								elseif($_SESSION['select'.$t]=='3' ){$s3='selected="selected"'; $s1=$s2=$s0=$s4=$s5=$s6='';}
								elseif($_SESSION['select'.$t]=='4' ){$s4='selected="selected"'; $s1=$s2=$s3=$s0=$s5=$s6='';}
								elseif($_SESSION['select'.$t]=='5' ){$s5='selected="selected"'; $s1=$s2=$s0=$s4=$s0=$s6='';}
								elseif($_SESSION['select'.$t]=='6' ){$s6='selected="selected"'; $s1=$s2=$s3=$s0=$s5=$s0='';}
							}
							else{$s0=$s1=$s2=$s3=$s4='';echo $sn;}
						echo '
							<select name="select'.$t.'" >
								<option value="NULL" '.$s0.'></option>
								<option value="1" '.$s1.'> 1 </option>
								<option value="2" '.$s2.'> 2 </option>
								<option value="3" '.$s3.'> 3 </option>
								<option value="4" '.$s4.'> 4 </option>
								<option value="5" '.$s5.'> 5 </option>
								<option value="6" '.$s6.'> 6 </option>
							</select>';}
	 						
						if($_GET['pn']=='UP004-000D' and ($_GET['cn']=='C21319')){
						echo '
							<select name="select'.$t.'" >
								<option value="NULL" '.$s0.'></option>
								<option value="1" '.$s1.'> 1 </option>
								<option value="2" '.$s2.'> 2 </option>
								<option value="3" '.$s3.'> 3 </option>
								<option value="4" '.$s4.'> 4 </option>
								<option value="5" '.$s5.'> 5 </option>
								<option value="6" '.$s6.'> 6 </option>
							</select>';}
						 
						?>
                        
					    <input type="checkbox" name="chkbox[]" value="<?php echo $t; ?>" id="<?php echo $data[0];?>"                   
                         <?php ///20161125
							if($sw==0 and !isset($_SESSION[$data[0]]))
							{
							echo "Checked";

							}
							elseif($sw==1 and !isset($_SESSION[$data[0]]))
							{
							echo "";
							$_SESSION[$data[0]]=1;
							}
							elseif($_SESSION[$data[0]]=='0')
							{
								echo "checked";
							}
							elseif($_SESSION[$data[0]]=='1')
							{
								echo "";
								$_SESSION[$data[0]]=1;
							}							
						?>
                        />
</td>
</tr>

							<?php	
							echo '<input type="hidden" name="lorry_no'.$t.'" value="'.$data[0].'" />'		;	
							$aa[$t][1]=$data[0];
							$t=$t+1;			} ?>

							<tr><td align="center"><input type="submit" name="submit" id="submit" value="  確定 / 離開  ">
							    <input type="submit" name="cancel" id="cancel" value="  取消  " />
							</form>
							</td></tr>
							<?php
    		  	}
						echo '</tr></form></table></br>';	
					}
mssql_close($dbhandle);
?>
</p>

<p> <span class="d1">
</span></p>
<?php

$myallsport = implode(";",$sport);

if(isset($_POST['cancel']))
{
	keep_session();
	echo '<script type="text/javascript">';
	echo 'window.close()';
	echo '</script>';
//	header("Location:index.php?url=fill_monthly_r&ym=".$_SESSION['ym']);
}

if(isset($_POST['submit'])){
	$cc=$_POST['chkbox'];
	$n=count($cc);		
	for($x=0;$x<$n;$x++)
	{		
			;
			$query="SELECT *
					FROM              dbo.FILLPLAN_OUT_DECIDE
					WHERE          (FOD_O_YEAR_MONTH = '".$_POST['ym']."') AND (FOD_O_DAY = '".$_POST['day']."') AND (FDM_LY_NO='".$aa[$cc[$x]][1]."')";       
//			echo $query."</br><BR>";      
			$result = mssql_query($query);
			$numRows = mssql_num_rows($result);	
			while($row=mssql_fetch_array($result))
			{
				$fodym=$row['FOD_YEAR_MONTH'];
				$fodday=$row['FOD_DAY'];
				$flo=$row['FDM_LY_NO'];
			}
			
			if($numRows==0)
			{
				if($_POST['select'.$cc[$x]]==''){$_POST['select'.$cc[$x]]='NULL';}
				$query="INSERT INTO dbo.FILLPLAN_OUT_DECIDE
									(LY_SN, FOD_O_YEAR_MONTH, FOD_O_DAY, CTD_CUST_NO, PDD_PROD_NO, FDM_LY_NO, FDM_CREATE_DATE, FDM_CREATOR, FDM_SPECIFIC, FDM_QTY_UNIT, FDM_PURGE)
						VALUES          (".$_POST['select'.$cc[$x]].", '".$_POST['ym']."','".$_POST['day']."','".$_POST['cn']."','".$_POST['pn']."','".$aa[$cc[$x]][1]."','".date("Ymdhis")."','".$_SESSION['uid']."','LY','KG','0')";	
				echo $query."<BR><BR>";     	
				$result = mssql_query($query);
			}	
			
			$myallsport=$myallsport.$aa[$cc[$x]][1].";"  ;
			
	}
		$myallsport=substr($myallsport,0,-1);
		$query="select * from FILLPLAN_LORRY_MONTH WHERE (FLM_YEAR_MONTH = '".$_POST['ym']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND (CTD_CUST_NO = '".$_POST['cn']."')";
		$result = mssql_query($query);
		$numRows=mssql_num_rows($result);
			if($numRows>0){
				$query="UPDATE          FILLPLAN_LORRY_MONTH
								SET                   FLM_DAY".$_POST['day']." = '".$myallsport."', FLM_CREATOR".$_POST['day']." = '".$_SESSION['uid']."'
								WHERE          (CTD_CUST_NO = '".$_POST['cn']."') AND (PDD_PROD_NO = '".$_POST['pn']."') AND (FLM_YEAR_MONTH = '".$_POST['ym']."')";	
			}
			
			if($numRows<1){
				$query="INSERT INTO FILLPLAN_LORRY_MONTH
											 (FLM_YEAR_MONTH, CTD_CUST_NO, PDD_PROD_NO, FLM_DAY".$_POST['day'].")
									VALUES          ('".$_POST['ym']."','".$_POST['cn']."','".$_POST['pn']."','".$myallsport."') ";
			}	
			echo $query."<BR><BR>";
			$result = mssql_query($query);
			
	echo '<script type="text/javascript">';
	echo 'window.close()';
	echo '</script>';
}

function csn($ym_o,$day_o,$lysn)
{
	$query="SELECT  LY_SN FROM FILLPLAN_OUT_DECIDE WHERE (FOD_O_DAY = '".$day_o."') AND (FDM_LY_NO = '".$lysn."') AND (FOD_O_YEAR_MONTH = '".$ym_o."')";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
}
?>
