<?php
session_start();
$_SESSION[$_GET['name']].=$_GET['value'].",";
?>
<script type="text/javascript">
	window.close()
</script>
