<?php
session_start();
$_SESSION['pid2']=$_GET['pro_no'];
?>
<script>
window.open('edit_change.php','_blank',config='height=170,width=1050');
window.close();
</script>
 