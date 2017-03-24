<?php

include '../employee/employee.php';
include 'timesheetDAO.php';

$outputMessage='';
$builder='';
$timesheetDate=0;
$subdivision='';
$lotNumber='';
$action='';
$timesheetCrew='';
$selectedEmployee='';
$forman='';
$actionCost=0;
$backWall=0;
$backWallLF=0;
$backWallActualLF=0;
$bwRetainerLF=0;
$bwRetainerActualLF=0;
$rbwActualCourses=0;
$rbwCourses=0;
$leftWall=0;
$leftWallLF=0;
$leftWallActualLF=0;
$lwRetainerLF=0;
$lwRetainerActualLF=0;
$rlwActualCourses=0;
$rlwCourses=0;
$rightWall=0;
$rightWallLF=0;
$rightWallActualLF=0;
$rwRetainerLF=0;
$rwRetainerActualLF=0;
$rrwActualCourses=0;
$rrwCourses=0;
$leftReturn=0;
$leftReturnLF=0;
$leftReturnActualLF=0;
$rightReturn=0;
$rightReturnLF=0;
$rightReturnActualLF=0;
$handDigDate=0;
$handDigLF=0;
$handPour=0;
$handPourLF=0;
$upHillSide=0;
$upHillSideLF=0;
$wheelBarrow=0;
$wheelBarrowLF=0;
$airCond=0;
$airCondLF=0;
$caps=0;
$capsLF=0;
$courtYard=0;
$courtYardLF=0;
$mailbox=0;
$mailboxLF=0;
$rockVeneer=0;
$rockVeneerSF=0;
$clean=0;
$footerDugDate='';
$footerSetTime='';
$footerPouredTime='';
$blockTime='';
$wallComplete=0;
$wallCompleteTime='';
$groutAndCaps=0;
$groutAndCapsTime='';
$warranty=0;
$warrantyTime='';
$poTime='';
$purchaseOrderNumber='';
$repair=0;
$groutRetainer=0;
$perimeterBWLF=0;
$perimeterLWLF=0;
$perimeterRWLF=0;
$perimeterBW=0;
$perimeterLW=0;
$perimeterRW=0;
$viewBWLF=0;
$viewLWLF=0;
$viewRWLF=0;
$viewBW=0;
$viewLW=0;
$viewRW=0;
$extensionBWLF=0;
$extensionLWLF=0;
$extensionRWLF=0;
$extensionBW=0;
$extensionLW=0;
$extensionRW=0;
$extras='';
$materialAtSite=0;
$waitForAnything=0;
$waitForAnythingTime='';
$concreteLate=0;
$cleanUp=0;
$concreteLateTime='';
$concrete='';
$cement='';
$rebar='';
$blockType='';
$lime='';
$miscellaneous='';
$grout='';
$others='';
$workDone='';
$partialWork='';
$nothingDone='';
$supervisorInitials='';
$workerTimes='';
$workerName='';
$workerList='';
$startTime='';
$stopTime='';
$totalTime='';
$layoutFound=0;

if(isset($_POST['layout'])) {
	$url = '../layout/layouts.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'];
	echo "<script>window.location='" . $url . "'</script>";
} 
if(isset($_POST['print'])) {
	$parsedAction = str_replace(' ',':', $_POST['selectedAction']);
	$parsedCrewName = str_replace(' ',':', $_POST['timesheetCrew']);
	$urlBuilder = str_replace('&','sAnd', $_POST['builder']);
	$urlBuilder = str_replace(' ',':',$urlBuilder);
	$urlSubdivision = str_replace('&','sAnd', $_POST['subdivision']);
	$urlSubdivision = str_replace(' ',':',$urlSubdivision);
	$url = 'timesheetPrint.php?lot=' . $_POST['timesheetDate'] . ',' . $urlBuilder . ','
            . $urlSubdivision . ',' . $_POST['lot'] . ',' . $parsedAction . ',' . $parsedCrewName;
	echo "<script>window.open('" . $url . "', '_blank')</script>";
	
} else if(isset($_POST['searchTimeSheet'])) {
	searchTimeSheet();
} else if(isset($_POST['saveTimeSheet'])) {
	saveTimeSheet();
} else if(isset($_POST['deleteTimeSheet'])) {
	deleteTimeSheet();
} else if(isset($_POST['clearTimeSheet'])) {
	clearTimeSheet();
} else if(isset($_POST['saveWorkerTime'])) {
	saveWorkerTimes();
} else if(isset($_POST['printTimeSheet'])) {
	include('timesheetPrint.php');
} else if(isset($_POST['addEmployee'])) {
	addEmployee();
} else if(isset($_POST['deleteEmployee'])) {
	deleteEmployee();
}

function validateDate() {
	if (!empty($_POST)) {
		global $outputMessage;
		
		if (!empty($_POST["timesheetDate"])) {
			global $timesheetDate;
			$timesheetDate=$_POST["timesheetDate"];
			if(empty($timesheetDate)) {
				$outputMessage='Please enter Date.';
				return FALSE;
			}
			return TRUE;
		} else {
			$outputMessage='Please enter Date.';
			return FALSE;
		}
	}
	return FALSE;
}

function validateBuilder() {
	if (!empty($_POST)) {
		global $outputMessage;
		if (!empty($_POST["builder"])) {
			global $builder;
			$builder=$_POST["builder"];
			
			if(empty($builder)) {
				$outputMessage='Please enter Builder.';
				return FALSE;
			}
			return TRUE;
		} else {
			$outputMessage='Please enter Builder.';
			return FALSE;
		}
	}
	return FALSE;
}

function validateSubdivision() {
	global $outputMessage;
	if (!empty($_POST["subdivision"])) {
		global $subdivision;
		$subdivision=$_POST["subdivision"];
		
		if(empty($subdivision)) {
			$outputMessage='Please enter Subdivision.';
			return FALSE;
		}
		return TRUE;
	} else {
		$outputMessage='Please enter Subdivision.';
		return FALSE;
	}
	return FALSE;
}

function validateLot() {
	global $outputMessage;
	if (!empty($_POST["lot"])) {
		global $lot;
		$lot=$_POST["lot"];
		
		if(empty($lot)) {
			$outputMessage='Please enter Lot #.';
			return FALSE;
		}
		return TRUE;
	} else {
		$outputMessage='Please enter Lot #.';
		return FALSE;
	}
	return FALSE;
}

function validateAction() {
	if (!empty($_POST)) {
		global $action;
		global $outputMessage;
		$action=$_POST["selectedAction"];
		
		if(empty($action)) {
			$outputMessage='Please enter Action.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateCrew() {
	if (!empty($_POST)) {
		global $timesheetCrew;
		global $outputMessage;
		$timesheetCrew = $_POST["timesheetCrew"];
		
		if(empty($timesheetCrew)) {
			$outputMessage='Please select Crew.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateEmployee() {
	if (!empty($_POST)) {
		global $selectedEmployee;
		global $outputMessage;
		
		$selectedEmployee = $_POST["selectedEmployee"];
		
		if(empty($selectedEmployee)) {
			$outputMessage='Please select Employee.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function displayDefaultWorkersTable() {
	for ($i = 0; $i < 5; $i++) {
		if($i == 0) {
			echo '<table id="workerTimesTable" style="border:5px solid black;" width="90%" border="5" cellpadding="10" cellspacing="1" class="db-table">';
			echo '<tr><th style="border:0px solid black;">Name</th><th style="border:0px solid black;">Start</th><th style="border:0px solid black;">End</th><th style="border:0px solid black;">Total</th><th style="border:0px solid black;">Cost</th></tr>';
		}
		echo '<tr>';
		echo "<td id='workerName' style='border:1px solid black;font-size:18px' width='40%' align='left' value=''></td>";
		if(!isset($_POST['print'])) {
			echo "<td align='center'><input placeholder='hh:mm' name='startTime' id='startTime' type='time' size='6' align='left' style='font-size:18px' value=''/></td>";
		} else {
			echo "<td align='center' style='font-size:18px'></td>";
		}
		if(!isset($_POST['print'])) {
			echo "<td align='center'><input placeholder='hh:mm' name='stopTime' id='stopTime' type='time' size='6' align='left' style='font-size:18px' value=''/></td>";
		} else {
			echo "<td align='center' style='font-size:18px'></td>";
		}
		echo "<td align='center' style='font-size:18px'></td>";
		echo "<td align='center' style='font-size:18px'></td>";
	    echo "</tr>";
	}
	
	echo "</table>";
}

function displayCrewDropMenu() {
	$dbInt=new timesheetDAO();
	$dbInt->connect();
	$dbInt->displayCrewDropMenu();
	$dbInt->disconnect();
}

function displayWorkers() {
	if(isset($_POST["clearTimeSheet"]) || isset($_POST["deleteTimeSheet"])) {
		displayDefaultWorkersTable();
		return;
	}
	
	if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction() && validateCrew()) {
		$dbInt=new timesheetDAO();
		$dbInt->connect();
		$dbInt->displayWorkers();
		$dbInt->disconnect();
	} else {
		displayDefaultWorkersTable();
	}
}

// Display Worker drop menu to add/delete Workers to timesheet.
function displayEmployeesToAddDelete() {
	$dbInt=new timesheetDAO();
	$dbInt->connect();
	$dbInt->displayEmployeesToAddDelete();
	$dbInt->disconnect();
}

function readTimeSheet() {
	global $timesheetDate;
	global $leftWallActualLF;
	global $lwRetainerActualLF;
	global $rlwActualCourses;
	global $leftReturnActualLF;
	global $backWallActualLF;
	global $bwRetainerActualLF;
	global $rbwActualCourses;
	global $rightWallActualLF;
	global $rwRetainerActualLF;
	global $rrwActualCourses;
	global $rightReturnActualLF;
	global $handDigDate;
	global $handDigLF;
	global $handPour;
	global $handPourLF;
	global $upHillSide;
	global $upHillSideLF;
	global $wheelBarrow;
	global $wheelBarrowLF;
	global $airCond;
	global $airCondLF;
	global $caps;
	global $capsLF;
	global $courtYard;
	global $courtYardLF;
	global $mailbox;
	global $mailboxLF;
	global $rockVeneer;
	global $rockVeneerSF;
	global $clean;
	global $purchaseOrderNumber;
	global $footerDugDate;
	global $footerSetTime;
	global $footerPouredTime;
	global $blockTime;
	global $wallComplete;
	global $wallCompleteTime;
	global $groutAndCaps;
	global $groutAndCapsTime;
	global $warranty;
	global $warrantyTime;
	global $poTime;
	global $repair;
	global $groutRetainer;
	global $perimeterBW;
	global $perimeterLW;
	global $perimeterRW;
	global $viewBW;
	global $viewLW;
	global $viewRW;
	global $extensionBW;
	global $extensionLW;
	global $extensionRW;
	global $extras;
	global $materialAtSite;
	global $waitForAnything;
	global $waitForAnythingTime;
	global $concreteLate;
	global $concreteLateTime;
	global $cleanUp;
	global $concrete;
	global $cement;
	global $rebar;
	global $blockType;
	global $lime;
	global $miscellaneous;
	global $grout;
	global $others;
	global $workDone;
	global $partialWork;
	global $nothingDone;
	global $supervisorInitials;
	
	if(!empty($_POST["timesheetDate"])) {
		$timesheetDate = $_POST["timesheetDate"];
	}
	if(!empty($_POST["leftWallActualLF"])) {
		$leftWallActualLF = $_POST["leftWallActualLF"];
	}
	if(!empty($_POST["lwRetainerActualLF"])) {
		$lwRetainerActualLF = $_POST["lwRetainerActualLF"];
	}
	if(!empty($_POST["rlwActualCourses"])) {
		$rlwActualCourses = $_POST["rlwActualCourses"];
	}
	if(!empty($_POST["leftReturnActualLF"])) {
		$leftReturnActualLF = $_POST["leftReturnActualLF"];
	}
	if(!empty($_POST["backWallActualLF"])) {
		$backWallActualLF = $_POST["backWallActualLF"];
	}
	if(!empty($_POST["bwRetainerActualLF"])) {
		$bwRetainerActualLF = $_POST["bwRetainerActualLF"];
	}
	if(!empty($_POST["rbwActualCourses"])) {
		$rbwActualCourses = $_POST["rbwActualCourses"];
	}
	if(!empty($_POST["rightWallActualLF"])) {
		$rightWallActualLF = $_POST["rightWallActualLF"];
	}
	if(!empty($_POST["rwRetainerActualLF"])) {
		$rwRetainerActualLF = $_POST["rwRetainerActualLF"];
	}
	if(!empty($_POST["rrwActualCourses"])) {
		$rrwActualCourses = $_POST["rrwActualCourses"];
	}
	if(!empty($_POST["rightReturnActualLF"])) {
		$rightReturnActualLF = $_POST["rightReturnActualLF"];
	}
	if(!empty($_POST["handDigDate"])) {
		$handDigDate = $_POST["handDigDate"];
	}
	if(!empty($_POST["handDigLF"])) {
		$handDigLF = $_POST["handDigLF"];
	}
	if(!empty($_POST["handPourLF"])) {
		$handPourLF = $_POST["handPourLF"];
	}
	if(!empty($_POST["upHillSide"])) {
		$upHillSide = true;
	}
	if(!empty($_POST["upHillSideLF"])) {
		$upHillSideLF = $_POST["upHillSideLF"];
	}
	if(!empty($_POST["wheelBarrow"])) {
		$wheelBarrow = true;
	}
	if(!empty($_POST["wheelBarrowLF"])) {
		$wheelBarrowLF = $_POST["wheelBarrowLF"];
	}
	if(!empty($_POST["airCond"])) {
		$airCond = true;
	}
	if(!empty($_POST["airCondLF"])) {
		$airCondLF = $_POST["airCondLF"];
	}
	if(!empty($_POST["caps"])) {
		$caps = true;
	}
	if(!empty($_POST["capsLF"])) {
		$capsLF = $_POST["capsLF"];
	}
	if(!empty($_POST["courtYard"])) {
		$courtYard = true;
	}
	if(!empty($_POST["courtYardLF"])) {
		$courtYardLF = $_POST["courtYardLF"];
	}
	if(!empty($_POST["mailbox"])) {
		$mailbox = true;
	}
	if(!empty($_POST["mailboxLF"])) {
		$mailboxLF = $_POST["mailboxLF"];
	}
	if(!empty($_POST["rockVeneer"])) {
		$rockVeneer = true;
	}
	if(!empty($_POST["rockVeneerSF"])) {
		$rockVeneerSF = $_POST["rockVeneerSF"];
	}
	if(!empty($_POST["clean"])) {
		$clean = true;
	}
	if(!empty($_POST["purchaseOrderNumber"])) {
		$purchaseOrderNumber = $_POST["purchaseOrderNumber"];
	}
	if(!empty($_POST["footerDugDate"])) {
		$footerDugDate = $_POST["footerDugDate"];
	}
	if(!empty($_POST["footerSetTime"])) {
		$footerSetTime = $_POST["footerSetTime"];
	}
	if(!empty($_POST["footerPouredTime"])) {
		$footerPouredTime = $_POST["footerPouredTime"];
	}
	if(!empty($_POST["blockTime"])) {
		$blockTime = $_POST["blockTime"];
	}
	if(!empty($_POST["wallComplete"])) {
		$wallComplete = true;
	}
	if(!empty($_POST["wallCompleteTime"])) {
		$wallCompleteTime = $_POST["wallCompleteTime"];
	}
	if(!empty($_POST["groutAndCaps"])) {
		$groutAndCaps = true;
	}
	if(!empty($_POST["groutAndCapsTime"])) {
		$groutAndCapsTime = $_POST["groutAndCapsTime"];
	}
	if(!empty($_POST["warranty"])) {
		$warranty = true;
	}
	if(!empty($_POST["warrantyTime"])) {
		$warrantyTime = $_POST["warrantyTime"];
	}
	if(!empty($_POST["poTime"])) {
		$poTime = $_POST["poTime"];
	}
	if(!empty($_POST["repair"])) {
		$repair = true;
	}
	if(!empty($_POST["groutRetainer"])) {
		$groutRetainer = true;
	}
	if(!empty($_POST["perimeterBW"])) {
		$perimeterBW = $_POST["perimeterBW"];
	}
	if(!empty($_POST["perimeterLW"])) {
		$perimeterLW = $_POST["perimeterLW"];
	}
	if(!empty($_POST["perimeterRW"])) {
		$perimeterRW = $_POST["perimeterRW"];
	}
	if(!empty($_POST["viewBW"])) {
		$viewBW = $_POST["viewBW"];
	}
	if(!empty($_POST["viewLW"])) {
		$viewLW = $_POST["viewLW"];
	}
	if(!empty($_POST["viewRW"])) {
		$viewRW = $_POST["viewRW"];
	}
	if(!empty($_POST["extensionBW"])) {
		$extensionBW = $_POST["extensionBW"];
	}
	if(!empty($_POST["extensionLW"])) {
		$extensionLW = $_POST["extensionLW"];
	}
	if(!empty($_POST["extensionRW"])) {
		$extensionRW = $_POST["extensionRW"];
	}
	if(!empty($_POST["extras"])) {
		$extras = $_POST["extras"];
	}
	if(!empty($_POST["materialAtSite"])) {
		$materialAtSite = true;
	}
	if(!empty($_POST["waitForAnything"])) {
		$waitForAnything = true;
	}
	if(!empty($_POST["waitForAnythingTime"])) {
		$waitForAnythingTime = $_POST["waitForAnythingTime"];
	}
	if(!empty($_POST["concreteLate"])) {
		$concreteLate = true;
	}
	if(!empty($_POST["concreteLateTime"])) {
		$concreteLateTime = $_POST["concreteLateTime"];
	}
	if(!empty($_POST["cleanUp"])) {
		$cleanUp = true;
	}
	if(!empty($_POST["concrete"])) {
		$concrete = $_POST["concrete"];
	}
	if(!empty($_POST["cement"])) {
		$cement = $_POST["cement"];
	}
	if(!empty($_POST["rebar"])) {
		$rebar = $_POST["rebar"];
	}
	if(!empty($_POST["blockType"])) {
		$blockType = $_POST["blockType"];
	}
	if(!empty($_POST["lime"])) {
		$lime = $_POST["lime"];
	}
	if(!empty($_POST["miscellaneous"])) {
		$miscellaneous = $_POST["miscellaneous"];
	}
	if(!empty($_POST["grout"])) {
		$grout = $_POST["grout"];
	}
	if(!empty($_POST["others"])) {
		$others = $_POST["others"];
	}
	if(!empty($_POST["workDone"])) {
		$workDone = true;
	}
	if(!empty($_POST["partialWork"])) {
		$partialWork = true;
	}
	if(!empty($_POST["nothingDone"])) {
		$nothingDone = true;
	}
	if(!empty($_POST["supervisorInitials"])) {
		$supervisorInitials = $_POST["supervisorInitials"];
	}
}

function readWorkerTime() {
	global $startTime;
	global $stopTime;
	
	if(!empty($_POST["startTime"])) {
		$startTime = $_POST["startTime"];
	}
	if(!empty($_POST["stopTime"])) {
		$stopTime = $_POST["stopTime"];
	}
}

function searchTimeSheet() {
	if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction() && validateCrew()) {
		$dbInt=new timesheetDAO();
		$dbInt->connect();
		$dbInt->searchTimeSheet();
		$dbInt->disconnect();
	}
}

function saveTimeSheet() {
	if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction() && validateCrew()) {
		readTimeSheet();
		$dbInt=new timesheetDAO();
		$dbInt->connect();
		$dbInt->saveTimeSheet();
		$dbInt->disconnect();
	}
}

function deleteTimeSheet() {
	if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction() && validateCrew()) {
		$dbInt=new timesheetDAO();
		$dbInt->connect();
		$dbInt->deleteTimeSheet();
		$dbInt->disconnect();
	}
}

function clearTimeSheet() {
	$_POST["timesheetDate"] = '';
	$_POST["builder"] = "";
	$_POST["subdivision"] = "";
	$_POST["lot"] = "";
	$_POST["selectedAction"] = "";
	$timesheetCrew = '';
	$_POST["timesheetCrew"] = "";
	$_POST["selectedEmployee"] = '';
	$_POST["courses"] = "";
	$_POST["supervisor"] = "";
	$_POST["forman"] = "";
	$_POST["backWall"] = "";
	$backWallLF = "";
	$_POST["leftWall"] = "";
	$leftWallLF = "";
	$_POST["rightWall"] = "";
	$rightWallLF = "";
	$_POST["bwRetainer"] = '';
	$bwRetainerLF = '';
	$rbwCourses = '';
	$_POST['bwRetainerActualLF'] = '';
	$_POST["lwRetainer"] = '';
	$lwRetainerLF = '';
	$rlwCourses = '';
	$_POST['lwRetainerActualLF'] = '';
	$_POST["rwRetainer"] = '';
	$rwRetainerLF = '';
	$rrwCourses = '';
	$_POST['rwRetainerActualLF'] = '';
	$_POST["leftReturn"] = "";
	$leftReturnLF = "";
	$_POST["rightReturn"] = "";
	$rightReturnLF = "";
	$_POST["backWallActualLF"] = "";
	$_POST["leftWallActualLF"] = "";
	$_POST["rightWallActualLF"] = "";
	$_POST["leftReturnActualLF"] = "";
	$_POST["rightReturnActualLF"] = "";
	$_POST["handDigDate"] = '';
	$_POST["handDigLF"] = "";
	$_POST["handPour"] = '';
	$_POST["handPourLF"] = "";
	$_POST["upHillSide"] = "";
	$_POST["upHillSideLF"] = "";
	$_POST["wheelBarrow"] = "";
	$_POST["wheelBarrowLF"] = "";
	$_POST["airCond"] = "";
	$_POST["airCondLF"] = "";
	$_POST["caps"] = "";
	$_POST["capsLF"] = "";
	$_POST["courtYard"] = "";
	$_POST["courtYardLF"] = "";
	$_POST["mailbox"] = "";
	$_POST["mailboxLF"] = "";
	$_POST["rockVeneer"] = "";
	$_POST["rockVeneerSF"] = "";
	$_POST["clean"] = "";
	$_POST["purchaseOrderNumber"] = "";;
	$_POST["footerDugDate"] = "";
	$_POST["footerSetTime"] = "";
	$_POST["footerPoured"] = "";
	$_POST["footerPouredTime"] = "";
	$_POST["blockTime"] = "";
	$_POST["wallComplete"] = "";
	$_POST["wallCompleteTime"] = "";
	$_POST["groutAndCaps"] = "";
	$_POST["groutAndCapsTime"] = "";
	$_POST["warranty"] = "";
	$_POST["warrantyTime"] = "";
	$_POST["poTime"] = "";
	$_POST["repair"] = "";
	$_POST["groutRetainer"] = "";
	$perimeterBWLF = '';
	$_POST["perimeterBW"] = "";
	$perimeterLWLF = '';
	$_POST["perimeterLW"] = "";
	$perimeterRWLF = '';
	$_POST["perimeterRW"] = "";
	$viewBWLF = '';
	$_POST["viewBW"] = "";
	$viewLWLF = '';
	$_POST["viewLW"] = "";
	$viewRWLF = '';
	$_POST["viewRW"] = "";
	$extensionBWLF = '';
	$_POST["extensionBW"] = "";
	$extensionLWLF = '';
	$_POST["extensionLW"] = "";
	$extensionRWLF = '';
	$_POST["extensionRW"] = "";
	$_POST["extras"] = "";
	$_POST["materialAtSite"] = "";
	$_POST["waitForAnything"] = "";
	$_POST["waitForAnythingTime"] = "";
	$_POST["concreteLate"] = "";
	$_POST["concreteLateTime"] = "";
	$_POST["cleanUp"] = "";
	$_POST["concrete"] = "";
	$_POST["cement"] = "";
	$_POST["rebar"] = "";
	$_POST["blockType"] = "";
	$_POST["lime"] = "";
	$_POST["miscellaneous"] = "";
	$_POST["grout"] = "";
	$_POST["others"] = "";
	$_POST["workDone"] = "";
	$_POST["partialWork"] = "";
	$_POST["nothingDone"] = "";
	$_POST["supervisorInitials"] = "";
	global $workerList;
	global $workerTimes;
	$workerTimes = '';
	$_POST["workerTimes"] = "";
	$_POST["workerName"] = "";
	$_POST["startTime"] = "";
	$_POST["stopTime"] = "";
	$_POST["totalTime"] = "";
	$_POST["actionCost"] = "";
	global $actionCost;
	$actionCost = 0;
	global $outputMessage;
	$outputMessage="";
}

function saveWorkerTimes() {
	global $workerTimes;
	$workerTimes = $_POST['workerTimes'];
	if(!empty($workerTimes) && strcmp($workerTimes, '')) {
		if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction() && validateCrew()) {
			$dbInt=new timesheetDAO();
			$dbInt->connect();
			$dbInt->saveWorkerTimes();
			$dbInt->disconnect();
		}
	}
}

function addEmployee() {
	if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction()
		 && validateCrew() && validateEmployee()) {
		$dbInt=new timesheetDAO();
		$dbInt->connect();
		$dbInt->addEmployee();
		$dbInt->disconnect();
	}
}

function deleteEmployee() {
	if(validateDate() && validateBuilder() && validateSubdivision() && validateLot() && validateAction()
		 && validateCrew() && validateEmployee()) {
		$dbInt=new timesheetDAO();
		$dbInt->connect();
		$dbInt->deleteEmployee();
		$dbInt->disconnect();
	}
}

?>