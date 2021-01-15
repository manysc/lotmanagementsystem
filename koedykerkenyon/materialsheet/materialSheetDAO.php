<?php

class materialSheetDAO {
   public $con=null;
   
   function connect() {
      global $materialOutputMessage;
	   global $databaseHostname;
		global $databaseId;
		global $databasePassword;
		global $databaseName;
		
		$this->con=mysqli_connect($databaseHostname,$databaseId,$databasePassword,$databaseName);
		
		if (mysqli_connect_errno($this->con)) {
		   $materialOutputMessage="Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}
   
   function searchMaterialSheet() {
      global $materialOutputMessage;
      global $databaseName;
		global $orderDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $supervisor;
		global $poNumber;

		global $vendorName;
      global $shipTo;
      global $billTo;
      global $shipVia;
      global $deliveryCharge;
      global $dateReceived;
      global $dateRequired;
      global $extraAddedPercentage;
      global $blockColor;
      global $materialSubTotal;
      global $salesTaxPercentage;
      global $salesTax;
      global $materialTotal;
      global $blockColor;

      global $fullBlockQuantity;
      global $vendorBlockPrice;
      global $fullBlockAmount;
      global $aBlockQuantity;
      global $vendorABlockPrice;
      global $aBlockAmount;
      global $hBlockQuantity;
      global $vendorHBlockPrice;
      global $hBlockAmount;
      global $lBlockQuantity;
      global $vendorLBlockPrice;
      global $lBlockAmount;
      global $bwTBlockNeeded;
      global $vendorTBlockPrice;
      global $tBlockAmount;
      global $capsQuantity;
      global $vendorCapsPrice;
      global $capsAmount;
      global $latterWireQuantity;
      global $vendorLatterWirePrice;
      global $latterWireAmount;
      global $decosQuantity;
      global $vendorDecoBlockPrice;
      global $decosAmount;
      global $block4Quantity;
      global $vendorBlock4Price;
      global $block4Amount;
      global $palletsQuantity;
      global $vendorPalletsPrice;
      global $palletsAmount;
      
      $sql = "select * from " . $databaseName . ".materialsheets where `order_date`='" . $orderDate . "' && `builder`='" .
         $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `po_number`='" . $poNumber . "'";
      $records = mysqli_query($this->con, $sql);

      /* determine number of rows result set */
      $row_cnt = mysqli_num_rows($records);

      if($row_cnt == 0) {
         $materialOutputMessage = "Material Sheet not found!";
         return FALSE;
      }

      # Get Supervisor from Layout.
      $layout = getLayout($builder, $subdivision, $lot);
      $supervisor = $layout->getSupervisor();

      $rows = mysqli_fetch_array($records);

      $vendorName = $rows['vendor_name'];
      $shipTo = $rows['ship_to'];
      $billTo = $rows['bill_to'];
      $shipVia = $rows['ship_via'];
      $deliveryCharge = $rows['delivery_charge'];
      $dateReceived = $rows['date_received'];
      $dateRequired = $rows['date_required'];
      $extraAddedPercentage = $rows['extra_added_percentage'];
      $blockColor = $rows['block_color'];
      $materialSubTotal = $rows['subtotal'];
      $salesTaxPercentage = $rows['sales_tax_percentage'];
      $salesTax = $rows['sales_tax'];
      $materialTotal = $rows['total'];
      $blockColor = $rows['block_color'];

      $fullBlockQuantity = $rows['4x8x16_block_quantity'];
      $vendorBlockPrice = $rows['4x8x16_block_price'];
      $fullBlockAmount = $rows['4x8x16_block_amount'];

      $aBlockQuantity = $rows['a_block_quantity'];
      $vendorABlockPrice = $rows['a_block_price'];
      $aBlockAmount = $rows['a_block_amount'];

      $hBlockQuantity = $rows['h_block_quantity'];
      $vendorHBlockPrice = $rows['h_block_price'];
      $hBlockAmount = $rows['h_block_amount'];

      $lBlockQuantity = $rows['l_block_quantity'];
      $vendorLBlockPrice = $rows['l_block_price'];
      $lBlockAmount = $rows['l_block_amount'];

      $bwTBlockNeeded = $rows['t_block_quantity'];
      $vendorTBlockPrice = $rows['t_block_price'];
      $tBlockAmount = $rows['t_block_amount'];

      $capsQuantity = $rows['8x2x16_caps_quantity'];
      $vendorCapsPrice = $rows['8x2x16_caps_price'];
      $capsAmount = $rows['8x2x16_caps_amount'];

      $latterWireQuantity = $rows['4_latter_wire_quantity'];
      $vendorLatterWirePrice = $rows['4_latter_wire_price'];
      $latterWireAmount = $rows['4_latter_wire_amount'];

      $decosQuantity = $rows['deco_block_quantity'];
      $vendorDecoBlockPrice = $rows['deco_block_price'];
      $decosAmount = $rows['deco_block_amount'];

      $block4Quantity = $rows['4x4x16_block_quantity'];
      $vendorBlock4Price = $rows['4x4x16_block_price'];
      $block4Amount = $rows['4x4x16_block_amount'];

      $palletsQuantity = $rows['pallets_quantity'];
      $vendorPalletsPrice = $rows['pallets_price'];
      $palletsAmount = $rows['pallets_amount'];

      $materialOutputMessage = "Material Sheet found!";

      return TRUE;
   }
   
   function saveMaterialSheet() {
      global $databaseName;
		global $orderDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $poNumber;
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

      global $fullBlockQuantity;
      global $aBlockQuantity;
      global $hBlockQuantity;
      global $lBlockQuantity;
      global $bwTBlockNeeded;
      global $decosQuantity;
      global $block4Quantity;
      global $capsQuantity;
      global $latterWireQuantity;
      global $palletsQuantity;

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

      global $materialSubTotal;
      global $salesTaxPercentage;
      global $salesTax;
      global $materialTotal;
      
      // Update existing material sheet, otherwise create new one.
      $layout = getLayout($builder, $subdivision, $lot);

      if (is_null($layout)) {
         $materialOutputMessage = 'No Layout found for Builder:'. $builder . ' Subdivision:' . $subdivision . ' Lot:' . $lot;
         return;
      }

      if (is_null($vendor)) {
         $materialOutputMessage = 'No Vendor found!';
         return;
      }

      $vendorName = $vendor->getName();
      $latterWirePrice = $vendor->getLatterWirePrice();
      $palletsPrice = $vendor->getPalletsPrice();

      if (strcasecmp($blockColor,'GRAY') == 0) {
         $blockPrice = $vendor->getBlockPrice();
         $aBlockPrice = $vendor->getABlockPrice();
         $hBlockPrice = $vendor->getHBlockPrice();
         $lBlockPrice = $vendor->getLBlockPrice();
         $tBlockPrice = $vendor->getTBlockPrice();
         $capsPrice = $vendor->getCapsPrice();
         $decoBlockPrice = $vendor->getDecoBlockPrice();
         $block4Price = $vendor->getBlock4Price();
      } else if (strcasecmp($blockColor,'COLOR') == 0) {
         $blockPrice = $vendor->getColorBlockPrice();
         $aBlockPrice = $vendor->getAColorBlockPrice();
         $hBlockPrice = $vendor->getHColorBlockPrice();
         $lBlockPrice = $vendor->getLColorBlockPrice();
         $tBlockPrice = $vendor->getTColorBlockPrice();
         $capsPrice = $vendor->getCapsColorPrice();
         $decoBlockPrice = $vendor->getDecoColorBlockPrice();
         $block4Price = $vendor->getBlockColor4Price();
      }

      // Check if material sheet already exists, otherwise, insert new one.
      $sql = "select * from " . $databaseName . ".materialsheets where `order_date`='" . $orderDate . "' && `builder`='" .
         $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `po_number`='" . $poNumber . "'";
      $records = mysqli_query($this->con, $sql);

      /* determine number of rows result set */
      $row_cnt = mysqli_num_rows($records);

      if($row_cnt == 0) {
         $sql = "INSERT INTO " . $databaseName . ".materialsheets (`order_date`, `builder`, `subdivision`, `lot`, `po_number`,
            `vendor_name`, `ship_to`, `bill_to`, `ship_via`, `delivery_charge`, `date_received`, `date_required`, `extra_added_percentage`,
            `block_color`, `4x8x16_block_quantity`, `4x8x16_block_price`, `4x8x16_block_amount`, `a_block_quantity`, `a_block_price`,
            `a_block_amount`, `h_block_quantity`, `h_block_price`, `h_block_amount`, `l_block_quantity`, `l_block_price`, `l_block_amount`,
            `t_block_quantity`, `t_block_price`, `t_block_amount`, `deco_block_quantity`, `deco_block_price`, `deco_block_amount`,
            `4x4x16_block_quantity`, `4x4x16_block_price`, `4x4x16_block_amount`, `8x2x16_caps_quantity`, `8x2x16_caps_price`,
            `8x2x16_caps_amount`, `4_latter_wire_quantity`, `4_latter_wire_price`, `4_latter_wire_amount`, `pallets_quantity`,
            `pallets_price`, `pallets_amount`, `subtotal`, `sales_tax_percentage`, `sales_tax`, `total`)
            VALUES ('$orderDate', '$builder', '$subdivision', '$lot', '$poNumber', '$vendorName', '$shipTo', '$billTo', '$shipVia',
            '$deliveryCharge', '$dateReceived', '$dateRequired', '$extraAddedPercentage', '$blockColor' , '$fullBlockQuantity',
            '$blockPrice', '$fullBlockAmount', '$aBlockQuantity', '$aBlockPrice', '$aBlockAmount', '$hBlockQuantity', '$hBlockPrice',
            '$hBlockAmount', '$lBlockQuantity', '$lBlockPrice', '$lBlockAmount', '$bwTBlockNeeded', '$tBlockPrice', '$tBlockAmount',
            '$decosQuantity', '$decoBlockPrice', '$decosAmount', '$block4Quantity', '$block4Price', '$block4Amount', '$capsQuantity',
            '$capsPrice', '$capsAmount', '$latterWireQuantity', '$latterWirePrice', '$latterWireAmount', '$palletsQuantity', '$palletsPrice',
            '$palletsAmount', '$materialSubTotal', '$salesTaxPercentage', '$salesTax', '$materialTotal')";

         if(mysqli_query($this->con, $sql)) {
            $materialOutputMessage = 'Saved Material Sheet successfully.';
         } else {
            $materialOutputMessage = 'Unable to save Material Sheet: ' . mysqli_error($this->con);
         }
      } else {
         // Update existing Material Sheet.
         $sql = "update " . $databaseName . ".materialsheets set `vendor_name`='" . $vendorName . "', `ship_to`='" . $shipTo . "', `bill_to`='" . $billTo
            . "', `ship_via`='" . $shipVia . "', `delivery_charge`='" . $deliveryCharge . "', `date_received`='" . $dateReceived
            . "', `date_required`='" . $dateRequired . "', `extra_added_percentage`='" . $extraAddedPercentage
            . "', `block_color`='" . $blockColor . "', `4x8x16_block_quantity`='" . $fullBlockQuantity
            . "', `4x8x16_block_price`='" . $blockPrice . "', `4x8x16_block_amount`='" . $fullBlockAmount
            . "', `a_block_quantity`='" . $aBlockQuantity . "', `a_block_price`='" . $aBlockPrice . "', `a_block_amount`='" . $aBlockAmount
            . "', `h_block_quantity`='" . $hBlockQuantity . "', `h_block_price`='" . $hBlockPrice . "', `h_block_amount`='" . $hBlockAmount
            . "', `l_block_quantity`='" . $lBlockQuantity . "', `l_block_price`='" . $lBlockPrice . "', `l_block_amount`='" . $lBlockAmount
            . "', `t_block_quantity`='" . $bwTBlockNeeded . "', `t_block_price`='" . $tBlockPrice . "', `t_block_amount`='" . $tBlockAmount
            . "', `deco_block_quantity`='" . $decosQuantity . "', `deco_block_price`='" . $decoBlockPrice . "', `deco_block_amount`='" . $decosAmount
            . "', `4x4x16_block_quantity`='" . $block4Quantity . "', `4x4x16_block_price`='" . $block4Price . "', `4x4x16_block_amount`='" . $block4Amount
            . "', `8x2x16_caps_quantity`='" . $capsQuantity . "', `8x2x16_caps_price`='" . $capsPrice . "', `8x2x16_caps_amount`='" . $capsAmount
            . "', `4_latter_wire_quantity`='" . $latterWireQuantity . "', `4_latter_wire_price`='" . $latterWirePrice . "', `4_latter_wire_amount`='" . $latterWireAmount
            .  "', `pallets_quantity`='" . $palletsQuantity .  "', `pallets_price`='" . $palletsPrice .  "', `pallets_amount`='" . $palletsAmount
            .   "', `subtotal`='" . $materialSubTotal .  "', `sales_tax_percentage`='" . $salesTaxPercentage . "', `sales_tax`='" . $salesTax . "', `total`='" . $materialTotal
            . "' where `order_date`='" . $orderDate . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision
            . "' && `lot`='" . $lot . "' && `po_number`='" . $poNumber . "'";
         if(mysqli_query($this->con, $sql)) {
            $materialOutputMessage = 'Updated Material Sheet successfully.';
         } else {
            $materialOutputMessage = 'Unable to update Material Sheet: ' . mysqli_error($this->con);
         }
      }
   }

   function deleteMaterialSheet() {
      global $databaseName;
      global $orderDate;
      global $builder;
      global $subdivision;
      global $lot;
      global $poNumber;
      global $materialOutputMessage;

      $sql = "select * from " . $databaseName . ".materialsheets where `order_date`='" . $orderDate . "' && `builder`='" .
         $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `po_number`='" . $poNumber . "'";

      $records = mysqli_query($this->con, $sql);

      /* determine number of rows result set */
      $row_cnt = mysqli_num_rows($records);
      if($row_cnt == 0) {
         $materialOutputMessage= "Material Sheet was not found!";
         return FALSE;
      }

      $rows = mysqli_fetch_array($records);

      $sql = "delete from " . $databaseName . ".materialsheets where `order_date`='" . $orderDate . "' && `builder`='" .
         $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `po_number`='" . $poNumber . "'";
      mysqli_query($this->con, $sql);
      $materialOutputMessage = 'Deleted Material Sheet for ' . $builder . " " . $subdivision . " " . $lot;

      return TRUE;
   }

   # ENHANCE Move this function to layoutDAO.php. Need more time to fix references when moving this function to layoutDAO.php.
   function getLayout($aBuilder, $aSubdivision, $aLot) {
      global $materialOutputMessage;
      global $databaseName;
      global $builder;
      global $subdivision;
      global $lot;

      $sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
      		       $subdivision . "' && `lot`='" . $lot . "'";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if ($row_cnt > 0) {
         $rows = mysqli_fetch_array($records);

         $layout = new Layout();
         $layout->setBuilder($rows['builder']);
         $layout->setSubdivision($rows['subdivision']);
         $layout->setLot($rows['lot']);
         $layout->setSupervisor($rows['supervisor']);
         $layout->setCourses($rows['courses']);
         $layout->setGate($rows['gate']);
         $layout->setEndLot($rows['endLot']);
         $layout->setBackWall($rows['lotBW']);
         $layout->setLeftWall($rows['lotLW']);
         $layout->setRightWall($rows['lotRW']);
         $layout->setLeftReturn($rows['returnLR']);
         $layout->setRightReturn($rows['returnRR']);

         // Update Material Sheet Form.
         $_POST["supervisor"] = $layout->getSupervisor();

         return $layout;
      } else {
        $materialOutputMessage = "Layout was not found!";
      }
   }

   # ENHANCE Move this function to vendorDAO.php. Need more time to fix references when moving this function to vendorDAO.php.
   function getVendor($aVendor) {
      global $materialOutputMessage;
      global $databaseName;

      $sql = "select * from " . $databaseName . ".vendors where `name`='" . $aVendor . "'";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if ($row_cnt > 0) {
         $rows = mysqli_fetch_array($records);

         $vendor = new Vendor();
         $vendor->setName($rows['name']);

         $vendor->setBlockPrice($rows['gray_4x8x16']);
         $vendor->setABlockPrice($rows['gray_a_block']);
         $vendor->setHBlockPrice($rows['gray_h_block']);
         $vendor->setLBlockPrice($rows['gray_l_block']);
         $vendor->setTBlockPrice($rows['gray_t_block']);
         $vendor->setDecoBlockPrice($rows['gray_deco_block']);
         $vendor->setBlock4Price($rows['gray_4x4x16']);
         $vendor->setCapsPrice($rows['gray_8x2x16_caps']);

         $vendor->setLatterWirePrice($rows['4_latter_wire']);
         $vendor->setPalletsPrice($rows['pallets']);

         $vendor->setColorBlockPrice($rows['color_4x8x16']);
         $vendor->setAColorBlockPrice($rows['color_a_block']);
         $vendor->setHColorBlockPrice($rows['color_h_block']);
         $vendor->setLColorBlockPrice($rows['color_l_block']);
         $vendor->setTColorBlockPrice($rows['color_t_block']);
         $vendor->setDecoColorBlockPrice($rows['color_deco_block']);
         $vendor->setBlockColor4Price($rows['color_4x4x16']);
         $vendor->setCapsColorPrice($rows['color_8x2x16_caps']);

         return $vendor;
      } else {
         $materialOutputMessage = "Vendor " . $aVendor . " was not found!";
      }
   }
   
   function disconnect() {
		mysqli_close($this->con);
   }
}

?>