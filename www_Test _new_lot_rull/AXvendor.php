<?php
include("./connections/ax_connections.php");

$query="select distinct LOCATIONNAME, address,street,ZIPCODE 
 from DIRPARTYPOSTALADDRESSVIEW 
where ISPRIMARY=1 and LOCATIONNAME='九連環境開發股份有限公司' and ISLOCATIONOWNER=1 and XRECVERSION_LOGISTICSPOSTALADDRESS=1";
$rlt=mssql_query($query);
$rr=mssql_fetch_row($rlt);
echo $rr[1]."<BR>";

?>