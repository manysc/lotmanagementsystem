<?php
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	session_start();
?>

<html> 
<head><title>Masonry Time Sheet (<?php echo ($_SESSION['username']);?>)</title>
</head>

<style>
#timesheetPrint {
	width : 100%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
}

#timesheetPrint #layoutLeft {
	<?php if($msie) : ?>
		width : 25%;
	<?php else : ?>
		width : 27%;
	<?php endif; ?>
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
}
#timesheetPrint #layoutMiddle {
	<?php if($msie) : ?>
		width : 45%;
	<?php else : ?>
		width : 1%;
	<?php endif; ?>
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
}
#timesheetPrint #layoutRight {
	<?php if($msie) : ?>
		width : 25%;
	<?php else : ?>
		width : 24%;
	<?php endif; ?>
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
}
	
.leftPaneLabel {
	font-size:14px;
}

.leftPaneCheckbox {
	<?php if($msie) : ?>
		width: 25px;
		height: 25px;
	<?php else : ?>
		width: 14px;
		height: 14px;
	<?php endif; ?>
	
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

<body>

<?php
	include 'masonryTimeSheetUtils.php';
	
	$_POST['print'] = true;
	include '../configuration.php';
	include '../common/commonUtils.php';

	if(isset($_GET['lot'])) {
		$_GET['lot'] = str_replace('sAnd','&',$_GET['lot']);
		$_GET['lot'] = str_replace(':',' ',$_GET['lot']);
		$lotInfo = explode(",", $_GET['lot']);
		$index = sizeof($lotInfo);
		if($index == 6) {
			list($_POST['timesheetDate'], $_POST['builder'], $_POST['subdivision'], $_POST['lot'], $_POST['action'], $_POST["timesheetCrew"])  = $lotInfo;
		}
	}
	
	if(isset($_POST['action'])) {
    	$_POST["selectedAction"] = $_POST['action'];
     	searchTimeSheet();
    }
?>

<fieldset id="timesheetPrint">
   <div align="center">
   <legend style="font-size:22px"><strong>Masonry Time Sheet</strong></legend>
   <form id="timeSheetPrintForm" name="timeSheetPrintForm" method="post" action="timesheetPrint.php">
   <label><strong>Date</strong>:
      <input name="date" type="text" size="10" align="left"style="font-size:14px" readonly value="<?php if(isset($actionCost) && ($actionCost > 0)) { echo $timesheetDate; } ?>"/>
   <label><strong>Builder</strong>:
      <input name="builder" type="text" size="28" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['builder'])) { echo $_POST['builder']; } ?>"/>
   </label>
   <label><strong>Subdivision</strong>:
	  <input name="subdivision" type="text" size="20" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['subdivision'])) { echo $_POST['subdivision']; } ?>"/> 
   </label>
   <label><strong>Lot</strong>:
	  <input name="lot" type="text" size="2" align="left" style="font-size:14px" readonly value="<?php if(isset($_POST['lot'])) { echo $_POST['lot']; } ?>"/>
   </label>
   <br/>
   <label><strong>Supervisor</strong>:
	  <input name="supervisor" type="text" size="15" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['supervisor'])) { echo $_POST['supervisor']; } ?>"/>
   </label>
   <label><strong>Forman</strong>:
	  <input name="forman" type="text" size="20" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['forman'])) { echo $_POST['forman']; } ?>"/>
   </label>
   <label><strong>Action</strong>:
	  <input name="action" type="text" size="12" align="left" style="font-size:14px" readonly value="<?php if(isset($_POST['action'])) { echo $_POST['action']; } ?>"/>
   </label>
   <label for="action"><strong>Cost:</strong></label>
  	  <input name="actionCost" type="text" size="4" align="left"style="font-size:14px" readonly value="<?php if(isset($actionCost) && ($actionCost > 0)) { echo $actionCost; } ?>"/>
   <br/>
   <label><strong>Courses</strong>:
	  <input name="supervisor" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['courses'])) { echo $_POST['courses']; } ?>"/>
   </label>
   <br/>
   
   <fieldset id="layoutLeft">
   <div align="left">
    <label style="font-size:18px"><strong>Work Done</strong></label><br />
    <input type="checkbox" class="leftPaneCheckbox" name="handDig" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Hand Dig") ? 'checked' : '' : '') ?> onclick="return false" />
    <label class="leftPaneLabel">Hand Dig&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input name="handDigLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['handDigLF']) && $_POST['handDigLF'] > 0) { echo $_POST['handDigLF']; } ?>"/>
  	<label class="leftPaneLabel">LF</label><br/>
  	<input type="checkbox" class="leftPaneCheckbox" name="handPour" <?= (isset($_POST['handPour']) ? strcmp($_POST["handPour"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">Hand Pour&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input name="handPourLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['handPourLF']) && $_POST['handPourLF'] > 0) { echo $_POST['handPourLF']; } ?>"/>
    <label class="leftPaneLabel">LF</label><br/>
  	<input type="checkbox" class="leftPaneCheckbox" name="upHillSide" <?= (isset($_POST['upHillSide']) ? strcmp($_POST["upHillSide"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">Up Hillside&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input name="upHillSideLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['upHillSideLF']) && $_POST['upHillSideLF']) { echo $_POST['upHillSideLF']; } ?>"/> 
  	<label class="leftPaneLabel">LF</label><br />
  	<input type="checkbox" class="leftPaneCheckbox" name="wheelBarrow" <?= (isset($_POST['wheelBarrow']) ? strcmp($_POST["wheelBarrow"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">Wheel Barrow</label>
	<input name="wheelBarrowLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['wheelBarrowLF']) && $_POST['wheelBarrowLF']) { echo $_POST['wheelBarrowLF']; } ?>"/> 
  	<label class="leftPaneLabel">LF</label><br />
  	<input type="checkbox" class="leftPaneCheckbox" name="airCond" <?= (isset($_POST['airCond']) ? strcmp($_POST["airCond"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">AC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input name="airCondLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['airCondLF']) && $_POST['airCondLF']) { echo $_POST['airCondLF']; } ?>"/> 
	<label class="leftPaneLabel">LF</label><br />
  	<input type="checkbox" class="leftPaneCheckbox" name="caps" <?= (isset($_POST['caps']) ? strcmp($_POST["caps"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">Caps&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input name="capsLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['capsLF']) && $_POST['capsLF']) { echo $_POST['capsLF']; } ?>"/> 
	<label class="leftPaneLabel">LF</label><br />
    <input type="checkbox" class="leftPaneCheckbox" name="courtYard" <?= (isset($_POST['courtYard']) ? strcmp($_POST["courtYard"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">Courtyard&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input name="courtYardLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['courtYardLF']) && $_POST['courtYardLF']) { echo $_POST['courtYardLF']; } ?>"/> 
  	<label class="leftPaneLabel">LF</label><br />
  	<input type="checkbox" class="leftPaneCheckbox" name="mailbox" <?= (isset($_POST['mailbox']) ? strcmp($_POST["mailbox"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label class="leftPaneLabel">Mailbox&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
	<input name="mailboxLF" type="text" size="3" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['mailboxLF']) && $_POST['mailboxLF']) { echo $_POST['mailboxLF']; } ?>"/> 
	<label class="leftPaneLabel">LF</label><br />
	<label for="leftWall" style="font-size:18px"><strong><u>Left Wall:</u></strong></label>&nbsp;&nbsp;<br/>
    <label style="font-size:18px">Wall&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="text" name="leftWallLF" id="leftWallLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($leftWallLF) && $leftWallLF > 0) { echo $leftWallLF; } ?>"/><br/>
    <label for="lwRetainer" style="font-size:18px">Retainer</label>&nbsp;&nbsp;&nbsp;
    <input type="text" name="lwRetainerLF" id="lwRetainerLF" size="2" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($lwRetainerLF) && $lwRetainerLF > 0) { echo $lwRetainerLF . ' LF'; } ?>"/>
    <input type="text" name="rlwCourses" id="rlwCourses" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($rlwCourses) && $rlwCourses > 0) { echo $rlwCourses . ' C'; } ?>"/><br/>
    <label for="leftReturn" style="font-size:18px">Return</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="leftReturnLF" id="leftReturnLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($leftReturnLF) && $leftReturnLF > 0) { echo $leftReturnLF; } ?>"/><br/>
    <label style="font-size:18px">Perimeter</label>&nbsp;
    <input type="text" name="perimeterLWLF" id="perimeterLWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($perimeterLWLF) && $perimeterLWLF > 0) { echo $perimeterLWLF; } ?>"/><br/>
    <label style="font-size:18px">View&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <input type="text" name="viewLWLF" id="viewLWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($viewLWLF) && $viewLWLF > 0) { echo $viewLWLF; } ?>"/><br/>
    <label style="font-size:18px">Extension&nbsp;</label>
    <input type="text" name="extensionLWLF" id="extensionLWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($extensionLWLF) && $extensionLWLF > 0) { echo $extensionLWLF; } ?>"/><br/>    
   </div>
   </fieldset>
   
   <fieldset id="layoutMiddle">
   <label style="font-size:18px"><strong><u>Back Wall:</u>&nbsp;</strong></label>
   <br/>
   <label style="font-size:18px">Wall</label>
   <input type="text" name="backWallLF" id="backWallLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($backWallLF > 0) { echo $backWallLF; } ?>"/>
   <label for="backWall" style="font-size:18px">Retainer</label>
   <input type="text" name="bwRetainerLF" id="bwRetainerLF" size="2" style="font-size:18px;font-weight:bold" readonly value="<?php if($bwRetainerLF > 0) { echo $bwRetainerLF . ' LF'; } ?>"/>
   <input type="text" name="rbwCourses" id="rbwCourses" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($rbwCourses) && $rbwCourses > 0) { echo $rbwCourses . ' C'; } ?>"/><br/>
   <label style="font-size:18px">Perimeter</label>
   <input type="text" name="perimeterBWLF" id="perimeterBWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($perimeterBWLF > 0) { echo $perimeterBWLF; } ?>"/>
   <label style="font-size:18px">View</label>
   <input type="text" name="viewBWLF" id="viewBWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($viewBWLF > 0) { echo $viewBWLF; } ?>"/>
   <br/>
   <label style="font-size:18px">Extension</label>
   <input type="text" name="extensionBWLF" id="extensionBWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($extensionBWLF > 0) { echo $extensionBWLF; } ?>"/>
   <input name="layoutPic" align="top-middle" type="image" value="layoutPic" src="../pictures/layout.gif">
   </fieldset>
   
   <fieldset id="layoutRight">
   <div align="left">
  	<input type="checkbox" class="leftPaneCheckbox" name="rockVeneer" <?= (isset($_POST['rockVeneer']) ? strcmp($_POST["rockVeneer"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label style="font-size:14px">Rock Veneer</label>
	<input name="rockVeneerSF" type="text" size="2" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['rockVeneerSF']) && $_POST['rockVeneerSF']) { echo $_POST['rockVeneerSF']; } ?>"/> 
	<label style="font-size:12px">SF</label><br />
   <input type="checkbox" class="leftPaneCheckbox" name="groutRetainer" <?= (isset($_POST['groutRetainer']) ? strcmp($_POST["groutRetainer"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label style="font-size:12px">Grout Retainer</label>
    <input name="groutRetainerCourses" type="text" size="1" align="left" style="font-size:10px" readonly value="<?php if(isset($_POST['groutRetainerCourses']) && $_POST['groutRetainerCourses'] > 0) { echo $_POST['groutRetainerCourses']; } ?>"/>
    <label style="font-size:12px">Courses</label><br/>
    <input type="checkbox" class="leftPaneCheckbox" name="repair" <?= (isset($_POST['repair']) ? strcmp($_POST["repair"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label style="font-size:14px">Repair</label><br/>
    <input type="checkbox" class="leftPaneCheckbox" name="footerDug" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Dug") ? 'checked' : '' : '') ?> onclick="return false" />
    <label for="footerDug">Dug</label>
    <input type="checkbox" class="leftPaneCheckbox" name="footerSet" <?= (isset($_POST['selectedAction']) ? (!strcmp($_POST["selectedAction"], "Set") || (isset($_POST['footerSetTime']) && $_POST['footerSetTime'] > 0)) ? 'checked' : '' : '') ?> onclick="return false" />
    <label for="footerSet">Set</label>
    <input type="checkbox" class="leftPaneCheckbox" name="footerPoured" <?= (isset($_POST['selectedAction']) ? (!strcmp($_POST["selectedAction"], "Pour") || (isset($_POST['footerPouredTime']) && $_POST['footerPouredTime'] > 0)) ? 'checked' : '' : '') ?> onclick="return false" />
    <label for="footerPoured">Pour</label><br/>
    <input type="checkbox" class="leftPaneCheckbox" name="block" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Block") ? 'checked' : '' : '') ?> onclick="return false" />
    <label for="block">Block</label>
    <input type="checkbox" class="leftPaneCheckbox" name="groutAndCaps" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Grout and Caps") ? 'checked' : '' : '') ?> onclick="return false" />
    <label style="font-size:14px">Grout & Caps</label><br/>
    <input type="checkbox" class="leftPaneCheckbox" name="backfill" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Backfill") ? 'checked' : '' : '') ?> onclick="return false"/>
    <label>Backfill&nbsp;</label>
    <input type="checkbox" class="leftPaneCheckbox" name="clean" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Clean") ? 'checked' : '' : '') ?> onclick="return false" />
    <label>Clean</label><br/>
    <input type="checkbox" class="leftPaneCheckbox" name="warranty" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "Warranty") ? 'checked' : '' : '') ?> onclick="return false" />
    <label>Warranty</label><br/>
    <input type="checkbox" class="leftPaneCheckbox" name="purchaseOrder" <?= (isset($_POST['selectedAction']) ? !strcmp($_POST["selectedAction"], "PO") ? 'checked' : '' : '') ?> onclick="return false" />
    <label for="purchaseOrder">PO</label>
    <input name="purchaseOrderNumber" type="text" size="13" align="left"style="font-size:14px" readonly value="<?php if(isset($_POST['purchaseOrderNumber'])) { echo $_POST['purchaseOrderNumber']; } ?>"/><br/>   
   <label for="rightWall" style="font-size:18px"><strong><u>Right Wall:</u></strong></label>&nbsp;&nbsp;<br/>
   <label style="font-size:18px">Wall</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <input type="text" name="rightWallLF" id="rightWallLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($rightWallLF > 0) { echo $rightWallLF; } ?>"/><br/>
   <label style="font-size:16px">Retainer</label>
   <input type="text" name="rwRetainerLF" id="rwRetainerLF" size="2" style="font-size:18px;font-weight:bold" readonly value="<?php if($rwRetainerLF > 0) { echo $rwRetainerLF . ' LF'; } ?>"/>
   <input type="text" name="rrwCourses" id="rrwCourses" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if(isset($rrwCourses) && $rrwCourses > 0) { echo $rrwCourses . ' C'; } ?>"/><br/>
   <label style="font-size:18px">Return</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <input type="text" name="rightReturnLF" id="rightReturnLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($rightReturnLF > 0) { echo $rightReturnLF; } ?>"/><br/>
   <label style="font-size:18px">Perimeter</label>&nbsp;
   <input type="text" name="perimeterRWLF" id="perimeterRWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($perimeterRWLF > 0) { echo $perimeterRWLF; } ?>"/><br/>
   <label style="font-size:18px">View&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
   <input type="text" name="viewRWLF" id="viewRWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($viewRWLF > 0) { echo $viewRWLF; } ?>"/><br/>
   <label style="font-size:18px">Extension&nbsp;</label>
   <input type="text" name="extensionRWLF" id="extensionRWLF" size="1" style="font-size:18px;font-weight:bold" readonly value="<?php if($extensionRWLF > 0) { echo $extensionRWLF; } ?>"/>
   <br/><br/>
   </div>
   </fieldset>
   <?php
      displayWorkers();
   ?>
   <div align="left">
   <input type="checkbox" class="leftPaneCheckbox" name="materialAtSite" <?= (isset($_POST['materialAtSite']) ? strcmp($_POST["materialAtSite"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label style="font-size:16px">Was Block, Sand and Material at the Site where you were sent?</label>
   <input type="checkbox" class="leftPaneCheckbox" name="concreteLate" <?= (isset($_POST['concreteLate']) ? strcmp($_POST["concreteLate"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label style="font-size:16px">Was concrete late?</label>
   <label style="font-size:16px">How Long?</label>
   <input name="concreteLateTime" type="text" size="4" align="left"style="font-size:10px" readonly value="<?php if(isset($_POST['concreteLateTime']) && $_POST['concreteLateTime'] > 0) { echo $_POST['concreteLateTime']; } ?>"/>
   <br/>
   <input type="checkbox" class="leftPaneCheckbox" name="waitForAnything" <?= (isset($_POST['waitForAnything']) ? strcmp($_POST["waitForAnything"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label style="font-size:16px">Did you have to wait for anything?</label>
   <label style="font-size:16px">How Long?</label>
   <input name="waitForAnythingTime" type="text" size="4" align="left"style="font-size:10px" readonly value="<?php if(isset($_POST['waitForAnythingTime']) && $_POST['waitForAnythingTime'] > 0) { echo $_POST['waitForAnythingTime']; } ?>"/>
   <input type="checkbox" class="leftPaneCheckbox" name="cleanUp" <?= (isset($_POST['cleanUp']) ? strcmp($_POST["cleanUp"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label style="font-size:16px">Clean Up?</label>
   <br/>
   </div>
   <div align="center">
   <label style="font-size:16px">Concrete:</label>
   <input name="concrete" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['concrete'])) { echo $_POST['concrete']; } ?>"/> 
   <label style="font-size:16px">Rebar:</label>
   <input name="rebar" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['rebar'])) { echo $_POST['rebar']; } ?>"/> 
   <label style="font-size:16px">Lime:</label>
   <input name="lime" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['lime'])) { echo $_POST['lime']; } ?>"/> 
   <label style="font-size:16px">Grout:</label>
   <input name="grout" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['grout'])) { echo $_POST['grout']; } ?>"/> 
   <br/>
   <label style="font-size:16px">Cement:</label>
   <input name="cement" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['cement'])) { echo $_POST['cement']; } ?>"/> 
   <label style="font-size:16px">Block:</label>
   <input name="blockType" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['blockType'])) { echo $_POST['blockType']; } ?>"/> 
   <label style="font-size:16px">Misc:</label>
   <input name="miscellaneous" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['miscellaneous'])) { echo $_POST['miscellaneous']; } ?>"/> 
   <label style="font-size:16px">Others:</label>
   <input name="others" type="text" size="3" align="left" style="font-size:12px" readonly value="<?php if(isset($_POST['others'])) { echo $_POST['others']; } ?>"/> 
   <br/><br/>
   <input type="checkbox" class="leftPaneCheckbox" name="workDone" <?= (isset($_POST['workDone']) ? strcmp($_POST["workDone"], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label><strong>DONE</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
   <input type="checkbox" class="leftPaneCheckbox" name="partialWork" <?= (isset($_POST['partialWork']) ? strcmp($_POST['partialWork'], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label><strong>PARTIAL</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
   <input type="checkbox" class="leftPaneCheckbox" name="nothingDone" <?= (isset($_POST['nothingDone']) ? strcmp($_POST['nothingDone'], "") ? 'checked' : '' : '') ?> onclick="return false"/>
   <label><strong>NOTHING DONE</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
   <label><strong>Supervisor Initials</strong></label>
   <input name="supervisorInitials" type="text" size="5" align="left" style="font-size:18px" readonly value="<?php if(isset($_POST['supervisorInitials'])) { echo $_POST['supervisorInitials']; } ?>"/>
   </div>
   
   <div align="left">
   <textarea name="extras" id="extras" cols="100" rows="4" style="font-size:12px;font-weight:bold">EXTRAS: <?php if(isset($_POST['extras'])) { echo $_POST['extras']; } ?></textarea>
   </div>
   
   <script type="text/javascript">
   	window.print();
   	document.getElementsByName("actionCost")[0].value = '<?php if(isset($actionCost) && ($actionCost > 0)) { echo $actionCost; } ?>';
   	document.getElementsByName("date")[0].value = '<?php if(isset($actionCost) && ($actionCost > 0)) { echo $timesheetDate; } ?>';
   </script>
   
   </form>
   </div>
</fieldset>
</body>

</html>