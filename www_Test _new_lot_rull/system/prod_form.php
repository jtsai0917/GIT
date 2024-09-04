<?php 
include("../lib/fun.php");
?>
<form name="form2" method="post" action="">
  基本資料
    <table width="720" border="1">
    <tr>
      <td width="200">料號：
        <label for="pid"></label>
      <input name="pid" type="text" id="pid" size="20"></td>
      <td width="320">品名：
      <input type="text" name="pname" id="pname"></td>
      <td width="200">簡稱：
        <input name="shortname" type="text" id="shortname" size="15"></td>
    </tr>
    <tr>
      <td>產品類型：
        <label for="pdt"></label>
        <select name="pdt" id="pdt">
          <option value=""></option>
          <option value="DM">DM</option>
          <option value="LY">LY</option>
          <option value="BTL">BTL</option>
          <option value="TOTO">TOTO</option>
      </select></td>
      <td>荷姿：
      <input name="pid4" type="text" id="pid4" size="10">
      藥品名：  
      <input name="pid5" type="text" id="pid5" size="10"></td>
      <td>單位：
        <label for="unit"></label>
        <select name="unit" id="unit">
          <option value=""></option>	
          <option value="L">L</option>
          <option value="KG">KG</option>
      </select></td>
    </tr>
    <tr>
      <td>產品類別：
        <label for="pdclass"></label>
        <select name="pdclass" id="pdclass">
          <option value=""></option>
          <option value="成品">成品</option>
          <option value="商品">商品</option>
          <option value="原料">原料</option>
      </select></td>
      <td>比重：
        <input name="pid4" type="text" id="pid4" size="10">
        <?php space(3);?>濃度：  
      <input name="pid5" type="text" id="pid5" size="10"></td>
      <td>桶重量：
        <label for="unit">
          <input name="pid6" type="text" id="pid6" size="10">
        </label></td>
    </tr>
  </table>
  <table width="720" border="1">
  	<tr>
    <td width="360">包裝型態：
      <input name="pid2" type="text" id="pid2" size="20"></td>
    <td width="360">
    	<input type="submit" name="normal" id="normal" value=" 常規 " />
        <input type="submit" name="delete" id="delete" value=" 刪除 " />
        <input type="submit" name="new" id="new" value=" 新增 " />
        <input type="submit" name="save" id="save" value=" 儲存 " />
	</td>
    </tr>
  </table>
        
</form>
