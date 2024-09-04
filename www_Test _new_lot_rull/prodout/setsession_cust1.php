<?php
session_start();
$_SESSION['cid1']=$_GET['cust_no'];
?>
<script>
window.open('<?php echo $_SESSION['edit_change'];?>','_blank',config='height=170,width=1050');
window.close();
</script>