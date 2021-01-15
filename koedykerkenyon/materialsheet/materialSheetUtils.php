<?php

include 'materialSheetDAO.php';
include 'materialCalculations.php';
include '../vendors/vendor.php';
include '../layout/layout.php';

$materialOutputMessage='';
$orderDate=0;
$builder='';
$subdivision='';
$lot='';
$supervisor='';
$vendor='';

// Open Layout from Material Sheet.
if(isset($_POST['layout'])) {
	$url = '../layout/layouts.php?lot=' . $_POST['builder'] . ',' . $_POST['subdivision'] . ',' . $_POST['lot'];
	echo "<script>window.location='" . $url . "'</script>";
}

// Open Material Sheet in print view.
if(isset($_POST['print'])) {
	$urlBuilder = str_replace('&','sAnd', $_POST['builder']);
   $urlBuilder = str_replace(' ',':',$urlBuilder);
   $urlSubdivision = str_replace('&','sAnd', $_POST['subdivision']);
   $urlSubdivision = str_replace(' ',':',$urlSubdivision);
   $url = 'materialSheetPrint.php?lot=' . $_POST["orderDate"] . ',' . $urlBuilder . ',' . $urlSubdivision . ',' .
      $_POST['lot'] . ',' . $_POST['poNumber'] . ',search';
	echo "<script>window.open('" . $url . "', '_blank')</script>";
}

if (isset($_POST['searchMaterialSheet']) || isset($_POST['print'])) {
 	searchMaterialSheet();
} else if (isset($_POST['saveMaterialSheet'])) {
   saveMaterialSheet();
} else if(isset($_POST['deleteMaterialSheet'])) {
  	deleteMaterialSheet();
} else if(isset($_POST['clearMaterialSheet'])) {
  	clearMaterialSheet();
}

function displayDefaultMaterialsTable() {
   echo '<table id="materialsTable" style="border:5px solid black;" width="90%" border="5" cellpadding="10" cellspacing="1" class="db-table">';
   echo '<tr><th style="border:0px solid black;">Qty</th><th style="border:0px solid black;">Unit/Measure</th><th style="border:0px solid black;">Description</th><th style="border:0px solid black;">Unit Price</th><th style="border:0px solid black;">Amount</th></tr>';

   for ($i = 0; $i < 8; $i++) {
	  echo '<tr>';
	  echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'></td>";
     echo "<td align='center' style='font-size:23px' width='20%'></td>";
	  echo "<td align='left' style='font-size:23px' width='40%'></td>";
	  echo "<td align='center' style='font-size:23px' width='15%'></td>";
	  echo "<td align='center' style='font-size:23px' width='15%'></td>";
	  echo "</tr>";
   }
   echo "</table>";
}

function displayMaterialsTable() {
   global $materialOutputMessage;
   global $deliveryCharge;
   global $blockColor;

   global $fullBlockQuantity;
   global $aBlockQuantity;
   global $hBlockQuantity;
   global $lBlockQuantity;
   global $bwTBlockNeeded;
   global $capsQuantity;
   global $latterWireQuantity;
   global $decosQuantity;
   global $block4Quantity;
   global $palletsQuantity;

   global $vendorBlockPrice;
   global $vendorABlockPrice;
   global $vendorHBlockPrice;
   global $vendorLBlockPrice;
   global $vendorTBlockPrice;
   global $vendorCapsPrice;
   global $vendorLatterWirePrice;
   global $vendorDecoBlockPrice;
   global $vendorBlock4Price;
   global $vendorPalletsPrice;

   global $fullBlockAmount;
   global $aBlockAmount;
   global $hBlockAmount;
   global $lBlockAmount;
   global $tBlockAmount;
   global $capsAmount;
   global $latterWireAmount;
   global $decosAmount;
   global $block4Amount;
   global $palletsAmount;

   echo '<table id="materialsTable" style="border:5px solid black;" width="90%" border="5" cellpadding="1" cellspacing="0" class="db-table">';
   echo '<tr><th style="border:0px solid black;">Qty</th><th style="border:0px solid black;">Unit/Measure</th><th style="border:0px solid black;">Description</th><th style="border:0px solid black;">Unit Price</th><th style="border:0px solid black;">Amount</th></tr>';

   if ($fullBlockQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $fullBlockQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>4X8X16 T & G - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorBlockPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $fullBlockAmount . "</td>";
      echo "</tr>";
   }

   if ($aBlockQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $aBlockQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>A BLOCK - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorABlockPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $aBlockAmount . "</td>";
      echo "</tr>";
   }

   if ($hBlockQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $hBlockQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>H BLOCK - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorHBlockPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $hBlockAmount . "</td>";
      echo "</tr>";
   }

   if ($lBlockQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $lBlockQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>L BLOCK - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorLBlockPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $lBlockAmount . "</td>";
      echo "</tr>";
   }

   if ($bwTBlockNeeded > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $bwTBlockNeeded . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>T BLOCK - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorTBlockPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $tBlockAmount . "</td>";
      echo "</tr>";
   }

   if ($capsQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $capsQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>8X2X16 CAPS - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorCapsPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $capsAmount . "</td>";
      echo "</tr>";
   }

   if ($latterWireQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $latterWireQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>4\" LATTER WIRE</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorLatterWirePrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $latterWireAmount . "</td>";
      echo "</tr>";
   }

   if ($decosQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $decosQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>8X4X16 DECOS - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorDecoBlockPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $decosAmount . "</td>";
      echo "</tr>";
   }

   if ($block4Quantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $block4Quantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'>Each</td>";
      echo "<td align='left' style='font-size:23px' width='40%'>4X4X16 BLOCK - " . $blockColor . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorBlock4Price . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $block4Amount . "</td>";
      echo "</tr>";
   }

   if ($palletsQuantity > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>" . $palletsQuantity . "</td>";
      echo "<td align='center' style='font-size:23px' width='20%'></td>";
      echo "<td align='left' style='font-size:23px' width='40%'>PALLETS</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $vendorPalletsPrice . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $palletsAmount . "</td>";
      echo "</tr>";
   }

   if ($deliveryCharge > 0) {
      echo '<tr>';
      echo "<td align='center' style='border:1px solid black;font-size:23px;height:40px;' width='10%'>1</td>";
      echo "<td align='center' style='font-size:23px' width='20%'></td>";
      echo "<td align='left' style='font-size:23px' width='40%'>DELIVERY CHARGE</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $deliveryCharge . "</td>";
      echo "<td align='center' style='font-size:23px' width='15%'>" . $deliveryCharge . "</td>";
      echo "</tr>";
   }

   echo "</table>";
}

function validateOrderDate() {
	if (!empty($_POST)) {
		global $materialOutputMessage;

		if (!empty($_POST["orderDate"])) {
			global $orderDate;
			$orderDate=$_POST["orderDate"];
			if(empty($orderDate)) {
				$materialOutputMessage='Please enter Order Date.';
				return FALSE;
			}
			return TRUE;
		} else {
			$materialOutputMessage='Please enter Order Date.';
			return FALSE;
		}
	}
	return FALSE;
}

function validateBuilder() {
   global $materialOutputMessage;
	if (!empty($_POST)) {
		if (!empty($_POST["builder"])) {
			global $builder;
			$builder=$_POST["builder"];
			
			if(empty($builder)) {
				$materialOutputMessage='Please enter Builder.';
				return FALSE;
			}
			return TRUE;
		} else {
			$materialOutputMessage='Please enter Builder.';
			return FALSE;
		}
	}
	return FALSE;
}

function validateSubdivision() {
	global $materialOutputMessage;
	if (!empty($_POST["subdivision"])) {
		global $subdivision;
		$subdivision=$_POST["subdivision"];
		
		if(empty($subdivision)) {
			$materialOutputMessage='Please enter Subdivision.';
			return FALSE;
		}
		return TRUE;
	} else {
		$materialOutputMessage='Please enter Subdivision.';
		return FALSE;
	}
	return FALSE;
}

function validateLot() {
	global $materialOutputMessage;
	if (!empty($_POST["lot"])) {
		global $lot;
		$lot=$_POST["lot"];
		
		if(empty($lot)) {
			$materialOutputMessage='Please enter Lot #.';
			return FALSE;
		}
		return TRUE;
	} else {
		$materialOutputMessage='Please enter Lot #.';
		return FALSE;
	}
	return FALSE;
}

function validatePONumber() {
	global $materialOutputMessage;
	if (!empty($_POST["poNumber"])) {
		global $poNumber;
		$poNumber=$_POST["poNumber"];

		if(empty($poNumber)) {
			$materialOutputMessage='Please enter PO #.';
			return FALSE;
		}
		return TRUE;
	} else {
		$materialOutputMessage='Please enter PO #.';
		return FALSE;
	}
	return FALSE;
}

function getMaterialSheetFields() {
   global $materialOutputMessage;

   global $vendor;
   global $shipTo;
   global $billTo;
   global $shipVia;
   global $deliveryCharge;
   global $dateReceived;
   global $dateRequired;
   global $extraAddedPercentage;
   global $blockColor;
   global $salesTaxPercentage;
   global $block4Quantity;
   global $palletsQuantity;

   $blockColor = '';
   if (!empty($_POST["blockColor"])) {
      $blockColor = $_POST["blockColor"];
   } else {
      $materialOutputMessage = "Please select a Block color.";
      return FALSE;
   }

   $vendor = getVendor();
   if (!is_null($vendor)) {
      global $blockColor;
      global $vendorBlockPrice;
      global $vendorABlockPrice;
      global $vendorHBlockPrice;
      global $vendorLBlockPrice;
      global $vendorTBlockPrice;
      global $vendorCapsPrice;
      global $vendorLatterWirePrice;
      global $vendorDecoBlockPrice;
      global $vendorBlock4Price;
      global $vendorPalletsPrice;

      if (strcasecmp($blockColor,'GRAY') == 0) {
         $vendorBlockPrice = $vendor->getBlockPrice();
         $vendorABlockPrice = $vendor->getABlockPrice();
         $vendorHBlockPrice = $vendor->getHBlockPrice();
         $vendorLBlockPrice = $vendor->getLBlockPrice();
         $vendorTBlockPrice = $vendor->getTBlockPrice();
         $vendorCapsPrice = $vendor->getCapsPrice();
         $vendorDecoBlockPrice = $vendor->getDecoBlockPrice();
         $vendorBlock4Price = $vendor->getBlock4Price();
      } else if (strcasecmp($blockColor,'COLOR') == 0) {
         $vendorBlockPrice = $vendor->getColorBlockPrice();
         $vendorABlockPrice = $vendor->getAColorBlockPrice();
         $vendorHBlockPrice = $vendor->getHColorBlockPrice();
         $vendorLBlockPrice = $vendor->getLColorBlockPrice();
         $vendorTBlockPrice = $vendor->getTColorBlockPrice();
         $vendorCapsPrice = $vendor->getCapsColorPrice();
         $vendorDecoBlockPrice = $vendor->getDecoColorBlockPrice();
         $vendorBlock4Price = $vendor->getBlockColor4Price();
      }

      $vendorLatterWirePrice = $vendor->getLatterWirePrice();
      $vendorPalletsPrice = $vendor->getPalletsPrice();
   } else {
      return FALSE;
   }

   $shipTo = $_POST["shipTo"];
   $billTo = $_POST["billTo"];
   $shipVia = $_POST["shipVia"];

   $deliveryCharge = 0;
   if (!empty($_POST["deliveryCharge"])) {
      $deliveryCharge = round($_POST["deliveryCharge"], 2);
   }

   $dateReceived = $_POST["dateReceived"];
   $dateRequired = $_POST["dateRequired"];

   $extraAddedPercentage = 0;
   if (!empty($_POST["extraAddedPercentage"])) {
      $extraAddedPercentage = $_POST["extraAddedPercentage"];
   }

   $salesTaxPercentage = 0;
   if (!empty($_POST["salesTaxPercentage"])) {
      $salesTaxPercentage = $_POST["salesTaxPercentage"];
   }

   $block4Quantity = 0;
   if (!empty($_POST["block4"])) {
      $block4Quantity = $_POST["block4"];
   }

   $palletsQuantity = 0;
   if (!empty($_POST["pallets"])) {
      $palletsQuantity = $_POST["pallets"];
   }

   return TRUE;
}

function updateMaterialSheetFields() {
   global $vendorName;
   global $shipTo;
   global $billTo;
   global $shipVia;
   global $deliveryCharge;
   global $dateReceived;
   global $dateRequired;
   global $extraAddedPercentage;
   global $blockColor;
   global $block4Quantity;
   global $palletsQuantity;
   global $materialSubTotal;
   global $salesTaxPercentage;
   global $salesTax;
   global $materialTotal;

   $_POST["vendorSelect"] = $vendorName;
   $_POST["shipTo"] = $shipTo;
   $_POST["billTo"] = $billTo;
   $_POST["shipVia"] = $shipVia;
   $_POST["deliveryCharge"] = $deliveryCharge;
   $_POST["dateReceived"] = $dateReceived;
   $_POST["dateRequired"] = $dateRequired;
   $_POST["extraAddedPercentage"] = $extraAddedPercentage;
   $_POST["blockColor"] = $blockColor;
   $_POST["block4"] = $block4Quantity;
   $_POST["pallets"] = $palletsQuantity;

   $_POST["materialSubTotal"] = $materialSubTotal;
   $_POST["salesTaxPercentage"] = $salesTaxPercentage;
   $_POST["salesTax"] = $salesTax;
   $_POST["materialTotal"] = $materialTotal;
}

function validateMaterialSheet() {
   if(validateOrderDate() && validateBuilder() && validateSubdivision() && validateLot() && validatePONumber()) {
      return getMaterialSheetFields();
   }
   return FALSE;
}

function getLayout() {
   global $materialOutputMessage;
   global $builder;
   global $subdivision;
   global $lot;
   $layout = null;

   if(validateBuilder() && validateSubdivision() && validateLot()) {
      $materialSheetDAO = new materialSheetDAO();
      $materialSheetDAO->connect();
      $layout = $materialSheetDAO->getLayout($builder, $subdivision, $lot);
      $materialSheetDAO->disconnect();
   }

   return $layout;
}

function getVendor() {
   global $materialOutputMessage;
   global $vendorName;

   $vendorName = '';
   if (!empty($_POST["vendorSelect"])) {
      $vendorName = $_POST["vendorSelect"];
   } else {
      $materialOutputMessage = "Please select a Vendor.";
   }

   $vendor = null;
   if (!empty($vendorName)) {
      $materialSheetDAO = new materialSheetDAO();
      $materialSheetDAO->connect();
      $vendor = $materialSheetDAO->getVendor($vendorName);
      $materialSheetDAO->disconnect();
   }

   return $vendor;
}

function calculateMaterials($layout) {
   calculateBackWallMaterials($layout);
   calculateLeftWallMaterials($layout);
   calculateRightWallMaterials($layout);
   calculateLeftReturnWallMaterials($layout);
   calculateRightReturnWallMaterials($layout);
   calculateMaterialQuantities();
   calculateMaterialTotals();
}

function searchMaterialSheet() {
    if(validateOrderDate() && validateBuilder() && validateSubdivision() && validateLot() && validatePONumber()) {
		$dbInt=new materialSheetDAO();
		$dbInt->connect();
		$dbInt->searchMaterialSheet();
		$dbInt->disconnect();

		updateMaterialSheetFields();
	}
}

function saveMaterialSheet() {
   if(validateMaterialSheet()) {
      $layout = getLayout();
      if (!is_null($layout)) {
         calculateMaterials($layout);

         $dbInt=new materialSheetDAO();
         $dbInt->connect();
         $dbInt->saveMaterialSheet();
         $dbInt->disconnect();

         updateMaterialSheetFields();
      }
	}
}

function deleteMaterialSheet() {
   if(validateMaterialSheet()) {
      $dbInt=new materialSheetDAO();
      $dbInt->connect();
      if ($dbInt->deleteMaterialSheet()) {
         clearMaterialSheet();
      }
      $dbInt->disconnect();
	}
}

function clearMaterialSheet() {
   $_POST["orderDate"] = "";
	$_POST["builder"] = "";
	$_POST["subdivision"] = "";
	$_POST["lot"] = "";
	$_POST["supervisor"] = "";
	$_POST["poNumber"] = "";

	$_POST["vendorSelect"] = "Select a Vendor:";
	$_POST["shipTo"] = "";
	$_POST["billTo"] = "";
	$_POST["shipVia"] = "";
	$_POST["deliveryCharge"] = "";
	$_POST["dateReceived"] = "";
	$_POST["dateRequired"] = "";
	$_POST["extraAddedPercentage"] = "";
	$_POST["blockColor"] = "";
	$_POST["block4"] = "";
	$_POST["pallets"] = "";

	$_POST["materialSubTotal"] = "";
	$_POST["salesTaxPercentage"] = "";
	$_POST["salesTax"] = "";
	$_POST["materialTotal"] = "";
}

function displayMaterials() {
	if(isset($_POST["clearMaterialSheet"]) || isset($_POST["deleteMaterialSheet"])) {
		displayDefaultMaterialsTable();
		return;
	}
	
	if(validateBuilder() && validateSubdivision() && validateLot()) {
      # TODO Display only if Material Sheet already exists.
      displayMaterialsTable();
	} else {
		displayDefaultMaterialsTable();
	}
}

?>