<?php
	include '../../header.php';
?>
<html>
<head>
<title>Cost Sheet</title>

<style>
#costSheetOutput {
	background-color: #0EA;
	border-color:transparent;
}

</style>
</head> 
<body>

<?php
include '../../common/commonStyle.php';
include 'costSheetUtils.php';
?>

<fieldset>
<legend class="formLegend">Cost Sheet</legend>
<form id="costSheet" name="costSheet" method="post" action="costSheet.php" onsubmit="setSelectedAction()">

<textarea class="formOutput" name="costSheetOutput" id="costSheetOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br /><br />

<fieldset>
  <label class="formHeaderLabel">Builder:
	  <input class="formMediumInput" name="builder" type="text" value="<?php if(isset($_POST['builder'])) { echo $_POST['builder']; } ?>"/> 
  </label>
  <label class="formHeaderLabel">Subdivision:
	  <input class="formMediumInput" name="subdivision" type="text" value="<?php if(isset($_POST['subdivision'])) { echo $_POST['subdivision']; } ?>"/> 
  </label><br>
  <label class="formHeaderLabel">Lot:
	  <input class="formMediumInput" name="lot" type="text" value="<?php if(isset($_POST['lot'])) { echo $_POST['lot']; } ?>"/>
  </label>
  <label class="formHeaderLabel">Action:</label>
  <input type="hidden" name="selectedAction" size="20" style="font-size:20px" value=''/>
  <select name="actionDropMenu" id="actionDropMenu" style="font-size:23px" onclick="setSelectedAction()">
     <option value=""></option>
     <option value="handDig" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Hand Dig") ? 'selected' : '' : '') ?> >Hand Dig</option>
     <option value="dug" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Dug") ? 'selected' : '' : '') ?> >Dug</option>
	 <option value="set" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Set") ? 'selected' : '' : '') ?> >Set</option>
	 <option value="pour" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Pour") ? 'selected' : '' : '') ?> >Pour</option>
	 <option value="block" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Block") ? 'selected' : '' : '') ?> >Block</option>
	 <option value="caps" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Caps") ? 'selected' : '' : '') ?> >Caps</option>
	 <option value="backfill" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Backfill") ? 'selected' : '' : '') ?> >Backfill</option>
	 <option value="clean" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Clean") ? 'selected' : '' : '') ?> >Clean</option>
	 <option value="warranty" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Warrany") ? 'selected' : '' : '') ?> >Warranty</option>
	 <option value="purchaseOrder" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "PO") ? 'selected' : '' : '') ?> >PO</option>
  </select>
  <br/><br/>
  <div align="center">
   <input class="formButton" type="submit" name="searchCostSheet" id="searchCostSheet" value="Search">
   <input class="formButton" type="submit" name="clearCostSheet" id="clearCostSheet" value="Clear">
  </div>
</fieldset><br />

<?php
    displayWorkers();
?>

<script type="text/javascript">
function setSelectedAction() {
	var x=document.getElementById("actionDropMenu").selectedIndex;
	var y=document.getElementById("actionDropMenu").options;
	document.getElementsByName("selectedAction")[0].value = y[x].text;

	setSelectedWorker();
}

document.getElementsByName("costSheetOutput")[0].value = '<?php echo $outputMessage; ?>';
</script>

</form>
</fieldset>

</body> 
</html>
