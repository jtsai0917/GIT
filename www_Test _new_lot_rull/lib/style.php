<?php
	function hr(){
		echo '<select name="hh" id="hh">';
		for($i=1;$i<=24;$i++)
		{
			echo '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
		}
      	echo '</select>';
	}
	
	function mm()
	{
		echo '<select name="mm" id="mm">';
		for($i=0;$i<=59;$i++)
		{
			echo '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
		}
      	echo '</select>';
	}
	
	function ran_sample($ranid,$lot_no)  ///
	{
		echo '<form id="ran_sample" name="ran_sample" method="post" action="'.$loginFormAction.'">';
		echo '<table width="1238" border="1">';
		echo '<tr align="center" bgcolor="#CCCCCC"><td><font size="+1">('.$lot_no.') 樣品瓶作業</font></td></tr></table>';
		
		echo '<table width="1238" border="1">';
		echo '<tr><td>';
		echo '新增瓶號：<input name="sam_no" type="text" id="sam_no" size="16" autofocus="autofocus"/>
  				<input type="submit" name="add" id="add" value=" 新增 "/></br>';
		echo '樣品來源：<select name="source" id="source">
      					<option value="NULL"></option>
        				<option value="充填內液">充填內液</option>
        				<option value="充填口">充填口</option>
        				<option value="前次多取樣品">前次多取樣品</option>
        				<option value="其他">其他</option>
      					</select>  ';
	  	echo 'Service：<select name="smp_type" id="smp_type">
 				<option value="1">廠內分析</option>
  				<option value="2">隨貨或評估</option>
  				<option value="3">保存</option>
  				<option value="4">先行</option>
  				<option value="5">客戶其他</option>
				</select>';
		echo '</td></tr>';	
		echo '</table>';
		echo '<table width="1024" border="1">';
		echo '<tr><td>樣品瓶號</td><td>SERVICE</td><td>Serial_No</td><td>客戶</td><td>取樣擔當</td><td>桶號</td><td>DHN桶號</td></tr>';
		
		$query="select * from Sample_All 
				WHERE          (SMA_LOT = '".$lot_no."')";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row=mssql_fetch_array($result))
		{
			switch ($row['SMA_SERVICE'])
			{
				case '1':
					$service="廠內分析樣品瓶";
					break;
				case '2':
					$service="隨貨或評估樣品瓶";
					break;
				case '3':
					$service="保存樣品";
					break;
				case '4':
					$service="先行樣品";
					break;
			}
			$rt=$row['SMA_USER'];
			echo '<tr><td>'.$row['SMA_ID'].'</td><td>'.$service.'</td><td>'.$row['SMA_SERIAL_NO'].'</td><td>'.get_cust_name($row['SMA_USER']).'</td><td>'.get_uname($row['SMA_SMP']).'</td><td>'.$row['SMA_DRUMNO'].'</td><td>'.$row['DHN_DRUM_NO'].'</td></tr>';
		}
		echo '</table>';	
		echo '</form>';
		return $rt;
	}
	
	function list_samples($lot_no)
	{
		include("../lib/fun.php");
		include("../connections/conn.php");
		echo '<table width="1024" border="1">';
		echo '<tr><td>樣品瓶號</td><td>SERVICE</td><td>Serial_No</td><td>客戶</td><td>取樣擔當</td><td>桶號</td><td>DHN桶號</td></tr>';
		
		$query="select * from Sample_All 
				WHERE          (SMA_LOT = '".$lot_no."')";
		$result = mssql_query($query);
		$numRows = mssql_num_rows($result);
		while($row=mssql_fetch_array($result))
		{
			switch ($row['SMA_SERVICE'])
			{
				case '1':
					$service="廠內分析樣品瓶";
					break;
				case '2':
					$service="隨貨或評估樣品瓶";
					break;
				case '3':
					$service="保存樣品";
					break;
				case '4':
					$service="先行樣品";
					break;
			}
			$rt=$row['SMA_USER'];
			echo '<tr><td>'.$row['SMA_ID'].'</td><td>'.$service.'</td><td>'.$row['SMA_SERIAL_NO'].'</td><td>'.get_cust_name($row['SMA_USER']).'</td><td>'.get_uname($row['SMA_SMP']).'</td><td>'.$row['SMA_DRUMNO'].'</td><td>'.$row['DHN_DRUM_NO'].'</td></tr>';
		}
		echo '</table>';	
		return $rt;
	}
	
	function select_testitems($lot,$items)
	{
		echo'<table border="1" width="720">
  
   	<td align="right"><form id="select_testitems" name="select_testitems" method="post" action="">
      <input type="submit" name="add1" id="add1" value="項目選擇完成"  />
 	 </tr>
  
    </table>
  	<table border="1" width="720">
  	<tr>
  	<td>  序號  </td>
  	<td>代號  </td>
  	<td>簡稱  </td>
  	<td>分析項目  </td>
  	<td>群組  </td>
  	<td>選擇</td>
  	</tr>   '; 
	$aa=explode(',',$items);
	$num_aa=count($aa);
	for($i=0;$i<$num_aa;$i++)
		{
			list($ani_id,$ani_nick,$ani_full,$ani_igroup)=analyzeItems($aa[$i]);
			echo '<tr><td>'.$aa[$i].'</td><td>'.$ani_id.'</td><td>'.$ani_nick.'</td><td>'.$ani_full.'</td><td>'.$ani_group.'</td><td>';
			echo '<td><input type="checkbox" name="chkbox[]" id="1" value="'.$aa[$i].'" /></td></tr>';
		}
	echo '</table></form></body></html>';
	
	$loginFormAction = $_SERVER['PHP_SELF'];
	//////////////////////////////////////////////////////////
	}
?>