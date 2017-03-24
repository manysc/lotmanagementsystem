<?php

include 'crewDAO.php';

$crewName='';
$workerName='';
$employeeName='';
$outputMessage='';

if(isset($_POST['clearCrew'])) {
	clearCrew();
} else if(isset($_POST['deleteCrew'])) {
	deleteCrew();
}

function validateName() {
	if (!empty($_POST)) {
		global $crewName;
		global $outputMessage;
		$crewName=$_POST["crewName"];
		if(empty($crewName)) {
			$outputMessage='Please enter Crew name.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateCrewName() {
	if (!empty($_POST)) {
		global $crewName;
		global $outputMessage;
		$crewName=$_POST["selectedCrew"];
		if(empty($crewName)) {
			$outputMessage='Please select Crew name.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateWorkerName() {
	if (!empty($_POST)) {
		global $crewName;
		global $workerName;
		global $outputMessage;
		
		$crewName=$_POST["selectedCrew"];
		$workerName = $_POST['selectedWorker'];
		
		if(empty($workerName)) {
			$outputMessage='Please select Worker name.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateEmployeeName() {
	if (!empty($_POST)) {
		global $crewName;
		global $employeeName;
		global $outputMessage;
		
		$crewName=$_POST["selectedCrew"];
		$employeeName = $_POST['selectedEmployee'];
		
		if(empty($employeeName)) {
			$outputMessage='Please select Employee name.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function displayCrewDropMenu() {
	$crewDAO = new crewDAO();
	$crewDAO->connect();
	$crewDAO->displayCrewDropMenu();
	$crewDAO->disconnect();
}

function displayCrewWorkers() {
	global $crewName;
	$crewName = $_GET['crewName'];
	$crewDAO = new crewDAO();
	$crewDAO->connect();
	$crewDAO->searchCrew();
	$crewDAO->disconnect();
}

function searchCrew() {
	if(validateCrewName()) {
		$crewDAO = new crewDAO();
		$crewDAO->connect();
		$crewDAO->searchCrew();
		$crewDAO->disconnect();
	}
}

function saveCrew() {
	if(validateName()) {
		$crewDAO = new crewDAO();
		$crewDAO->connect();
		$crewDAO->saveCrew();
		$crewDAO->disconnect();
	}
}

function deleteCrew() {
	if(validateCrewName()) {
		$crewDAO = new crewDAO();
		$crewDAO->connect();
		$crewDAO->deleteCrew();
		$crewDAO->disconnect();
	}
}

function clearCrew() {
	$_POST['crewName'] = '';
}

function clearWorker() {
	$_POST['selectedWorker'] = '';
}

function deleteWorker() {
	if(validateWorkerName()) {
		$crewDAO = new crewDAO();
		$crewDAO->connect();
		$crewDAO->deleteWorker();
		$crewDAO->disconnect();
	}
}

function addEmployee() {
	if(validateEmployeeName()) {
		$crewDAO = new crewDAO();
		$crewDAO->connect();
		$crewDAO->addEmployee();
		$crewDAO->disconnect();
	}
}

function displayWorkerDropMenu() {
	if(validateCrewName()) {
		$crewDAO = new crewDAO();
		$crewDAO->connect();
		$crewDAO->displayWorkerDropMenu();
		$crewDAO->disconnect();
	}
}

function displayEmployeeDropMenu() {
	$crewDAO = new crewDAO();
	$crewDAO->connect();
	$crewDAO->displayEmployeeDropMenu();
	$crewDAO->disconnect();
}

?>