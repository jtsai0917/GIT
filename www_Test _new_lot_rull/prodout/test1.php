<?php
$path_root=$_SERVER['HTTP_HOST'];
echo $fileurl=$path_root."/prodout/x.txt";
?>
<meta http-equiv="Content-Type" content="text/html; charset=big5" />

<script language="javascript" type="text/javascript">
    //===============================================
    //函式名稱：列印指定的 Word 文件
    //傳入參數：docPath - Word 文件路徑
    //===============================================
    function PrintDoc(docPath)
    {
        var oHead = document.getElementsByTagName("HEAD");
        
        if(oHead)
        {
            var nNode = document.createElement("link");
            nNode.setAttribute("rel","alternate");
            nNode.setAttribute("media","print");
            nNode.setAttribute("href",docPath);
            oHead[0].appendChild(nNode);
            window.print();
            oHead[0].removeChild(nNode);
        }
    }
</script>
<input id="Button1" type="button" value="列印 Doc" onclick="PrintDoc(<?php echo $fileurl;?>)" />