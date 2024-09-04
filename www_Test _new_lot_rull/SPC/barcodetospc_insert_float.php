<?php
	session_start();
	include_once("../connections/conn.php");
	include("../lib/fun.php");
	include("../lib/jtsai.php");
	datepick();
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<form name="SS" method="post">
	客戶代號:<input type="text" name="cust_no"  value="<?php echo $_SESSION['cust_no']; ?>" onchange="set_date_session(this.name,this.value)"><br>
	產品代號:<input type="text" name="prod_no"  value="<?php echo $_SESSION['prod_no']; ?>" onchange="set_date_session(this.name,this.value)">
	輸入型態<select name="status" id="status" onChange="set_date_session(this.name,this.value)">
          <option value="0" <?php if($_SESSION['status']==0){echo "selected";}?>>新增</option>
          <option value="1" <?php if($_SESSION['status']==1){echo "selected";}?>>新規</option>
          <option value="2" <?php if($_SESSION['status']==2){echo "selected";}?>>更新</option>
        </select>
	<br>
	<input type="submit" name="enter" value="搜尋分析項目">
	<input type="submit" name="intospc" value="導入SPC TEMPMAIN & TEMPNEWVSUB">
</form>
<?php
if(isset($_POST['intospc'])){  
	if($_POST['status']==0){
		$status1='A';$status2='A';
	}
	elseif($_POST['status']==1){
		$status1='AS';$status2='AC';
	}
	elseif($_POST['status']==2){
		$status1='ES';$status2='EC';
	}
		include_once("../connections/conn.php");
		{  //read TEMPMAIN
			$TEMPMAIN=array();
			$query="SELECT DISTINCT 
		                            QC_CustProdSpec.ProdNo, QC_CustProdSpec.CustNo, CUSTOMER_DATA.CTD_CUST_NAME, 
		                            PRODUCT_DATA.PDD_PROD_NAME
							FROM              AnalyzeItem INNER JOIN
		                            QC_CustProdSpec INNER JOIN
		                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
		                            AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName INNER JOIN
		                            CUSTOMER_DATA ON QC_CustProdSpec.CustNo = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
		                            PRODUCT_DATA ON QC_CustProdSpec.ProdNo = PRODUCT_DATA.PDD_PROD_NO                                                  
							WHERE          (QC_CustProdSpec.CustNo <>'') "; 
			if($_POST['cust_no']<>''){
					$query.=" and (QC_CustProdSpec.CustNo like '%".$_POST['cust_no']."%')";	
				}
				if($_POST['prod_no']<>''){
					$query.=" and (QC_CustProdSpec.ProdNo like '%".$_POST['prod_no']."%')";	
				}
		//		echo $query."<BR>";
			$result=mssql_query($query);
			$i=0;
			while($row=mssql_fetch_array($result)){
				$groupname=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
				$filename=trim($row['CustNo']).'_'.trim($row['CTD_CUST_NAME']);
				$TEMPMAIN[$i][1]=$groupname;
				$TEMPMAIN[$i][2]=$filename;
			//	echo "GroupName:".$groupname."FileName:".$filename."<BR>";
				$i++;
			}		
			$cnt_TEMPMAIN=$i;
			echo "群組檔案共：".$i."項<BR>";
		}	 //end read TEMPMAIN
		{  //read TEMPNEWVSUB
			$TEMPNEWVSUB=array();
			$query="SELECT DISTINCT 
	                            QC_CustProdSpec.ProdNo, QC_Spec.SpecNo, QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, 
	                            QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.Digit, QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, 
	                            QC_Spec.STD_L, QC_Spec.showUSL, AnalyzeItem.ANI_INDEX, AnalyzeItem.ANI_ID, AnalyzeItem.ANI_FULLNAME, 
	                            AnalyzeItem.ANI_UNIT, QC_CustProdSpec.CustNo, CUSTOMER_DATA.CTD_CUST_NAME, PRODUCT_DATA.PDD_PROD_NAME,AnalyzeItem.ANI_NICKNAME
						FROM              AnalyzeItem INNER JOIN
	                            QC_CustProdSpec INNER JOIN
	                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
	                            AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName INNER JOIN
	                            CUSTOMER_DATA ON QC_CustProdSpec.CustNo = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
	                            PRODUCT_DATA ON QC_CustProdSpec.ProdNo = PRODUCT_DATA.PDD_PROD_NO                                                  
							WHERE          (QC_CustProdSpec.CustNo <>'')";
			if($_POST['cust_no']<>''){
					$query.=" and (QC_CustProdSpec.CustNo like '%".$_POST['cust_no']."%')";	
				}
				if($_POST['prod_no']<>''){
					$query.=" and (QC_CustProdSpec.ProdNo like '".($_POST['prod_no'])."')";
				}
//			echo $query."<BR>";
			$result=mssql_query($query);
			$i=0;
			while($row=mssql_fetch_array($result)){
				$groupname=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
				$filename=trim($row['CustNo']).'_'.trim($row['CTD_CUST_NAME']);
				$TEMPNEWVSUB[$i][1]=$groupname;
				$TEMPNEWVSUB[$i][2]=$filename;
				$TEMPNEWVSUB[$i][3]=$row['ItemUnit'];
				$TEMPNEWVSUB[$i][4]=trim($row['ANI_NICKNAME'])."_".trim($row['ANI_FULLNAME']);
				$TEMPNEWVSUB[$i][5]=$row['USL'];
				$TEMPNEWVSUB[$i][6]=$row['LSL'];
				$TEMPNEWVSUB[$i][7]=$row['DL'];
				$TEMPNEWVSUB[$i][8]=$row['UCL'];
				$TEMPNEWVSUB[$i][9]=$row['LCL'];
				$TEMPNEWVSUB[$i][10]=$row['STD_U'];
				$TEMPNEWVSUB[$i][11]=$row['STD_L'];
			//	echo "GroupName:".$groupname."FileName:".$filename."<BR>";
				$i++;
			}		
			$cnt_ani_items=$i;
			echo "分析項目共： ".$cnt_ani_items." 項<BR>";
		}  //end read TEMPNEWVSUB
		;
		include("conn_spc.php");  
		$x=0; 
		for($j=0;$j < $cnt_TEMPMAIN;$j++){  //填寫TEMPMAIN
			$date=date("Y-m-d H:i:s");			
				$query="INSERT INTO TEMPMAIN (GROUPNAME, FILENAME, TRANSINTIME, FLAG, STATUS, TYPE, TYPE2) 
				VALUES (N'".$TEMPMAIN[$j][1]."', N'".$TEMPMAIN[$j][2]."', CONVERT(DATETIME, '".$date."', 102), 'N', 'E', 'N', 'V') SELECT SCOPE_IDENTITY()";
	 	//		echo $query."<BR>";
	 			$result=mssql_query($query);
				$row=mssql_fetch_row($result);
				$mid=$row[0];
				$query="update TEMPMAIN SET INSPECTID=".$mid." where MID=".$mid;
				$result=mssql_query($query);
		}		 //結束填寫TEMPMAIN
		
		for($i=0;$i < $cnt_ani_items;$i++){ //填寫分析項目 TEMPNEWVSUB	
				$query="select MID from TEMPMAIN where GROUPNAME='".$TEMPNEWVSUB[$i][1]."' and FILENAME='".$TEMPNEWVSUB[$i][2]."' order by MID desc" ; 
				$result=mssql_query($query);
				$row=mssql_fetch_row($result);
				$mid=$row[0]; //TEMPMAIN id					
				$tnvsid=write_TEMPNEWVSUB($mid,$TEMPNEWVSUB[$i][5],$TEMPNEWVSUB[$i][6],$TEMPNEWVSUB[$i][3],$TEMPNEWVSUB[$i][4],$status1,$$status2,$TEMPNEWVSUB[$i][10],$TEMPNEWVSUB[$i][11]);
//				echo $mid.":".$tnvsid."<BR>";
				$x++;
		}  //結束填寫分析項目
		

		unset($TEMPMAIN);
		   //填寫TEMPNEWVSUB1-4
		for($i=0;$i < $cnt_ani_items;$i++){ //填寫分析項目 TEMPNEWVSUB2-4			
			$query="SELECT top(1) TEMPMAIN.MID, TEMPNEWVSUB.TNVSID, TEMPNEWVSUB.ALIAS, TEMPNEWVSUB.UNIT, TEMPMAIN.GROUPNAME, TEMPMAIN.FILENAME
							FROM TEMPMAIN INNER JOIN TEMPNEWVSUB ON TEMPMAIN.MID = TEMPNEWVSUB.MID 
							where TEMPMAIN.FILENAME='".$TEMPNEWVSUB[$i][2]."' and TEMPNEWVSUB.CTRLITEM ='".$TEMPNEWVSUB[$i][4]."' ORDER BY   TEMPMAIN.MID DESC ";
//							echo $query."<BR>";
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			$mid=$row[0];
			$tnvsid=$row[1];
			// 		TEMPNEWVSUB2

				$query="INSERT INTO TEMPNEWVSUB2
                (MID, TNVSID, CTRLTYPE, CHARTTYPE, MEAN, STDEV, EWMATYPE, EWMATARGET, CUSUMTYPE, CUSUMTARGET,DELTATYPE, DELTA, FLAG, STATUS)
 								VALUES (".$mid.", ".$tnvsid.", 1, '3', 0, 0, 0, 0, 0, 0, 0, 1, 'N','".$status2."')";
 				$result=mssql_query($query);

//				echo "TEMPNEWVSUB2 OK...<BR>";
			
			// 		TEMPNEWVSUB3
				if(trim($TEMPNEWVSUB[$i][8])<>'' and trim($TEMPNEWVSUB[$i][9])<>''){
					$CL=($TEMPNEWVSUB[$i][8]+$TEMPNEWVSUB[$i][9])/2;
				}
				else{
					$CL=NULL;
				}
				$query="INSERT INTO TEMPNEWVSUB3 (MID, TNVSID, CHARTTYPE, UCL, CL, LCL, FLAG, STATUS)
								VALUES          (".$mid.", ".$tnvsid.", 3, '".$TEMPNEWVSUB[$i][8]."','".$CL."', '".$TEMPNEWVSUB[$i][9]."', 'N', '".$status2."')";
//				echo $query."<BR>";
 				$result=mssql_query($query);
	
//			echo "TEMPNEWVSUB3 OK...<BR>";
			/*
			// 		TEMPNEWVSUB4
			$query="select count(TNVSID) as a from TEMPNEWVSUB4 where TNVSID=".$tnvsid;
			$result=mssql_query($query);
			$row=mssql_fetch_row($result);
			if($row[0]==0){
				$query="INSERT INTO TEMPNEWVSUB4 (MID, TNVSID, USL, LSL, FLAG, STATUS) VALUES (".$mid.", ".$tnvsid.", N'".$TEMPNEWVSUB[$i][5]."',
				 N'".$TEMPNEWVSUB[$i][6]."', 'N', 'A')";
 				$result=mssql_query($query);
			}	
			*/
		}
	echo "填寫完成<BR>";
}  // end POST intospc
	
if(isset($_POST['enter'])){
	$query="SELECT DISTINCT 
	                            QC_CustProdSpec.ProdNo, QC_Spec.SpecNo, QC_Spec.ItemName, QC_Spec.ItemUnit, QC_Spec.USL, QC_Spec.LSL, 
	                            QC_Spec.DL, QC_Spec.UCL, QC_Spec.LCL, QC_Spec.Digit, QC_Spec.UAXIS, QC_Spec.LAXIS, QC_Spec.STD_U, 
	                            QC_Spec.STD_L, QC_Spec.showUSL, AnalyzeItem.ANI_INDEX, AnalyzeItem.ANI_ID, AnalyzeItem.ANI_FULLNAME, 
	                            AnalyzeItem.ANI_UNIT, QC_CustProdSpec.CustNo, CUSTOMER_DATA.CTD_CUST_NAME, PRODUCT_DATA.PDD_PROD_NAME,
	                            AnalyzeItem.ANI_NICKNAME
						FROM              AnalyzeItem INNER JOIN
	                            QC_CustProdSpec INNER JOIN
	                            QC_Spec ON QC_CustProdSpec.SpecNo = QC_Spec.SpecNo AND QC_CustProdSpec.SpecVer = QC_Spec.SpecVer ON 
	                            AnalyzeItem.ANI_FULLNAME = QC_Spec.ItemName INNER JOIN
	                            CUSTOMER_DATA ON QC_CustProdSpec.CustNo = CUSTOMER_DATA.CTD_CUST_NO INNER JOIN
	                            PRODUCT_DATA ON QC_CustProdSpec.ProdNo = PRODUCT_DATA.PDD_PROD_NO                                                  
							WHERE          (QC_CustProdSpec.CustNo <>'')  ";
	if($_POST['cust_no']<>''){
		$query.=" and (QC_CustProdSpec.CustNo like '%".$_POST['cust_no']."%')";	
	}
	if($_POST['prod_no']<>''){
		$query.=" and (QC_CustProdSpec.ProdNo like '%".$_POST['prod_no']."%')";	
	}
//	echo $query."<BR>";
	$result=mssql_query($query);
	echo '<table width="1440" border="1"><tr><td>Group_Name</td><td>Filename</td><td>Unit</td><td>Item_Name</td><td>Nick_Name</td><td>USL</td><td>LSL</td><td>DL</td>
	<td>UCL</td><td>LCL</td><td>UAXIC</td><td>LAXIC</td><td>STD_U</td><td>STD_L</td></tr>';
	while($row=mssql_fetch_array($result)){
		$groupname=trim($row['ProdNo'])."_".trim($row['PDD_PROD_NAME']);
		$filename=trim($row['CustNo']).'_'.trim($row['CTD_CUST_NAME']);
		echo '<tr><td>'.$groupname.'</td><td>'.$filename.'</td><td>'.$row['ItemUnit'].'</td><td>'.$row['ANI_FULLNAME'].'
		</td><td>'.$row['ANI_NICKNAME'].'</td><td>'.$row['USL'].'</td><td>'.$row['LSL'].'</td><td>'.$row['DL'].'</td><td>'.$row['UCL'].'</td><td>'.$row['LCL'].'
		</td><td>'.$row['UAXIC'].'</td><td>'.$row['LAXIS'].'</td><td>'.$row['STD_U'].'</td><td>'.$row['STD_L'].'</td></tr>';
	}
	echo '</table>';
}

function write_TEMPNEWVSUB($mid,$su,$sl,$unit,$alias,$status1,$status2,$uwl,$lwl){
	if(trim($su)<>'' and trim($sl)<>''){
		$sc=($su+$sl)/2;
	}
	else{
		$sc=NULL;
	}
	_getFloatLength($su);
	$query="insert into TEMPNEWVSUB (MID,CTRLITEM,SC,SU,SL,SPSIZE,DP, UNIT, ALIAS,FLAG, STATUS,CPKLL,CPLL,UWL,LWL)
  values (".$mid.",'".$alias."','".$sc."','".$su."','".$sl."',1,3,'".$unit."','X-RM','N','".$status1."','1.33','1.33','".$uwl."','".$lwl."') SELECT SCOPE_IDENTITY()";
//	echo $query."<BR>";
	$result=mssql_query($query);
	$row=mssql_fetch_row($result);
	return $row[0];
//  echo $query."<BR>";
}

function update_TEMPNEWVSUB($mid,$su,$sl,$unit,$alias){
	$query="update TEMPNEWVSUB set SU='".$su."',SL='".$sl."' where MID=".$mid." and CTRLITEM='".$alias."'";
	$result=mssql_query($query);
//	echo $query."<BR>";
}

private function _getFloatLength($num) {
$count = 0;

$temp = explode ( '.', $num );

if (sizeof ( $temp ) > 1) {
$decimal = end ( $temp );
$count = strlen ( $decimal );
}

return $count;
}
?>