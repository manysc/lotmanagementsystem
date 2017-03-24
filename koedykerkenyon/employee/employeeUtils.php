<?php

include 'employeeDAO.php';

$outputMessage='';
$first='';
$last='';
$wage='';
$email='';
$crewName='';
$isSupervisor=0;
$isForman=0;
$isMason=0;
$isApprentice=0;
$isLabor=0;
$isDriver=0;
$isOperator=0;
$isFooter=0;

if(isset($_POST['searchEmployee'])) {
	searchEmployee();
} else if(isset($_POST['saveEmployee'])) {
	saveEmployee();
} else if(isset($_POST['deleteEmployee'])) {
	deleteEmployee();
} else if(isset($_POST['clearEmployee'])) {
	clearEmployee();
}

function validateName() {
	if (!empty($_POST)) {
		global $first;
		global $last;
		$first=$_POST["firstName"];
		$last=$_POST["lastName"];
		global $outputMessage;
		if(empty($first)) {
			$outputMessage='Please enter employee first name.';
			return FALSE;
		} 
		if(empty($last)) {
			$outputMessage='Please enter employee last name.';
			return FALSE;
		}
		
		return TRUE;
	}
	return FALSE;
}

function validateWage() {
	if (!empty($_POST)) {
		global $wage;
		global $outputMessage;
		$wage=$_POST["wage"];
		
		if(empty($wage)) {
			$outputMessage='Please enter employee wage.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateTitle() {
	if(!empty($_POST)) {
		global $isSupervisor;
		global $isForman;
		global $formanChecked;
		global $isMason;
		global $isApprentice;
		global $isLabor;
		global $isDriver;
		global $isOperator;
		global $isFooter;
		global $outputMessage;
		
		if(isset($_POST['supervisor'])) {
			$isSupervisor = true;
		}
		if(isset($_POST['forman'])) {
			$isForman = true;
			$formanChecked="checked";
		}
		if(isset($_POST['mason'])) {
			$isMason = true;
		}
		if(isset($_POST['apprentice'])) {
			$isApprentice = true;
		}
		if(isset($_POST['labor'])) {
			$isLabor = true;
		}
		if(isset($_POST['driver'])) {
			$isDriver = true;
		}
		if(isset($_POST['operator'])) {
			$isOperator = true;
		}
		
		if(isset($_POST['footer'])) {
			$isFooter = true;
		}
		
		if(!$isSupervisor && !$isForman && !$isMason && !$isApprentice && !$isLabor && !$isDriver && !$isOperator && !$isFooter) {
			$outputMessage='Please select employee title.';
			return FALSE;
		}
		
		return TRUE;
	}
	return FALSE;
}

function readEmail() {
	global $email;
	if(isset($_POST['email'])) {
		$email = $_POST['email'];
	}
}

function validateEmployee() {
	return validateName() && validateWage() && validateTitle();
}

function searchEmployee() {
	if(validateName()) {
		$employeeDAO = new employeeDAO();
		$employeeDAO->connect();
		$employeeDAO->searchEmployee();
		$employeeDAO->disconnect();
	}
}

function saveEmployee() {
	if(validateEmployee()) {
		readEmail();
		$employeeDAO = new employeeDAO();
		$employeeDAO->connect();
		$employeeDAO->saveEmployee();
		$employeeDAO->disconnect();
	}
}

function deleteEmployee() {
	if(validateName()) {
		$employeeDAO = new employeeDAO();
		$employeeDAO->connect();
		$employeeDAO->deleteEmployee();
		$employeeDAO->disconnect();
	}
}

function clearEmployee() {
	$_POST["firstName"] = "";
	$_POST["lastName"] = "";
	$_POST["wage"] = "";
	$_POST["email"] = "";
	$_POST["supervisor"] = "";
	$_POST["forman"] = "";
	$_POST["mason"] = "";
	$_POST["apprentice"] = "";
	$_POST["labor"] = "";
	$_POST["driver"] = "";
	$_POST["operator"] = "";
	$_POST["footer"] = "";
}

function clearEmployeeExcludingName() {
	$_POST["wage"] = "";
	$_POST["email"] = "";
	$_POST["supervisor"] = "";
	$_POST["forman"] = "";
	$_POST["mason"] = "";
	$_POST["apprentice"] = "";
	$_POST["labor"] = "";
	$_POST["driver"] = "";
	$_POST["operator"] = "";
	$_POST["footer"] = "";
}

?>
