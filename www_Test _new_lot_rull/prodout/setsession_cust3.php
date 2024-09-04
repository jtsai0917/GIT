<?php
session_start();
$_SESSION['cid3']=$_GET['cust_no'];
?>
<script>
window.open('<?php echo $_SESSION['edit_change'];?>','_blank',config='height=260,width=1200');
window.close();
</script>