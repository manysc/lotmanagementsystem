<?php
    $msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	include '../header.php';
?>

<html> 
<head><title>Layouts</title>
</head> 
<body>

<?php
include '../common/commonStyle.php';
include 'layoutsStyle.php';
include 'layoutUtils.php';

if(isset($_GET['lot'])) {
	$lotInfo = explode(",", $_GET['lot']);
	$index = sizeof($lotInfo);
	if($index == 3) {
		list($_POST['builder'], $_POST['subdivision'], $_POST['lot'])  = $lotInfo;
		searchLayout();
	}
}
?>

<fieldset>
<legend class="formLegend">Layout</legend>

<form id="layout" name="layout" method="post" action="layouts.php" onsubmit="setSelectedCrew()" enctype="multipart/form-data">
  <textarea class="formOutput" name="layoutOutput" id="layoutOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br /><br />
  <fieldset>
  <label class="formHeaderLabel">Builder:</label>
  <input class="formMediumInput" name="builder" type="text" value="<?php if(isset($_POST['builder'])) { echo $_POST['builder']; } ?>"/>
    <label class="formHeaderLabel">Subdivision:</label>
	  <input class="formMediumInput" name="subdivision" type="text" value="<?php if(isset($_POST['subdivision'])) { echo $_POST['subdivision']; } ?>"/> 
  <br />
  <label class="formHeaderLabel">Lot:</label>
  <input class="formSmallInput" type="text" name="lot" id="lot" value="<?php if(isset($_POST['lot'])) { echo $_POST['lot']; } ?>"/>
  <label class="formHeaderLabel">Supervisor:</label>
  <input class="formMediumInput" type="text" name="supervisor" id="supervisor" value="<?php if(isset($_POST['supervisor'])) { echo $_POST['supervisor']; } ?>"/><br/>
  <label class="formHeaderLabel">Courses:</label>
  <input class="formSmallerInput" type="text" name="courses" id="courses" value="<?php if(isset($_POST['courses']) && $_POST['courses'] > 0) { echo $_POST['courses']; } ?>"/>
  <label class="formHeaderLabel">Gate:</label>
  <input class="formSmallInput" type="text" name="gate" id="gate" value="<?php if(isset($_POST['gate'])) { echo $_POST['gate']; } ?>"/>
  <label class="formHeaderLabel">End Lot L/R?</label>
  <input class="formSmallInput" type="text" name="endLot" id="endLot" value="<?php if(isset($_POST['endLot'])) { echo $_POST['endLot']; } ?>"/>
  <br/><br/>

  <div align="center">
    <input class="formButton" type="submit" name="searchLayout" id="searchLayout" value="Search">
    <input class="formButton" type="submit" name="saveLayout" id="saveLayout" value="Save" onclick="return confirm('Are you sure you want to Save this Layout?')">
    <input class="formButton" type="submit" name="deleteLayout" id="deleteLayout" value="Delete" onclick="return confirm('Are you sure you want to Delete this Layout?')">
    <input class="formButton" type="submit" name="clearLayout" id="clearLayout" value="Clear">
  </div>
  </fieldset>
  <br/>
  
  <fieldset id="retainers">
  <legend class="sectionLegend">Retainers</legend><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
    <input class="formSmallerInput" type="text" name="retainerBW" id="retainerBW" value="<?php if(isset($_POST['retainerBW']) && $_POST['retainerBW'] > 0) { echo $_POST['retainerBW']; } ?>"/>
    <label class="formLabel">&nbsp;Courses</label>
    <input class="formSmallerInput" type="text" name="rbwCourses" id="rbwCourses" value="<?php if(isset($_POST['rbwCourses']) && $_POST['rbwCourses'] > 0) { echo $_POST['rbwCourses']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <input class="formSmallerInput" type="text" name="retainerLW" id="retainerLW" value="<?php if(isset($_POST['retainerLW']) && $_POST['retainerLW'] > 0) { echo $_POST['retainerLW']; } ?>"/>
    <label class="formLabel">&nbsp;Courses</label>
    <input class="formSmallerInput" type="text" name="rlwCourses" id="rlwCourses" value="<?php if(isset($_POST['rlwCourses']) && $_POST['rlwCourses'] > 0) { echo $_POST['rlwCourses']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <input class="formSmallerInput" type="text" name="retainerRW" id="retainerRW" value="<?php if(isset($_POST['retainerRW']) && $_POST['retainerRW'] > 0) { echo $_POST['retainerRW']; } ?>"/>
    <label class="formLabel">&nbsp;Courses</label>
    <input class="formSmallerInput" type="text" name="rrwCourses" id="rrwCourses" value="<?php if(isset($_POST['rrwCourses']) && $_POST['rrwCourses'] > 0) { echo $_POST['rrwCourses']; } ?>"/><br/>
  <br/>
  </fieldset>
  &nbsp;&nbsp;&nbsp;
  <fieldset id="perimeters">
  <legend class="sectionLegend">Perimeters</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
    <input class="formSmallerInput" type="text" name="perimeterBW" id="perimeterBW" value="<?php if(isset($_POST['perimeterBW']) && $_POST['perimeterBW'] > 0) { echo $_POST['perimeterBW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <input class="formSmallerInput" type="text" name="perimeterLW" id="perimeterLW" value="<?php if(isset($_POST['perimeterLW']) && $_POST['perimeterLW'] > 0) { echo $_POST['perimeterLW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <input class="formSmallerInput" type="text" name="perimeterRW" id="perimeterRW" value="<?php if(isset($_POST['perimeterRW']) && $_POST['perimeterRW'] > 0) { echo $_POST['perimeterRW']; } ?>"/><br/>
  <br/>
  </fieldset>
  &nbsp;&nbsp;&nbsp;
  <fieldset id="viewWall">
  <legend class="sectionLegend">View Wall</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
    <input class="formSmallerInput" type="text" name="viewBW" id="viewBW" value="<?php if(isset($_POST['viewBW']) && $_POST['viewBW'] > 0) { echo $_POST['viewBW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <input class="formSmallerInput" type="text" name="viewLW" id="viewLW" value="<?php if(isset($_POST['viewLW']) && $_POST['viewLW'] > 0) { echo $_POST['viewLW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <input class="formSmallerInput" type="text" name="viewRW" id="viewRW" value="<?php if(isset($_POST['viewRW']) && $_POST['viewRW'] > 0) { echo $_POST['viewRW']; } ?>"/><br/>
  <br/>
  </fieldset>
  <br/><br/>
  <fieldset id="lots">
  <legend class="sectionLegend">Lot</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
    <input class="formSmallerInput" type="text" name="lotBW" id="lotBW" value="<?php if(isset($_POST['lotBW']) && $_POST['lotBW'] > 0) { echo $_POST['lotBW']; } ?>"/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <input class="formSmallerInput" type="text" name="lotLW" id="lotLW" value="<?php if(isset($_POST['lotLW']) && $_POST['lotLW'] > 0) { echo $_POST['lotLW']; } ?>"/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <input class="formSmallerInput" type="text" name="lotRW" id="lotRW" value="<?php if(isset($_POST['lotRW']) && $_POST['lotRW'] > 0) { echo $_POST['lotRW']; } ?>"/>
  <br/><br/>
  </fieldset>
  &nbsp;&nbsp;&nbsp;
  <fieldset id="returns">
  <legend class="sectionLegend">Returns</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;LR</label>
    <input class="formSmallerInput" type="text" name="returnLR" id="returnLR" value="<?php if(isset($_POST['returnLR']) && $_POST['returnLR'] > 0) { echo $_POST['returnLR']; } ?>"/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RR</label>
    <input class="formSmallerInput" type="text" name="returnRR" id="returnRR" value="<?php if(isset($_POST['returnRR']) && $_POST['returnRR'] > 0) { echo $_POST['returnRR']; } ?>"/>
  <br/><br/>
  </fieldset>
  <br/><br/>
  <fieldset>
  <legend class="sectionLegend">Extras</legend>
  <label class="formLabel">PT</label>
    <input class="formSmallerInput" type="text" name="extraPT" id="extraPT" value="<?php if(isset($_POST['extraPT']) && $_POST['extraPT'] > 0) { echo $_POST['extraPT']; } ?>"/>
    <label class="formLabel">&nbsp;AC</label>
    <input class="formSmallerInput" type="text" name="extraAC" id="extraAC" value="<?php if(isset($_POST['extraAC']) && $_POST['extraAC'] > 0) { echo $_POST['extraAC']; } ?>"/>
    <label class="formLabel">&nbsp;CY</label>
    <input class="formSmallerInput" type="text" name="extraCY" id="extraCY" value="<?php if(isset($_POST['extraCY']) && $_POST['extraCY'] > 0) { echo $_POST['extraCY']; } ?>"/>
    <label class="formLabel">&nbsp;E-BW</label>
    <input class="formSmallerInput" type="text" name="extraBW" id="extraBW" value="<?php if(isset($_POST['extraBW']) && $_POST['extraBW'] > 0) { echo $_POST['extraBW']; } ?>"/>
    <label class="formLabel">&nbsp;E-LW</label>
    <input class="formSmallerInput" type="text" name="extraLW" id="extraLW" value="<?php if(isset($_POST['extraLW']) && $_POST['extraLW'] > 0) { echo $_POST['extraLW']; } ?>"/>
    <label class="formLabel">&nbsp;E-RW</label>
    <input class="formSmallerInput" type="text" name="extraRW" id="extraRW" value="<?php if(isset($_POST['extraRW']) && $_POST['extraRW'] > 0) { echo $_POST['extraRW']; } ?>"/>
  </fieldset><br>
  <label class="sectionLegend">Grand Totals</label><br>
  <input class="formSmallerInput" type="text" name="grandTotals" id="grandTotals" readonly value="<?php if($grandTotals > 0) echo $grandTotals; ?>"/>
  <br><br>
  
  <fieldset>
	  <legend class="formLegend">Work to be Done</legend>
	  <input type="checkbox" class="checkbox"  class="checkbox" name="handPour" <?= (isset($_POST['handPour']) ? strcmp($_POST["handPour"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Hand Pour (HP)&nbsp;</label>
      <input type="checkbox" class="checkbox"  name="upHillSide" <?= (isset($_POST['upHillSide']) ? strcmp($_POST["upHillSide"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Up HillSide (UH)&nbsp;</label>
      <input type="checkbox" class="checkbox"  name="caps" <?= (isset($_POST['caps']) ? strcmp($_POST["caps"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Caps&nbsp;</label>
      <input type="checkbox" class="checkbox"  name="wheelBarrow" <?= (isset($_POST['wheelBarrow']) ? strcmp($_POST["wheelBarrow"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Wheel Barrow (WB)&nbsp;</label>
	  <input type="checkbox" class="checkbox"  name="mailbox" <?= (isset($_POST['mailbox']) ? strcmp($_POST["mailbox"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Mailbox</strong>&nbsp;<br/><br/>
      <input type="checkbox" class="checkbox"  name="repair" <?= (isset($_POST['repair']) ? strcmp($_POST["repair"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Repair&nbsp;&nbsp;</label>
      <input type="checkbox" class="checkbox"  name="rockVeneer" <?= (isset($_POST['rockVeneer']) ? strcmp($_POST["rockVeneer"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Rock Veneer&nbsp;&nbsp;</label>
      <input type="checkbox" class="checkbox"  name="groutRetainer" <?= (isset($_POST['groutRetainer']) ? strcmp($_POST["groutRetainer"], "") ? 'checked' : '' : '') ?> />
      <label class="formLabel">Grout Retainer</label>
      <br/>
  </fieldset>
  <br/>

  <fieldset>
    	<legend class="sectionLegend">Material Sheets</legend>
      <?php
         if(!isset($_POST['clearLayout'])) {
            displayExistingMaterialSheets();
         }
      ?>
  	</fieldset><br/>

  <fieldset>
  	<legend class="sectionLegend">Timesheets</legend>
	<?php
  		if(!isset($_POST['clearLayout'])) {
			displayExistingTimesheets();
  		}
	?>
	<br/>
	<table id="selectTimesheetTable" style="border:0px solid black;" width="45%" border="5" cellpadding="1" cellspacing="5" class="db-table">
	<tr><th style="border:0px solid black;">Date</th><th style="border:0px solid black;">Action</th><th style="border:0px solid black;">Crew</th><th style="border:0px solid black;"></th><th style="border:0px solid black;"></th></tr>
	<tr>			
	  <td align='center' id='selectedTimesheetDateCol' style='border:0px solid black;' width='10%' align='left'>
	     <?php if($msie) : ?>
  	        <input placeholder="mm/dd/yyyy" type="text" name="selectedTimesheetDate" id="selectedTimesheetDate" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['selectedTimesheetDate'])) { echo $_POST['selectedTimesheetDate']; } ?>"/>
  	     <?php else : ?>
            <input name="selectedTimesheetDate" type="date" style="font-size:23px" value="<?php if(isset($_POST['selectedTimesheetDate'])) { echo $_POST['selectedTimesheetDate']; } ?>"/>
         <?php endif; ?>
	  </td>
	  <td align='center' id='selectedTimesheetActionCol' style='border:0px solid black;' width='10%' align='left'>
	  	 <input type="hidden" name="selectedTimesheetAction" size="20" style="font-size:20px" value=''/>
	  	 <select name="actionDropMenu" id="actionDropMenu" style="font-size:23px" onclick="setSelectedAction()">
	        <option value=""></option>
	    	<option value="handDig" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Hand Dig") ? 'selected' : '' : '') ?> >Hand Dig</option>
	    	<option value="dug" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Dug") ? 'selected' : '' : '') ?> >Dug</option>
			<option value="set" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Set") ? 'selected' : '' : '') ?> >Set</option>
			<option value="pour" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Pour") ? 'selected' : '' : '') ?> >Pour</option>
			<option value="block" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Block") ? 'selected' : '' : '') ?> >Block</option>
			<option value="groutAndCaps" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Grout and Caps") ? 'selected' : '' : '') ?> >Grout and Caps</option>
			<option value="backfill" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Backfill") ? 'selected' : '' : '') ?> >Backfill</option>
			<option value="clean" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Clean") ? 'selected' : '' : '') ?> >Clean</option>
			<option value="warranty" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "Warranty") ? 'selected' : '' : '') ?> >Warranty</option>
			<option value="purchaseOrder" <?= (isset($_POST['selectedTimesheetAction']) ? !strcmp($_POST["selectedTimesheetAction"], "PO") ? 'selected' : '' : '') ?> >PO</option>
	     </select>
  	  </td>
  	  <td align='center' id='selectedTimesheetCrewCol' style='border:0px solid black;' width='15%' align='left'>
  	     <?php displayCrewDropMenu(); ?>
  	  </td>
	  <td align='center' id='saveSelectedTimesheet' style='border:0px solid black;' width='10%' align='left'>
  	     <input class="sectionButton" type="submit" name="saveSelectedTimesheet" id="saveSelectedTimesheet" value="Save">
  	  </td>
  	  <td align='center' id='deleteSelectedTimesheet' style='border:0px solid black;' width='10%' align='left'>
  	     <input class="sectionButton" type="submit" name="deleteSelectedTimesheet" id="deleteSelectedTimesheet" value="Delete" >
  	  </td>
  	</tr>
	</table>
	<input type="hidden" name="selectedTimesheetCrew" size="20" style="font-size:20px" value=''>
  </fieldset>
  <br/>

  <fieldset id="Layout Plan">
    <legend class="sectionLegend">Layout Plan</legend>
    <textarea class="formOutput" name="fileUploadOutput" id="fileUploadOutput" cols="74" rows="1" readonly><?php echo $uploadFileMessage; ?></textarea><br/><br/>

    <img class="hideImage" id="planImage" src="<?php echo $layout_plan_images_dir . $_POST['planImageFile']; ?>" style="margin-left:auto;margin-right:auto;width:45%;border:5px solid black">

    <label class="formHeaderLabel">Plan Image:</label>
    <input class="formLargeInput" name="planImageFile" type="text" value="<?php if(isset($_POST['planImageFile'])) { echo $_POST['planImageFile']; } ?>"/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    <input class="formButton" type="button" name="viewPlanImage" value= "View" onclick="showPlanImage()">
    <input class="formButton" type="button" name="hidePlanImageButton" value= "Hide" onclick="hidePlanImage()">
    <input class="formButton" type="submit" name="deletePlanImage" id="deletePlanImage" value="Delete" onclick="return confirm('Are you sure you want to Delete this Plan Image?')"><br/><br/>
    <label class="formHeaderLabel">Select image to upload:</label>
      <input class="formMediumLargeInput" type="file" name="fileToUpload" id="fileToUpload">
      <input class="formMediumSmallInput" type="submit" name="updatePlanImage" id="updatePlanImage" value="Update Image">
  </fieldset>
  <br/>

   <fieldset>
      <legend class="sectionLegend">Comments</legend>
      <textarea name="comments" id="comments" cols="80" rows="5" style="font-size:18px"><?php if(isset($_POST['comments'])) { echo $_POST['comments']; } ?></textarea>
   </fieldset><br />
        
    <div align="center">
      <input class="formButton" type="submit" name="searchLayout" id="searchLayout" value="Search">
      <input class="formButton" type="submit" name="saveLayout" id="saveLayout" value="Save" onclick="return confirm('Are you sure you want to Save this Layout?')">
      <input class="formButton" type="submit" name="deleteLayout" id="deleteLayout" value="Delete" onclick="return confirm('Are you sure you want to Delete this Layout?')">
      <input class="formButton" type="submit" name="clearLayout" id="clearLayout" value="Clear">
    </div>
    
<script type="text/javascript">
function setSelectedCrew() {
	var x=document.getElementById("crewDropMenu").selectedIndex;
	var y=document.getElementById("crewDropMenu").options;
	document.getElementsByName("selectedTimesheetCrew")[0].value = y[x].text;

	setSelectedAction();
}

function setSelectedAction() {
	var x=document.getElementById("actionDropMenu").selectedIndex;
	var y=document.getElementById("actionDropMenu").options;
	document.getElementsByName("selectedTimesheetAction")[0].value = y[x].text;
}

function showPlanImage() {
    var planImageFile = document.getElementsByName("planImageFile")[0].value;
    if(planImageFile == null || planImageFile.length == 0) {
        document.getElementsByName("fileUploadOutput")[0].value = 'Layout plan image is not set.';
        return;
    }

    var obj=document.getElementById('planImage');
    obj.className = 'showImage';

    document.getElementsByName("fileUploadOutput")[0].value = '';
}

function hidePlanImage() {
    var planImageFile = document.getElementsByName("planImageFile")[0].value;
    if(planImageFile == null || planImageFile.length == 0) {
        document.getElementsByName("fileUploadOutput")[0].value = 'Layout plan image is not set.';
        return;
    }

    var obj=document.getElementById('planImage');
    obj.className = 'hideImage';

    document.getElementsByName('fileUploadOutput')[0].value = 'Layout plan image has been hidden.';
}

document.getElementsByName("layoutOutput")[0].value = '<?php echo $outputMessage; ?>';
</script>

</form>
</fieldset>
</body> 
</html>
