<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<?php
session_start();
include_once("../connections/conn.php");
include_once("fun.php");
include_once("jtsai.php");
datepick();
class creat_ana_lot_no
{	
	public $year,$month,$day,$pid,$new_lotno,$ana_type,$dept,$post_str,$lotno ;
	function type_ana()
	{
		$M=$value='';
		$query1="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE (TABLE_NAME ='lot_no_rules')";
		$result_column = mssql_query($query1);
		while($row_column = mssql_fetch_array($result_column))
		{
			if(($row_column['COLUMN_NAME']!='index') && ($row_column['COLUMN_NAME']!='pid') 
			&& ($row_column['COLUMN_NAME']!='sample_no'))
			{
				$query="select [".$row_column['COLUMN_NAME']."] from lot_no_rules where (pid='".$this->pid."')";
				$result = mssql_query($query);
				$numRows = mssql_num_rows($result);
				if($numRows>0)
				{
					while($row = mssql_fetch_array($result))
					{
						$str=trim($row[$row_column['COLUMN_NAME']]);
						switch ($str)
						{
							case 'yr':
							
							$a=substr($this->day,2,2);
							$M=$M.$a;$value.=$a;
							break;
							
							case 'mo':
							$a=exmonth(substr($this->day,4,2));
							$M=$M.$a;$value.=$a;
							break;
							
							case 'dy':
							$a=substr($this->day,6,2);
							$M=$M.$a;$value.=$a;
							break;
							
							case 'AC':
							if($_SESSION['AC']=='A'){$a=' selected';}
							if($_SESSION['AC']=='B'){$b=' selected';}
							if($_SESSION['AC']=='C'){$c=' selected';}
							$a='<select name="AC" id="AC" onchange="set_date_session(this.name,this.value)">
            						<option value="A" '.$a.'>A</option>
            						<option value="B" '.$b.'>B</option>
            						<option value="C" '.$c.'>C</option>
          						</select>';
							$M=$M.$a;
							if($_SESSION['AC']==''){$_SESSION['AC']='A';}else{$value.=$_SESSION['AC'];}
							break;
							
							case 'AB':
							if($_SESSION['AB']=='A'){$a1=' selected';}
							if($_SESSION['AB']=='B'){$b1=' selected';}
							$a='<select name="AB" id="AB" onchange="set_date_session(this.name,this.value)">
            						<option value="A" '.$a1.'>A</option>
            						<option value="B" '.$b1.'>B</option>
          						</select>';
							$M=$M.$a;
							if($_SESSION['AB']==''){$_SESSION['AB']='A';}else{$value.=$_SESSION['AB'];}
							break;
							
							case 'AD':
							if($_SESSION['AD']=='A'){$a=' selected';}
							if($_SESSION['AD']=='B'){$b=' selected';}
							if($_SESSION['AD']=='C'){$c=' selected';}
							if($_SESSION['AD']=='D'){$d=' selected';}
							$a='<select name="AD" id="AD" onchange="set_date_session(this.name,this.value)">
            						<option value="A" '.$a.'>A</option>
            						<option value="B" '.$b.'>B</option>
            						<option value="C" '.$c.'>C</option>
									<option value="D" '.$d.'>D</option>
          						</select>';
							$M=$M.$a;
							if($_SESSION['AD']==''){$_SESSION['AD']='A';}else{$value.=$_SESSION['AD'];}
							break;
							
							case 'BM':
							if($_SESSION['BM']=='B'){$B1=' selected';}
							if($_SESSION['BM']=='M'){$M1=' selected';}
							if($_SESSION['BM']=='E'){$E=' selected';}
							$a='<select name="BM" id="BM" onchange="set_date_session(this.name,this.value)">
            						<option value="B" '.$B1.'>B</option>
            						<option value="M" '.$M1.'>M</option>
            						<option value="E" '.$E.'>E</option>
          						</select>';
								if($_SESSION['BM']==''){$_SESSION['BM']='B';}else{$value.=$_SESSION['BM'];}
							$M=$M.$a;
							break;
							
							case 'N2':
							if($_SESSION['N2']=='01'){$n201=' selected';}
							if($_SESSION['N2']=='02'){$n202=' selected';}
							if($_SESSION['N2']=='03'){$n203=' selected';}
							$a='<select name="N2" id="N2" onchange="set_date_session(this.name,this.value)">
            						<option value="01" '.$n201.'>01</option>
            						<option value="02" '.$n202.'>02</option>
            						<option value="03" '.$n203.'>03</option>
          						</select>';
							$M=$M.$a;
							if($_SESSION['N2']==''){$_SESSION['N2']='01';}else{$value.=$_SESSION['N2'];}
							break;
							
							case 'NX9':
							if($_SESSION['N2']=='0'){$n200=' selected';}
							if($_SESSION['N2']=='1'){$n201=' selected';}
							if($_SESSION['N2']=='2'){$n202=' selected';}
							if($_SESSION['N2']=='3'){$n203=' selected';}
							if($_SESSION['N2']=='4'){$n204=' selected';}
							if($_SESSION['N2']=='5'){$n205=' selected';}
							if($_SESSION['N2']=='6'){$n206=' selected';}
							if($_SESSION['N2']=='7'){$n207=' selected';}
							if($_SESSION['N2']=='8'){$n208=' selected';}
							if($_SESSION['N2']=='9'){$n200=' selected';}
							$a='<select name="N2" id="N2" onchange="set_date_session(this.name,this.value)">
            						<option value="0" '.$n200.'>0</option>
									<option value="1" '.$n201.'>1</option>
            						<option value="2" '.$n202.'>2</option>
            						<option value="3" '.$n203.'>3</option>
									<option value="4" '.$n204.'>4</option>
            						<option value="5" '.$n205.'>5</option>
            						<option value="6" '.$n206.'>6</option>
									<option value="7" '.$n207.'>7</option>
            						<option value="8" '.$n208.'>8</option>
            						<option value="9" '.$n209.'>9</option>
          						</select>';
							$M=$M.$a;
							if($_SESSION['N2']==''){$_SESSION['N2']='01';}else{$value.=$_SESSION['N2'];}
							break;
							
							case 'N3':
							if($_SESSION['N3']=='001'){$n301=' selected';}else{$n301='';}
							if($_SESSION['N3']=='002'){$n302=' selected';}else{$n302='';}
							if($_SESSION['N3']=='003'){$n303=' selected';}else{$n303='';}
							$a='<select name="N3" id="N3" onchange="set_date_session(this.name,this.value)">
            						<option value="001" '.$n301.'>001</option>
            						<option value="002" '.$n302.'>002</option>
            						<option value="003" '.$n303.'>003</option>
          						</select>';
							$M=$M.$a;
							if($_SESSION['N3']==''){$_SESSION['N3']='001';}else{$value.=$_SESSION['N3'];}
							break;
							
							case 'DEP':
							if($_SESSION['DEP']=='HD'){$n301=' selected';}else{$n301='';}
							if($_SESSION['DEP']=='IC2'){$n302=' selected';}else{$n302='';}
							if($_SESSION['DEP']=='BR'){$n303=' selected';}else{$n303='';}
							if($_SESSION['DEP']=='CE'){$n304=' selected';}else{$n304='';}
							if($_SESSION['DEP']=='GU'){$n305=' selected';}else{$n305='';}
							if($_SESSION['DEP']=='IC4'){$n306=' selected';}else{$n306='';}
							$a='<select name="DEP" id="DEP" onchange="set_date_session(this.name,this.value)">
            						<option value="HD" '.$n301.'>本棟</option>
            						<option value="IC2" '.$n302.'>IC2</option>
            						<option value="BR" '.$n303.'>物流</option>
									<option value="CE" '.$n304.'>CE</option>
									<option value="GU" '.$n305.'>GUARD</option>
									<option value="IC4" '.$n306.'>IC4</option>
          						</select>';
							$M=$M.$a;
							$value.=$_SESSION['DEP'];
							break;
													
							case 'X09':
							if($_SESSION['X09']=="5"){
								$a='selected="selected"';$b="";
							}
							elseif($_SESSION['X09']=="10"){
								$b='selected="selected"';$a="";
							}
							$a='<select name="X09" id="X09" onchange="set_date_session(this.name,this.value)">
												<option value="5" '.$a.'>0~5</option>
            						<option value="10" '.$b.'>0~9</option>
          						</select>';
							$value=$M;
							$M=$M.$a;
							break;
							
							case '_AD':
							$a='<select name="_AD" id="_AD" onchange="set_date_session(this.name,this.value)">
            						<option value="" '.$a.'>A~D</option>
          						</select>';
							$value=$M;
							$M=$M.$a;
							break;
							
							case 'TANK_NO':
							$query="SELECT  PROD_TANK, PDD_PROD_NO
									FROM      TANK_DATA1
									WHERE   (PDD_PROD_NO LIKE '%".substr($_SESSION['AND_GOODS'],0,4)."%') order by PROD_TANK";
						//	echo $query."<BR>";
							$result=mssql_query($query);
							$numRows=mssql_num_rows($result);
							$a='<select name="TANK_NO" id="TANK_NO" onchange="set_date_session(this.name,this.value)">';
							while($row=mssql_fetch_array($result)){
								if(trim($_SESSION['TANK_NO'])==trim($row['PROD_TANK'])){$str= ' selected';$value=$M.trim($row['PROD_TANK']);}else{$str='';}
								$a.= '<option value="'.trim($row['PROD_TANK']).'" '.$str.'>'.trim($row['PROD_TANK']).'</option>';
							}
							$a.='</select>共('.$numRows.")個選項";
							
					//		$value=$M.$_SESSION['TANK_NO'];
							$M=$M.$a;
							break;
							
							case 'XAB':
							$a='<select name="XAB" id="XAB" onchange="set_date_session(this.name,this.value)">
            						<option value="" '.$a.'>A~B</option>
          						</select>';
							$value=$M;
							$M=$M.$a;
							break;
							
							case 'LY_NO':
							$a='<input name="LY_NO" id="LY_NO" value="'.$_SESSION['LY_NO'].'" onchange="set_date_session(this.name,this.value)">';
							$value=$M.$_SESSION['LY_NO'];
							$M=$M.$a;
							break;
							
							default:
 							$a=trim($row[$row_column['COLUMN_NAME']]);	
							$M=$M.$a;$value.=$a;		
						}
					}//end while
					$this->new_lotno=$M;$_SESSION['newlot']=$value;$this->post_str=$value;
					
				}//end if
			}//end if
		}//end while
	}//end type_ana
}//class end


function tank_no($pdd){
	include_once("../connections/conn.php");
	$query="SELECT  PROD_TANK, PDD_PROD_NO
FROM      TANK_DATA
WHERE   (PDD_PROD_NO LIKE '%".$pdd."%') ";
	$result=mssql_query($query);
	$a='<select name="TANK_NO" id="TANK_NO" onchange="set_date_session(this.name,this.value)">';
	while($row=mssql_fetch_array($result)){
		$a.= '<option value="'.$row['TANK_NO'].'">'.$row['TANK_NO'].'</option>';
	}
	$a.='</select>';
	echo $a;
}
?>