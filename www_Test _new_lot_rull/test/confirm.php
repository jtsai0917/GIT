<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />
<title>無標題文件</title>
<script language="JavaScript"  type="text/javascript" >

function confirmDelete() {
    if(confirm("Would you like to delete the selected products?")) {
        <?php 
            $allCheckBoxId = $_POST['checkbox'];
            array_map ('mysql_real_escape_string', $allCheckBoxId);
            $ids = implode(",", $allCheckBoxId);
            $sql = "DELETE FROM products WHERE `id` IN ($ids)";
            mysql_query($sql);
        ?>
    }
}

</script></head>

<body>
</body>
</html>