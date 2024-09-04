<?php
header("Content-Type:text/html; charset=utf-8");
?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
        $(function () {
            $("#button1").click(function () {
                getQrcode();             
            });
            $("#range").change(function () {
                getQrcode();
            });
            function getQrcode() {
                var txt = $("#txt").val();
                if(txt.trim() != ""){
                var width = $("#range").val()
                var rectangle = width + "x" + width;
                var url = "https://chart.googleapis.com/chart?chs=" + rectangle + "&cht=qr&chl=" + txt + "&choe=UTF-8&chld=M|2";
                var qr = "<img alt='您的Qrcode' src='" + url + "' />";
                $('#qrcode').html(qr);
            }
            }
        });
    </script>

<br />
<br />
內容:<input id="txt" style="width:500px" type="url" /> <input id="button1" type="button" value="產生QRcode" /><br />
大小:<input class="selector" id="range" max="500" min="50" step="10" style="width: 300px" type="range" value="250" /><br />
<div id="qrcode">
&nbsp;</div>
