<?php

include '../employee/employee.php';
include 'layoutDAO.php';
include '../common/fileUtils.php';

$outputMessage='';
$builder='';
$subdivision='';
$supervisor='';
$courses=0;
$lot=0;
$gate='';
$selectedTimesheetDate='';
$selectedTimesheetAction='';
$selectedTimesheetCrew='';
$endLot='';
$planImageFile='';
$comments='';
$retainerBW=0;
$rbwCourses=0;
$retainerLW=0;
$rlwCourses=0;
$retainerRW=0;
$rrwCourses=0;
$perimeterBW=0;
$perimeterLW=0;
$perimeterRW=0;
$viewBW=0;
$viewLW=0;
$viewRW=0;
$lotBW=0;
$lotLW=0;
$lotRW=0;
$returnLR=0;
$returnRR=0;
$extraPT=0;
$extraAC=0;
$extraCY=0;
$extraBW=0;
$extraLW=0;
$extraRW=0;
$grandTotals='';
$handPour=0;
$upHillSide=0;
$caps=0;
$wheelBarrow=0;
$mailbox=0;
$rockVeneer=0;
$repair=0;
$groutRetainer=0;
$crewName='';
$handDigCrewName='';
$dugCrewName='';
$setCrewName='';
$pourCrewName='';
$blockCrewName='';
$groutAndCapsCrewName='';
$backfillCrewName='';
$cleanCrewName='';
$warrantyCrewName='';
$pOCrewName='';
$formCleared=false;

//------------------------------------------------------------
// TODO Test if this can be removed.
if(isset($_POST['handDigTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Hand Dig';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['dugTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Dug';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['setTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Set';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['pourTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Pour';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['blockTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Block';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['groutAndCapsTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Grout and Caps';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['backfillTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Backfill';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['cleanTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Clean';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['warrantyTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'Warranty';
	echo "<script>window.location='" . $url . "'</script>";
} else if(isset($_POST['pOTimesheet'])) {
	$url = '../timesheet/masonryTimeSheet.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'] . ',' . 'PO';
	echo "<script>window.location='" . $url . "'</script>";
}
//------------------------------------------------------------ 
else if(isset($_POST['saveSelectedTimesheet'])) {
	saveSelectedTimesheet();
} else if(isset($_POST['deleteSelectedTimesheet'])) {
	deleteSelectedTimesheet();
} else if(isset($_POST['searchLayout'])) {
	searchLayout();
} else if(isset($_POST['saveLayout'])) {
	saveLayout();
} else if(isset($_POST['deleteLayout'])) {
	deleteLayout();
} else if(isset($_POST['clearLayout'])) {
	clearLayout();
} else if(isset($_POST['deletePlanImage'])) {
	deletePlanImage();
} else if(isset($_POST['updatePlanImage'])) {
    updatePlanImage();
}

function displayExistingMaterialSheets() {
	if(validateBuilder() && validateSubdivision() && validateLot()) {
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->displayExistingMaterialSheets();
		$layoutDAO->disconnect();
	}
}

function displayExistingTimesheets() {
	if(validateBuilder() && validateSubdivision() && validateLot()) {
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->displayExistingTimesheets();
		$layoutDAO->disconnect();
	}
}

function displayCrewDropMenu() {
	$layoutDAO=new layoutDAO();
	$layoutDAO->connect();
	$layoutDAO->displayCrewDropMenu();
	$layoutDAO->disconnect();
}

function validateBuilder() {
	if (!empty($_POST)) {
		global $builder;
		global $outputMessage;

		if(!isset($_POST["builder"])) {
		    $outputMessage='Please enter Builder.';
            return FALSE;
		}

		$builder=$_POST["builder"];
		
		if(empty($builder)) {
			$outputMessage='Please enter Builder.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateSubdivision() {
	if (!empty($_POST)) {
		global $subdivision;
		global $outputMessage;
		$subdivision=$_POST["subdivision"];
		
		if(empty($subdivision)) {
			$outputMessage='Please enter Subdivision.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateSupervisor() {
	if (!empty($_POST)) {
		global $supervisor;
		global $outputMessage;
		$supervisor=$_POST["supervisor"];
		
		if(empty($supervisor)) {
			$outputMessage='Please enter Supervisor.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateCourses() {
	if (!empty($_POST)) {
		global $courses;
		global $outputMessage;
		$courses=$_POST["courses"];
		
		if(empty($courses)) {
			$outputMessage='Please enter Courses.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateLot() {
	if (!empty($_POST)) {
		global $lot;
		global $outputMessage;
		$lot=$_POST["lot"];
		
		if(empty($lot)) {
			$outputMessage='Please enter Lot #.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateGate() {
	if (!empty($_POST)) {
		global $gate;
		global $outputMessage;
		$gate=$_POST["gate"];
		
		if(empty($gate)) {
			$outputMessage='Please enter Gate.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateSelectedDate() {
	if (!empty($_POST)) {
		global $selectedTimesheetDate;
		global $outputMessage;
		$selectedTimesheetDate = $_POST["selectedTimesheetDate"];
		
		if(!isset($selectedTimesheetDate)) {
			$outputMessage='Please select Timesheet Date.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateSelectedAction() {
	if (!empty($_POST)) {
		global $selectedTimesheetAction;
		global $outputMessage;
		$selectedTimesheetAction = $_POST["selectedTimesheetAction"];
		
		if(empty($selectedTimesheetAction)) {
			$outputMessage='Please select Timesheet Action.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateSelectedCrew() {
	if (!empty($_POST)) {
		global $selectedTimesheetCrew;
		global $outputMessage;
		$selectedTimesheetCrew= $_POST["selectedTimesheetCrew"];
		
		if(empty($selectedTimesheetCrew)) {
			$outputMessage='Please select Timesheet Crew.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function calculateGrandTotals() {
	global $retainerBW;
	global $retainerLW;
	global $retainerRW;
	global $perimeterBW;
	global $perimeterLW;
	global $perimeterRW;
	global $viewBW;
	global $viewLW;
	global $viewRW;
	global $lotBW;
	global $lotLW;
	global $lotRW;
	global $returnLR;
	global $returnRR;
	global $extraPT;
	global $extraAC;
	global $extraCY;
	global $extraBW;
	global $extraLW;
	global $extraRW;
	global $grandTotals;
	
	if(!empty($_POST["retainerBW"])) {
		$retainerBW = $_POST["retainerBW"];
	}
	
	if(!empty($_POST["retainerLW"])) {
		$retainerLW = $_POST["retainerLW"];
	}
	
	if(!empty($_POST["retainerRW"])) {
		$retainerRW = $_POST["retainerRW"];
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
	
	if(!empty($_POST["lotBW"])) {
		$lotBW = $_POST["lotBW"];
	}
	
	if(!empty($_POST["lotLW"])) {
		$lotLW = $_POST["lotLW"];
	}
	
	if(!empty($_POST["lotRW"])) {
		$lotRW = $_POST["lotRW"];
	}
	
	if(!empty($_POST["returnLR"])) {
		$returnLR = $_POST["returnLR"];
	}
	
	if(!empty($_POST["returnRR"])) {
		$returnRR = $_POST["returnRR"];
	}
	
	if(!empty($_POST["extraPT"])) {
		$extraPT = $_POST["extraPT"];
	}
	
	if(!empty($_POST["extraAC"])) {
		$extraAC = $_POST["extraAC"];
	}
	
	if(!empty($_POST["extraCY"])) {
		$extraCY = $_POST["extraCY"];
	}
	
	if(!empty($_POST["extraBW"])) {
		$extraBW = $_POST["extraBW"];
	}
	
	if(!empty($_POST["extraLW"])) {
		$extraLW = $_POST["extraLW"];
	}
	
	if(!empty($_POST["extraRW"])) {
		$extraRW = $_POST["extraRW"];
	}
	
	$grandTotals = $retainerBW + $retainerLW + $retainerRW + $perimeterBW + $perimeterLW + $perimeterRW + $viewBW
				   + $viewLW + $viewRW + $lotBW + $lotLW + $lotRW + $returnLR + $returnRR + $extraPT + $extraAC
				   + $extraCY + $extraBW + $extraLW + $extraRW;
}

function readCourses() {
	global $rbwCourses;
	global $rlwCourses;
	global $rrwCourses;
	
	if(isset($_POST['rbwCourses'])) {
		$rbwCourses = $_POST["rbwCourses"];
	}
	if(isset($_POST['rlwCourses'])) {
		$rlwCourses = $_POST["rlwCourses"];
	}
	if(isset($_POST['rrwCourses'])) {
		$rrwCourses = $_POST["rrwCourses"];
	}
}

function readWorkToBeDone() {
	global $handPour;
	global $upHillSide;
	global $caps;
	global $wheelBarrow;
	global $mailbox;
	global $rockVeneer;
	global $repair;
	global $groutRetainer;
	
	if(isset($_POST['handPour'])) {
		$handPour = true;
	}
	if(isset($_POST['upHillSide'])) {
		$upHillSide = true;
	}
	if(isset($_POST['caps'])) {
		$caps = true;
	}
	if(isset($_POST['wheelBarrow'])) {
		$wheelBarrow = true;
	}
	if(isset($_POST['mailbox'])) {
		$mailbox = true;
	}
	if(isset($_POST['rockVeneer'])) {
		$rockVeneer = true;
	}
	if(isset($_POST['repair'])) {
		$repair = true;
	}
	if(isset($_POST['groutRetainer'])) {
		$groutRetainer = true;
	}
}

function clearSelectedTimesheet() {
	$selectedTimesheetDate='';
	$_POST["selectedTimesheetDate"] = "";
	$selectedTimesheetAction='';
	$_POST["selectedTimesheetAction"] = "";
	$selectedTimesheetCrew='';
	$_POST["selectedTimesheetCrew"] = "";
}

function validateLayout() {
	if(validateBuilder() && validateSubdivision() && validateLot()) {
		global $supervisor;
		global $courses;
		global $gate;
		global $endLot;
		global $comments;
		
		global $handDigCrewName;
		global $dugCrewName;
		global $setCrewName;
		global $pourCrewName;
		global $blockCrewName;
		global $groutAndCapsCrewName;
		global $backfillCrewName;
		global $cleanCrewName;
		global $warrantyCrewName;
		global $pOCrewName;
		
		global $outputMessage;
		
		if(!empty($_POST["supervisor"])) {
			$supervisor = $_POST["supervisor"];
		}
		if(!empty($_POST["courses"])) {
			$courses = $_POST["courses"];
		}
		if(!empty($_POST["gate"])) {
			$gate = $_POST["gate"];
		}
		if(!empty($_POST["endLot"])) {
			$endLot = $_POST["endLot"];
		}
		if(!empty($_POST["comments"])) {
			$comments = $_POST["comments"];
		}
		
		calculateGrandTotals();
		
		readCourses();
		readWorkToBeDone();
		
		if(!empty($_POST["selectedHandDigCrew"])) {
			$handDigCrewName = $_POST["selectedHandDigCrew"];
		}

		if(!empty($_POST["selectedDugCrew"])) {
			$dugCrewName = $_POST["selectedDugCrew"];
		}
		
		if(!empty($_POST["selectedSetCrew"])) {
			$setCrewName = $_POST["selectedSetCrew"];
		}
		
		if(!empty($_POST["selectedPourCrew"])) {
			$pourCrewName = $_POST["selectedPourCrew"];
		}
		
		if(!empty($_POST["selectedBlockCrew"])) {
			$blockCrewName = $_POST["selectedBlockCrew"];
		}
		
		if(!empty($_POST["selectedGroutAndCapsCrew"])) {
			$groutAndCapsCrewName = $_POST["selectedGroutAndCapsCrew"];
		}
		
		if(!empty($_POST["selectedBackfillCrew"])) {
			$backfillCrewName = $_POST["selectedBackfillCrew"];
		}
		
		if(!empty($_POST["selectedCleanCrew"])) {
			$cleanCrewName = $_POST["selectedCleanCrew"];
		}
		
		if(!empty($_POST["selectedWarrantyCrew"])) {
			$warrantyCrewName = $_POST["selectedWarrantyCrew"];
		}
		
		if(!empty($_POST["selectedPOCrew"])) {
			$pOCrewName = $_POST["selectedPOCrew"];
		}
		
		return TRUE;
	}
	return FALSE;
}

function searchLayout() {
	if(validateBuilder() && validateSubdivision() && validateLot()) {
		readCourses();
		readWorkToBeDone();
		clearSelectedTimesheet();
		
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->searchLayout();
		$layoutDAO->disconnect();
	}
}

function saveLayout() {
	if(validateLayout()) {
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->saveLayout();
		$layoutDAO->disconnect();
	}
}

function deleteLayout() {
	if(validateBuilder() && validateSubdivision() && validateLot()) {
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->deleteLayout();
		$layoutDAO->disconnect();
	}
}

function saveSelectedTimesheet() {
	if(validateLayout() && validateSelectedDate() && validateSelectedAction() && validateSelectedCrew()) {
		// ENHANCE Use a common class for saving timesheet in layoutUtils and masonryTimeSheetUtils.
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->saveSelectedTimesheet();
		$layoutDAO->disconnect();
	}
}

function deleteSelectedTimesheet() {
	if(validateLayout() && validateSelectedDate() && validateSelectedAction() && validateSelectedCrew()) {
		// ENHANCE Use a common class for deleting timesheet in layoutUtils and masonryTimeSheetUtils.
		$layoutDAO = new layoutDAO();
		$layoutDAO->connect();
		$layoutDAO->deleteSelectedTimesheet();
		$layoutDAO->disconnect();
	}
}

function clearLayout() {
	$_POST["builder"] = "";
	$_POST["subdivision"] = "";
	$_POST["lot"] = "";
	$_POST["supervisor"] = "";
	$_POST["courses"] = "";
	$_POST["gate"] = "";
	$_POST["endLot"] = "";
	$_POST["planImageFile"] = "";
	$_POST["comments"] = "";
	$_POST["retainerBW"] = "";
	$_POST["rbwCourses"] = "";
	$_POST["retainerLW"] = "";
	$_POST["rlwCourses"] = "";
	$_POST["retainerRW"] = "";
	$_POST["rrwCourses"] = "";
	$_POST["perimeterBW"] = "";
	$_POST["perimeterLW"] = "";
	$_POST["perimeterRW"] = "";
	$_POST["viewBW"] = "";
	$_POST["viewLW"] = "";
	$_POST["viewRW"] = "";
	$_POST["lotBW"] = "";
	$_POST["lotLW"] = "";
	$_POST["lotRW"] = "";
	$_POST["returnLR"] = "";
	$_POST["returnRR"] = "";
	$_POST["extraPT"] = "";
	$_POST["extraAC"] = "";
	$_POST["extraCY"] = "";
	$_POST["extraBW"] = "";
	$_POST["extraLW"] = "";
	$_POST["extraRW"] = "";
	global $grandTotals;
	$grandTotals = "";
	$_POST["handPour"] = "";
	$_POST["upHillSide"] = "";
	$_POST["caps"] = "";
	$_POST["wheelBarrow"] = "";
	$_POST["courtYard"] = "";
	$_POST["mailbox"] = "";
	$_POST["rockVeneer"] = "";
	$_POST["repair"] = "";
	$_POST["groutRetainer"] = "";
	clearSelectedTimesheet();
	global $outputMessage;
	$outputMessage="";
	global $formCleared;
	$formCleared = true;
}

function deletePlanImage() {
    global $layout_plan_images_dir;
    global $planImageFile;
    global $uploadFileMessage;

    if(isset($_POST['planImageFile']) && !empty($_POST['planImageFile'])) {
        if(validateBuilder() && validateSubdivision() && validateLot()) {
            // Update DB
            $layoutDAO = new layoutDAO();
            $layoutDAO->connect();
            $layoutDAO->deletePlanImageFilename();
            $layoutDAO->disconnect();

            $planImageFile = $_POST['planImageFile'];

            // Delete file from file system
            deleteImage($layout_plan_images_dir, $planImageFile);

            // Update UI
            $_POST["planImageFile"] = "";
        } else {
            $uploadFileMessage = 'Layout Builder, Subdivision and/or Lot not set yet';
        }

    } else {
        $uploadFileMessage = 'Layout plan image is not set yet, unable to delete.';
    }
}

function updatePlanImage() {
    global $layout_plan_images_dir;
    global $planImageFile;

    if(validateBuilder() && validateSubdivision() && validateLot()) {
  	    if(uploadImage($layout_plan_images_dir, $planImageFile)) {
  	        // Update UI
  	        $_POST['planImageFile'] = $planImageFile;

  	        // Save image filename to database
            $layoutDAO = new layoutDAO();
            $layoutDAO->connect();
            $layoutDAO->updatePlanImageFilename();
            $layoutDAO->disconnect();
  	    }
  	}
}

?>