<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include("../connection/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");

class sign_in
{
	public $sa_no,$cbt_no,$create_time,$create_man,$finished_memo,$finished_time,$terminate,$comfirm_count,$cfm1;
	public $sai_serial_no,$aut_no,$emp_no,$sai_ok_ng,$sai_time,$sai_memo,$sa_no_decision;	
	public $row1,$row2,$row3,$row4,$dep,$t1,$t2,$t3,$t4,$row1_,$row2_,$row3_,$row4_,$order_no,$ret,$aut_no_now,$ok_ng,$cancel;
	public $otd_no,$po_no,$invoice_no,$cust_no,$create_date,$eta_date,$tainan_cache,$opm_stock,$aa,$aa_sai_ok,$aa_sai_time,$aa_emp_no,$aa_aut_name,$numrows,$showlot,$aa_sai_memo;
	function dep()
	{
		include("../connections/conn.php");
		$query="SELECT         dbo.AUTHORITY_DATA.*
		FROM             dbo.AUTHORITY_DATA where (AUT_NO='".$this->aut_no."')";
		
		$result=mssql_query($query);	
		while($row=mssql_fetch_array($result)){
			$this->dep=$row['AUT_NAME'];			
		}
	}//end dep
	function sag_no_from_order_no_outplan(){
		$query="SELECT          OPM_ORDER_NO, SAG_NO
		FROM              OUT_PLAN
		WHERE          (OPM_ORDER_NO = '".$this->order_no."')";
		$result=mssql_query($query);	
		while($row=mssql_fetch_array($result)){
			$this->sa_no=$row['SAG_NO'];
		}
	}
	function sag_no_from_order_no_outdecision(){
		$query="SELECT          OPM_ORDER_NO, SAG_NO
		FROM              OUT_DECISION
		WHERE          (OPM_ORDER_NO = '".$this->order_no."')";
		$result=mssql_query($query);	
		while($row=mssql_fetch_array($result)){
			$this->sa_no=$row['SAG_NO'];
		}
	}
	function read_agree(){
		include("../connections/conn.php");
		$query="SELECT          SIGN_AGREE.CBT_NO, SIGN_AGREE.SAG_CREATE_TIME, SIGN_AGREE.SAG_CREATE_MAN, 
                            SIGN_AGREE.SAG_FINISHED_MEMO, SIGN_AGREE.SAG_FINISHED_TIME, SIGN_AGREE.SAG_TERMINATE_TIME, 
                            SIGN_AGREE.SAG_COMFIRM_COUNT, SIGN_AGREE_ITEM.SAG_NO, SIGN_AGREE_ITEM.SAI_SERIAL_NO, 
                            SIGN_AGREE_ITEM.AUT_NO, SIGN_AGREE_ITEM.EMP_NO, SIGN_AGREE_ITEM.SAI_OK_NG, 
                            SIGN_AGREE_ITEM.SAI_TIME, SIGN_AGREE_ITEM.SAI_MEMO
FROM              SIGN_AGREE INNER JOIN
                            SIGN_AGREE_ITEM ON SIGN_AGREE.SAG_NO = SIGN_AGREE_ITEM.SAG_NO
				WHERE       (SIGN_AGREE.SAG_NO = ".$this->sa_no.")  ";
		if($this->aut_no<>''){$query.=" and (SIGN_AGREE_ITEM.AUT_NO='".$this->aut_no."') ";}	
		$query.=" order by SAI_SERIAL_NO";
		$result=mssql_query($query);	
		while($row=mssql_fetch_array($result)){
			$this->create_man=$row['SAG_CREATE_MAN'];
			$this->create_time=$row['SAG_CREATE_TIME'];
			$this->emp_no=$row['EMP_NO'];
			$this->sai_ok_ng=$row['SAI_OK_NG'];
			$this->sai_time=$row['SAI_TIME'];
			$this->sai_memo=$row['SAI_MEMO'];
			$this->finished_time=$row['SAG_FINISHED_TIME'];
			$this->finished_memo=$row['SAG_FINISHED_MEMO'];
		}
	}// read_agree
	function read_agree_item(){
		include("../connections/conn.php");
		$query="SELECT          SIGN_AGREE_ITEM.*, SIGN_AGREE.SAG_FINISHED_MEMO, SIGN_AGREE.SAG_FINISHED_TIME, 
                            AUTHORITY_DATA.AUT_NAME, SIGN_AGREE.SAG_CREATE_MAN, SIGN_AGREE.SAG_CREATE_TIME
				FROM              SIGN_AGREE_ITEM INNER JOIN
                            SIGN_AGREE ON SIGN_AGREE_ITEM.SAG_NO = SIGN_AGREE.SAG_NO INNER JOIN
                            AUTHORITY_DATA ON SIGN_AGREE_ITEM.AUT_NO = AUTHORITY_DATA.AUT_NO
				WHERE          (SIGN_AGREE_ITEM.SAG_NO = ".$this->sa_no.")";
		$result=mssql_query($query);	
		$p=0;
		while($row=mssql_fetch_array($result)){
			$this->create_man=$row['SAG_CREATE_MAN'];
			$this->create_time=$row['SAG_CREATE_TIME'];	
			$this->finished_time=$row['SAG_FINISHED_TIME'];
			$this->finished_memo=$row['SAG_FINISHED_MEMO'];
			$this->aa_emp_no[$p]=$row['EMP_NO'];
			$this->aa_sai_ok[$p]=$row['SAI_OK_NG'];
			$this->aa_sai_time[$p]=$row['SAI_TIME'];
			$this->aa_aut_name[$p]=$row['AUT_NAME'];
			$this->aa_sai_memo[$p]=$row['SAI_MEMO'];
			$p=$p+1;
		}
	}
	function row(){
		include("../connections/conn.php");
		$this->row1='<tr align="center" bgcolor="#CCCCCC"><td width="200">簽核一覽表</td><td width="80">簽核單位</td><td width="150">製表人員：'.get_uname($this->create_man).'</td>';
		
		if($this->finished_time<>''){$status="已確認";}else{$status="未確認";}
		$this->row2_='<td>'.$status.'</td></tr>';		
		$this->row3_='<td>'.sta($this->finished_time).'</td></tr>';
		$this->row4_='<td>'.$this->finished_memo.'</td></tr>';	
		
		$this->row2='<tr align="center"><td width="100">簽核輸入</td><td>簽核結果</td><td> N/A </td>';
		$this->row3='<tr align="center"><td width="100">'.$this->cancel.'</td><td width="60">簽核時間</td><td>'.sta($this->create_time).'</td>';
		$this->row4='<tr align="center" height="120"><td align="left" width="150" >意見輸入'.$this->ret.'</br><textarea name="memo" cols="20" rows="5"></textarea></td><td>意見</td><td> N/A </td>';
		$this->row1_='<td width="150">最後確認：'.get_uname($this->create_man).'</td></tr>';	
		
		$this->capinility();
		$this->aa=explode(";",$this->cfm1);
		for($i=0;$i<count($this->aa);$i++){
			$this->aut_no=$this->aa[$i];
			$this->dep();
			$this->read_agree();
			$this->t1.='<td width="150">'.$this->dep.":".get_uname($this->emp_no).'</td>';
			$this->t2.='<td>'.$this->sai_ok_ng.'</td>';
			$this->t3.='<td>'.sta($this->sai_time).'</td>';
			$this->t4.='<td>'.$this->sai_memo.'</td>';
		}	
		
		$query="SELECT          SIGN_AGREE.CBT_NO, SIGN_AGREE.SAG_CREATE_TIME, SIGN_AGREE.SAG_CREATE_MAN, 
                            SIGN_AGREE.SAG_FINISHED_MEMO, SIGN_AGREE.SAG_FINISHED_TIME, SIGN_AGREE.SAG_TERMINATE_TIME, 
                            SIGN_AGREE.SAG_COMFIRM_COUNT, SIGN_AGREE_ITEM.SAG_NO, SIGN_AGREE_ITEM.SAI_SERIAL_NO, 
                            SIGN_AGREE_ITEM.AUT_NO, SIGN_AGREE_ITEM.EMP_NO, SIGN_AGREE_ITEM.SAI_OK_NG, 
                            SIGN_AGREE_ITEM.SAI_TIME, SIGN_AGREE_ITEM.SAI_MEMO
FROM              SIGN_AGREE INNER JOIN
                            SIGN_AGREE_ITEM ON SIGN_AGREE.SAG_NO = SIGN_AGREE_ITEM.SAG_NO
				WHERE       (SIGN_AGREE.SAG_NO = ".$this->sa_no.") and (EMP_NO is null) order by SAI_SERIAL_NO desc";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result)){
			$_SESSION['tsmp']=$this->aut_no_now=$row['AUT_NO'];
		}
	}///end row
	
	function show()
	{
		$query="SELECT      SIGN_AGREE.CBT_NO, SIGN_AGREE.SAG_CREATE_TIME, SIGN_AGREE.SAG_CREATE_MAN, 
                            SIGN_AGREE.SAG_FINISHED_MEMO, SIGN_AGREE.SAG_FINISHED_TIME, SIGN_AGREE.SAG_TERMINATE_TIME, 
                            SIGN_AGREE.SAG_COMFIRM_COUNT, SIGN_AGREE_ITEM.SAG_NO, SIGN_AGREE_ITEM.SAI_SERIAL_NO, 
                            SIGN_AGREE_ITEM.AUT_NO, SIGN_AGREE_ITEM.EMP_NO, SIGN_AGREE_ITEM.SAI_OK_NG, 
                            SIGN_AGREE_ITEM.SAI_TIME, SIGN_AGREE_ITEM.SAI_MEMO
				FROM              SIGN_AGREE INNER JOIN
                            SIGN_AGREE_ITEM ON SIGN_AGREE.SAG_NO = SIGN_AGREE_ITEM.SAG_NO
				WHERE       (SIGN_AGREE.SAG_NO = ".$this->sa_no.") order by SAI_SERIAL_NO desc";
		$result=mssql_query($query);
		$numrows=mssql_num_rows($result);
		while($row=mssql_fetch_array($result)){
			if($row['EMP_NO']==''){
			$this->aut_no_now=$row['AUT_NO'];}
			elseif($row['SAG_FINISHED_TIME']==''){
				$this->aut_no_now='9999';}
			else{$this->aut_no_now='0000';}
		}
		echo '<form name="sag" method="post" action="'.$loginFormAction.'">';
		echo '<table border="1" >';
		$this->read_agree();
		$this->check_user();
		$this->row();
		echo $this->row1.$this->t1.$this->row1_;		
		echo $this->row2.$this->t2.$this->row2_;
		echo $this->row3.$this->t3.$this->row3_;
		echo $this->row4.$this->t4.$this->row4_;
		echo '</table>';
		echo '<input type="hidden" name="MM_insert" value="sag">';
		echo '</form>';
		if(isset($_POST["cancel"]) and isset($_POST['MM_insert']))  //取消簽核
		{
			fun_confirm("確定取消??","..".$_SESSION['lasturl'],"../../lib/delete_sag.php?no=".$this->sa_no."&order_no=".$_GET['order_no']);
		}
		
		if(isset($_POST["add"]) and isset($_POST['MM_insert']))     //簽核
		{	
//			$this->insert_decision();
			if($_POST['checkbox']=='on'){$this->sai_ok_ng ='OK' ;}else{$this->sai_ok_ng='NG' ;}
			if($this->aut_no_now<>'9999'){
			$query="UPDATE          SIGN_AGREE_ITEM
			SET                   EMP_NO ='".$_SESSION['uid']."', SAI_OK_NG ='".$this->sai_ok_ng."', SAI_TIME ='".date("YmdHi")."', SAI_MEMO ='".$_POST['memo']."'";
			$query.=" WHERE          (EMP_NO IS NULL) AND (SAG_NO = '".$this->sa_no."') AND (AUT_NO = '".$this->aut_no_now."')";
			$result=mssql_query($query);
			}
			else
			{
				if($_SESSION['uid']<>$this->create_man){$memo="代理人：".get_uname($_SESSION['uid']);}
				$query="UPDATE          SIGN_AGREE
						SET                   SAG_FINISHED_MEMO ='".$memo."</br>".$_POST['memo']."', SAG_FINISHED_TIME ='".date("YmdHis")."'
						WHERE          (SAG_NO = ".$this->sa_no.")";
				$result=mssql_query($query);
/*
				$query="select TOP(1) * from SIGN_AGREE order by SAG_NO desc";
				$result=mssql_query($query);
				while($row=mssql_fetch_array($result))
				{
					$decision_sa_no=$row['SAG_NO']+1;
				}
*/

				$this->cbt_no='2-03';
				$this->create_sag_out_decision();
				$this->insert_decision();
			}
  			jumpto($_SESSION['sag']);
		}
	}//end show	
	
	function check_user()
	{
		$query="select AUT_GROUP from EMPLOYEE_AUTHORITY where (EMP_NO='".$_SESSION['uid']."')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$aut=$row['AUT_GROUP'];
		}
		$aa=explode(";",$aut);
		$this->comfirm_count=count($aa);
		for($i=0;$i<count($aa);$i++){
			if($this->aut_no_now ==$aa[$i] or $this->aut_no_now=='9999')
			{
				$this->ret ='<input type="checkbox" name="checkbox" id="checkbox" checked="checked"/>確認<input type="submit" name="add" value="  簽 核  " onClick="return confirm('.'確定新增?'.')" />';	
			}
		}
//		echo "SS: ".$aa[$i]."<BR>";
		for($i=0;$i<count($aa);$i++){
			if($aa[$i]=='1029')
			{
				$this->cancel='<input type="submit" name="cancel" value="緊急取消">';
			}
		}
		/*
		if($this->aut_no_now<>'0000')
		{
			$query="select * from EMPLOYEE_DATA where EMP_NO='".$_SESSION['uid']."'";
			$result=mssql_query($query);
			$row=mssql_fetch_array($result);
			if($_SESSION['uid']=='E455' or $row['DEP_NO']=='MIS'  or $_SESSION['uid']=='N268'){
			$this->cancel='<input type="submit" name="cancel" value="緊急取消">';}	
		}
		else{$this->cancel="已完成簽核";}
		*/
	}
	
	function order_sag()
	{
		$query="SELECT         *
				FROM              OUT_PLAN
				WHERE          (OPM_ORDER_NO = '".$this->order_no."')";	
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->sa_no=$row['SAG_NO'];	
			$this->po_no=$row['OPM_PO_NO'];
			$this->opm_stock=$row['OPM_STOCK'];
		}
	}
	
	function insert_decision()
	{
		$today=date("Ymd");
		$query="select OTD_NO from OUT_DECISION where (OTD_NO like '".$today."%')";
		$result=mssql_query($query);
		$numrows=mssql_num_rows($result);
		while($row=mssql_fetch_array($result))
		{
			$this->otd_no=$row['OTD_NO'];
		}
		if($numrows>0){$this->otd_no=$this->otd_no+1;}
		else{$this->otd_no=$today.'0001';}
		$rr=invoice_no();
		// 刪掉舊的出荷決定
		$query="DELETE FROM OUT_DECISION WHERE   (OPM_ORDER_NO = '".$this->order_no."')";
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);	
		
		$query="INSERT INTO OUT_DECISION
                            (OTD_INVOICE_NO, OPM_ORDER_NO, OTD_NO, OPM_PO_NO, CTD_CUST_NO, OTD_CREATE_DATE, 
                            OPM_ETA_DATE, SAG_NO, OPM_TAINAN_CACHE, OPM_STOCK)
				VALUES          ('".$rr."','".$this->order_no."','".$this->otd_no."','".$this->po_no."','".$this->cust_no."','".date("YmdHis")."',
				'".$this->eta_date."160000','".$this->sa_no_decision."','N','".$this->opm_stock."')";
//		echo "<BR>".$query."<BR>";
		$result=mssql_query($query);	
		$query="update OUT_PRODUCT set OTD_NO='".$this->otd_no."' where OPM_ORDER_NO='".$this->order_no."'";
		$result=mssql_query($query);
		
		// 藥品相關	
		$aaa=$_SESSION['prod_list'];
		$i=count($aaa);
		for($a=0;$a<$i;$a++){	
			$lot_no=$aaa[$a][2];
			$query="Update AnalyzeDesign Set ALM_IDENTITY53 = NULL Where AND_LOT_NO = '".$lot_no."'";
//			echo "<BR>".$query."<BR>";
			$result=mssql_query($query);
			
			$query="SELECT  Max(PRA_OUT_COUNT) as max_out_count  FROM LORRY_EXAMINE_LIST WHERE (LEL_LOT_NO = '".$lot_no."') ";
			$result=mssql_query($query);
			$rowa=mssql_fetch_row($result);
			$sn=$rowa[0]+1;
			$query="INSERT INTO PRODUCT_RUNNING_ACCOUNT (PRA_LOT_NO,PRA_SERIAL_NO,PDD_PROD_NO,PRA_PURPOSE,CTD_CUST_NO,PRA_OLD_LOT_NO,PRA_OUT_TERM,
			PRA_OUT_COUNT,OPM_ORDER_NO,OPM_PO_NO,OTD_NO) VALUES ('".$lot_no."',".$sn.",'".$aaa[$a][1]."',-1,'".$aaa[$a][6]."','','".$aaa[$a][5]."',
			".$sn.",'".$aaa[$a][7]."','','".$this->otd_no."')";
			echo "<BR>".$query."<BR>";
			$result=mssql_query($query);
			$query="Insert Into LORRY_EXAMINE_LIST (LEL_LOT_NO, PRA_OUT_COUNT,  PDD_PROD_NO, LEL_LY_NO) Values('".$lot_no."',".$sn." ,  '".$aaa[$a][1]."','".$aaa[$a][8]."')";
//			echo "<BR>".$query."<BR>";
			$result=mssql_query($query);
		}
		
	}
	
	function save_plan(){
		$query="UPDATE          OUT_PLAN
				SET                   OPM_STOCK = '".$this->opm_stock."'
				WHERE          (OPM_ORDER_NO = '".$this->order_no."')";	
	$result=mssql_query($query);
	}
	
	function create_sag_outplan(){
		$query=" select max(SAG_NO) as sagmax from SIGN_AGREE";	
		$result=mssql_query($query);
		$row=mssql_fetch_row($result);
		$sagmax=$row[0];
		$this->sa_no=$sagmax+1;
		
		$query="INSERT INTO SIGN_AGREE
                            (CBT_NO, SAG_CREATE_TIME, SAG_CREATE_MAN, SAG_COMFIRM_COUNT)
				VALUES          ('".$this->cbt_no."','".date("YmdHis")."','".$_SESSION['uid']."','".$this->comfirm_count."') select TOP (1) SAG_NO from SIGN_AGREE order by SAG_NO desc";	
//		echo $query."<BR>";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->sa_no =$row['SAG_NO'];
		}
			
		$query="UPDATE          OUT_PLAN
				SET                   SAG_NO = '".$this->sa_no."'
				WHERE          (OPM_ORDER_NO = '".$this->order_no."')";	
//		echo $query."<BR>";
		$result=mssql_query($query);
		for($i=1;$i<=count($this->aa);$i++){
			$query="INSERT INTO SIGN_AGREE_ITEM (SAG_NO,SAI_SERIAL_NO,AUT_NO) VALUES (".$this->sa_no.",".$i.",'".$this->aa[$i-1]."')" ;
//			echo $query."<BR>";
			$result=mssql_query($query);
		}
 		$this->insert_decision();
	}
	function create_sag_out_decision(){
		$query="INSERT INTO SIGN_AGREE
                            (CBT_NO, SAG_CREATE_TIME, SAG_CREATE_MAN, SAG_COMFIRM_COUNT)
				VALUES          ('".$this->cbt_no."','".date("YmdHis")."','".$_SESSION['uid']."','".$this->comfirm_count."') select TOP (1) SAG_NO from SIGN_AGREE order by SAG_NO desc";	
//		echo $query."<BR>";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->sa_no_decision =$row['SAG_NO'];
		}
		$this->capinility();
		$this->aa=explode(";",$this->cfm1);
		for($i=1;$i<=count($this->aa);$i++){
			$query="INSERT INTO SIGN_AGREE_ITEM (SAG_NO,SAI_SERIAL_NO,AUT_NO) VALUES ('".$this->sa_no_decision."',".$i.",'".$this->aa[$i-1]."')" ;
			$result=mssql_query($query);
		}
	}
	
	function capinility(){
	$query="SELECT          CBT_CFM1,CBT_NO
				FROM              CAPABILITY_DATA
				WHERE (CBT_NO='".$this->cbt_no."')";
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->cfm1=$row['CBT_CFM1'];
		}
	}
	
	function r_opf_file_(){
		$query="SELECT          OAF_DELI_CUST_NO
				FROM              OUT_PLAN_FROM_FILE
				WHERE          (OPM_ORDER_NO = '".$this->order_no."')";	
		$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$this->cust_no =$row['OAF_DELI_CUST_NO'];
		}
	}
	function read_decision(){
		$query="SELECT  SAG_FINISHED_TIME FROM SIGN_AGREE WHERE (SAG_NO = '".$this->sa_no."') ";
		$result = mssql_query($query);
		$row=mssql_fetch_row($result);
		$this->numrows=$row[0];
	}
}///end class sign_in


// Function Start
function invoice_no(){
	$query="select IVD_CURR_NO from INVOICE_DATA";
	$result=mssql_query($query);
		while($row=mssql_fetch_array($result))
		{
			$cinvoice_no=$row['IVD_CURR_NO'];
		}
	$query="update INVOICE_DATA set IVD_CURR_NO='".($cinvoice_no+1)."'";
	$result=mssql_query($query);
	return substr(printf("%010s",$cinvoice_no),0,-2);
}
?>