<?php
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	include '../header.php';
?>
<html> 
<head>
   <title>Masonry Time Sheet</title>
</head> 
<body>

<?php
include '../common/commonStyle.php';
include 'masonryTimeSheetStyle.php';
include 'masonryTimeSheetUtils.php';

if(isset($_GET['lot'])) {
	$_GET['lot'] = str_replace('sAnd','&',$_GET['lot']);
	$_GET['lot'] = str_replace(':',' ',$_GET['lot']);
	$lotInfo = explode(",", $_GET['lot']);
	$index = sizeof($lotInfo);
	if($index == 4) {
		list($_POST['builder'], $_POST['subdivision'], $_POST['lot'], $_POST['action'])  = $lotInfo;
	} 
	else if($index == 7) {
		list($_POST['timesheetDate'], $_POST['builder'], $_POST['subdivision'], $_POST['lot'], $_POST['action'],
		     $_POST["timesheetCrew"], $_POST['options'])  = $lotInfo;
	}
}

if(isset($_POST['action'])) {
    $_POST["selectedAction"] = $_POST['action'];
    if(isset($_POST['options']) && (!strcmp($_POST['options'], 'delete'))) {
    	deleteTimeSheet();
    } else if(isset($_POST['options']) && (!strcmp($_POST['options'], 'search'))) {
    	searchTimeSheet();
    }
}
?>

<fieldset>
<legend class="formLegend">Masonry Time Sheet</legend>
<form id="timeSheet" name="timeSheet" method="post" action="masonryTimeSheet.php" onsubmit="setSelectedAction()">

<textarea class="formOutput" name="timeSheetOutput" id="timeSheetOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br /><br />

<fieldset>
  <label class="formHeaderLabel">Date:
  	  <input name="timesheetDate" type="date" size="10" align="left"style="font-size:23px" value="<?php if(isset($_POST['timesheetDate'])) { echo $_POST['timesheetDate']; } ?>"/>
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
  <label class="formHeaderLabel">Action:</label>
  <input type="hidden" name="selectedAction" size="20" style="font-size:20px" value=''/>
  <select name="actionDropMenu" id="actionDropMenu" style="font-size:23px" onclick="setSelectedAction()">
     <option value=""></option>
     <option value="handDig" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Hand Dig") ? 'selected' : '' : '') ?> >Hand Dig</option>
     <option value="dug" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Dug") ? 'selected' : '' : '') ?> >Dug</option>
	 <option value="set" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Set") ? 'selected' : '' : '') ?> >Set</option>
	 <option value="pour" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Pour") ? 'selected' : '' : '') ?> >Pour</option>
	 <option value="block" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Block") ? 'selected' : '' : '') ?> >Block</option>
	 <option value="groutAndCaps" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Grout and Caps") ? 'selected' : '' : '') ?> >Grout and Caps</option>
	 <option value="backfill" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Backfill") ? 'selected' : '' : '') ?> >Backfill</option>
	 <option value="clean" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Clean") ? 'selected' : '' : '') ?> >Clean</option>
	 <option value="warranty" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Warranty") ? 'selected' : '' : '') ?> >Warranty</option>
	 <option value="purchaseOrder" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "PO") ? 'selected' : '' : '') ?> >PO</option>
  </select>
  <label class="formHeaderLabel">Crew:
  <?php displayCrewDropMenu();?>
  <input type="hidden" name="timesheetCrew" size="20" style="font-size:20px" value=''>
  <label class="formHeaderLabel">Action Cost:</label>
  <input class="formSmallInput" name="actionCost" type="text" readonly value="<?php if(isset($actionCost) && ($actionCost > 0)) { echo $actionCost; } ?>"/><br/>
  <label class="formHeaderLabel">Supervisor:
	  <input class="formMediumInput" name="supervisor" type="text" value="<?php if(isset($_POST['supervisor'])) { echo $_POST['supervisor']; } ?>"/>
  </label>
  <label class="formHeaderLabel">Forman:
	  <input class="formMediumInput" name="forman" type="text" readonly value="<?php if(isset($_POST['forman'])) { echo $_POST['forman']; } ?>"/>
  </label>
  <label class="formHeaderLabel">Courses:
	  <input class="formSmallerInput" name="courses" type="text" readonly value="<?php if(isset($_POST['courses'])) { echo $_POST['courses']; } ?>"/>
  </label>
  <br/>
  <br/>
  <div align="center">
     <input class="formButton" type="submit" name="layout" id="layout" value="Layout" style="width: 100px;">
     <input class="formButton" type="submit" name="searchTimeSheet" id="searchTimeSheet" value="Search">
     <input class="formButton" type="submit" name="saveTimeSheet" id="saveTimeSheet" value="Save" onclick="return confirm('Are you sure you want to Save this Timesheet?')">
     <input class="formButton" type="submit" name="deleteTimeSheet" id="deleteTimeSheet" value="Delete" onclick="return confirm('Are you sure you want to Delete this Timesheet?')">
     <input class="formButton" type="submit" name="clearTimeSheet" id="clearTimeSheet" value="Clear">
     <input class="formButton" type="submit" name="print" id="print" value="Print">
  </div>
</fieldset><br/>

<fieldset id="layoutLeft">
	<div align="left">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" class="checkbox" name="leftWall" <?= (isset($_POST['leftWall']) ? strcmp($_POST["leftWall"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Left Wall (LW)&nbsp;</label>
    <label class="formLabel"><strong><?php if($leftWallLF > 0) { echo '<u>' . $leftWallLF . ' LF</u>'; } ?></strong></label><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="leftWallActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['leftWallActualLF']) && $_POST['leftWallActualLF'] > 0) { echo $_POST['leftWallActualLF']; } ?>"/>
	<label>LF</label><br/><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="checkbox" class="checkbox" name="lwRetainer" <?= (isset($_POST['lwRetainer']) ? strcmp($_POST["lwRetainer"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Retainer LW</label><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label class="formLabel"><strong><?php if($lwRetainerLF > 0) { echo '&nbsp;&nbsp;&nbsp;<u>' . $lwRetainerLF . ' LF</u>'; } ?></strong></label>&nbsp;
    <label class="formLabel"><strong><?php if($rlwCourses > 0) { echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>' . $rlwCourses . ' Courses</u>'; } ?></strong></label>
    <?php if($lwRetainerLF > 0 || $rlwCourses > 0) { echo '<br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; } ?>
    <input name="lwRetainerActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['lwRetainerActualLF']) && $_POST['lwRetainerActualLF'] > 0) { echo $_POST['lwRetainerActualLF']; } ?>"/>
  	<label class="formLabel">LF</label>
  	<input name="rlwActualCourses" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['rlwActualCourses']) && $_POST['rlwActualCourses'] > 0) { echo $_POST['rlwActualCourses']; } ?>"/>
  	<label class="formLabel">Courses</label><br/>
  	<br /><br /><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  	<input type="checkbox" class="checkbox" name="leftReturn" <?= (isset($_POST['leftReturn']) ? strcmp($_POST["leftReturn"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Left Return (LR)</label>&nbsp;
    <label class="formLabel"><strong><?php if($leftReturnLF > 0) { echo '<u>' . $leftReturnLF . ' LF</u>'; } ?></strong></label><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="leftReturnActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['leftReturnActualLF']) && $_POST['leftReturnActualLF'] > 0) { echo $_POST['leftReturnActualLF']; } ?>"/>
	<label class="formLabel">LF</label><br/><br/>
	</div>
</fieldset>
<fieldset id="layoutPic">
<div align="center">
	<input type="checkbox" class="checkbox" name="backWall" <?= (isset($_POST['backWall']) ? strcmp($_POST["backWall"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Back Wall (BW)</label>&nbsp;&nbsp;
    <label class="formLabel"><strong><?php if($backWallLF > 0) { echo '<u>' . $backWallLF . ' LF</u>'; } ?></strong></label><br/>
    <input name="backWallActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['backWallActualLF']) && $_POST['backWallActualLF'] > 0) { echo $_POST['backWallActualLF']; } ?>"/> 
    <label class="formLabel">LF</label><br/><br/>
    <input type="checkbox" class="checkbox" name="bwRetainer" <?= (isset($_POST['bwRetainer']) ? strcmp($_POST["bwRetainer"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Retainer BW</label>&nbsp;&nbsp;<br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label class="formLabel"><strong><?php if($bwRetainerLF > 0) { echo '<u>' . $bwRetainerLF . ' LF</u>'; } ?></strong></label>&nbsp;
    <label class="formLabel"><strong><?php if($rbwCourses > 0) { echo '&nbsp;&nbsp;<u>' . $rbwCourses . ' Courses</u>'; } ?></strong></label>
    <?php if($bwRetainerLF > 0 || $rbwCourses > 0) { echo '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; } ?>
    <input name="bwRetainerActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['bwRetainerActualLF']) && $_POST['bwRetainerActualLF'] > 0) { echo $_POST['bwRetainerActualLF']; } ?>"/> 
    <label class="formLabel">LF</label>
    <input name="rbwActualCourses" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['rbwActualCourses']) && $_POST['rbwActualCourses'] > 0) { echo $_POST['rbwActualCourses']; } ?>"/>
  	<label class="formLabel">Courses</label><br/><br/>
	<input name="layout" type="image" value="layout" src="../pictures/layout.gif" align="middle"><br /><br />
</div>
</fieldset>
<fieldset id="layoutRight">
	<input type="checkbox" class="checkbox" name="rightWall" <?= (isset($_POST['rightWall']) ? strcmp($_POST["rightWall"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Right Wall (RW)</label>&nbsp;&nbsp;
    <label class="formLabel"><strong><?php if($rightWallLF > 0) { echo '<u>' . $rightWallLF . ' LF</u>'; } ?></strong></label><br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="rightWallActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['rightWallActualLF']) && $_POST['rightWallActualLF'] > 0) { echo $_POST['rightWallActualLF']; } ?>"/> 
    <label class="formLabel">LF</label><br/><br/>
  	<input type="checkbox" class="checkbox" name="rwRetainer" <?= (isset($_POST['rwRetainer']) ? strcmp($_POST["rwRetainer"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Retainer RW</label>&nbsp;&nbsp;<br/>
    &nbsp;
    <label class="formLabel"><strong><?php if($rwRetainerLF > 0) { echo '&nbsp;&nbsp;&nbsp;<u>' . $rwRetainerLF . ' LF</u>'; } ?></strong></label>&nbsp;
    <label class="formLabel"><strong><?php if($rrwCourses > 0) { echo '&nbsp;&nbsp;&nbsp;&nbsp;<u>' . $rrwCourses . ' Courses</u>'; } ?></strong></label>
    <?php if($rwRetainerLF > 0 || $rrwCourses > 0) { echo '<br/>&nbsp;&nbsp;&nbsp;&nbsp;'; } ?>
    <input name="rwRetainerActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['rwRetainerActualLF']) && $_POST['rwRetainerActualLF']) { echo $_POST['rwRetainerActualLF']; } ?>"/>
  	<label class="formLabel">LF</label>
  	<input name="rrwActualCourses" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['rrwActualCourses']) && $_POST['rrwActualCourses'] > 0) { echo $_POST['rrwActualCourses']; } ?>"/>
  	<label class="formLabel">Courses</label><br/><br/><br/><br/>
  	<input type="checkbox" class="checkbox" name="rightReturn" <?= (isset($_POST['rightReturn']) ? strcmp($_POST["rightReturn"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="formLabel">Right Return (RR)</label>
    <label class="formLabel"><strong><?php if($rightReturnLF  > 0) { echo '<u>' . $rightReturnLF . ' LF</u>'; } ?></strong></label><br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="rightReturnActualLF" type="text" size="1" align="left"style="font-size:18px" value="<?php if(isset($_POST['rightReturnActualLF']) && $_POST['rightReturnActualLF']) { echo $_POST['rightReturnActualLF']; } ?>"/>
	<label>LF</label><br/><br/>
</fieldset>

<fieldset id="workDone">
	<legend class="sectionLegend">Work Done</legend>
    <div align="center">
    <label class="formHeaderLabel">Mark all that apply</label><br />
    </div>
  	<input type="checkbox" class="checkbox" name="handPour" <?= (isset($_POST['handPour']) ? strcmp($_POST["handPour"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Hand Pour (HP)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="handPourLF" type="text" value="<?php if(isset($_POST['handPourLF']) && $_POST['handPourLF'] > 0) { echo $_POST['handPourLF']; } ?>"/> 
  	<br/>
  	<input type="checkbox" class="checkbox" name="upHillSide" <?= (isset($_POST['upHillSide']) ? strcmp($_POST["upHillSide"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Up Hillside (UH) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="upHillSideLF" type="text" value="<?php if(isset($_POST['upHillSideLF']) && $_POST['upHillSideLF']) { echo $_POST['upHillSideLF']; } ?>"/> 
  	<br/>
  	<input type="checkbox" class="checkbox" name="wheelBarrow" <?= (isset($_POST['wheelBarrow']) ? strcmp($_POST["wheelBarrow"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Wheel Barrow (WB) &nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="wheelBarrowLF" type="text" value="<?php if(isset($_POST['wheelBarrowLF']) && $_POST['wheelBarrowLF']) { echo $_POST['wheelBarrowLF']; } ?>"/> 
  	<br/>
  	<input type="checkbox" class="checkbox" name="airCond" <?= (isset($_POST['airCond']) ? strcmp($_POST["airCond"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">AC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="airCondLF" type="text" value="<?php if(isset($_POST['airCondLF']) && $_POST['airCondLF']) { echo $_POST['airCondLF']; } ?>"/> 
  	<br/>
  	<input type="checkbox" class="checkbox" name="caps" <?= (isset($_POST['caps']) ? strcmp($_POST["caps"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Caps &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="capsLF" type="text" value="<?php if(isset($_POST['capsLF']) && $_POST['capsLF']) { echo $_POST['capsLF']; } ?>"/> 
    <br/>
    <input type="checkbox" class="checkbox" name="courtYard" <?= (isset($_POST['courtYard']) ? strcmp($_POST["courtYard"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Courtyard (CY) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="courtYardLF" type="text" value="<?php if(isset($_POST['courtYardLF']) && $_POST['courtYardLF']) { echo $_POST['courtYardLF']; } ?>"/> 
  	<br/>
  	<input type="checkbox" class="checkbox" name="mailbox" <?= (isset($_POST['mailbox']) ? strcmp($_POST["mailbox"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Mailbox (MB)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">LF</label>
	<input class="formSmallerInput" name="mailboxLF" type="text" value="<?php if(isset($_POST['mailboxLF']) && $_POST['mailboxLF']) { echo $_POST['mailboxLF']; } ?>"/> 
  	<br/>
  	<input type="checkbox" class="checkbox" name="rockVeneer" <?= (isset($_POST['rockVeneer']) ? strcmp($_POST["rockVeneer"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Rock Veneer &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <label class="formLabel">SF</label>
	<input class="formSmallerInput" name="rockVeneerSF" type="text" value="<?php if(isset($_POST['rockVeneerSF']) && $_POST['rockVeneerSF']) { echo $_POST['rockVeneerSF']; } ?>"/> 
  	<br/><br/>
  	
</fieldset>

<fieldset id="layout">
	<input type="checkbox" class="checkbox" name="handDig" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Hand Dig") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="formLabel">Hand Dig (HD)&nbsp;</label>
  	<?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="handDigDate" id="handDigDate" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['handDigDate']) && $_POST['handDigDate'] > 0) { echo $_POST['handDigDate']; } ?>"/>
  	<?php else : ?>
  	   &nbsp;
       <input name="handDigDate" type="date" style="font-size:18px" value="<?php if(isset($_POST['handDigDate'])) { echo $_POST['handDigDate']; } ?>"/>
    <?php endif; ?>
  	<label class="formLabel">LF</label>
	<input class="formSmallerInput" name="handDigLF" type="text" value="<?php if(isset($_POST['handDigLF']) && $_POST['handDigLF'] > 0) { echo $_POST['handDigLF']; } ?>"/>
  	<br />
	<input type="checkbox" class="checkbox" name="footerDug" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Dug") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="formLabel">Dug</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="footerDugDate" id="footerDugDate" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['footerDugDate']) && $_POST['footerDugDate'] > 0) { echo $_POST['footerDugDate']; } ?>"/><br/>
  	<?php else : ?>
       <input name="footerDugDate" type="date" style="font-size:18px" value="<?php if(isset($_POST['footerDugDate'])) { echo $_POST['footerDugDate']; } ?>"/><br/>
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="footerSet" <?= (isset($_POST['selectedAction']) ? (!strcmp($_POST["selectedAction"], "Set") || (isset($_POST['footerSetTime']) && $_POST['footerSetTime'] > 0)) ? 'checked' : '' : '') ?> />
    <label class="formLabel">Set</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="footerSetTime" id="footerSetTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['footerSetTime'])) { echo $_POST['footerSetTime']; } ?>"/><br/>
  	<?php else : ?>
       <input name="footerSetTime" type="date" size="20" align="left"style="font-size:18px" value="<?php if(isset($_POST['footerSetTime'])) { echo $_POST['footerSetTime']; } ?>"/><br />
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="footerPoured" <?= (isset($_POST['selectedAction']) ? (!strcmp($_POST["selectedAction"], "Pour") || (isset($_POST['footerPouredTime']) && $_POST['footerPouredTime'] > 0)) ? 'checked' : '' : '') ?> />
    <label class="formLabel">Poured</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="footerPouredTime" id="footerPouredTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['footerPouredTime'])) { echo $_POST['footerPouredTime']; } ?>"/><br/>
  	<?php else : ?>
       <input name="footerPouredTime" type="date" size="20" align="left"style="font-size:18px" value="<?php if(isset($_POST['footerPouredTime'])) { echo $_POST['footerPouredTime']; } ?>"/><br />
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="block" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Block") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="formLabel">Block</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="blockTime" id="blockTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['blockTime']) && $_POST['blockTime'] > 0) { echo $_POST['blockTime']; } ?>"/><br/>
  	<?php else : ?>
       <input name="blockTime" type="date" size="20" align="left"style="font-size:18px" value="<?php if(isset($_POST['blockTime'])) { echo $_POST['blockTime']; } ?>"/><br />
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="wallComplete" <?= (isset($_POST['wallComplete']) ? strcmp($_POST["wallComplete"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Wall Complete</label>
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="wallCompleteTime" id="wallCompleteTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['wallCompleteTime']) && $_POST['wallCompleteTime'] > 0) { echo $_POST['wallCompleteTime']; } ?>"/><br/>
  	<?php else : ?>
  	   &nbsp;
       <input name="wallCompleteTime" type="date" size="20" align="left"style="font-size:18px" value="<?php if(isset($_POST['wallCompleteTime'])) { echo $_POST['wallCompleteTime']; } ?>"/><br />
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="groutAndCaps" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Grout and Caps") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="formLabel">Grout & Caps</label>&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="groutAndCapsTime" id="groutAndCapsTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['groutAndCapsTime']) && $_POST['groutAndCapsTime'] > 0) { echo $_POST['groutAndCapsTime']; } ?>"/><br/>
  	<?php else : ?>
  	   &nbsp;
       <input name="groutAndCapsTime" type="date" size="20" align="left"style="font-size:18px" value="<?php if(isset($_POST['groutAndCapsTime'])) { echo $_POST['groutAndCapsTime']; } ?>"/><br />
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="warranty" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Warranty") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="formLabel">Warranty</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="warrantyTime" id="warrantyTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['warrantyTime']) && $_POST['warrantyTime'] > 0) { echo $_POST['warrantyTime']; } ?>"/><br/>
  	<?php else : ?>
  	   &nbsp;
       <input name="warrantyTime" type="date" size="20" align="left"style="font-size:18px" value="<?php if(isset($_POST['warrantyTime'])) { echo $_POST['warrantyTime']; } ?>"/><br />
    <?php endif; ?>
    
    <input type="checkbox" class="checkbox" name="purchaseOrder" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "PO") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="formLabel">PO</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if($msie) : ?>
  	   <input placeholder="mm/dd/yyyy" type="text" name="poTime" id="poTime" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['poTime']) && $_POST['poTime'] > 0) { echo $_POST['poTime']; } ?>"/>
  	<?php else : ?>
  	   &nbsp;
       <input name="poTime" type="date" style="font-size:18px" value="<?php if(isset($_POST['poTime'])) { echo $_POST['poTime']; } ?>"/>
    <?php endif; ?>
    <input name="purchaseOrderNumber" type="text" size="12" align="left"style="font-size:18px" value="<?php if(isset($_POST['purchaseOrderNumber'])) { echo $_POST['purchaseOrderNumber']; } ?>"/>
    <br/>
    
    <input type="checkbox" class="checkbox" name="repair" <?= (isset($_POST['repair']) ? strcmp($_POST["repair"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Repair</label><br />
    <input type="checkbox" class="checkbox" name="groutRetainer" <?= (isset($_POST['groutRetainer']) ? strcmp($_POST["groutRetainer"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Grout Retainer</label>&nbsp;&nbsp;&nbsp;&nbsp;
    <br/>
</fieldset><br /><br />

<fieldset id="perimeters">
  <legend class="sectionLegend">Perimeters</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
    <label class="formLabel"><strong><?php if($perimeterBWLF > 0) { echo $perimeterBWLF . ' LF'; } ?></strong></label>
    <input type="text" name="perimeterBW" id="perimeterBW" size="5" style="font-size:18px" value="<?php if(isset($_POST['perimeterBW']) && $_POST['perimeterBW'] > 0) { echo $_POST['perimeterBW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <label class="formLabel"><strong><?php if($perimeterLWLF > 0) { echo $perimeterLWLF . ' LF'; } ?></strong></label>
    <input type="text" name="perimeterLW" id="perimeterLW" size="5" style="font-size:18px" value="<?php if(isset($_POST['perimeterLW']) && $_POST['perimeterLW'] > 0) { echo $_POST['perimeterLW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <label class="formLabel"><strong><?php if($perimeterRWLF > 0) { echo $perimeterRWLF . ' LF'; } ?></strong></label>
    <input type="text" name="perimeterRW" id="perimeterRW" size="5" style="font-size:18px" value="<?php if(isset($_POST['perimeterRW']) && $_POST['perimeterRW'] > 0) { echo $_POST['perimeterRW']; } ?>"/><br/>
  <br/>
  </fieldset>
  &nbsp;&nbsp;&nbsp;
  <fieldset id="viewWall">
  <legend class="sectionLegend">View Wall</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
  	<label class="formLabel"><strong><?php if($viewBWLF > 0) { echo $viewBWLF . ' LF'; } ?></strong></label>
    <input type="text" name="viewBW" id="viewBW" size="5" style="font-size:18px" value="<?php if(isset($_POST['viewBW']) && $_POST['viewBW'] > 0) { echo $_POST['viewBW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <label class="formLabel"><strong><?php if($viewLWLF > 0) { echo $viewLWLF . ' LF'; } ?></strong></label>
    <input type="text" name="viewLW" id="viewLW" size="5" style="font-size:18px" value="<?php if(isset($_POST['viewLW']) && $_POST['viewLW'] > 0) { echo $_POST['viewLW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <label class="formLabel"><strong><?php if($viewRWLF > 0) { echo $viewRWLF . ' LF'; } ?></strong></label>
    <input type="text" name="viewRW" id="viewRW" size="5" style="font-size:18px" value="<?php if(isset($_POST['viewRW']) && $_POST['viewRW'] > 0) { echo $_POST['viewRW']; } ?>"/><br/>
  <br/>
</fieldset>
&nbsp;&nbsp;&nbsp;
  <fieldset id="extensionWall">
  <legend class="sectionLegend">Extension Wall</legend><br/>
  	<label class="formLabel">&nbsp;&nbsp;&nbsp;BW</label>
  	<label class="formLabel"><strong><?php if($extensionBWLF > 0) { echo $extensionBWLF . ' LF'; } ?></strong></label>
    <input type="text" name="extensionBW" id="extensionBW" size="5" style="font-size:18px" value="<?php if(isset($_POST['extensionBW']) && $_POST['extensionBW'] > 0) { echo $_POST['extensionBW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;LW</label>
    <label class="formLabel"><strong><?php if($extensionLWLF > 0) { echo $extensionLWLF . ' LF'; } ?></strong></label>
    <input type="text" name="extensionLW" id="extensionLW" size="5" style="font-size:18px" value="<?php if(isset($_POST['extensionLW']) && $_POST['extensionLW'] > 0) { echo $_POST['extensionLW']; } ?>"/><br/>
    <label class="formLabel">&nbsp;&nbsp;&nbsp;RW</label>
    <label class="formLabel"><strong><?php if($extensionRWLF > 0) { echo $extensionRWLF . ' LF'; } ?></strong></label>
    <input type="text" name="extensionRW" id="extensionRW" size="5" style="font-size:18px" value="<?php if(isset($_POST['extensionRW']) && $_POST['extensionRW'] > 0) { echo $_POST['extensionRW']; } ?>"/><br/>
  <br/>
</fieldset>
<br/><br/>
<fieldset>
 	<legend class="sectionLegend">Extras</legend>
 	<textarea name="extras" id="extras" cols="80" rows="8" style="font-size:18px"><?php if(isset($_POST['extras'])) { echo $_POST['extras']; } ?></textarea>
</fieldset><br/>

<fieldset>
	<input type="hidden" name="workerTimes" size="20" style="font-size:20px" value=''/>
	<input type="hidden" name="selectedEmployee" size="20" style="font-size:20px" value=''>
	
	<legend class="sectionLegend">Workers</legend>
	<?php
    	displayWorkers();
	?>
	<br/>
	<div align="center">
    <input class="sectionButton" type="submit" name="saveWorkerTime" id="saveWorkerTime" value="Save">
    </div>
    
    <label class="formHeaderLabel">Employee:</label>
    <?php
    	displayEmployeesToAddDelete();
	?>
	<input class="sectionButton" type="submit" name="addEmployee" id="addEmployee" value="Add">
	<input class="sectionButton" type="submit" name="deleteEmployee" id="deleteEmployee" value="Delete">
    
</fieldset><br/>

<fieldset>
	<input type="checkbox" class="checkbox" name="materialAtSite" <?= (isset($_POST['materialAtSite']) ? strcmp($_POST["materialAtSite"], "") ? 'checked' : '' : '') ?> />
	<label class="formLabel">Was Block, Sand and Material at the Site where you were sent? </label><br/>
    <input type="checkbox" class="checkbox" name="waitForAnything" <?= (isset($_POST['waitForAnything']) ? strcmp($_POST["waitForAnything"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Did you have to wait for anything? </label>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <label class="formLabel">How Long?</label>
    <input class="formSmallerInput" name="waitForAnythingTime" type="text" value="<?php if(isset($_POST['waitForAnythingTime']) && $_POST['waitForAnythingTime'] > 0) { echo $_POST['waitForAnythingTime']; } ?>"/>
	<br>
    <input type="checkbox" class="checkbox" name="concreteLate" <?= (isset($_POST['concreteLate']) ? strcmp($_POST["concreteLate"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Was concrete late?</label>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<label class="formLabel">How Long?</label>
    <input class="formSmallerInput" name="concreteLateTime" type="text" value="<?php if(isset($_POST['concreteLateTime']) && $_POST['concreteLateTime'] > 0) { echo $_POST['concreteLateTime']; } ?>"/><br/>
    <input type="checkbox" class="checkbox" name="cleanUp" <?= (isset($_POST['cleanUp']) ? strcmp($_POST["cleanUp"], "") ? 'checked' : '' : '') ?> />
    <label class="formLabel">Clean Up</label>
	<br>
</fieldset><br/><br/>

<fieldset>
	<label class="formLabel">Concrete:
	  <input class="formSmallerInput" name="concrete" type="text" value="<?php if(isset($_POST['concrete'])) { echo $_POST['concrete']; } ?>"/> 
	</label>&nbsp;
	<label class="formLabel">Rebar:
	  <input class="formSmallerInput" name="rebar" type="text" value="<?php if(isset($_POST['rebar'])) { echo $_POST['rebar']; } ?>"/> 
	</label>&nbsp;
	<label class="formLabel">Lime:
	  <input class="formSmallerInput" name="lime" type="text" value="<?php if(isset($_POST['lime'])) { echo $_POST['lime']; } ?>"/> 
	</label>&nbsp;
	<label class="formLabel">Grout:
	  <input class="formSmallerInput" name="grout" type="text" value="<?php if(isset($_POST['grout'])) { echo $_POST['grout']; } ?>"/> 
	</label><br/><br/>&nbsp;&nbsp;
	<label class="formLabel">Cement:
	  <input class="formSmallerInput" name="cement" type="text" value="<?php if(isset($_POST['cement'])) { echo $_POST['cement']; } ?>"/> 
	</label>&nbsp;
	<label class="formLabel">Block:
	  <input class="formSmallerInput" name="blockType" type="text" value="<?php if(isset($_POST['blockType'])) { echo $_POST['blockType']; } ?>"/> 
	</label>&nbsp;
	<label class="formLabel">Misc:
	  <input class="formSmallerInput" name="miscellaneous" type="text" value="<?php if(isset($_POST['miscellaneous'])) { echo $_POST['miscellaneous']; } ?>"/> 
	</label>&nbsp;
	<label class="formLabel">Others:
	  <input class="formSmallerInput" name="others" type="text" value="<?php if(isset($_POST['others'])) { echo $_POST['others']; } ?>"/> 
	</label>&nbsp;
</fieldset><br/><br/>

<div align="center">
<fieldset>
	<input type="checkbox" class="checkbox" name="workDone" <?= (isset($_POST['workDone']) ? strcmp($_POST["workDone"], "") ? 'checked' : '' : '') ?> />
	<label class="formLabel">DONE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="checkbox" class="checkbox" name="partialWork" <?= (isset($_POST['partialWork']) ? strcmp($_POST['partialWork'], "") ? 'checked' : '' : '') ?> />
	<label class="formLabel">PARTIAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input type="checkbox" class="checkbox" name="nothingDone" <?= (isset($_POST['nothingDone']) ? strcmp($_POST['nothingDone'], "") ? 'checked' : '' : '') ?> />
	<label class="formLabel">NOTHING DONE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<label class="formLabel">Supervisor Initials</label>
    <input class="formSmallerInput" name="supervisorInitials" type="text" value="<?php if(isset($_POST['supervisorInitials'])) { echo $_POST['supervisorInitials']; } ?>"/>
</fieldset><br/><br/>

	<div align="center">
	  <input class="formButton" type="submit" name="layout" id="layout" value="Layout" style="width: 100px;">
      <input class="formButton" type="submit" name="searchTimeSheet" id="searchTimeSheet" value="Search" >
      <input class="formButton" type="submit" name="saveTimeSheet" id="saveTimeSheet" value="Save" onclick="return confirm('Are you sure you want to Save this Timesheet?')">
      <input class="formButton" type="submit" name="deleteTimeSheet" id="deleteTimeSheet" value="Delete" onclick="return confirm('Are you sure you want to Delete this Timesheet?')">
      <input class="formButton" type="submit" name="clearTimeSheet" id="clearTimeSheet" value="Clear">
      <input class="formButton" type="submit" name="print" id="print" value="Print">
    </div>
</div>

<script type="text/javascript">

function setSelectedAction() {
	var x=document.getElementById("actionDropMenu").selectedIndex;
	var y=document.getElementById("actionDropMenu").options;
	document.getElementsByName("selectedAction")[0].value = y[x].text;

	setSelectedCrew();
	setSelectedEmployee();
	updateWorkerTimes();
}

function setSelectedCrew() {
	var x=document.getElementById("crewDropMenu").selectedIndex;
	var y=document.getElementById("crewDropMenu").options;
	document.getElementsByName("timesheetCrew")[0].value = y[x].text;
}

function setSelectedEmployee() {
	var x=document.getElementById("employeeDropMenu").selectedIndex;
	var y=document.getElementById("employeeDropMenu").options;
	document.getElementsByName("selectedEmployee")[0].value = y[x].text;
}

function updateWorkerTimes() {
	var workerTimesRowNum = document.getElementById("workerTimesTable").rows.length;
	var temp = '';
	for (var i=1;i<workerTimesRowNum;i++)	{
		var worker = document.getElementById("workerTimesTable").rows[i].cells[0].innerHTML;
		<?php if($msie) : ?>
			var startTime = document.getElementById("workerTimesTable").rows[i].cells[1].children[0].value +
							":" +
							document.getElementById("workerTimesTable").rows[i].cells[1].children[1].value;
			var stopTime = document.getElementById("workerTimesTable").rows[i].cells[2].children[0].value +
						   ":" +
						   document.getElementById("workerTimesTable").rows[i].cells[2].children[1].value;
	  	<?php else : ?>
	  	var startTime = document.getElementById("workerTimesTable").rows[i].cells[1].children[0].value;
	  	var stopTime = document.getElementById("workerTimesTable").rows[i].cells[2].children[0].value;
	    <?php endif; ?>
		temp += worker + "," + startTime + "," + stopTime + ";";
	}
	document.getElementsByName("workerTimes")[0].value = temp;
}

document.getElementsByName("timeSheetOutput")[0].value = '<?php echo $outputMessage; ?>';
document.getElementsByName("actionCost")[0].value = '<?php if(isset($actionCost) && ($actionCost > 0)) { echo $actionCost; } ?>';
</script>

</form>
</fieldset>

</body> 
</html>