<?php

class clipboardDAO {
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
	
	function displayLotStatus() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $outputMessage;
		
		$layoutsQuery = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' order by CAST(lot as decimal)";
		$layoutRecords = mysqli_query($this->con, $layoutsQuery);
		$count = 0;
		$fromDate = $_POST['fromDate'];
		$toDate = $_POST['toDate'];
		$lotStatuses = array();
		while($layoutRows = mysqli_fetch_array($layoutRecords)){
			if(in_array($layoutRows['lot'], $lotStatuses)) {
				continue;
			}
			array_push($lotStatuses, $layoutRows['lot']);
			
			// Get data from lotstatuses table if it exists.
			$sql = "select * from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $layoutRows['lot'] . "'";
			$records = mysqli_query($this->con, $sql);

            while($rows = mysqli_fetch_array($records)){
                $existingLotStatus = $rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];

                // Get existing lotstatuses.
                if(strcmp($existingLotStatus, "  ")) {

                    $lotStatusDate = $rows['date'];
                    if($lotStatusDate != '') {
                        $lotStatusDate = new DateTime($lotStatusDate);
                    }

                    # Display within given dates.
                    if( isset($fromDate) && strcmp($fromDate, '')) {
                        $parsedFromDate = new DateTime($fromDate);
                        if( isset($toDate) && strcmp($toDate, '')) {
                            $parsedToDate = new DateTime($toDate);
                            if(($lotStatusDate < $parsedFromDate) || ($lotStatusDate >= $parsedToDate)) {
                                continue;
                            }
                        }
                    }

                    echo '<tr>';
                    $lotNumber = $rows['lot'];
                    echo "<td align='center'>$lotNumber</td>";
                    $handDigDate = $rows['hand_dig_date'];
                    if($handDigDate == 0) {
                        $handDigDate = '';
                    }
                    echo "<td align='center'>$handDigDate</td>";
                    $footerDugTime = $rows['footer_dug_date'];
                    if($footerDugTime == 0) {
                        $footerDugTime = '';
                    }
                    echo "<td align='center'>$footerDugTime</td>";
                    $footerSetTime = $rows['footer_set_date'];
                    if($footerSetTime == 0) {
                        $footerSetTime = '';
                    }
                    echo "<td align='center'>$footerSetTime</td>";
                    $footerPouredTime = $rows['footer_pour_date'];
                    if($footerPouredTime == 0) {
                        $footerPouredTime = '';
                    }
                    echo "<td align='center'>$footerPouredTime</td>";
                    $blockTime = $rows['block_date'];
                    if($blockTime == 0) {
                        $blockTime = '';
                    }
                    echo "<td align='center'>$blockTime</td>";
                    $wallCompleteTime = $rows['wall_complete_date'];
                    if($wallCompleteTime == 0) {
                        $wallCompleteTime = '';
                    }
                    echo "<td align='center'>$wallCompleteTime</td>";
                    $groutAndCapsTime = $rows['grout_and_caps_date'];
                    if($groutAndCapsTime == 0) {
                        $groutAndCapsTime = '';
                    }
                    echo "<td align='center'>$groutAndCapsTime</td>";
                    $warrantyTime = $rows['warranty_date'];
                    if($warrantyTime == 0) {
                        $warrantyTime = '';
                    }
                    echo "<td align='center'>$warrantyTime</td>";
                    $poTime = $rows['po_date'];
                    if($poTime == 0) {
                        $poTime = '';
                    }
                    echo "<td align='center'>$poTime</td>";
                    $invoiceDate = $rows['invoice_date'];
                    if($invoiceDate == 0) {
                        $invoiceDate = '';
                    }
                    echo "<td align='center'>$invoiceDate</td>";
                    $mailOutDate = $rows['mail_out_date'];
                    if($mailOutDate == 0) {
                        $mailOutDate = '';
                    }
                    echo "<td align='center'>$mailOutDate</td>";
                    echo "</tr>";
                } else { // Get data from masonrytimesheets and store new lot statuses.
                    $sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
                           "' && `lot`='" . $layoutRows['lot'] . "' order by date";
                    $records = mysqli_query($this->con, $sql);

                    $handDigDate = '';
                    $footerDugDate = '';
                    $footerSetDate = '';
                    $footerPourDate = '';
                    $blockDate = '';
                    $wallCompleteDate = '';
                    $groutAndCapsDate = '';
                    $warrantyDate = '';
                    $poDate = '';

                    // Get date from latest timesheet with specific action.
                    $timesheetFound = false;
                    while($rows = mysqli_fetch_array($records)){
                        $timesheetFound = true;
                        $action = $rows['action'];
                        switch ($action) {
                            case 'Hand Dig':
                                if(!strcmp($handDigDate, '')) {
                                    $storedHandDigDate = $rows['hand_dig_date'];
                                    if($storedHandDigDate > 0) {
                                        $handDigDate = $storedHandDigDate;
                                    }
                                }
                                break;
                            case 'Dug':
                                if(!strcmp($footerDugDate, '')) {
                                    $storedFooterDugDate = $rows['footer_dug_time'];
                                    if($storedFooterDugDate > 0) {
                                        $footerDugDate = $storedFooterDugDate;
                                    }
                                }
                                break;
                            case 'Set':
                                if(!strcmp($footerSetDate, '')) {
                                    $storedFooterSetDate = $rows['footer_set_time'];
                                    if($storedFooterSetDate > 0) {
                                        $footerSetDate = $storedFooterSetDate;
                                    }
                                }
                                break;
                            case 'Pour':
                                if(!strcmp($footerPourDate, '')) {
                                    $storedFooterPourDate = $rows['footer_poured_time'];
                                    if($storedFooterPourDate > 0) {
                                        $footerPourDate = $storedFooterPourDate;
                                    }
                                }
                                break;
                            case 'Block':
                                if(!strcmp($blockDate, '')) {
                                    $storedBlockDate = $rows['block_time'];
                                    if($storedBlockDate > 0) {
                                        $blockDate = $storedBlockDate;
                                    }
                                }
                                break;
                        }

                        if(!strcmp($wallCompleteDate, '')) {
                            $storedWallCompleteDate = $rows['wall_complete_time'];
                            if($storedWallCompleteDate > 0) {
                                $wallCompleteDate = $storedWallCompleteDate;
                            }
                        }

                        if(!strcmp($groutAndCapsDate, '')) {
                            $storedGroutAndCapsDate = $rows['grout_and_caps_time'];
                            if($storedGroutAndCapsDate > 0) {
                                $groutAndCapsDate = $storedGroutAndCapsDate;
                            }
                        }

                        if(!strcmp($warrantyDate, '')) {
                            $storedWarrantyDate = $rows['warranty_time'];
                            if($storedWarrantyDate > 0) {
                                $warrantyDate = $storedWarrantyDate;
                            }
                        }

                        if(!strcmp($poDate, '')) {
                            $storedPODate = $rows['po_time'];
                            if($storedPODate > 0) {
                                $poDate = $storedPODate;
                            }
                        }
                    }

                    $lotNumber = $layoutRows['lot'];
                    if($timesheetFound) {
                        echo '<tr>';
                            echo "<td align='center'>$lotNumber</td>";
                            echo "<td align='center'>$handDigDate</td>";
                            echo "<td align='center'>$footerDugDate</td>";
                            echo "<td align='center'>$footerSetDate</td>";
                            echo "<td align='center'>$footerPourDate</td>";
                            echo "<td align='center'>$blockDate</td>";
                            echo "<td align='center'>$wallCompleteDate</td>";
                            echo "<td align='center'>$groutAndCapsDate</td>";
                            echo "<td align='center'>$warrantyDate</td>";
                            echo "<td align='center'>$poDate</td>";
                            echo "<td align='center'></td>";
                            echo "<td align='center'></td>";
                        echo "</tr>";
                    } else { // Get data from layouts table.
                        echo '<tr>';
                            echo "<td align='center'>$lotNumber</td>";
                        echo "</tr>";
                    }
                }
                $count++;
            }
		}
		
		if($count == 0) {
			$outputMessage = 'No Lots found for ' . $builder . ' ' . $subdivision;
		}
		
		$_POST['lotNumber'] = '';
	}
	
	// Display lot Drop Menu
	function displayLotDropMenu() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lotNumber;
		global $invoiceDate;
		$layoutsQuery = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' order by CAST(lot as decimal)";
		$layoutRecords = mysqli_query($this->con, $layoutsQuery);
		$count = 0;
		$lotStatuses = array();
		while($layoutRows = mysqli_fetch_array($layoutRecords)){
			if(in_array($layoutRows['lot'], $lotStatuses)) {
				continue;
			}
			array_push($lotStatuses, $layoutRows['lot']);
			
			if($count == 0) {
				echo '<br/>';
				echo '<fieldset>';
				echo '<legend class="sectionLegend">Update</legend>';
				echo '<label class="formHeaderLabel">Lot # ';
				echo '<select name="lotDropMenu" id="lotDropMenu" style="font-size:23px" onclick="setSelectedLot()">';
				echo "<option value=''></option>";
			}
			$layoutLotNumber = $layoutRows['lot'];
			if(isset($_POST["selectedLot"])) {
				if(!strcmp($_POST["selectedLot"], $layoutLotNumber)) {
					echo "<option value=$layoutLotNumber selected='selected'>$layoutLotNumber</option>";
				} else {
					echo "<option value=$layoutLotNumber>$layoutLotNumber</option>";
				}
			} else {
				echo "<option value=$layoutLotNumber>$layoutLotNumber</option>";
			}
			
			$count++;
		}		
		if($count > 0) {
			echo '</select>';
			echo '<label class="formHeaderLabel">&nbsp;&nbsp;&nbsp;&nbsp;Invoice Date  ';
			echo '<input class="formMediumSmallInput" name="invoiceDate" type="date" value=""/>';
			echo '<label class="formHeaderLabel">&nbsp;&nbsp;&nbsp;&nbsp;Mail Out  ';
			echo '<input class="formMediumSmallInput" name="mailOutDate" type="date" value=""/>';
			echo '</fieldset>';
			echo '<br/>';
		}
	}
	
	function saveLotStatus() {
		global $databaseName;
		global $date;
		global $builder;
		global $subdivision;
		global $lotNumber;
		global $wallCompleteDate;
		global $invoiceDate;
		global $mailOutDate;
		global $outputMessage;
		
		// Format Dates.
		$invoiceDateTS = strtotime($invoiceDate);
		if($invoiceDateTS > 0) {
			$invoiceDate = date('m/d/y', $invoiceDateTS);
		}
		$mailOutDateTS = strtotime($mailOutDate);
		if($mailOutDateTS > 0) {
			$mailOutDate = date('m/d/y', $mailOutDateTS);
		}
		
		$sql = "select * from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lotNumber . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(strcmp($outputMessage, "  ")) {
			// Update existing lotStatus.
			$sql = "update " . $databaseName . ".lotstatuses set `date`='" . $date . "', `invoice_date`='" . $invoiceDate .
				   "', `mail_out_date`='" . $mailOutDate . "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			       "' && `lot`='" . $lotNumber . "'"; 
			$wallCompleteDate = $rows['wall_complete_date'];
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Updated Lot ' . $lotNumber . ' status successfully.';
			} else {
				$outputMessage='Unable to update lot status: ' . mysqli_error($this->con);
			}
		} else {
			$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
						   "' && `lot`='" . $lotNumber . "'";
			$records = mysqli_query($this->con, $sql);
			$rows = mysqli_fetch_array($records);
			$handDigDate = $rows['hand_dig_date'];
			$footerDugDate = $rows['footer_dug_time'];
			$footerSetDate = $rows['footer_set_time'];
			$footerPourDate = $rows['footer_poured_time'];
			$blockDate = $rows['block_time'];
			$wallCompleteDate = $rows['wall_complete_time'];
			$groutAndCapsDate = $rows['grout_and_caps_time'];
			$warrantyDate = $rows['warranty_time'];
			$poDate = $rows['po_time'];
			
			$sql = "INSERT INTO " . $databaseName . ".lotstatuses (date, builder, subdivision, lot, hand_dig_date, footer_dug_date, footer_set_date,
						 footer_pour_date, block_date, wall_complete_date, grout_and_caps_date, warranty_date, po_date, invoice_date, mail_out_date) VALUES ('$date', '$builder',
						 '$subdivision', '$lotNumber', '$handDigDate', '$footerDugDate', '$footerSetDate', '$footerPourDate', '$blockDate',
						 '$wallCompleteDate', '$groutAndCapsDate', '$warrantyDate', '$poDate', '$invoiceDate', '$mailOutDate')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Lot status saved successfully.';
			} else {
				$outputMessage='Unable to save lot status: ' . mysqli_error($this->con);
			}
		}
	}
	
	function deleteTimeSheet() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lotNumber;
		global $outputMessage;
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lotNumber . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(strcmp($outputMessage, "  ")) {
			$sql = "delete from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lotNumber . "'";
			mysqli_query($this->con, $sql);
			$outputMessage = 'Successfully deleted ' . $builder . " " . $subdivision . " " . $lotNumber;
			clearTimeSheet();
		} else {
			$outputMessage="Timesheet is not found!";
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>