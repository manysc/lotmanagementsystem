<?php

include 'monitorDAO.php';

$selectedForman='';
$cameraName='';
$outputMessage='';

if(isset($_POST['saveForman'])) {
	saveForman();
} else if(isset($_POST['deleteForman'])) {
	deleteForman();
} else if(isset($_POST['clearForman'])) {
	clearForman();
} 

function saveForman() {
	if(validateForman() && validateCameraName()) {
		$monitorDAO = new monitorDAO();
		$monitorDAO->connect();
		$monitorDAO->saveForman();
		$monitorDAO->disconnect();
	}
}

function deleteForman() {
	if(validateForman()) {
		$monitorDAO = new monitorDAO();
		$monitorDAO->connect();
		$monitorDAO->deleteForman();
		$monitorDAO->disconnect();
	}
}

function clearForman() {
	global $selectedForman;
	global $cameraName;
	global $outputMessage;
	
	$_POST["selectedForman"] = '';
	$_POST["cameraName"] = '';
	$selectedForman = '';
	$cameraName = '';
	$outputMessage = '';	
}

function displayFormanList() {
	echo "<fieldset>";
		echo '<table style="border:5px solid black; width:70%" border-style:"outset" border="1" cellpadding="0" cellspacing="12" class="myTable">';
			echo '<tr><th>Forman</th><th>Camera</th></tr>';
			$monitorDAO = new monitorDAO();
			$monitorDAO->connect();
			$monitorDAO->displayFormanList();
			$monitorDAO->disconnect();
		echo "</table>";
	echo "</fieldset><br />";
}

function displayFormanDropBox() {
	$monitorDAO = new monitorDAO();
	$monitorDAO->connect();
	$monitorDAO->displayFormanDropMenu();
	$monitorDAO->disconnect();
}

function validateForman() {
	if (!empty($_POST)) {
		global $selectedForman;
		global $outputMessage;
		$selectedForman = $_POST["selectedForman"];
		
		if(empty($selectedForman)) {
			$outputMessage='Please select a Forman.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateCameraName() {
	global $outputMessage;
	if (!empty($_POST["cameraName"])) {
		global $cameraName;
		$cameraName=$_POST["cameraName"];
		
		if(empty($cameraName)) {
			$outputMessage='Please enter Camera name.';
			return FALSE;
		}
		return TRUE;
	} else {
		$outputMessage='Please enter Camera name.';
		return FALSE;
	}
	return FALSE;
}

?>