<?php

include 'clipboardDAO.php';

$builder='';
$subdivision='';
$lotNumber='';
$footerDugDate='';
$footerSetDate='';
$footerPourDate='';
$blockDate='';
$wallCompleteDate='';
$groutAndCapsDate='';
$warrantyDate='';
$poDate='';
$invoiceDate='';
$mailOutDate='';
$comments='';
$outputMessage='';

if(isset($_POST['searchLotStatus'])) {
	searchLotStatus();
} else if(isset($_POST['saveLotStatus'])) {
	saveLotStatus();
} else if(isset($_POST['clearLotStatus'])) {
	clearLotStatus();
}

function validateBuilder() {
	if (!empty($_POST)) {
		global $builder;
		global $outputMessage;
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

function validateLot() {
	if (!empty($_POST)) {
		global $lotNumber;
		global $outputMessage;
		$lotNumber=$_POST["selectedLot"];
		
		if(empty($lotNumber)) {
			$outputMessage='Please enter Lot #.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function readLotStatus() {
	global $invoiceDate;
	global $mailOutDate;
	
	if(!empty($_POST["invoiceDate"])) {
		$invoiceDate = $_POST["invoiceDate"];
	}
	if(!empty($_POST["mailOutDate"])) {
		$mailOutDate = $_POST["mailOutDate"];
	}
}

function displayLotStatuses() {
	$validated = false;
	echo "<fieldset>";
		echo "<legend class='sectionLegend'>Lot Status</legend>";
		echo '<table style="border:5px solid black;" border-style:"outset" border="1" cellpadding="0" cellspacing="12" class="myTable">';
			echo '<tr><th>Lot #</th><th>Hand Dig</th><th>Dug</th><th>Set</th><th>Pour</th><th>Block</th><th>Wall Done</th>
				      <th>Grout & Caps</th><th>Warranty</th><th>PO</th><th>Invoice Date</th><th>Mail Out</th>
				  </tr>';
			if( !isset($_POST['clearLotStatus'])  && validateBuilder() && validateSubdivision()) {
				$clipboardDAO = new clipboardDAO();
				$clipboardDAO->connect();
				$clipboardDAO->displayLotStatus();
				$clipboardDAO->disconnect();
				$validated = true;
			}
		echo "</table>";
		if($validated) {
			$clipboardDAO = new clipboardDAO();
			$clipboardDAO->connect();
			$clipboardDAO->displayLotDropMenu();
			$clipboardDAO->disconnect();
		}
	echo "</fieldset><br />";
}

function searchLotStatus() {
	if(validateBuilder()) {
		validateSubdivision();
	}
}

function saveLotStatus() {
	if(validateBuilder() && validateSubdivision() && validateLot()) {
		readLotStatus();
		$clipboardDAO = new clipboardDAO();
		$clipboardDAO->connect();
		$clipboardDAO->saveLotStatus();
		$clipboardDAO->disconnect();
	}
}

function clearLotStatus() {
	$_POST["builder"] = '';
	$_POST["subdivision"] = '';
	$_POST["lotNumber"] = '';
	global $lotStatusText;
	$lotStatusText = '';
	$_POST["invoiceDate"] = '';
	$_POST["mailOutDate"] = '';
}

?>
