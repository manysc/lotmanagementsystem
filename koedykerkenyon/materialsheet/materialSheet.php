<?php
	include '../header.php';
?>

<html> 
<head>
   <title>Masonry Material Sheet</title>
</head> 
<body>

<?php
   include '../common/commonStyle.php';
   include 'materialSheetStyle.php';
   include 'materialSheetUtils.php';
   include '../vendors/vendorUtils.php';

   // Open Material Sheet from Layout
   if(isset($_GET['lot'])) {
      $_GET['lot'] = str_replace('sAnd','&',$_GET['lot']);
      $_GET['lot'] = str_replace(':',' ',$_GET['lot']);
      $lotInfo = explode(",", $_GET['lot']);
      $index = sizeof($lotInfo);
      if($index == 6) {
         list($_POST['orderDate'], $_POST['builder'], $_POST['subdivision'], $_POST['lot'], $_POST['poNumber'])  = $lotInfo;

         if(isset($_POST['orderDate'])) {
            searchMaterialSheet();
         }
      }
   }
?>

<fieldset>
   <legend class="formLegend">Material Sheet</legend>
   <form id="materialSheet" name="materialSheet" method="post" action="materialSheet.php" onsubmit="">
      <textarea class="formOutput" name="materialSheetOutput" id="materialSheetOutput" cols="74" rows="1" readonly><?php echo $materialOutputMessage; ?></textarea><br/><br/>
      
   <fieldset>
      <label class="formHeaderLabel">Order Date:
         <input name="orderDate" type="date" size="10" align="left" style="font-size:23px" value="<?php if(isset($_POST['orderDate'])) { echo $_POST['orderDate']; } ?>"/>
      </label>
      <label class="formHeaderLabel">Builder:
         <input class="formMediumInput" name="builder" type="text" value="<?php if(isset($_POST['builder'])) { echo $_POST['builder']; } ?>"/>
      </label>
      <label class="formHeaderLabel">Subdivision:
         <input class="formMediumInput" name="subdivision" type="text" value="<?php if(isset($_POST['subdivision'])) { echo $_POST['subdivision']; } ?>"/>
      </label><br>
      <label class="formHeaderLabel">Lot:
         <input class="formSmallInput" name="lot" type="text" value="<?php if(isset($_POST['lot'])) { echo $_POST['lot']; } ?>"/>
      </label>
      <label class="formHeaderLabel">Supervisor:
        <input class="formMediumInput" name="supervisor" type="text" value="<?php if(isset($_POST['supervisor'])) { echo $_POST['supervisor']; } ?>"/>
      </label>
      <label class="formHeaderLabel">PO #:
        <input class="formMediumSmallInput" name="poNumber" type="text" value="<?php if(isset($_POST['poNumber'])) { echo $_POST['poNumber']; } ?>"/>
      </label><br/><br/>
      <div align="center">
        <input class="formButton" type="submit" name="layout" id="layout" value="Layout">
         <input class="formButton" type="submit" name="searchMaterialSheet" id="searchMaterialSheet" value="Search" >
         <input class="formButton" type="submit" name="saveMaterialSheet" id="saveMaterialSheet" value="Save" onclick="return confirm('Are you sure you want to SAVE this Material Sheet?')">
         <input class="formButton" type="submit" name="deleteMaterialSheet" id="deleteMaterialSheet" value="Delete" onclick="return confirm('Are you sure you want to DELETE this Material Sheet?')">
         <input class="formButton" type="submit" name="clearMaterialSheet" id="clearMaterialSheet" value="Clear">
         <input class="formButton" type="submit" name="print" id="print" value="Print">
      </div>
   </fieldset><br/><br/>
   
   <fieldset id="vendor">
      <legend class="sectionLegend">Vendor:</legend>
      <select class="vendors-select" name="vendorSelect" id="vendorSelect">
         <option value="0">Select a Vendor:</option>
         <?php
            displayVendorNames();
         ?>
      </select><br/><br/><br/>
   </fieldset>
   
   <fieldset id="shipTo">
      <legend class="sectionLegend">Ship To:</legend>
	   <textarea class="combobox" name="shipTo" id="shipTo" style="width:500px; height: 120px;"><?php if(isset($_POST['shipTo'])) { echo $_POST['shipTo']; } ?></textarea>
   </fieldset>

   <fieldset id="billTo">
      <legend class="sectionLegend">Bill To:</legend>
      <textarea class="combobox" name="billTo" id="billTo" style="width:500px; height: 120px;"><?php if(isset($_POST['billTo'])) { echo $_POST['billTo']; } ?></textarea>
   </fieldset>
   <br/><br/>
   
   <fieldset>
      <label class="formHeaderLabel">Date Required:</label>
         <input name="dateRequired" type="date" size="10" align="left" style="font-size:23px" value="<?php if(isset($_POST['dateRequired'])) { echo $_POST['dateRequired']; } ?>"/>
      <label class="formHeaderLabel">&nbsp;Ship Via:</label>
	      <input class="formMediumInput" name="shipVia" type="text" value="<?php if(isset($_POST['shipVia'])) { echo $_POST['shipVia']; } ?>"/>
	   <label class="formHeaderLabel">&nbsp;Delivery Charge:</label>
         <input class="formMediumSmallInput" name="deliveryCharge" type="number" value="<?php if(isset($_POST['deliveryCharge'])) { echo $_POST['deliveryCharge']; } ?>"/>
      <label class="formHeaderLabel">&nbsp;Date Received:</label>
	      <input name="dateReceived" type="date" size="10" align="left" style="font-size:23px" value="<?php if(isset($_POST['dateReceived'])) { echo $_POST['dateReceived']; } ?>"/>
      <br/><br/>

      <label class="formHeaderLabel">Block Color:
         <input type="radio" class="mediumRadioButton" name="blockColor" value="GRAY" <?= (isset($_POST['blockColor']) ? !strcmp($_POST["blockColor"], "GRAY") ? 'checked' : '' : '') ?>>
         <label style="font-weight: normal">GRAY
         <input type="radio" class="mediumRadioButton" name="blockColor" value="COLOR" <?= (isset($_POST['blockColor']) ? !strcmp($_POST["blockColor"], "COLOR") ? 'checked' : '' : '') ?>>
         <label style="font-weight: normal">COLOR
      </label>
      <label class="formHeaderLabel">&nbsp;&nbsp;&nbsp;&nbsp;4X4X16 BLOCK:</label>
         <input class="formSmallInput" name="block4" type="number" value="<?php if(isset($_POST['block4'])) { echo $_POST['block4']; } ?>"/>
      <label class="formHeaderLabel">&nbsp;&nbsp;PALLETS:</label>
         <input class="formSmallInput" name="pallets" type="number" value="<?php if(isset($_POST['pallets'])) { echo $_POST['pallets']; } ?>"/>
      <label class="formHeaderLabel">&nbsp;&nbsp;Extra Added</label>
         <input class="formSmallInput" name="extraAddedPercentage" type="number" value="<?php if(isset($_POST['extraAddedPercentage'])) { echo $_POST['extraAddedPercentage']; } ?>"/>
      <label class="formHeaderLabel">%&nbsp;</label>
   </fieldset><br/><br/>
   
   <fieldset id="materials">
   <legend class="sectionLegend">Materials</legend>
	<?php
	   displayMaterials();
	?>
   </fieldset><br/><br/>

   <fieldset id="receivedBy">
      <legend class="sectionLegend">Received by:</legend>
      <textarea class="combobox" readonly name="receivedBy" id="receivedBy" style="width:500px; height:150px; text-align:center; border:none" placeholder="&#10&#10&#10______________________________________&#10Signature"><?php if(isset($_POST['receivedBy'])) { echo $_POST['receivedBy']; } ?></textarea>
   </fieldset>

   <fieldset id="materialTotals">
      <table border="0" id="materialTotalsTable" style="border:0px solid black;" width="80%" cellpadding="1" cellspacing="1" class="db-table">
         <tr><th align="left"><label class="formLabel">Sub Total</label></th>
            <th align="left"><input class="formMediumSmallInput" readonly name="materialSubTotal" type="number" style="border:none;border-bottom:1px solid black;" value="<?php if(isset($_POST['materialSubTotal'])) { echo $_POST['materialSubTotal']; } ?>"/></th>
         </tr>
         <tr><th align="left"><label class="formLabel">Sales Tax</label>
            <input class="formSmallInput" name="salesTaxPercentage" type="number" style="border:none;border-bottom:1px solid black;text-align:right;font-size:20px" value="<?php if(isset($_POST['salesTaxPercentage'])) { echo $_POST['salesTaxPercentage']; } ?>"/>
            <label class="formLabel">%</label>
            </th>
            <th align="left"><input class="formMediumSmallInput" readonly name="salesTax" type="number" style="border:none;border-bottom:1px solid black;" value="<?php if(isset($_POST['salesTax'])) { echo $_POST['salesTax']; } ?>"/></th>
         </tr>
         <tr><th align="left"><label class="formLabel">TOTAL</label></th>
            <th align="left"><input class="formMediumSmallInput" readonly name="materialTotal" type="number" style="border:none; font-weight: bold;" value="<?php if(isset($_POST['materialTotal'])) { echo $_POST['materialTotal']; } ?>"/></th>
         </tr>
      </table>
      <br/><br/>
   </fieldset><br/><br/>

   <div align="center">
      <input class="formButton" type="submit" name="layout" id="layout" value="Layout">
      <input class="formButton" type="submit" name="searchMaterialSheet" id="searchMaterialSheet" value="Search" >
      <input class="formButton" type="submit" name="saveMaterialSheet" id="saveMaterialSheet" value="Save" onclick="return confirm('Are you sure you want to SAVE this Material Sheet?')">
      <input class="formButton" type="submit" name="deleteMaterialSheet" id="deleteMaterialSheet" value="Delete" onclick="return confirm('Are you sure you want to DELETE this Material Sheet?')">
      <input class="formButton" type="submit" name="clearMaterialSheet" id="clearMaterialSheet" value="Clear">
      <input class="formButton" type="submit" name="printMaterialSheet" id="printMaterialSheet" value="Print">
   </div>
   
   <script type="text/javascript">
      document.getElementsByName("materialSheetOutput")[0].value = '<?php echo $materialOutputMessage; ?>';
   </script>
  
   </form>
</fieldset>


</body> 
</html>