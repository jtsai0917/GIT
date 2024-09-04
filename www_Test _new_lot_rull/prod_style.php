
<td width="250" class="d1">²ü«º :
                  <select name="t1" id="t1">
            <option value=""></option>
            <?php include("../connections/conn.php");
		      $query="SELECT DISTINCT([PDD_STYLE]) FROM [dbo].[PRODUCT_DATA]";
			  $result = mssql_query($query);

$numRows = mssql_num_rows($result);
//echo $query;
while($row = mssql_fetch_array($result))
{
    echo "<option value=".iconv("big5","UTF-8",$row['PDD_STYLE']).'>'.iconv("big5","UTF-8",$row['PDD_STYLE']).'</option>';
}
mssql_close($dbhandle);

			  ?>
            
          </select></td>