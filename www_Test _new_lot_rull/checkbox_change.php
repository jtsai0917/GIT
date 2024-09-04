<?php
session_start();
if($_SESSION[$_GET['name']]=='on'){
	$_SESSION[$_GET['name']]='';
}
elseif($_SESSION[$_GET['name']]==''){
	$_SESSION[$_GET['name']]='on';
}
?>
<script type="text/javascript">
	window.close()
</script>