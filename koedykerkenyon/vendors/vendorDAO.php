<?php

class VendorDAO {
   public $con=null;
   
   function connect() {
	   global $databaseHostname;
		global $databaseId;
		global $databasePassword;
		global $databaseName;
		
		$this->con=mysqli_connect($databaseHostname,$databaseId,$databasePassword,$databaseName);
		
		if (mysqli_connect_errno($this->con)) {
		   $outputMessage="Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}

	function displayVendorNames() {
      global $outputMessage;
      global $databaseName;

      $sql = "select * from " . $databaseName . ".vendors ORDER BY name ASC";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if($row_cnt > 0) {
         while($rows = mysqli_fetch_array($records)) {
            if (!empty($_POST["vendorSelect"])) {
               if(!strcmp($_POST["vendorSelect"], $rows['name'])) {
                  echo '<option value="' . $rows['name'] . '" selected>' . $rows['name'] . '</option>';
                  continue;
               }
            }
            echo '<option value="' . $rows['name'] . '">' . $rows['name'] . '</option>';
         }
      } else {
         echo '<option>No Vendors found!</option>';
      }
   }

	function displayExistingVendors() {
      global $outputMessage;
      global $databaseName;

      echo '<table class="vendorsTable">';

      echo '<tr>';
      echo '<th>Name</th>';
      echo '<th>GRAY 4x8x16 T & G</th>';
      echo '<th>GRAY A Block</th>';
      echo '<th>GRAY H Block</th>';
      echo '<th>GRAY L Block</th>';
      echo '<th>GRAY T Block</th>';
      echo '<th>GRAY Deco Block</th>';
      echo '<th>GRAY 4X4X16</th>';
      echo '<th>GRAY 8X2X16 Caps</th>';
      echo '<th>4" Latter Wire</th>';
      echo '<th>Pallets</th>';
      echo '<th>GRAY 4x8x16 T & G</th>';
      echo '<th>COLOR A Block</th>';
      echo '<th>COLOR H Block</th>';
      echo '<th>COLOR L Block</th>';
      echo '<th>COLOR T Block</th>';
      echo '<th>COLOR Deco Block</th>';
      echo '<th>COLOR 4X4X16</th>';
      echo '<th>COLOR 8X2X16 Caps</th>';
      echo '</tr>';

      // Populate table from DB.
      $sql = "select * from " . $databaseName . ".vendors ORDER BY name ASC";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if($row_cnt > 0) {
        $count = 0; # Used for managing row background color.
        while($rows = mysqli_fetch_array($records)) {
           echo '<tr>';
           # Set different background color for odd rows.
           if ($count % 2 != 0) {
              echo '<td style="background-color: #80EBCD;">' . $rows['name'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_4x8x16'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_a_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_h_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_l_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_t_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_deco_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_4x4x16'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['gray_8x2x16_caps'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['4_latter_wire'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['pallets'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_4x8x16'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_a_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_h_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_l_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_t_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_deco_block'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_4x4x16'] . '</td>';
              echo '<td style="background-color: #80EBCD;">' . $rows['color_8x2x16_caps'] . '</td>';
           } else {
              echo '<td>' . $rows['name'] . '</td>';
              echo '<td>' . $rows['gray_4x8x16'] . '</td>';
              echo '<td>' . $rows['gray_a_block'] . '</td>';
              echo '<td>' . $rows['gray_h_block'] . '</td>';
              echo '<td>' . $rows['gray_l_block'] . '</td>';
              echo '<td>' . $rows['gray_t_block'] . '</td>';
              echo '<td>' . $rows['gray_deco_block'] . '</td>';
              echo '<td>' . $rows['gray_4x4x16'] . '</td>';
              echo '<td>' . $rows['gray_8x2x16_caps'] . '</td>';
              echo '<td>' . $rows['4_latter_wire'] . '</td>';
              echo '<td>' . $rows['pallets'] . '</td>';
              echo '<td>' . $rows['color_4x8x16'] . '</td>';
              echo '<td>' . $rows['color_a_block'] . '</td>';
              echo '<td>' . $rows['color_h_block'] . '</td>';
              echo '<td>' . $rows['color_l_block'] . '</td>';
              echo '<td>' . $rows['color_t_block'] . '</td>';
              echo '<td>' . $rows['color_deco_block'] . '</td>';
              echo '<td>' . $rows['color_4x4x16'] . '</td>';
              echo '<td>' . $rows['color_8x2x16_caps'] . '</td>';
           }

           echo '</tr>';

           $count += 1;
        }
      } else {
        if(strlen($outputMessage) > 0) {
            $outputMessage = $outputMessage . '\n';
        }
        $outputMessage = $outputMessage . 'No vendors found.';
      }

      echo "</table>";
   }
   
   function displayExistingVendorsOld() {
      global $outputMessage;
      global $databaseName;
      
      echo '<div style="overflow-x:auto;">';
      echo '<table border="0" id="vendorsTable" width="100%" border="5" cellpadding="5" cellspacing="5" class="db-table" style="text-align: center;">';

      echo '<tr>';
      echo '<th width="15%" style="border-bottom:3px solid black;border-top:3px solid black;">Name</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY 4x8x16 T & G</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY A Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY H Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY L Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY T Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY Deco Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY 4X4X16</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY 8X2X16 Caps</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">4" Latter Wire</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">Pallets</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">GRAY 4x8x16 T & G</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR A Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR H Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR L Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR T Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR Deco Block</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR 4X4X16</th>';
      echo '<th style="border-bottom:3px solid black; border-top:3px solid black;">COLOR 8X2X16 Caps</th>';
      echo '</tr>';
      
      // Populate table from DB.
      $sql = "select * from " . $databaseName . ".vendors";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if($row_cnt > 0) {
        while($rows = mysqli_fetch_array($records)) {
           echo '<tr>';
           echo '<th width="15%" style="border-bottom:1px solid black; font-weight:normal">' . $rows['name'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_4x8x16'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_a_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_h_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_l_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_t_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_deco_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_4x4x16'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['gray_8x2x16_caps'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['4_latter_wire'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['pallets'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_4x8x16'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_a_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_h_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_l_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_t_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_deco_block'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_4x4x16'] . '</th>';
           echo '<th style="border-bottom:1px solid black; font-weight:normal">' . $rows['color_8x2x16_caps'] . '</th>';
           echo '</tr>';
        }
      } else {
        if(strlen($outputMessage) > 0) {
            $outputMessage = $outputMessage . '\n';
        }
        $outputMessage = $outputMessage . 'No vendors found.';
      }
      
      echo "</table>";
      echo '</div>';
   }

   function searchVendor() {
      global $outputMessage;
      global $databaseName;
      global $vendorName;

      $sql = "select * from " . $databaseName . ".vendors where `name`='" . $vendorName . "'";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if ($row_cnt > 0) {
         $rows = mysqli_fetch_array($records);
         $outputMessage="Vendor found!";
         $_POST["blockPrice"] = $rows['gray_4x8x16'];
         $_POST["aBlockPrice"] = $rows['gray_a_block'];
         $_POST["hBlockPrice"] = $rows['gray_h_block'];
         $_POST["lBlockPrice"] = $rows['gray_l_block'];
         $_POST["tBlockPrice"] = $rows['gray_t_block'];
         $_POST["decoBlockPrice"] = $rows['gray_deco_block'];
         $_POST["block4Price"] = $rows['gray_4x4x16'];
         $_POST["capsPrice"] = $rows['gray_8x2x16_caps'];
         $_POST["latterWirePrice"] = $rows['4_latter_wire'];
         $_POST["palletsPrice"] = $rows['pallets'];
         $_POST["colorBlockPrice"] = $rows['color_4x8x16'];
         $_POST["aColorBlockPrice"] = $rows['color_a_block'];
         $_POST["hColorBlockPrice"] = $rows['color_h_block'];
         $_POST["lColorBlockPrice"] = $rows['color_l_block'];
         $_POST["tColorBlockPrice"] = $rows['color_t_block'];
         $_POST["decoColorBlockPrice"] = $rows['color_deco_block'];
         $_POST["blockColor4Price"] = $rows['color_4x4x16'];
         $_POST["capsColorPrice"] = $rows['color_8x2x16_caps'];
      } else {
         $outputMessage=$vendorName . " not found in the list of vendors.";
      }
   }
   
   function saveVendor() {
      global $outputMessage;
      global $databaseName;

      global $vendorName;
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
      
      $sql = "select * from " . $databaseName . ".vendors where `name`='" . $vendorName . "'";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if ($row_cnt > 0) {
         // Update existing Vendor
         $rows = mysqli_fetch_array($records);
         $outputMessage='Pending to implement update vendor, - ' . $rows['name'];
         $sql = "update " . $databaseName . ".vendors set `gray_4x8x16`='" . $blockPrice . "', `gray_a_block`='" . $aBlockPrice . "', `gray_h_block`='"
               . $hBlockPrice . "', `gray_l_block`='" . $lBlockPrice . "', `gray_t_block`='" . $tBlockPrice . "', `gray_deco_block`='" . $decoBlockPrice . "', `gray_4x4x16`='"
               . $block4Price . "', `gray_8x2x16_caps`='" . $capsPrice . "', `4_latter_wire`='" . $latterWirePrice . "', `pallets`='"
               . $palletsPrice . "', `color_4x8x16`='" . $colorBlockPrice . "', `color_a_block`='" . $aColorBlockPrice . "', `color_h_block`='"
               . $hColorBlockPrice . "', `color_l_block`='" . $lColorBlockPrice . "', `color_t_block`='" . $tColorBlockPrice . "', `color_deco_block`='"
               . $decoColorBlockPrice . "', `color_4x4x16`='" . $blockColor4Price . "', `color_8x2x16_caps`='" . $capsColorPrice
               . "' where `name`='" . $vendorName . "'";
         if(mysqli_query($this->con, $sql)) {
            $outputMessage='Updated vendor ' . $vendorName . ' successfully.';
         } else {
            $outputMessage='Unable to update vendor ' . $vendorName . ': ' . mysqli_error($this->con);
         }
      } else {
         // Save new Vendor
         $sql = "INSERT INTO " . $databaseName . ".vendors (`name`, `gray_4x8x16`, `gray_a_block`, `gray_h_block`, `gray_l_block`, `gray_t_block`, `gray_deco_block`,
               `gray_4x4x16`, `gray_8x2x16_caps`, `4_latter_wire`, `pallets`, `color_4x8x16`, `color_a_block`, `color_h_block`, `color_l_block`, `color_t_block`,
               `color_deco_block`, `color_4x4x16`, `color_8x2x16_caps`)
            VALUES ('$vendorName', '$blockPrice', '$aBlockPrice', '$hBlockPrice', '$lBlockPrice', '$tBlockPrice', '$decoBlockPrice', '$block4Price', '$capsPrice',
               '$latterWirePrice', '$palletsPrice', '$colorBlockPrice', '$aColorBlockPrice', '$hColorBlockPrice', '$lColorBlockPrice', '$tColorBlockPrice',
               '$decoColorBlockPrice', '$blockColor4Price', '$capsColorPrice')";
         if(mysqli_query($this->con, $sql)) {
            $outputMessage='Saved vendor ' . $vendorName . ' successfully.';
         } else {
            $outputMessage='Unable to save vendor ' . $vendorName . ': ' . mysqli_error($this->con);
         }
      }
   }

   function deleteVendor() {
      global $outputMessage;
      global $databaseName;
      global $vendorName;

      $sql = "select * from " . $databaseName . ".vendors where `name`='" . $vendorName . "'";
      $records = mysqli_query($this->con, $sql);
      $row_cnt = mysqli_num_rows($records);
      if ($row_cnt > 0) {
         $sql = "delete from " . $databaseName . ".vendors where `name`='" . $vendorName . "'";
         if (mysqli_query($this->con, $sql)) {
            $outputMessage = 'Successfully deleted ' . $vendorName;
            clearVendor();
         } else {
            $outputMessage = 'Unable to delete vendor ' . $vendorName . ': ' . mysqli_error($this->con);
         }
      } else {
         $outputMessage = "Vendor was not found!";
      }
   }
   
   function disconnect() {
		mysqli_close($this->con);
	}
}

?>