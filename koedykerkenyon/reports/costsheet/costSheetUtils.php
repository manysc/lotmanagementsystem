<?php
include '../../employee/employee.php';
include 'costsheetDAO.php';

$action='';
$outputMessage='';

if(isset($_POST['clearCostSheet'])) {
	clearCostSheet();
}

function clearCostSheet() {
	$_POST["builder"] = "";
	$_POST["subdivision"] = "";
	$_POST["lot"] = "";
	$_POST["selectedAction"] = "";
	global $outputMessage;
	$outputMessage="";
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
		return TRUE;
	}
	return FALSE;
}

function displayWorkers() {
	if(!isset($_POST["clearCostSheet"])) {
		if(validateBuilder() && validateSubdivision() && validateLot() && validateAction()) {
			$costsheetDAO = new costsheetDAO();
			$costsheetDAO->connect();
			$costsheetDAO->displayWorkers();
			$costsheetDAO->disconnect();
		}
	}
}

?>