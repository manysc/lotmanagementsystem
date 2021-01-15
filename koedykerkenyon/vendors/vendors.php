<?php
	include '../header.php';
   include 'vendorUtils.php';
?>
<html>
<head><title>Vendors</title>
</head> 

<body>

<?php
include '../common/commonStyle.php';
include 'vendorStyle.php';
?>

<fieldset>
<legend class="formLegend">Vendors</legend>
<form id="vendor" name="vendor" method="post" action="vendors.php" onsubmit="">
  <textarea class="formOutput" name="vendorOutput" id="vendorOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br/><br/>
  
   <?php
  		displayExistingVendors();
   ?>
   <br><br>
   
   <fieldset>
   <legend class="sectionLegend">Update Vendors</legend><br>
   <label class="formHeaderLabel">Name</label>
   <input class="formLargeInput" name="vendorName" type="text" value="<?php if(isset($_POST['vendorName'])) { echo $_POST['vendorName']; } ?>"/><br><br>
   
   <table border="0" id="vendorTable" style="border:0px solid black;" width="90%" align=center cellpadding="1" cellspacing="1" class="db-table">
   
   <tr><th align="left" width="23%"><label class="formLabel">GRAY 4x8x16 T & G</label></th>
      <th><input class="formSmallInput" name="blockPrice" type="number" step=00.001 value="<?php if(isset($_POST['blockPrice'])) { echo $_POST['blockPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel">4&quot; Latter Wire</label></th>
      <th><input class="formSmallInput" name="latterWirePrice" type="number" step=00.001 value="<?php if(isset($_POST['latterWirePrice'])) { echo $_POST['latterWirePrice']; } ?>"/></th>
      <th align="left" width="25%"><label class="formLabel">COLOR 4x8x16 T & G</label></th>
      <th><input class="formSmallInput" name="colorBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['colorBlockPrice'])) { echo $_POST['colorBlockPrice']; } ?>"/></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY A Block</label></th>
      <th><input class="formSmallInput" name="aBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['aBlockPrice'])) { echo $_POST['aBlockPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel">Pallets</label></th>
      <th><input class="formSmallInput" name="palletsPrice" type="number" step=00.001 value="<?php if(isset($_POST['palletsPrice'])) { echo $_POST['palletsPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel">COLOR A Block</label></th>
      <th><input class="formSmallInput" name="aColorBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['aColorBlockPrice'])) { echo $_POST['aColorBlockPrice']; } ?>"/></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY H Block</label></th>
      <th><input class="formSmallInput" name="hBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['hBlockPrice'])) { echo $_POST['hBlockPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel"></label></th>
      <th><input hidden class="formSmallInput"/></th>
      <th align="left"><label class="formLabel">COLOR H Block</label></th>
      <th><input class="formSmallInput" name="hColorBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['hColorBlockPrice'])) { echo $_POST['hColorBlockPrice']; } ?>"/></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY L Block</label></th>
      <th><input class="formSmallInput" name="lBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['lBlockPrice'])) { echo $_POST['lBlockPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel"></label></th>
      <th><input hidden class="formSmallInput"/></th>
      <th align="left"><label class="formLabel">COLOR L Block</label></th>
      <th><input class="formSmallInput" name="lColorBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['lColorBlockPrice'])) { echo $_POST['lColorBlockPrice']; } ?>"/></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY T Block</label></th>
      <th><input class="formSmallInput" name="tBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['tBlockPrice'])) { echo $_POST['tBlockPrice']; } ?>"/><br></th>
      <th align="left"><label class="formLabel"></label></th>
      <th><input hidden class="formSmallInput"/></th>
      <th align="left"><label class="formLabel">COLOR T Block</label></th>
      <th><input class="formSmallInput" name="tColorBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['tColorBlockPrice'])) { echo $_POST['tColorBlockPrice']; } ?>"/><br></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY Deco Block</label></th>
      <th><input class="formSmallInput" name="decoBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['decoBlockPrice'])) { echo $_POST['decoBlockPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel"></label></th>
      <th><input hidden class="formSmallInput"/></th>
      <th align="left"><label class="formLabel">COLOR Deco Block</label></th>
      <th><input class="formSmallInput" name="decoColorBlockPrice" type="number" step=00.001 value="<?php if(isset($_POST['decoColorBlockPrice'])) { echo $_POST['decoColorBlockPrice']; } ?>"/></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY 4X4X16</label></th>
      <th><input class="formSmallInput" name="block4Price" type="number" step=00.001 value="<?php if(isset($_POST['block4Price'])) { echo $_POST['block4Price']; } ?>"/></th>
      <th align="left"><label class="formLabel"></label></th>
      <th><input hidden class="formSmallInput"/></th>
      <th align="left"><label class="formLabel">COLOR 4X4X16</label></th>
      <th><input class="formSmallInput" name="blockColor4Price" type="number" step=00.001 value="<?php if(isset($_POST['blockColor4Price'])) { echo $_POST['blockColor4Price']; } ?>"/></th>
   </tr>
   <tr><th align="left"><label class="formLabel">GRAY 8X2X16 Caps</label></th>
      <th><input class="formSmallInput" name="capsPrice" type="number" step=00.001 value="<?php if(isset($_POST['capsPrice'])) { echo $_POST['capsPrice']; } ?>"/></th>
      <th align="left"><label class="formLabel"></label></th>
      <th><input hidden class="formSmallInput"/></th>
      <th align="left"><label class="formLabel">COLOR 8X2X16 Caps</label></th>
      <th><input class="formSmallInput" name="capsColorPrice" type="number" step=00.001 value="<?php if(isset($_POST['capsColorPrice'])) { echo $_POST['capsColorPrice']; } ?>"/></th>
   </tr>
   </table><br/><br/>
   <div align="center">
   <input class="formButton" type="submit" name="searchVendor" id="searchVendor" value="Search" />
   <input class="formButton" type="submit" name="saveVendor" id="saveVendor" value="Save" />
   <input class="formButton" type="submit" name="deleteVendor" id="deleteVendor" value="Delete" />
   <input class="formButton" type="submit" name="clearVendor" id="clearVendor" value="Clear" />
   </div>
   </fieldset>
   
   <p id="demo"></p>
   
<script type="text/javascript">
   document.getElementsByName("vendorOutput")[0].value = '<?php echo $outputMessage; ?>';
   var lines = document.getElementsByName("vendorOutput")[0].value.split("\n");
   var count = lines.length;
   document.getElementById("vendorOutput").rows = count;
</script>

</form>
</fieldset>

</body>
</html>