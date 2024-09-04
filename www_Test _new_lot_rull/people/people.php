
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
	<?php
//2017.4.24 806,1388 行增加所有m13前處理時間	
session_start();
include("../connections/conn.php");
include("../lib/fun.php");
include("../lib/jtsai.php");
include  "../PHPEXCEL/Classes/PHPExcel.php";
include "../PHPEXCEL/Classes/PHPExcel/IOFactory.php";
lasturl();
auth('5-04',$_SESSION['aut']);
datepick();
?>
<script type="text/javascript" src="/css/jquery.min.js"></script>
    <script type="text/javascript" src="/css/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/css3menu/GridViewScroll/gridviewScroll.min.js"></script>
    <script src="/css/datepicker-zh-TW.js"></script>
    <link href="/css3menu/GridViewScroll/GridviewScroll.css" rel="stylesheet" />
        <script type="text/javascript">
	    $(document).ready(function () {
	        gridviewScroll();
	    });
	
	    function gridviewScroll() {
	        gridView1 = $('#GridView1').gridviewScroll({
                width: 1150,
                height: 450,
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
</br>
檢測日期：<form id="form1" name="form1" method="post" action="<?php echo $loginFormAction; ?>">
   <input name="datepicker1" type="text" id="datepicker1" size="10" value="<?php if($_SESSION['datepicker1']){echo $_SESSION['datepicker1'];}?>" onChange="set_date_session(this.name,this.value)">
~
<input name="datepicker2" type="text" id="datepicker2" size="10" value="<?php if($_SESSION['datepicker2']){echo $_SESSION['datepicker2'];}?>" onChange="set_date_session(this.name,this.value)"></td>
<input type="button" name="worktime" id="worktime" value="工時設定" onClick="window.open('./worktime_set.php ', '_self');" >
        &emsp;</br>
         
檢測人員:
<input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['user']){
			echo $_GET['user'];
			$_SESSION['user']=$_GET['user'];
		}
		elseif($_SESSION['user']){
			echo $_SESSION['user'];
		}
		else{
		echo '';
		}?>">
   
        
		名字:<input name="textfield4" type="text" id="textfield4" size="10" value="<?php 
if ($_GET['name']){
			echo $_GET['name'];
			$_SESSION['name']=$_GET['name'];
		}
		elseif($_SESSION['name']){
			echo $_SESSION['name'];
		}
		else{
		echo '';
		}
		 
		
		?>" readonly>
        <input type="button" name="pdd_no" id="pdd_no" value="選擇人員" onClick="window.open('./people_watch.php ', '_self');" >
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('./erase_people.php ', '_self');" />
        檢驗項目：
          <?php select_ani_group11();?>
        藥品類別：
        <?php select_needno();?>
        檢驗藥品：
        <?PHP select_chemical(); ?>
           
        </br>
        
        前處理人員:
<input name="pdd_chemical1" type="text" id="pdd_chemical1" size="10" value="<?php 
		if ($_GET['no1']){
			echo $_GET['no1'];
			$_SESSION['no1']=$_GET['no1'];
		}
		elseif($_SESSION['no1']){
			echo $_SESSION['no1'];
		}
		else{
		echo '';
		}?>">
        
        
		名字:<input name="textfield4" type="text" id="textfield4" size="10" value="<?php 
if ($_GET['no2']){
			echo $_GET['no2'];
			$_SESSION['no2']=$_GET['no2'];
		}
		elseif($_SESSION['no2']){
			echo $_SESSION['no2'];
		}
		else{
		echo '';
		}
		
		
		?>" readonly>
        
        
        
      
        <input type="button" name="pdd_no" id="pdd_no" value="選擇前處理人" onClick="window.open('./first_watch.php ', '_self');" >
        <input type="button" name="X2" id="X2" value="X" onclick="window.open('./erase_first.php ', '_self');" />
        &emsp;&emsp;&emsp;&emsp;&emsp;
        <input name="submit" type="submit" class="center button" id="submit" value="  查    詢  ">
        <input name="submit1" type="submit" class="center button" id="submit1" value="  所有結果  ">
         <input name="submit2" type="submit" class="center button" id="submit2" value=" 下載報告 ">請先使用過左邊的查詢再下載
        
                                                                             
         
         <?PHP
		
		 $date1=dod($_SESSION['datepicker1']);
		$date3=dod($_SESSION['datepicker2']);
		$date4=strtotime("+1 day",strtotime($date3));
		$date2=date("Ymd",$date4);
		$_SESSION['d2']=$date2;

///	 start submit	

if(isset($_POST['submit']))   
{
			//============================================查單人/=======================================================//
	if($_SESSION['user']<>'')
	{
		echo "選擇人員";
		$query="select AF.first,AF.lot_no,AF.create_time,AF.chemical,AF.ani_group,AF.create_user,ED1.EMP_NAME as ed1
			 ,ED2.EMP_NAME as ed2,AF.need_no
			 from analyze_first1 as AF
			 left join EMPLOYEE_DATA as ED1 on AF.create_user=ED1.EMP_NO
			 left join EMPLOYEE_DATA as ED2 on ED2.EMP_NO=AF.first

			 where (AF.create_user='".$_SESSION['user']."') and  (AF.create_time>='".$date1."083099') and (AF.create_time<='".$date2."083000') ";
			 
			////////////////////////////////////////增加檢測項目搜尋///////////////////////////////////////////////
		if($_POST['selected_group']<>'0'){
		$query=$query." and (AF.ani_group ='".$_POST['selected_group']."')";}
	
	/////////////////////////////////////////////藥品類別搜尋////////////////////////////////////////////////////
		if($_POST['needno']<>'0')
		{
				if(	$_POST['needno']==1){$query=$query." and (AF.need_no <= 150)";}
				if(	$_POST['needno']==2){$query=$query." and (AF.need_no > 150) and (AF.need_no <=270)";}
				if(	$_POST['needno']==3){$query=$query." and (AF.need_no > 270)";}
		}
	
		////////////////////////////////////////////藥品分類搜尋//////////////////////////////////////////////////////
		if($_POST['selected_chemical']<>'0')
		{
			$query=$query." and (AF.chemical ='".$_POST['selected_chemical']."')";
		}
		////////////////////////////////////////////列出搜尋結果///////////////////////////////////////////////////
		
		$query=$query."order by AF.create_time";
		//echo $query;
		$result = mssql_query($query);
		$numRows=mssql_num_rows($result);	
			 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."工號".'</td>';
			  echo  '<td>'."姓名".'</td>';
			   echo  '<td>'."LOT NO".'</td>';
			   echo  '<td>'."類別".'</td>';
			    echo  '<td>'."檢測日期".'</td>';
			  echo  '<td>'."藥品名稱".'</td>';
			 echo  '<td>'."檢測項目".'</td>';
			  echo  '<td>'."前處理人員".'</td>';
			   echo  '<td>'."姓名".'</td>';
			   echo  '<td>'."工時".'</td>';
		$num=$numRows;
		$A01=array();
		while ($row = mssql_fetch_array($result))
			{
				echo '<tr height="">';
				echo  '<td>'.$row['create_user'].'</td>';
				echo '<td>'.$row['ed1'].'</td>';
				echo  '<td>'.$row['lot_no'].'</td>';
				if($row['need_no']<=150){echo '<td>'."製品".'</td>';}
				if($row['need_no']>150 and $row['need_no']<=270){echo '<td>'."解析".'</td>';}
				if($row['need_no']>270){echo '<td>'."受入".'</td>';}
				echo  '<td>'.$row['create_time'].'</td>';
				echo  '<td>'.$row['chemical'].'</td>';
				echo  '<td>'.$row['ani_group'].'</td>';
				echo  '<td>'.$row['first'].'</td>';
				echo  '<td>'.$row['ed2'].'</td>';
				
/////////////////////////////////////////////////////////////////////前處理計算////////////////////////////////////
				if(trim($row['first'])<>'')
				{ 
					$RF="select hrs
					from WORKTIME
					where PDD_CHEMICAL='".$row['chemical']."' and ANI_GROUPNAME='".trim($row['ani_group'])."FIRST'";
							$resultrf = mssql_query($RF);
					$numRows=mssql_num_rows($resultrf);	
					while ($rowrf = mssql_fetch_array($resultrf))
					{
						$firsttime=$firsttime+$rowrf['hrs'];
					}
				}
				
//////////////////////////////////////////工時計算//////////////////////////////////////////////////////////////
				
				if(trim($row['ani_group'])=='PMS' or trim($row['ani_group'])=='RION'){$num=$num-1;}
				
				if(trim($row['ani_group'])!='PMS' and trim($row['ani_group'])!='RION')//<>'TT' and trim($row['ani_group'])<>'I')
				{ 
					$query1="select  PDD_CHEMICAL,hrs,ANI_GROUPNAME,ELF_FORM,ANI_FULLNAME
					from WORKTIME
					where PDD_CHEMICAL='".$row['chemical']."' and ANI_GROUPNAME='".$row['ani_group']."'";
					//echo $query1;
					//////////////////////////echo $query1;////////////////////////
					 $result1 = mssql_query($query1);
					$numRows1=mssql_num_rows($result1);	
					$c=0;
					$test=0;
					while ($row1 = mssql_fetch_array($result1))
					{ 

	///////////////////////////////////////////////////////////////算工時遇到I/////////////////////////////////
					if(trim($row['ani_group'])=="I")
							{
								if(trim($row['chemical'])=="H2SO4")
					{	
							
								$H2SO4="select AnalyzeTime from [".$row1['ELF_FORM']."] where AnalyzeTime='".$row['create_time']."'";
								//echo $H2SO4;

								$resultH = mssql_query($H2SO4);
								$numRowsH=mssql_num_rows($resultH);	
								if($numRowsH==1){$alltime=$alltime+$row1['hrs'];}
								
							}
							else
							{
								$II="select [NO3],[PO4],[SO4]
								FROM	[".$row1['ELF_FORM']."]
								where AnalyzeTime='".$row['create_time']."'";
								$resultII = mssql_query($II);
								$numRowsII=mssql_num_rows($resultII);
								while ($rowII = mssql_fetch_array($resultII))
								{
									
									if($rowII['NO3']<>''){$alltime=$alltime+$row1['hrs']/$numRows1;}
									if($rowII['PO4']<>''){$alltime=$alltime+$row1['hrs']/$numRows1;}
									if($rowII['SO4']<>''){$alltime=$alltime+$row1['hrs']/$numRows1;}
								}
							}
						}		
							
/////////////////////////////////////////////////////////算工時大部分/////////////////////////////////////////////		
						else
						{
						   $c++;           
							if($numRows1==1)
							{
								$alltime=$alltime+$row1['hrs'];
								echo  '<td>'.$row1['hrs'].'</td>';
							}
							if($numRows1>1)
							{	
								array_push($A01,$row1['hrs']);
								if($c==$numRows1)
								{
									for($i=0;$i<$numRows1;$i++)
									{							
										if($A01[0]/$A01[$i]==1 and $A01[0]/$A01[1]==1)
										{	
											$plus=array_sum($A01);
											$iftime=floor($plus/$numRows1);
											
										}//if
										if($A01[0]/$A01[$i]<>1 and $A01[0]/$A01[1]<>1)
										{	
											$alltime=$alltime+$A01[$i];
											
										}//if
									}//for							
									$alltime=$alltime+$iftime;
									echo  '<td>'.$iftime.'</td>';
									unset($A01);
									$A01=array();
								}//if
							}//if
						}
						
					}//while
				}			
			}
		
		echo '</br>'."分析項目".$num."件";
		echo '</br>'."分析時間".$alltime."分鐘";
		echo $alltime/'60'."小時";
		echo '</br>'.$firsttime."前處理分鐘".$firsttime/'60'."小時";
	}
	
	// END ============================================查單人/=======================================================//
	
	//=========================================================查前處理======================================================//
		elseif($_SESSION['no1']<>''){echo "選擇前處理人";
			$query="select AF.first,AF.lot_no,AF.create_time,AF.chemical,AF.ani_group,AF.create_user,ED1.EMP_NAME as ed1
			 ,ED2.EMP_NAME as ed2,AF.need_no
			 from analyze_first1 as AF
			 left join EMPLOYEE_DATA as ED1 on AF.create_user=ED1.EMP_NO
			 left join EMPLOYEE_DATA as ED2 on ED2.EMP_NO=AF.first

			 where (AF.first='".$_SESSION['no1']."') and  (AF.create_time>='".$date1."083099') and (AF.create_time<='".$date2."083000')  
			 ";
			////////////////////////////////////////增加檢測項目搜尋///////////////////////////////////////////////
			 if($_POST['selected_group']<>'0'){
	$query=$query." and (AF.ani_group ='".$_POST['selected_group']."')";}
	
	
	/////////////////////////////////////////////藥品類別搜尋////////////////////////////////////////////////////
	if($_POST['needno']<>'0')
	{
			if(	$_POST['needno']==1){$query=$query." and (AF.need_no <= 150)";}
			if(	$_POST['needno']==2){$query=$query." and (AF.need_no > 150) and (AF.need_no <=270)";}
			if(	$_POST['needno']==3){$query=$query." and (AF.need_no > 270)";}
	}
		////////////////////////////////////////////藥品分類搜尋//////////////////////////////////////////////////////
	if($_POST['selected_chemical']<>'0')
	{
		$query=$query." and (AF.chemical ='".$_POST['selected_chemical']."')";
	}
	///////////////////////////////////////////列出搜尋結果///////////////////////////////////////////////////
	
	$query=$query."order by AF.create_time";
	//echo $query;
	  $result = mssql_query($query);
			$numRows=mssql_num_rows($result);	
			 echo '<table border="1" width="60%">';
			 echo '<tr height="">';
			 echo  '<td>'."工號".'</td>';
			  echo  '<td>'."姓名".'</td>';
			   echo  '<td>'."LOT NO".'</td>';
			   echo  '<td>'."類別".'</td>';
			    echo  '<td>'."檢測日期".'</td>';
			  echo  '<td>'."藥品名稱".'</td>';
			 echo  '<td>'."檢測項目".'</td>';
			  echo  '<td>'."前處理人員".'</td>';
			   echo  '<td>'."姓名".'</td>';
			   echo  '<td>'."工時".'</td>';
			   $num=$numRows;
			   $A01=array();
		while ($row = mssql_fetch_array($result))
			{
				echo '<tr height="">';
				echo  '<td>'.$row['create_user'].'</td>';
					
					echo '<td>'.$row['ed1'].'</td>';
					echo  '<td>'.$row['lot_no'].'</td>';
					if($row['need_no']<=150){echo '<td>'."製品".'</td>';}
					if($row['need_no']>150 and $row['need_no']<=270){echo '<td>'."解析".'</td>';}
					if($row['need_no']>270){echo '<td>'."受入".'</td>';}
					echo  '<td>'.$row['create_time'].'</td>';
					
					echo  '<td>'.$row['chemical'].'</td>';
					echo  '<td>'.$row['ani_group'].'</td>';
					echo  '<td>'.$row['first'].'</td>';
					echo  '<td>'.$row['ed2'].'</td>';
/////////////////////////////////////////////////////////////////////前處理計算////////////////////////////////////
				if(trim($row['first'])<>'')
				{				
					$RF="select top 1 hrs
					from WORKTIME
					where PDD_CHEMICAL='".$row['chemical']."' and ANI_GROUPNAME='".trim($row['ani_group'])."FIRST'";
					
					//echo $RF;
					
					$resultrf = mssql_query($RF);
					$numRows=mssql_num_rows($resultrf);	
					while ($rowrf = mssql_fetch_array($resultrf))
					{
						$firsttime=$firsttime+$rowrf['hrs'];
						echo  '<td>'.$rowrf['hrs'].'</td>';
						//還有錯
					}
					
				//ELSE
				}
//////////////////////////////////////////工時計算//////////////////////////////////////////////////////////////
				
				if(trim($row['ani_group'])=='PMS' or trim($row['ani_group'])=='RION'){$num=$num-1;}
				
				if(trim($row['ani_group'])!='PMS' and trim($row['ani_group'])!='RION')//<>'TT' and trim($row['ani_group'])<>'I')
				{ 
					$query1="select  PDD_CHEMICAL,hrs,ANI_GROUPNAME,ELF_FORM,ANI_FULLNAME
					from WORKTIME
					where PDD_CHEMICAL='".$row['chemical']."' and ANI_GROUPNAME='".$row['ani_group']."'";
					//////////////////////////echo $query1;////////////////////////
					 $result1 = mssql_query($query1);
					$numRows1=mssql_num_rows($result1);	
					$c=0;
					$test=0;
					while ($row1 = mssql_fetch_array($result1))
					{ 

	///////////////////////////////////////////////////////////////算工時遇到I===////////////////////////////
						if(trim($row['ani_group'])=="I")
						{
							if(trim($row['chemical'])=="H2SO4")
							{
								$H2SO4="select AnalyzeTime from [".$row1['ELF_FORM']."] where AnalyzeTime='".$row['create_time']."'";
								$resultH = mssql_query($H2SO4);
								$numRowsH=mssql_num_rows($resultH);	
								if($numRowsH==1){$alltime=$alltime+$row1['hrs'];}
								
							}
							else
							{
								$II="select [NO3],[PO4],[SO4]
								FROM	[".$row1['ELF_FORM']."]
								where AnalyzeTime='".$row['create_time']."'";
								$resultII = mssql_query($II);
								$numRowsII=mssql_num_rows($resultII);
								while ($rowII = mssql_fetch_array($resultII))
								{
									
									if($rowII['NO3']<>''){$alltime=$alltime+$row1['hrs']/$numRows1;}
									if($rowII['PO4']<>''){$alltime=$alltime+$row1['hrs']/$numRows1;}
									if($rowII['SO4']<>''){$alltime=$alltime+$row1['hrs']/$numRows1;}
								}
							}
						}			
						
/////////////////////////////////////////////////////////算工時大部分/////////////////////////////////////////////		
						else
						{
						   $c++;           
							if($numRows1==1)
							{
								$alltime=$alltime+$row1['hrs'];
								
							}
							if($numRows1>1)
							{	
								array_push($A01,$row1['hrs']);
								if($c==$numRows1)
								{
									//echo "近來".$c;
									//echo $row1['PDD_CHEMICAL']."/".$row1['ANI_GROUPNAME'];
									//echo '</br>';
									for($i=0;$i<$numRows1;$i++)
									{							
										if($A01[0]/$A01[$i]==1 and $A01[0]/$A01[1]==1)
										{	
											$plus=array_sum($A01);
											$iftime=floor($plus/$numRows1);
											
										}//if
										if($A01[0]/$A01[$i]<>1 and $A01[0]/$A01[1]<>1)
										{	
											$alltime=$alltime+$A01[$i];
										}//if
									}//for							
									$alltime=$alltime+$iftime;
									
									unset($A01);
									$A01=array();
								}//if
							}//if
						}
						
					}//while
					
				
				}
								
			}
		
		echo '</br>'."分析項目".$num."件";
		echo '</br>'."分析時間".$alltime."分鐘";
		echo $alltime/'60'."小時";
		
		echo '</br>'.$firsttime."前處理分鐘".$firsttime/'60'."小時";
		}
		//END =========================================================查前處理======================================================//
		
		//=======================================================查全部=================================================================//
		else
		{ 
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load("report.xlsx");
			$objPHPExcel->setActiveSheetIndex(0);
			global $p;
			$p=1;
			
			$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","工號"));
			$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8","姓名"));
			$objPHPExcel->getActiveSheet()->setCellValue("C".$p, iconv("big5","utf-8","分析項目"));
			$objPHPExcel->getActiveSheet()->setCellValue("D".$p, iconv("big5","utf-8","分析時間/分鐘"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","包含前處理工時/分鐘"));
			$objPHPExcel->getActiveSheet()->setCellValue("F".$p, iconv("big5","utf-8","樣品瓶處理工時/分鐘"));
			$objPHPExcel->getActiveSheet()->setCellValue("G".$p, iconv("big5","utf-8","工作時數"));
			$objPHPExcel->getActiveSheet()->setCellValue("H".$p, iconv("big5","utf-8","工作人天數"));
			$objPHPExcel->getActiveSheet()->setCellValue("I".$p, iconv("big5","utf-8","實際工作天數"));
			$GLOBALS['p']++;
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	  		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	   		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	   
		
		$F=0;
		$BRK=1;
			echo '<table border="1" width="1024">';			   
			$allPP="SELECT distinct ED.DEP_NO,AF.create_user,ED.EMP_NAME as EMP1 
				FROM analyze_first1 as AF 
				left join EMPLOYEE_DATA as ED on AF.create_user=ED.EMP_NO
				WHERE (AF.create_user<>'') and  (AF.create_time>='".$date1."083099') 
			 and (AF.create_time<='".$date2."083000') AND DEP_NO='AN'";
			 
 			//echo "<BR>".$allPP."<BR>";//找所有的項目

			$resultPP = mssql_query($allPP);
			$numRowsPP=mssql_num_rows($resultPP);
			while ($rowPP = mssql_fetch_array($resultPP))
			{
			$query="SELECT  AF.first, AF.lot_no, AF.create_time, AF.chemical, AF.ani_group, AF.create_user, ED1.EMP_NAME AS ed1, 
                   ED2.EMP_NAME AS ed2, AF.need_no, AF.work_type, WORKTIME.hrs
FROM      analyze_first1 AS AF LEFT OUTER JOIN
                   WORKTIME ON AF.work_type = WORKTIME.ELM_ID LEFT OUTER JOIN
                   EMPLOYEE_DATA AS ED1 ON AF.create_user = ED1.EMP_NO LEFT OUTER JOIN
                   EMPLOYEE_DATA AS ED2 ON AF.first = ED2.EMP_NO 
			 where (AF.create_time>='".$date1."083099') 
			 and (AF.create_time<='".$date2."083000')  and AF.create_user='".$rowPP['create_user']."'";
			
//		if(trim($rowPP['create_user'])=='E903' or trim($rowPP['create_user'])=='E1022'){echo "<BR>#".trim($rowPP['create_user'])."#<BR>";}
			////////////////////////////////////////增加檢測項目搜尋///////////////////////////////////////////////
			 if($_POST['selected_group']<>'0')
			 {
				$query=$query." and (AF.ani_group ='".$_POST['selected_group']."')";
			 }
	
	
	/////////////////////////////////////////////藥品類別搜尋////////////////////////////////////////////////////
	if($_POST['needno']<>'0')
	{
		if(	$_POST['needno']==1){$query=$query." and (AF.need_no <= 150)";}
		if(	$_POST['needno']==2){$query=$query." and (AF.need_no > 150) and (AF.need_no <=270)";}
		if(	$_POST['needno']==3){$query=$query." and (AF.need_no > 270)";}
	}
		////////////////////////////////////////////藥品分類搜尋//////////////////////////////////////////////////////
	if($_POST['selected_chemical']<>'0')
	{
		$query=$query." and (AF.chemical ='".$_POST['selected_chemical']."')";
	}
	///////////////////////////////////////////列出搜尋結果///////////////////////////////////////////////////
	$querys=$query."and ((AF.work_type = 702) OR (AF.work_type = 703) OR (AF.work_type = 704))";
	
//	if(trim($rowPP['create_user'])=='E1022'){echo "<BR>".$querys."<BR>";}
	
	$results=mssql_query($querys);
	$nums=mssql_num_rows($results);
			$query=$query."order by AF.create_time";
		//echo $query.'<br>';
//			if(trim($rowPP['create_user'])=='E1022'){echo "<BR>".$query."<BR>";}

			 
	 		$result = mssql_query($query);
			$numRows=mssql_num_rows($result);	
			$num[$rowPP['create_user']]=$numRows-$nums;
			$A01=array();
			$A02=array();
			$A03=array();
			$CON=0;
			$smp=array();
			while ($row = mssql_fetch_array($result))
			{	
/////////////////////////////////////////////////////////////////////前處理計算////////////////////////////////////

				if((trim($row['first']))<>'' or trim($row['ani_group'])=='M13')
				{ 
	//			echo "<BR>ENTER 3<BR>";
					$count1=array();		
						$RF="select hrs
						from WORKTIME
						where PDD_CHEMICAL='".$row['chemical']."' and ANI_GROUPNAME='".trim($row['ani_group'])."FIRST'";

						$resultrf = mssql_query($RF);
						$numRowsrf=mssql_num_rows($resultrf);	
						//echo $numRowsrf;
						
						while($rowrf = mssql_fetch_array($resultrf))
						{	
							$first1[$row['first']]=$first1[$row['first']]+$rowrf['hrs'];
							$first2=$first2+$rowrf['hrs'];
						}
				}
				
////////////////////////////////////////////////分析項目個數////////////////////////////////////////////////////////
				
				if(trim($row['ani_group'])=='PMS' or trim($row['ani_group'])=='RION'){$num[$rowPP['create_user']]=$num[$rowPP['create_user']]-1;}
				//$_SESSION['numbbb']=$num[$rowPP['create_user']];
	///////////////////////////////////////////////工時計算/////////////////////////////////////////////////////////
				if(trim($row['ani_group'])!='PMS' and trim($row['ani_group'])!='RION')//<>'TT' and trim($row['ani_group'])<>'I')
				{ 
	//			echo "<BR>ENTER 4<BR>";
					$query1="select  PDD_CHEMICAL,hrs,ANI_GROUPNAME,ELF_FORM,ANI_FULLNAME
					from WORKTIME
					where PDD_CHEMICAL='".$row['chemical']."' and ANI_GROUPNAME='".$row['ani_group']."'";
					if($row['work_type']==704 or $row['work_type']==703 or $row['work_type']==702)
					{
						$query1="select distinct PDD_CHEMICAL,hrs,ANI_GROUPNAME,ELF_FORM,ANI_FULLNAME from WORKTIME where PDD_CHEMICAL='*' and ANI_GROUPNAME='SAMPLE'";	
				//		echo "<BR>".$query1."<BR>";
					}
						//////////////////////////echo $query1;////////////////////////
	//					echo "<BR>".$query1."<BR>";
						$result1 = mssql_query($query1);
						$numRows1=mssql_num_rows($result1);	
						$c=0;
						$test=0;
						while ($row1 = mssql_fetch_array($result1))
						{ 
	//                echo "<BR>ENTER 5<BR>";
		///////////////////////////////////////////////////////////////算工時遇到I===////////////////////////////
							if(trim($row['ani_group'])=="I")
							{
								if(trim($row['chemical'])=="H2SO4")
								{
									
									$H2SO4="select AnalyzeTime from [".$row1['ELF_FORM']."] where AnalyzeTime='".$row['create_time']."'";
									//echo $H2SO4;
									$resultH = mssql_query($H2SO4);
									$numRowsH=mssql_num_rows($resultH);	
									if($numRowsH==1){
										$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$row1['hrs'];
									$alltime1=$alltime1+$row1['hrs'];}
									
								}
								else
								{
									$II="select [NO3],[PO4],[SO4]
									FROM	[".$row1['ELF_FORM']."]
									where AnalyzeTime='".$row['create_time']."'";
									$resultII = mssql_query($II);
									$numRowsII=mssql_num_rows($resultII);
									while ($rowII = mssql_fetch_array($resultII))
									{
										
										if($rowII['NO3']<>''){$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$row1['hrs']/$numRows1;$alltime1=$alltime1+$row1['hrs']/$numRows1;}
										if($rowII['PO4']<>''){$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$row1['hrs']/$numRows1;$alltime1=$alltime1+$row1['hrs']/$numRows1;}
										if($rowII['SO4']<>''){$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$row1['hrs']/$numRows1;$alltime1=$alltime1+$row1['hrs']/$numRows1;}
									}
								}
							}			
	/////////////////////////////////////////////////////////算工時大部分/////////////////////////////////////////////		
							elseif($row['work_type']<>704 and $row['work_type']<>703 and $row['work_type']<>702)
							{
								$c++;           
								if($numRows1==1)
								{	
									$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$row1['hrs'];
									$alltime1=$alltime1+$row1['hrs'];
									
								}
								if($numRows1>1)
								{	
									array_push($A01,$row1['hrs']);
									if($c==$numRows1)
									{
										//echo "近來".$c;
										//echo $row1['PDD_CHEMICAL']."/".$row1['ANI_GROUPNAME'];
										//echo '</br>';
										for($i=0;$i<$numRows1;$i++)
										{							
											if($A01[0]/$A01[$i]==1 and $A01[0]/$A01[1]==1)
											{	
												$plus=array_sum($A01);
												$iftime=floor($plus/$numRows1);
												
											}//if
											if($A01[0]/$A01[$i]<>1 and $A01[0]/$A01[1]<>1)
											{	
												$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$A01[$i];
												$alltime1=$alltime1+$A01[$i];
											}//if
										}//for							
										$alltime[$rowPP['create_user']]=$alltime[$rowPP['create_user']]+$iftime;
										$alltime1=$alltime1+$iftime;
										
										unset($A01);
										$A01=array();
									}//if
								}//if
							}
						}//while	
			
			
			
			
					if($row['work_type']==704 or $row['work_type']==703 or $row['work_type']==702)
					{
						$query1="select distinct PDD_CHEMICAL,hrs,ANI_GROUPNAME,ELF_FORM,ANI_FULLNAME from WORKTIME where PDD_CHEMICAL='*' and ANI_GROUPNAME='SAMPLE'";	
						// 樣品瓶作業
						$result1 = mssql_query($query1);
						$numRows1=mssql_num_rows($result1);	
						$c=0;
						$test=0;
						while ($row1 = mssql_fetch_array($result1))
						{ 
			//				echo "<BR>".$query."<BR>";
			//				echo "<BR>".$query1."<BR>";

							if($numRows1==1)
							{	
								$alltime3=$alltime3+$row1['hrs'];
								$A3[$rowPP['create_user']]=$A3[$rowPP['create_user']]+$row1['hrs'];
							}
							if($numRows1>1)
							{	
								if($c==$numRows1)
								{
									$A3[$rowPP['create_user']]=$A3[trim($rowPP['create_user'])]+$row1['hrs'];
								}//if
							}//if
						}
					}
				}
			}
			
			}	
			
			echo '</br>'."總分析時間".round($alltime1)."分鐘";
			echo '</br>'."總前處理時間".round($first2)."分鐘";
			echo '</br>'."總樣品瓶處理時間".$alltime3."分鐘";
			$bigmax=$alltime1+$first2;
			echo '</br>'."共".round($bigmax)."分鐘".'</br>'; 
			$PRINT1="SELECT distinct AF.create_user,ED.EMP_NAME as EMP1 
				FROM analyze_first1 as AF 
				left join EMPLOYEE_DATA as ED on AF.create_user=ED.EMP_NO
				WHERE (AF.create_user<>'') and  (AF.create_time>='".$date1."083099') 
			 and (AF.create_time<='".$date2."083000')";
				$resultP1 = mssql_query($PRINT1);
//				echo $PRINT1;
//				echo "<BR>";
				$numRowsP1=mssql_num_rows($resultP1);
				while ($rowP1 = mssql_fetch_array($resultP1))
				{
						$_SESSION['create_user']=$rowP1['create_user'];
						if($num[$rowP1['create_user']]>0 )
						{
								if($BRK==1)
								{
									
								echo '<tr height="">';
								 echo  '<td>'."工號".'</td>';
								  echo  '<td>'."姓名".'</td>';
								   echo  '<td>'."分析項目".'</td>';
								   echo  '<td>'."分析時間/分鐘".'</td>';
								   echo  '<td>'."包含前處理時間/分鐘".'</td>';
								   echo  '<td>'."樣品瓶處理時間/分鐘".'</td>';
								    echo  '<td>'."工作時數".'</td>';
									 echo  '<td>'."工作人天數".'</td>';
									  echo  '<td>'."實際工作天數".'</td></tr>';
									  //  echo  '<td>'."姓名".'</td>';
								  //  echo  '<td>'."前處理/分鐘".'</td>';
								$BRK++;	 
								}
								$totime[$rowP1['create_user']]=$alltime[$rowP1['create_user']]+$first1[$rowP1['create_user']];
								echo '<tr height="">';
							echo '<td>'.$rowP1['create_user'].'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("A".$p,$rowP1['create_user']);
							echo '<td>'.$rowP1['EMP1'].'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8",$rowP1['EMP1']));
							
							echo'<td>'.$num[$rowP1['create_user']].'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("C".$p,$num[$rowP1['create_user']]);
							echo '<td>'.$alltime[$rowP1['create_user']].'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("D".$p,$alltime[$rowP1['create_user']]);				
							echo  '<td>'.$totime[$rowP1['create_user']].'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("E".$p,$totime[$rowP1['create_user']]);
							$workhr=$totime[$rowP1['create_user']]/60;
							echo  '<td>'.$A3[$rowP1['create_user']].'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("F".$p,$A3[$rowP1['create_user']]);
							
							echo  '<td>'. number_format($workhr,1).'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("G".$p,number_format($workhr,1));
							$workday=$workhr/7.5;
							echo  '<td>'.number_format($workday,1).'</td>';
							$objPHPExcel->getActiveSheet()->setCellValue("H".$p,number_format($workday,1));
							/*
							$OPQ=HR();
							echo '<td></td></tr>';
							$objPHPExcel->getActiveSheet()->setCellValue("I".$p,$OPQ);
							*/
							echo '<td></td></tr>';
							$objPHPExcel->getActiveSheet()->setCellValue("I".$p,'');
							$GLOBALS['p']++;
						}
				}
	
		//=========================================前處理的列印============================================================
				$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8","合計"));
				$objPHPExcel->getActiveSheet()->setCellValue("E".$p, "=SUM(E2:"."E".($p-1).")");//bigmax
				$workhr1=$bigmax/60;
				$objPHPExcel->getActiveSheet()->setCellValue("F".$p,"=SUM(F2:"."F".($p-1).")");$here=$p;//number_format($workhr1,1))
				$workday1=$workhr1/7.5;
				$objPHPExcel->getActiveSheet()->setCellValue("G".$p,"=SUM(G2:"."G".($p-1).")");//number_format($workday1,1) 
				$objPHPExcel->getActiveSheet()->setCellValue("H".$p,"=SUM(H2:"."H".($p-1).")");
				$GLOBALS['p']++;
				include_once("../connections/conn.php");
				$queryed="SELECT DISTINCT AF.first, ED2.EMP_NAME AS ed2
							FROM      analyze_first1 AS AF LEFT OUTER JOIN
											   EMPLOYEE_DATA AS ED1 ON AF.create_user = ED1.EMP_NO LEFT OUTER JOIN
											   EMPLOYEE_DATA AS ED2 ON AF.first = ED2.EMP_NO 
			 where ED2.EMP_NAME<>'' and (AF.create_time>='".$date1."083099') 
			 and (AF.create_time<='".$date2."083000')  ";
			 			////////////////////////////////////////增加檢測項目搜尋///////////////////////////////////////////////
			 if($_POST['selected_group']<>'0')
			 {
				$queryed=$queryed." and (AF.ani_group ='".$_POST['selected_group']."')";
			 }
			
	
	/////////////////////////////////////////////藥品類別搜尋////////////////////////////////////////////////////
			if($_POST['needno']<>'0')
			{
				my_msg("needno");
					if(	$_POST['needno']==1){$queryed=$queryed." and (AF.need_no <= 150)";}
					if(	$_POST['needno']==2){$queryed=$queryed." and (AF.need_no > 150) and (AF.need_no <=270)";}
					if(	$_POST['needno']==3){$queryed=$queryed." and (AF.need_no > 270)";}
			}
				////////////////////////////////////////////藥品分類搜尋//////////////////////////////////////////////////////
			if($_POST['selected_chemical']<>'0')
			{
				$queryed=$queryed." and (AF.chemical ='".$_POST['selected_chemical']."')";
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue("A".$GLOBALS['p'], iconv("big5","utf-8","只有前處理者"));
			$objPHPExcel->getActiveSheet()->setCellValue("C".$GLOBALS['p'], iconv("big5","utf-8","前處理工時/分鐘"));
			
			$A=fst123($queryed,$alltime,$first1,$objPHPExcel, $GLOBALS['p']);
			 
			 $GLOBALS['p']++;
			 $allfirst1=$allfirst/60;
			 $objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","合計"));
			 $objPHPExcel->getActiveSheet()->setCellValue("F".$p,number_format($allfirst1,1));
			 $alldayfirst=$allfirst1/7.5;
			 $objPHPExcel->getActiveSheet()->setCellValue("G".$p,number_format($alldayfirst,1));
			 $objPHPExcel->getActiveSheet()->setCellValue("H".$p,"=SUM("."H".($p-$a).":"."H".($p-1).")");
			  $GLOBALS['p']++;
			  $objPHPExcel->getActiveSheet()->setCellValue("E".$p, iconv("big5","utf-8","總計"));
			  $objPHPExcel->getActiveSheet()->setCellValue("F".$p,"=SUM(F".$here."+F".($p-1).")");
			  $objPHPExcel->getActiveSheet()->setCellValue("G".$p,"=SUM(G".$here."+G".($p-1).")");
			  $objPHPExcel->getActiveSheet()->setCellValue("H".$p,"=SUM(H".$here."+H".($p-1).")");
			  
			  
			  //======================================第二頁====================================================================
			
			  
			 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('report1.xlsx');
			
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			 
		}
		
}   
/// end submit
		
////////////////////////////////////////IFPOST列出所有結果//////////////////////////////////////////////////		
if(isset($_POST['submit1']))
{
	echo '<table id="GridView1" width="1124" border="1" align="left">
  <tr class="GridviewScrollHeader">';
	//	echo '<table border="1" width="1024">';
		//echo '<tr height="">';
			 echo  '<td>'."工號".'</td>';
			  echo  '<td>'."姓名".'</td>';
			   echo  '<td>'."LOT NO".'</td>';
			   echo  '<td>'."類別".'</td>';
			    echo  '<td>'."檢測日期".'</td>';
			  echo  '<td>'."藥品名稱".'</td>';
			 echo  '<td>'."檢測項目".'</td>';
			  echo  '<td>'."前處理人員".'</td>';
			   echo  '<td>'."姓名".'</td>';
			   echo '<td>'."增加工時".'</td>';
			      echo '<td>'."工時".'</td>';
				  echo  '<td>'."前處理工時".'</td>';
			  echo '</tr>';
				
			$query="select ED1.DEP_NO,AF.first,AF.lot_no,AF.create_time,AF.chemical,AF.ani_group,AF.create_user,ED1.EMP_NAME as ed1
			 ,AF.need_no,AF.ps
			 from analyze_first1 as AF
			 left join EMPLOYEE_DATA as ED1 on AF.create_user=ED1.EMP_NO
			 

			 where (AF.create_time>='".$date1."083099') 
			 and (AF.create_time<='".$date2."083000') ";
			// echo $query;
			 
			////////////////////////////////////////增加檢測項目搜尋///////////////////////////////////////////////
			 if($_POST['selected_group']<>'0')
			 {
				$query=$query." and (AF.ani_group ='".$_POST['selected_group']."')";
			 }
	
	
	/////////////////////////////////////////////藥品類別搜尋////////////////////////////////////////////////////
	if($_POST['needno']<>'0')
	{
			if(	$_POST['needno']==1){$query=$query." and (AF.need_no <= 150)";}
			if(	$_POST['needno']==2){$query=$query." and (AF.need_no > 150) and (AF.need_no <=270)";}
			if(	$_POST['needno']==3){$query=$query." and (AF.need_no > 270)";}
	}
		////////////////////////////////////////////藥品分類搜尋//////////////////////////////////////////////////////
	if($_POST['selected_chemical']<>'0')
	{
		$query=$query." and (AF.chemical ='".$_POST['selected_chemical']."')";
	}
	///////////////////////////////////////////列出搜尋結果///////////////////////////////////////////////////
	
	$query=$query."and ani_group!='PMS' and ani_group!='RION' and ED1.DEP_NO='AN' order by AF.create_time";
	  $result = mssql_query($query);
	  //echo $query;
			$numRows=mssql_num_rows($result);	
			   $num[$rowPP['create_user']]=$numRows;
			   $A01=array();
			   $CON=0;$made=0;$fc=0;$inside=0;
			   
			   $objPHPExcel = new PHPExcel();
	$objPHPExcel = PHPExcel_IOFactory::load("./report1.xlsx");
			$objPHPExcel->setActiveSheetIndex(1);
			$H2SO41=$H2SO42=$H2SO43=$H2O2A=$H2O21=$H2O22=$H2O23=$NH4OH1=$NH4OH2=$NH4OH3=$IPA1=$IPA2=$IPA3=$HF1=$HF2=$HF3=$HNO31=$HNO32=$HNO33=$CAN1=$CAN2=$CAN3=$AIR1=$AIR2=$AIR3=0;

			global $d;
			$d=3;
		while ($row = mssql_fetch_array($result))
			{	
			
				
				
				$query2="select top 1 hrs from WORKTIME 
				where ani_groupname='".$row['ani_group']."' and PDD_chemical='".$row['chemical']."' and hrs!='' ";
				//echo $query2.'<br>';
				 $result2 = mssql_query($query2);
			$numRows2=mssql_num_rows($result2);
					while ($row2 = mssql_fetch_array($result2))
					{
					

			
			   $objPHPExcel->getActiveSheet()->setCellValue("A".$d, iconv("big5","utf-8","工號"));
			   echo '<tr class="GridviewScrollItem">';
				
				echo  '<td>'.$row['create_user'].'</td>';
				$objPHPExcel->getActiveSheet()->setCellValue("A".$d,$row['create_user']);					
					echo '<td>'.$row['ed1'].'</td>';
				$objPHPExcel->getActiveSheet()->setCellValue("B".$d, iconv("big5","utf-8",$row['ed1']));
					echo  '<td>'.$row['lot_no'].'</td>';
				$objPHPExcel->getActiveSheet()->setCellValue("C".$d,$row['lot_no']);
					if($row['need_no']<=150){echo '<td>'."製品".'</td>'; ;$made++;
					$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","製品"));}
					if($row['need_no']>150 and $row['need_no']<=270){echo '<td>'."解析".'</td>';$fc++;
					$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","解析"));}
					if($row['need_no']>270){echo '<td>'."原料".'</td>';$inside++;
					$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","原料"));}
					echo  '<td>'.ddt($row['create_time']).'</td>';
					
					$objPHPExcel->getActiveSheet()->setCellValue("E".$d, ddt($row['create_time']));
					echo  '<td>'.$row['chemical'].'</td>';
					if(trim($row['chemical'])=='H2SO4')
					{
					if($row['need_no']<=150){$H2SO41=$H2SO41+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$H2SO42=$H2SO42+1;}
					if($row['need_no']>270){$H2SO43=$H2SO43+1;}
					}
					if(trim($row['chemical'])=='H2O2')
					{
					if($row['need_no']<=150){$H2O21=$H2O21+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$H2O22=$H2O22+1;}
					if($row['need_no']>270){$H2O23=$H2O23+1;}
					}
					if(trim($row['chemical'])=='NH4OH')
					{
					if($row['need_no']<=150){$NH4OH1=$NH4OH1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$NH4OH2=$NH4OH2+1;}
					if($row['need_no']>270){$NH4OH3=$NH4OH3+1;}
					}
					if(trim($row['chemical'])=='IPA')
					{
					if($row['need_no']<=150){$IPA1=$IPA1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$IPA2=$IPA2+1;}
					if($row['need_no']>270){$IPA3=$IPA3+1;}
					}
					if(trim($row['chemical'])=='HF')
					{
					if($row['need_no']<=150){$HF1=$HF1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$HF2=$HF2+1;}
					if($row['need_no']>270){$HF3=$HF3+1;}
					}
					if(trim($row['chemical'])=='HNO3')
					{
					if($row['need_no']<=150){$HNO31=$HNO31+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$HNO32=$HNO32+1;}
					if($row['need_no']>270){$HNO33=$HNO33+1;}
					}
					if(trim($row['chemical'])=='CAN')
					{
					if($row['need_no']<=150){$CAN1=$CAN1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$CAN2=$CAN2+1;}
					if($row['need_no']>270){$CAN3=$CAN3+1;}
					}
					if(trim($row['chemical'])=='CH3COOH')
					{
					if($row['need_no']<=150){$CH3COOH1=$CH3COOH1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$CH3COOH2=$CH3COOH2+1;}
					if($row['need_no']>270){$CH3COOH3=$CH3COOH3+1;}
					}
					if(trim($row['chemical'])=='3MAE')
					{
					if($row['need_no']<=150){$MAE1=$MAE1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$MAE2=$MAE2+1;}
					if($row['need_no']>270){$MAE3=$MAE3+1;}
					}
					if(trim($row['chemical'])=='H2O')
					{
					if($row['need_no']<=150){$H2O1=$H2O1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$H2O2=$H2O2+1;}
					if($row['need_no']>270){$H2O3=$H2O3+1;}
					}
					if(trim($row['chemical'])=='AIR')
					{
					if($row['need_no']<=150){$AIR1=$AIR1+1;}
					if($row['need_no']>150 and $row['need_no']<=270){$AIR2=$AIR2+1;}
					if($row['need_no']>270){$AIR3=$AOR3+1;}
					}
					$objPHPExcel->getActiveSheet()->setCellValue("F".$d,trim($row['chemical']));
					echo  '<td>'.$row['ani_group'].'</td>';
					if(trim($row['ani_group'])=='M13' or trim($row['ani_group'])=='A' or trim($row['ani_group'])=='P')
					{$three=$three+1;}
					else{$els=$els+1;}
					$objPHPExcel->getActiveSheet()->setCellValue("G".$d,iconv("big5","utf-8",trim($row['ani_group'])));
					echo  '<td>'.$row['first'].'</td>';
					$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$row['first']);
					echo  '<td>'.$row['ed2'].'</td>';
					$objPHPExcel->getActiveSheet()->setCellValue("I".$d, iconv("big5","utf-8",getusername($row['first'])));
					echo  '<td>'.$row['ps'].'</td>';
					$objPHPExcel->getActiveSheet()->setCellValue("J".$d, iconv("big5","utf-8",$row['ps']));		
					echo  '<td>'.$row2['hrs'].'</td>';	
					$objPHPExcel->getActiveSheet()->setCellValue("K".$d, iconv("big5","utf-8",$row2['hrs']));
					
					
					if(trim($row['first'])<>'' or trim($row['ani_group'])=='M13')
					{
						$query3="select top 1 hrs from WORKTIME 
						where ani_groupname='".trim($row['ani_group'])."FIRST' and PDD_chemical='".$row['chemical']."' and hrs!='' ";
						//echo $query3;
						 $result3 = mssql_query($query3);
						$numRows3=mssql_num_rows($result3);
						while ($row3 = mssql_fetch_array($result3))
							{
								echo  '<td>'.$row3['hrs'].'</td>';
								$objPHPExcel->getActiveSheet()->setCellValue("L".$d, iconv("big5","utf-8",$row3['hrs']));

							}
							if($numRows3==''){echo  '<td></td>';}
					}
					else
					{
						echo  '<td>'."".'</td>';
					}					
		
					$GLOBALS['d']++;
					

					echo '</tr>';
					}
					
			}
			
			echo '</table>'.'</br>'.'</br>'.'</br>';
			echo "總共".$numRows."件".'</br>';
			$objPHPExcel->getActiveSheet()->setCellValue("A1", iconv("big5","utf-8","總共".$numRows."件"));
			echo "製品共".$made."件__";
			$objPHPExcel->getActiveSheet()->setCellValue("B1", iconv("big5","utf-8","製品共".$made."件"));
			echo "解析共".$fc."件__";
			$objPHPExcel->getActiveSheet()->setCellValue("C1", iconv("big5","utf-8","解析共".$fc."件"));
			echo "原料共".$inside."件".'</br>';
			$objPHPExcel->getActiveSheet()->setCellValue("D1", iconv("big5","utf-8","原料共".$inside."件"));
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","藥品"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d, iconv("big5","utf-8","件數"));
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d, iconv("big5","utf-8","製品"));
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d, iconv("big5","utf-8","解析"));
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d, iconv("big5","utf-8","原料"));
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","H2SO4"));
			$H2SO4=$H2SO41+$H2SO42+$H2SO43;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$H2SO4);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$H2SO41);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$H2SO42);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$H2SO43);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","H2O2"));
			$H2O2A=$H2O21+$H2O22+$H2O23;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$H2O2A);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$H2O21);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$H2O22);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$H2O23);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","NH4OH"));
			$NH4OH=$NH4OH1+$NH4OH2+$NH4OH3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$NH4OH);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$NH4OH1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$NH4OH2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$NH4OH3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","IPA"));
			$IPA=$IPA1+$IPA2+$IPA3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$IPA);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$IPA1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$IPA2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$IPA3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","HF"));
			$HF=$HF1+$HF2+$HF3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$HF);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$HF1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$HF2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$HF3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","HNO3"));
			$HNO3=$HNO31+$HNO32+$HNO33;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$HNO3);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$HNO31);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$HNO32);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$HNO33);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","CAN"));
			$CAN=$CAN1+$CAN2+$CAN3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$CAN);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$CAN1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$CAN2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$CAN3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","CH3COOH"));
			$CH3COOH=$CH3COOH1+$CH3COOH2+$CH3COOH3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$CH3COOH);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$CH3COOH1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$CH3COOH2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$CH3COOH3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","MAE"));
			$MAE=$MAE1+$MAE2+$MAE3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$MAE);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$MAE1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$MAE2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$MAE3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","UPW/WASTE"));
			$H2O=$H2O1+$H2O2+$H2O3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$H2O);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$H2O1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$H2O2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$H2O3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","AIR"));
			$AIR=$AIR1+$AIR2+$AIR3;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$AIR);
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$AIR1);
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$AIR2);
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$AIR3);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","件數合計"));
			$toto=$H2SO4+$H2O2A+$NH4OH+$IPA+$HF+$HNO3+$CAN+$CH3COOH+$MAE+$H2O+$AIR;
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d,$toto);
			$toto1=$H2SO41+$H2O21+$NH4OH1+$IPA1+$HF1+$HNO31+$CAN1+$CH3COOH1+$MAE1+$H2O1+$AIR1;
			$objPHPExcel->getActiveSheet()->setCellValue("F".$d,$toto1);
			$toto2=$H2SO42+$H2O22+$NH4OH2+$IPA2+$HF2+$HNO32+$CAN2+$CH3COOH2+$MAE2+$H2O2+$AIR2;
			$objPHPExcel->getActiveSheet()->setCellValue("G".$d,$toto2);
			$toto3=$H2SO43+$H2O23+$NH4OH3+$IPA3+$HF3+$HNO33+$CAN3+$CH3COOH3+$MAE3+$H2O3+$AIR3;
			$objPHPExcel->getActiveSheet()->setCellValue("H".$d,$toto3);
			$GLOBALS['d']++;
			$GLOBALS['d']++;			
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","A,M13,P 三項件數"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d, $three);
			$GLOBALS['d']++;			
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","A,M13,P 以外全項件數"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d, $els);
			$GLOBALS['d']++;
			$query1="select * from AnalyzeDesign where AND_SMP_DATETIME>='".$date1."083099' 
			 and AND_SMP_DATETIME<='".$date2."083000' and rut!=''";
			//echo $query1;
			$result1 = mssql_query($query1);$NUM=0;
			while($row1 = mssql_fetch_array($result1))
			{
			$row1['AND_ITEM']=$row1['AND_ITEM'].",";
			$aa=explode(',',$row1['AND_ITEM'],-1);
				$query2="SELECT DISTINCT ANI_GROUPNAME
			FROM              AnalyzeItem
			WHERE          (ANI_INDEX <>'') and ";
				for($i=0;$i<count($aa);$i++)
				{
					if ($i<(count($aa)-1)){
					$query2.="(ANI_INDEX =".$aa[$i].") OR ";}
					else {$query2.="(ANI_INDEX =".$aa[$i].")";}	
				}
				//echo $query2.'<br>';
				$query4="select pdd_chemical from PRODUCT_DATA where pdd_prod_no like '%".$row1['AND_GOODS']."%'";
				//echo $query4.'<br><br>';
				$result4 = mssql_query($query4);
				$row4 = mssql_fetch_array($result4);
					$result2 = mssql_query($query2);
					while($row2 = mssql_fetch_array($result2)){
					$query3="SELECT DISTINCT ELEMENT_FORM.ELF_FORM
FROM              dbo.ELEMENT_FORM INNER JOIN
                            dbo.AnalyzeItem ON ELEMENT_FORM.ELM_ID = AnalyzeItem.ANI_INDEX
WHERE          (ELEMENT_FORM.PDD_CHEMICAL = '".$row4[0]."') AND (AnalyzeItem.ANI_GROUPNAME = '".$row2[0]."')";
					//echo $query3.'<br>';
					$result3 = mssql_query($query3);
					$row3 = mssql_fetch_array($result3);
					$query5="select * from ".$row3[0]." where lotno like '%".$row1['AND_LOT_NO']."%'";
					//echo $query5.'<br>';
					//echo $query5.'<br>';
					$result5 = mssql_query($query5);
					$numRows5 = mssql_num_rows($result5);
					//echo $query5;
					//echo $numRows5.'<br>';
					$NUM=$NUM+$numRows5;
					}
			}
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","非定常分析"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d, $NUM);
			$GLOBALS['d']++;
			$objPHPExcel->getActiveSheet()->setCellValue("D".$d, iconv("big5","utf-8","定常分析"));
			$objPHPExcel->getActiveSheet()->setCellValue("E".$d, abs($toto-$NUM));
			
			
			
			
			
			
			
			
				
			/*$objPHPExcel->getActiveSheet()->setCellValue("A2", iconv("big5","utf-8","工號"));
			$objPHPExcel->getActiveSheet()->setCellValue("B2", iconv("big5","utf-8","姓名"));
			$objPHPExcel->getActiveSheet()->setCellValue("C2", iconv("big5","utf-8","LOT NO"));
			$objPHPExcel->getActiveSheet()->setCellValue("D2", iconv("big5","utf-8","類別"));
			$objPHPExcel->getActiveSheet()->setCellValue("E2", iconv("big5","utf-8","檢測日期"));
			$objPHPExcel->getActiveSheet()->setCellValue("F2", iconv("big5","utf-8","藥品名稱"));
			$objPHPExcel->getActiveSheet()->setCellValue("G2", iconv("big5","utf-8","檢測項目"));
			$objPHPExcel->getActiveSheet()->setCellValue("H2", iconv("big5","utf-8","前處理人員"));
			$objPHPExcel->getActiveSheet()->setCellValue("I2", iconv("big5","utf-8","姓名"));
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	   */
	   		
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
			$objWriter->save('report1.xlsx');
}
if(isset($_POST['submit2']))
		{
			
			
$path_root=$_SERVER['HTTP_HOST'];
			echo '</br>';
			echo "列印完成";
		echo '<script>document.location.href="http://'.$path_root.'/people/report1.xlsx";</script>';	
		}
		 ?>
<?PHP 
function select_needno()
{
	session_start();
	echo '<select name="needno" id="needno">';
	echo '<option value="0"></option>';
	echo '<option value="1" '.$select.'>'."製品".'</option>';
	echo '<option value="2" '.$select.'>'."解析".'</option>';
	echo '<option value="3" '.$select.'>'."原料".'</option>';
	
	echo '</select>';

}

function select_chemical(){
	session_start();
	echo '<select name="selected_chemical" id="selected_chemical">';
	echo '<option value="0"></option>';
	$query="SELECT DISTINCT PDD_CHEMICAL
	FROM              WORKTIME";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		//if($row['PDD_CHEMICAL']==$_SESSION['selected_group']){$select='selected';}
		//else{$select='';}
		echo '<option value="'.$row['PDD_CHEMICAL'].'" >'.$row['PDD_CHEMICAL'].'</option>';
	}
	echo '</select>';
}
function HR(){
	include_once("../connections/conn_hr.php");
	$A=substr($_SESSION['datepicker1'],6,4)."/".substr($_SESSION['datepicker1'],0,2);
	$B=substr($_SESSION['datepicker1'],6,4)."".substr($_SESSION['datepicker1'],0,2);
	//echo $_SESSION['create_user'];
	$query="select empno, month_1, count(distinct mon) cnt from (   
select empno, substring(mon,1,7) month_1, mon   --打卡時間超過7點的，視為當天有上班
from (                
select empno, 
       convert(varchar, [recorddatetime], 111) mon, 
       substring(convert(varchar, [recorddatetime], 108),1,4) tm
from ACDOOR_REC
where 1=1
and (readername like '%考勤%' or readername = '補打卡')
AND EMPNO = '".trim($_SESSION['create_user'])."' 
group by empno, convert(varchar, [recorddatetime], 111),substring(convert(varchar, [recorddatetime], 108),1,4)
) m1
where tm>='07:0'
union all
select empno, substring(mon,1,7) month_1, mon   --上&下班時間都在00:00~07:00也要+1
from (                
select empno, 
       convert(varchar, [recorddatetime], 111) mon, 
       max(recorddatetime) max_tm, min(recorddatetime) min_tm ,
       Datediff(minute,min(recorddatetime),max(recorddatetime)) diff_tm
from ACDOOR_REC
where 1=1
and (readername like '%考勤%' or readername = '補打卡')  
and substring(convert(varchar, [recorddatetime], 108),1,5) between '00:0' and '07:00' 
AND EMPNO = '".trim($_SESSION['create_user'])."'  
group by empno, convert(varchar, [recorddatetime], 111)  
) m2
where diff_tm>=120
) m3
WHERE empno = '".trim($_SESSION['create_user'])."'   AND month_1 like '".$A."'
group by empno, month_1
order by empno, month_1";
  	$result=mssql_query($query); 
	//echo $query.'<BR>';
	$numRows=mssql_num_rows($result);
	while($row=mssql_fetch_array($result))
	{
		$T=$row['cnt'];
	}
		$query3="select hr from EMPLOYEE_DAYOFF where empno LIKE '%".trim($_SESSION['create_user'])."%' and datefrom like '%".$B."%' and active=1";
		//echo $query3.'<br>';
		$result3= mssql_query($query3);
		$numRows3=mssql_num_rows($result3);
		if($numRows3==0){$holiday=0;}
		while ($row3 = mssql_fetch_array($result3))
		{
		$holiday=$row3['hr']/8;
		if($holiday<10 and substr($holiday,2,1)=='5'){$T=$T-0.5;}
		elseif($holiday>10 and substr($holiday,3,1)=='5'){$T=$T-0.5;}
		}
	if($numRows==0){$T=0;}
	return $T;
}

function HR2(){
	include_once("../connections/conn_hr.php");
	$A=substr($_SESSION['datepicker1'],6,4)."/".substr($_SESSION['datepicker1'],0,5);
	$B=substr($_SESSION['datepicker2'],6,4)."/".substr($_SESSION['datepicker2'],0,5);
	//echo $_SESSION['create_user'];
	$query="select * from
		(
		SELECT 
		CONVERT(varchar(100), [recorddatetime], 111) as time1, 
		convert(varchar, [recorddatetime], 108) as time2,*
		  FROM ACDOOR_REC
		  left join SIGN_AGREE on SAG_NO=readertype
		  where empno like '%".trim($_SESSION['first'])."%' and readername like '%考勤%' or ((readername like '%補打卡%' or readername like '%出差%') and SAG_FINISHED_TIME!='')  
		  )  as OOXX
			where time1 >='".$A."' and time1 <= '".$B."' order by time1,time2";
			//echo $query;
			$result = mssql_query($query);
			$numRows=mssql_num_rows($result);
			while($row1=mssql_fetch_array($result))
	{
		$T=$numRows/2;
		//echo $last1.substr($row1['time2'],0,5).'<br>';
		if(($last1!=substr($row1['time2'],0,5)))
		{

		if($last!='')   
		{	
			
			//echo '<br>'.$last1."abc";
			//echo $last;
			$now=substr($row1['time2'],0,2)-$hr;
			//echo $last.'<br>';
			if($now==0){$now=24;}
			$tmplast=$last;
			//echo $now.'<br>';
			if($last==0){$tmplast=24;}
			//echo $now;
			//echo $last.$now.'<br>';
			if($now>$last){
			//echo $now.$last.'<br>';
			$wtime=abs($last-$now)-$hr+$holiday;}
			//echo $wtime.'<br>';
			if($wtime>8){}
			
			elseif($wtime<=8){$query4="select max(time1) from
(
SELECT 
CONVERT(varchar(100), [recorddatetime], 111) as time1, 
convert(varchar, [recorddatetime], 108) as time2,*
  FROM ACDOOR_REC
  left join SIGN_AGREE on SAG_NO=readertype
  where empno like '%".trim($_SESSION['first'])."%' and readername like '%考勤%' or ((readername like '%補打卡%' or readername like '%出差%') /*and SAG_FINISHED_TIME!=''*/)  
  )  as OOXX
    where time1 >='".$A."' and time1 <= '".$B."' AND empno like '%".trim($_SESSION['first'])."%' group by time1";
  	$result4=mssql_query($query4); 
	//echo $query4.'<BR>';
	$numRows4=mssql_num_rows($result4);
	while($row4=mssql_fetch_array($result4));
				$T=$numRows4;
			
			
			} 
			
			
		}
	
		//echo substr($row['time2'],0,2);
		
		//echo $hr.'<br>';
		$DATE=substr($row['time1'],0,4).substr($row1['time1'],5,2).substr($row1['time1'],8,2);
		$query2="select overtime from EMPLOYEE_OVERTIME where empno='".$row['empno']."' and datefrom='".$DATE."'";
		$result2 = mssql_query($query2);
		//echo $query1;
		$numRows2=mssql_num_rows($result2);
		if($numRows2==0){$hr=0;}
		while ($row2 = mssql_fetch_array($result2)){
		if(substr($row2['overtime'],0,1)=='0'){
		$hr='1';}else{$hr=$row2['overtime'];}
		}
		
		$query3="select hr from EMPLOYEE_DAYOFF where empno='".$row['empno']."' and datefrom='".$DATE."'";
		$result3= mssql_query($query3);
		$numRows3=mssql_num_rows($result3);
		if($numRows3==0){$holiday=0;}
		while ($row3 = mssql_fetch_array($result3))
		{
		$holiday=$row3['hr'];
		}
		
		
		
		$last=substr($row1['time2'],0,2); 
		//echo '<BR>'.$last;
		$lastrow=substr($row1['time1'],8,2); 
		$last1=substr($row1['time2'],0,5);
		if($clear==0){$last='';  $lastrow=''; $clear=1;}
		
		}
		
	}
			if($T<10){
			if(substr($T,2,1)!=''){$T=substr($T,0,1);}}
			return $T;
}
function fst123($query,$alltime,$first1,$objPHPExcel,$p)
{
	require("../connections/conn.php");
	$result = mssql_query($query);
	$$numrows=mssql_num_rows($result);
	$A=0;
	while($row=mssql_fetch_array($result))	
	{		
		$p++;
		echo $row['ed2'];
		echo "<BR>";
		$objPHPExcel->getActiveSheet()->setCellValue("A".$p, iconv("big5","utf-8",$row['first']));				
		$objPHPExcel->getActiveSheet()->setCellValue("B".$p, iconv("big5","utf-8",$row['ed2']));
		$objPHPExcel->getActiveSheet()->setCellValue("C".$p,$first1[$row['first']]);
		
		$first60=$first1[$row['first']]/60;
						
		$objPHPExcel->getActiveSheet()->setCellValue("F".$p,number_format($first60,1));
		
		$first75=$first60/7.5;
		$objPHPExcel->getActiveSheet()->setCellValue("G".$p,number_format($first75,1));
						
						//$objPHPExcel->getActiveSheet()->setCellValue("H".$p,HR2());
		
		$allfirst=$allfirst+$first1[$row['first']];
						
		$A=$A+$row['first'];
		/*
		echo $row['first'].$row['ed2'].$first1[$row['first']];
		$str="A".$p.":".$row['first'];
		my_msg($str);
		*/
	}
	$GLOBALS['p']=$p;
	return $A;
}

function select_ani_group11(){
	session_start();
	echo '<select name="selected_group" id="selected_group">';
	echo '<option value="0"></option>';
	echo '<option value="702">退回樣品</option>';
	echo '<option value="703">保留樣品</option>';
	echo '<option value="704">接收樣品</option>';
	$query="SELECT DISTINCT ANI_GROUPNAME
	FROM              AnalyzeItem
	WHERE          (ANI_GROUPNAME <> '')";
	$result = mssql_query($query);
	while($row=mssql_fetch_array($result)){		
		if($row['ANI_GROUPNAME']==$_SESSION['selected_group']){$select='selected';}
		else{$select='';}
		echo '<option value="'.$row['ANI_GROUPNAME'].'" '.$select.'>'.$row['ANI_GROUPNAME'].'</option>';
	}
	echo '</select>';
}
?>