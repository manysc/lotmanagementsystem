<?php

include 'vendorDAO.php';

$outputMessage='';
$vendorName='';

$blockPrice=0;
$aBlockPrice=0;
$hBlockPrice=0;
$lBlockPrice=0;
$tBlockPrice=0;
$decoBlockPrice=0;
$block4Price=0;
$capsPrice=0;

$latterWirePrice=0;
$palletsPrice=0;

$colorBlockPrice=0;
$aColorBlockPrice=0;
$hColorBlockPrice=0;
$lColorBlockPrice=0;
$tColorBlockPrice=0;
$decoColorBlockPrice=0;
$blockColor4Price=0;
$capsColorPrice=0;

if(isset($_POST['searchVendor'])) {
	searchVendor();
} else if(isset($_POST['saveVendor'])) {
   saveVendor();
} else if(isset($_POST['deleteVendor'])) {
   deleteVendor();
} else if(isset($_POST['clearVendor'])) {
   clearVendor();
}

function readVendorFields() {
   global $outputMessage;

   global $blockPrice;
   global $aBlockPrice;
   global $hBlockPrice;
   global $lBlockPrice;
   global $tBlockPrice;
   global $decoBlockPrice;
   global $block4Price;
   global $capsPrice;
   global $latterWirePrice;
   global $palletsPrice;
   global $colorBlockPrice;
   global $aColorBlockPrice;
   global $hColorBlockPrice;
   global $lColorBlockPrice;
   global $tColorBlockPrice;
   global $decoColorBlockPrice;
   global $blockColor4Price;
   global $capsColorPrice;

   if (!empty($_POST["blockPrice"])) {
      $blockPrice = $_POST["blockPrice"];
   }

   if (!empty($_POST["aBlockPrice"])) {
      $aBlockPrice = $_POST["aBlockPrice"];
   }

   if (!empty($_POST["hBlockPrice"])) {
      $hBlockPrice = $_POST["hBlockPrice"];
   }

   if (!empty($_POST["lBlockPrice"])) {
      $lBlockPrice = $_POST["lBlockPrice"];
   }

   if (!empty($_POST["tBlockPrice"])) {
      $tBlockPrice = $_POST["tBlockPrice"];
   }

   if (!empty($_POST["decoBlockPrice"])) {
      $decoBlockPrice = $_POST["decoBlockPrice"];
   }

   if (!empty($_POST["block4Price"])) {
      $block4Price = $_POST["block4Price"];
   }

   if (!empty($_POST["capsPrice"])) {
      $capsPrice = $_POST["capsPrice"];
   }

   if (!empty($_POST["latterWirePrice"])) {
      $latterWirePrice = $_POST["latterWirePrice"];
   }

   if (!empty($_POST["palletsPrice"])) {
      $palletsPrice = $_POST["palletsPrice"];
   }

   if (!empty($_POST["colorBlockPrice"])) {
      $colorBlockPrice = $_POST["colorBlockPrice"];
   }

   if (!empty($_POST["aColorBlockPrice"])) {
      $aColorBlockPrice = $_POST["aColorBlockPrice"];
   }

   if (!empty($_POST["hColorBlockPrice"])) {
      $hColorBlockPrice = $_POST["hColorBlockPrice"];
   }

   if (!empty($_POST["lColorBlockPrice"])) {
      $lColorBlockPrice = $_POST["lColorBlockPrice"];
   }

   if (!empty($_POST["tColorBlockPrice"])) {
      $tColorBlockPrice = $_POST["tColorBlockPrice"];
   }

   if (!empty($_POST["decoColorBlockPrice"])) {
      $decoColorBlockPrice = $_POST["decoColorBlockPrice"];
   }

   if (!empty($_POST["blockColor4Price"])) {
      $blockColor4Price = $_POST["blockColor4Price"];
   }

   if (!empty($_POST["capsColorPrice"])) {
      $capsColorPrice = $_POST["capsColorPrice"];
   }
}

function validateVendorName() {
	if (!empty($_POST)) {
		global $outputMessage;
		global $vendorName;

		if (!empty($_POST["vendorName"])) {
		   $vendorName=$_POST["vendorName"];
		   return TRUE;
      }
      
      $outputMessage='Please enter Vendor name.';
	}
	return FALSE;
}

function displayVendorNames() {
   $dbInt = new vendorDAO();
   $dbInt->connect();
   $dbInt->displayVendorNames();
   $dbInt->disconnect();
}

function displayExistingVendors() {
   $dbInt = new vendorDAO();
   $dbInt->connect();
   $dbInt->displayExistingVendors();
   $dbInt->disconnect();
}

function searchVendor() {
	if(validateVendorName()) {
   		$vendorDAO = new vendorDAO();
   		$vendorDAO->connect();
   		$vendorDAO->searchVendor();
   		$vendorDAO->disconnect();
   	}
}

function saveVendor() {
   if(validateVendorName()) {
      readVendorFields();
		$vendorDAO = new vendorDAO();
		$vendorDAO->connect();
		$vendorDAO->saveVendor();
		$vendorDAO->disconnect();
	}
}

function deleteVendor() {
	if(validateVendorName()) {
   		$vendorDAO = new vendorDAO();
   		$vendorDAO->connect();
   		$vendorDAO->deleteVendor();
   		$vendorDAO->disconnect();
   	}
}

function clearVendor() {
	$_POST["vendorName"] = "";
	$_POST["blockPrice"] = "";
   $_POST["aBlockPrice"] = "";
   $_POST["hBlockPrice"] = "";
   $_POST["lBlockPrice"] = "";
   $_POST["tBlockPrice"] = "";
   $_POST["decoBlockPrice"] = "";
   $_POST["block4Price"] = "";
   $_POST["capsPrice"] = "";
   $_POST["latterWirePrice"] = "";
   $_POST["palletsPrice"] = "";
   $_POST["colorBlockPrice"] = "";
   $_POST["aColorBlockPrice"] = "";
   $_POST["hColorBlockPrice"] = "";
   $_POST["lColorBlockPrice"] = "";
   $_POST["tColorBlockPrice"] = "";
   $_POST["decoColorBlockPrice"] = "";
   $_POST["blockColor4Price"] = "";
   $_POST["capsColorPrice"] = "";
}

?>