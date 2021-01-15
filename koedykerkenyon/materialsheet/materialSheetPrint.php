<html>
<head><title>Masonry Material Sheet</title>
</head>

<style>
#materialSheetPrint {
	width : 100%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
}
@page
{
    size: auto;   /* auto is the initial value */

    /* this affects the margin in the printer settings */
    margin: 5mm 3mm 1mm 3mm;
}

body
{
    /* this affects the margin on the content before sending to printer */
    margin: 0px;
}

</style>

<?php
   include '../configuration.php';
   include '../common/commonStyle.php';
   include 'materialSheetStyle.php';
   include 'materialSheetUtils.php';
   include '../vendors/vendorUtils.php';

   if(isset($_GET['lot'])) {
      $_GET['lot'] = str_replace('sAnd','&',$_GET['lot']);
      $_GET['lot'] = str_replace(':',' ',$_GET['lot']);
      $lotInfo = explode(",", $_GET['lot']);
      $index = sizeof($lotInfo);
      if($index == 6) {
         list($_POST['orderDate'], $_POST['builder'], $_POST['subdivision'], $_POST['lot'], $_POST['poNumber'])  = $lotInfo;
      }
   }

   if(isset($_POST['orderDate'])) {
      searchMaterialSheet();
   }
?>

<fieldset id="materialSheetPrint">
   <form id="materialSheet" name="materialSheet" method="post" action="materialSheetPrint.php" onsubmit="">

   <fieldset style="border-color:transparent; padding: 0; margin: 0;">
      <textarea name="companyName" id="companyName">KOEDYKER & KENYON CONSTRUCTION, INC.&#10;Masonry&#10;901 W. Calle Progreso&#10;Tucson, AZ 85705&#10;Phone:(520) 882-7006 Fax:(520) 882-3013</textarea>
      <textarea name="poNumber" id="poNumber" style="font-weight:bold">PURCHASE&#10;ORDER #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if(isset($_POST['poNumber'])) { echo $_POST['poNumber']; } ?></textarea>
      <br><br>
      <label class="formHeaderLabel" style="font-size:20px;">Order Date:
         <input class="formMediumSmallInput" name="orderDate" type="date" size="10" align="left" style="font-size:20px; border-color:transparent" value="<?php if(isset($_POST['orderDate'])) { echo $_POST['orderDate']; } ?>"/>
      </label>
      <label class="formHeaderLabel" style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Builder:
         <input class="formMediumSmallInput" name="builder" type="text" style="font-size: 20px; border-color:transparent" value="<?php if(isset($_POST['builder'])) { echo $_POST['builder']; } ?>"/>
      </label>
      <br>
      <label class="formHeaderLabel" style="font-size:20px;">Subdivision:
         <input class="formMediumSmallInput" name="subdivision" type="text" style="font-size: 20px; border-color:transparent" value="<?php if(isset($_POST['subdivision'])) { echo $_POST['subdivision']; } ?>"/>
      </label>
      <label class="formHeaderLabel" style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lot:
         <input class="formSmallestInput" name="lot" type="text" style="font-size: 20px; border-color:transparent" value="<?php if(isset($_POST['lot'])) { echo $_POST['lot']; } ?>"/>
      </label>
      <label class="formHeaderLabel" style="font-size:20px;">Supervisor:
        <input class="formMediumSmallInput" name="supervisor" type="text" style="font-size: 20px; border-color:transparent" value="<?php if(isset($_POST['supervisor'])) { echo $_POST['supervisor']; } ?>"/>
      </label><br>
      <label class="formHeaderLabel" style="font-size:20px;">Vendor:
         <input name="vendorSelect" type="text" style="width: 200px; font-size: 20px;border-color:transparent" value="<?php if(isset($_POST['vendorSelect'])) { echo $_POST['vendorSelect']; } ?>"/>
      <label class="formHeaderLabel" style="font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ship Via:</label>
         <input class="formMediumSmallInput" name="shipVia" type="text" style="font-size: 20px; border-color:transparent" value="<?php if(isset($_POST['shipVia'])) { echo $_POST['shipVia']; } ?>"/>
      <br>
      <label class="formHeaderLabel" style="font-size:20px;">Date Required:</label>
         <input class="formMediumSmallInput" name="dateRequired" type="date" align="left" style="font-size:20px; border-color:transparent;" value="<?php if(isset($_POST['dateRequired'])) { echo $_POST['dateRequired']; } ?>"/>
      <label class="formHeaderLabel" style="font-size:20px;">Date Received:</label>
         <input class="formMediumSmallInput" name="dateReceived" type="date" align="left" style="font-size:20px; border-color:transparent;" value="<?php if(isset($_POST['dateReceived'])) { echo $_POST['dateReceived']; } ?>"/>
      <br><br>
      <textarea name="shipToLabel" id="shipToLabel">Ship To:</textarea>
      <textarea name="shipToValue" id="shipToValue"><?php if(isset($_POST['shipTo'])) { echo $_POST['shipTo']; } ?></textarea>
      <textarea name="billToLabel" id="billToLabel">Bill To:</textarea>
      <textarea name="billToValue" id="billToValue"><?php if(isset($_POST['billTo'])) { echo $_POST['billTo']; } ?></textarea>
   </fieldset>

   <fieldset id="materials">
	<?php
    	displayMaterials();
	?>
   </fieldset>

   <fieldset id="receivedBy">
      <textarea name="receivedBy" id="receivedBy" style="font-weight:bold" placeholder="&#10Received by:&#10&#10&#10___________________________"><?php if(isset($_POST['receivedBy'])) { echo $_POST['receivedBy']; } ?></textarea>
      </label>
   </fieldset>

   <fieldset id="materialTotals">
      <table border="0" id="materialTotalsTable" style="border:0px solid black;" width="80%" cellpadding="1" cellspacing="1" class="db-table">
         <tr><th align="left"><label class="formLabel">Sub Total&nbsp;</label></th>
            <th align="left"><input class="formMediumSmallerInput" readonly name="materialSubTotal" type="number" style="border:none;border-bottom:1px solid black;" value="<?php if(isset($_POST['materialSubTotal'])) { echo $_POST['materialSubTotal']; } ?>"/></th>
         </tr>
         <tr><th align="left"><label class="formLabel">Sales Tax&nbsp;</label></th>
            <th align="left"><input class="formMediumSmallerInput" readonly name="salesTax" type="number" style="border:none;border-bottom:3px solid black;" value="<?php if(isset($_POST['salesTax'])) { echo $_POST['salesTax']; } ?>"/></th>
         </tr>
         <tr><th align="left"><label class="formLabel">TOTAL&nbsp;</label></th>
            <th align="left"><input class="formMediumSmallerInput" readonly name="materialTotal" type="number" style="border:none; font-weight: bold;" value="<?php if(isset($_POST['materialTotal'])) { echo $_POST['materialTotal']; } ?>"/></th>
         </tr>
      </table>
   </fieldset>

   <script type="text/javascript">
      window.print();
      document.getElementsByName("materialSheetOutput")[0].value = '<?php echo $materialOutputMessage; ?>';
   </script>

   </form>
</fieldset>