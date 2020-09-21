<?php

class layoutDAO {
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
	
	function searchLayout() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $grandTotals;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
		       $subdivision . "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(!strcmp($outputMessage, "  ")) {
			$outputMessage= "No layout found!";
		} else {
			$outputMessage = "Layout has been found.";
			$_POST["date"] = $rows['date'];
			$_POST["supervisor"] = $rows['supervisor'];
			$_POST["courses"] = $rows['courses'];
			$_POST["gate"] = $rows['gate'];
			$_POST["endLot"] = $rows['endLot'];
			$_POST["planImageFile"] = $rows['plan_image_filename'];
			$_POST["comments"] = $rows['comments'];
			$_POST["retainerBW"] = $rows['retainerBW'];
			$_POST["rbwCourses"] = $rows['rbw_courses'];
			$_POST["retainerLW"] = $rows['retainerLW'];
			$_POST["rlwCourses"] = $rows['rlw_courses'];
			$_POST["retainerRW"] = $rows['retainerRW'];
			$_POST["rrwCourses"] = $rows['rrw_courses'];
			$_POST["perimeterBW"] = $rows['perimeterBW'];
			$_POST["perimeterLW"] = $rows['perimeterLW'];
			$_POST["perimeterRW"] = $rows['perimeterRW'];
			$_POST["viewBW"] = $rows['viewBW'];
			$_POST["viewLW"] = $rows['viewLW'];
			$_POST["viewRW"] = $rows['viewRW'];
			$_POST["lotBW"] = $rows['lotBW'];
			$_POST["lotLW"] = $rows['lotLW'];
			$_POST["lotRW"] = $rows['lotRW'];
			$_POST["returnLR"] = $rows['returnLR'];
			$_POST["returnRR"] = $rows['returnRR'];
			$_POST["extraPT"] = $rows['extraPT'];
			$_POST["extraAC"] = $rows['extraAC'];
			$_POST["extraCY"] = $rows['extraCY'];
			$_POST["extraBW"] = $rows['extraBW'];
			$_POST["extraLW"] = $rows['extraLW'];
			$_POST["extraRW"] = $rows['extraRW'];
			$grandTotals = $rows['grandTotals'];
			
			$_POST["handPour"] = !strcmp($rows['hand_pour'], 1) ? $rows['hand_pour'] : "";
			$_POST["upHillSide"] = !strcmp($rows['up_hill_side'], 1) ? $rows['up_hill_side'] : "";
			$_POST["caps"] = !strcmp($rows['caps'], 1) ? $rows['caps'] : "";
			$_POST["wheelBarrow"] = !strcmp($rows['wheel_barrow'], 1) ? $rows['wheel_barrow'] : "";
			$_POST["mailbox"] = !strcmp($rows['mailbox'], 1) ? $rows['mailbox'] : "";
			$_POST["rockVeneer"] = !strcmp($rows['rock_veneer'], 1) ? $rows['rock_veneer'] : "";
			$_POST["repair"] = !strcmp($rows['repair'], 1) ? $rows['repair'] : "";
			$_POST["groutRetainer"] = !strcmp($rows['grout_retainer'], 1) ? $rows['grout_retainer'] : "";
		}
	}
	
	function displayExistingTimesheets() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			   "' && `lot`='" . $lot . "' order by `date`";
		$records = mysqli_query($this->con, $sql);
		$count = 0;
		while($rows = mysqli_fetch_array($records)) {
			$dateTS = strtotime($rows['date']);
			$formattedDate = ($dateTS > 0) ? date('m/d/y', $dateTS) : '';
			$action = $rows['action'];
			$crewName = $rows['crew_name'];
			
			if($count == 0) {
				echo '<table id="timesheetsTable" style="border:5px solid black;" width="57%" border="5" cellpadding="1" cellspacing="5" class="db-table">';
				echo '<tr><th style="border:0px solid black;">Date</th><th style="border:0px solid black;">Action</th><th style="border:0px solid black;">Crew</th></tr>';
			}
			
			echo '<tr>';
				$urlBuilder = str_replace('&','sAnd',$builder);
				$urlBuilder = str_replace(' ',':',$urlBuilder);
				$urlSubdivision = str_replace('&','sAnd',$subdivision);
				$urlSubdivision = str_replace(' ',':',$urlSubdivision);
				$urlAction = str_replace(' ',':',$action);
				$urlCrewName = str_replace(' ',':',$crewName);
				$searchUrl = '../timesheet/masonryTimeSheet.php?lot=' . $formattedDate . ',' . $urlBuilder . ',' . $urlSubdivision . ',' . $lot . ',' . $urlAction . ',' . $urlCrewName . ',' . 'search';
				echo "<td align='center' id='existingTimesheetDate' style='border:0px solid black;' width='17%' align='left'><a href=$searchUrl>$formattedDate</a></td>";
				echo "<td align='center' id='existingTimesheetAction' style='border:0px solid black;' width='17%' align='left'>$action</td>";
				echo "<td id='existingTimesheetCrewName' style='border:0px solid black;' width='20%' align='left'>$crewName</td>";
			echo "</tr>";
			$count++;
		}
		
		if($count == 0) {
			echo 'No timesheets found';
		} else {
			echo "</table>";
		}
	}
	
	function displayCrewDropMenu() {
		global $databaseName;
		global $crewName;
		$sql = "select * from " . $databaseName . ".crews";
		$records = mysqli_query($this->con, $sql);
		$count = 0; 
		
		while($rows = mysqli_fetch_array($records)) {
			if($count == 0) {
				echo '<select name="crewDropMenu" id="crewDropMenu" style="font-size:22px" onclick="setSelectedCrew()">';
				echo "<option value=''></option>";
			}
			
			$crewName = $rows['crew_name'];
			
			if(isset($_POST["selectedTimesheetCrew"])) {
				if(!strcmp($_POST["selectedTimesheetCrew"], $crewName)) {
					echo "<option value=$crewName selected='selected'>$crewName</option>";
				} else {
					echo "<option value=$crewName>$crewName</option>";
				}
			} else {
				echo "<option value=$crewName>$crewName</option>";
			}
			
			$count++;
		}
		if($count > 0) {
			echo '</select>';
		}
	}
	
	function saveLayout() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $date;
		global $supervisor;
		global $courses;
		global $gate;
		global $endLot;
		global $comments;
		global $retainerBW;
		global $rbwCourses;
		global $retainerLW;
		global $rlwCourses;
		global $retainerRW;
		global $rrwCourses;
		global $perimeterBW;
		global $perimeterLW;
		global $perimeterRW;
		global $viewBW;
		global $viewLW;
		global $viewRW;
		global $lotBW;
		global $lotLW;
		global $lotRW;
		global $returnLR;
		global $returnRR;
		global $extraPT;
		global $extraAC;
		global $extraCY;
		global $extraBW;
		global $extraLW;
		global $extraRW;
		global $grandTotals;
		global $handPour;
		global $upHillSide;
		global $caps;
		global $wheelBarrow;
		global $mailbox;
		global $rockVeneer;
		global $repair;
		global $groutRetainer;
		global $handDigCrewName;
		global $dugCrewName;
		global $setCrewName;
		global $pourCrewName;
		global $blockCrewName;
		global $groutAndCapsCrewName;
		global $backfillCrewName;
		global $cleanCrewName;
		global $warrantyCrewName;
		global $pOCrewName;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
			   $subdivision . "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(!strcmp($outputMessage, "  ")) {
			$sql = "INSERT INTO " . $databaseName . ".layouts (`builder`, `subdivision`, `date`, `supervisor`, `courses`,
			       `lot`, `gate`, `endLot`, `comments`, `retainerBW`, `rbw_courses`, `retainerLW`, `rlw_courses`, `retainerRW`,
			       `rrw_courses`, `perimeterBW`, `perimeterLW`, `perimeterRW`, `viewBW`, `viewLW`, `viewRW`, `lotBW`,
			       `lotLW`, `lotRW`, `returnLR`, `returnRR`, `extraPT`, `extraAC`, `extraCY`, `extraBW`, `extraLW`,
			       `extraRW`, `grandTotals`, `hand_pour`, `up_hill_side`, `caps`, `wheel_barrow`, `mailbox`,
			       `rock_veneer`, `repair`, `grout_retainer`)
			       VALUES ('$builder', '$subdivision', '$date', '$supervisor', '$courses', '$lot', '$gate', '$endLot',
			       '$comments', '$retainerBW', '$rbwCourses', '$retainerLW', '$rlwCourses', '$retainerRW', '$rrwCourses',
			       '$perimeterBW', '$perimeterLW', '$perimeterRW', '$viewBW', '$viewLW', '$viewRW', '$lotBW', '$lotLW',
			       '$lotRW', '$returnLR', '$returnRR', '$extraPT', '$extraAC', '$extraCY', '$extraBW', '$extraLW', '$extraRW',
			       '$grandTotals', '$handPour', '$upHillSide', '$caps', '$wheelBarrow', '$mailbox', '$rockVeneer',
			       '$repair', '$groutRetainer')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Saved layout successfully.';
			} else {
				$outputMessage='Unable save layout: ' . mysqli_error($this->con);
			}
		} else {
			$sql = "update " . $databaseName . ".layouts set `date`='" . $date . "', `supervisor`='" . $supervisor . "', `courses`='"
				   . $courses . "', `gate`='" . $gate . "', `endLot`='" . $endLot . "', `comments`='" . $comments . "', `retainerBW`='"
				   . $retainerBW . "', `rbw_courses`='" . $rbwCourses . "', `retainerLW`='" . $retainerLW . "', `rlw_courses`='"
				   . $rlwCourses . "', `retainerRW`='" . $retainerRW . "', `rrw_courses`='" . $rrwCourses . "', `perimeterBW`='"
				   . $perimeterBW . "', `perimeterLW`='" . $perimeterLW . "', `perimeterRW`='" . $perimeterRW . "', `viewBW`='"
				   . $viewBW . "', `viewLW`='" . $viewLW . "', `viewRW`='" . $viewRW . "', `lotBW`='"
				   . $lotBW . "', `lotLW`='" . $lotLW . "', `lotRW`='" . $lotRW . "', `returnLR`='" . $returnLR . "', `returnRR`='"
				   . $returnRR . "', `extraPT`='" . $extraPT . "', `extraAC`='" . $extraAC . "', `extraCY`='" . $extraCY .  "', `extraBW`='"
				   . $extraBW .  "', `extraLW`='" . $extraLW .   "', `extraRW`='" . $extraRW .  "', `grandTotals`='"
				   . $grandTotals . "', `hand_pour`='" . $handPour . "', `up_hill_side`='"
				   . $upHillSide . "', `caps`='" . $caps .  "', `wheel_barrow`='" . $wheelBarrow . "', `mailbox`='" . $mailbox . "', `rock_veneer`='"
				   . $rockVeneer . "', `repair`='" . $repair . "', `grout_retainer`='" . $groutRetainer
				   . "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Updated layout successfully.';
			} else {
				$outputMessage='Unable to update layout: ' . mysqli_error($this->con);
			}
		}
	}
	
	function deleteLayout() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $outputMessage;
		$sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
			   $subdivision . "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(strcmp($outputMessage, "  ")) {
			$sql = "delete from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
			       $subdivision . "' && `lot`='" . $lot . "'";
			mysqli_query($this->con, $sql);
			$outputMessage = 'Deleted ' . $builder . " " . $subdivision . " " . $lot;
		} else {
			$outputMessage="Layout is not found!";
		}
	}

	// Save Layout Plan image filename
    function updatePlanImageFilename() {
        global $databaseName;
        global $builder;
        global $subdivision;
        global $lot;
        global $planImageFile;
        global $outputMessage;

        $sql = "update $databaseName.layouts set plan_image_filename='$planImageFile' where builder='$builder' && subdivision='$subdivision' && lot='$lot'";

        if(mysqli_query($this->con, $sql)) {
            $outputMessage='Update Layout Image filename successfully.';
        } else {
            $outputMessage='Unable to update Layout Image filename: ' . mysqli_error($this->con);
        }

        return true;
    }

    // Delete Layout Plan image filename
    function deletePlanImageFilename() {
        global $databaseName;
        global $builder;
        global $subdivision;
        global $lot;
        global $planImageFile;
        global $outputMessage;

        $sql = "update $databaseName.layouts set plan_image_filename=NULL where builder='$builder' && subdivision='$subdivision' && lot='$lot'";

        if(mysqli_query($this->con, $sql)) {
            $outputMessage='Deleted Layout Image filename successfully.';
        } else {
            $outputMessage='Unable to delete Layout Image filename: ' . mysqli_error($this->con);
        }

        return true;
    }
	
	function saveSelectedTimesheet() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $selectedTimesheetDate;
		global $selectedTimesheetAction;
		global $selectedTimesheetCrew;
		global $outputMessage;
		
		$timesheetDateTS = strtotime($selectedTimesheetDate);
		if($timesheetDateTS > 0) {
			$timesheetDate = date('m/d/y', $timesheetDateTS);
		}
		
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $timesheetDate . "' && `builder`='"
		       . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $selectedTimesheetAction
		       . "' && `crew_name`='" . $selectedTimesheetCrew . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(!strcmp($outputMessage, "  ")) {
			// Note that there might be existing timesheets with no Crew defined yet.
			// Note that existing timesheets with no Crew will not exist once the System is stable
			// as the previous design allowed creating timesheets with no Crew.
			$timesheetNoCrewQuery = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $timesheetDate . "' && `builder`='"
		       . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $selectedTimesheetAction
		       . "' && `crew_name`=''";
	        $timesheetNoCrewRecords = mysqli_query($this->con, $timesheetNoCrewQuery);
			$timesheetNoCrewRows = mysqli_fetch_array($timesheetNoCrewRecords);
			$timesheetNoCrewData = $timesheetNoCrewRows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
			$saved = false;
			if(!strcmp($timesheetNoCrewData, "  ")) {
				$sql = "INSERT INTO " . $databaseName . ".masonrytimesheets (`date`, `builder`, `subdivision`, `lot`, `action`, `crew_name`) VALUES ('$timesheetDate', '$builder',
				        '$subdivision', '$lot', '$selectedTimesheetAction', '$selectedTimesheetCrew')";
				if(mysqli_query($this->con, $sql)) {
					// Save Workers to keep existing Crew.
					if($this->saveWorkers()) {
						$outputMessage = 'Saved Timesheet successfully.';
						$saved = true;
					} else {
						
					}
				} else {
					$outputMessage='Unable create Timesheet: ' . mysqli_error($this->con);
				}
			} else {
				$updateTimesheetQuery = "update " . $databaseName . ".masonrytimesheets set `crew_name`='" . $selectedTimesheetCrew . "' where `date`='" .
			 						$timesheetDate . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			 						"' && `lot`='" . $lot . "' && `action`='" . $selectedTimesheetAction . "' && `crew_name`=''";
				if(mysqli_query($this->con, $updateTimesheetQuery)) {
					$outputMessage = 'Updated Timesheet successfully.';
					$saved = true;
				} else {
					$outputMessage='Unable update Timesheet: ' . mysqli_error($this->con);
				}
			}
			if($saved) {
				clearSelectedTimesheet();
			}
		} else {
			$outputMessage = 'Timesheet already saved.';
		}
	}
	
	function deleteSelectedTimesheet() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $selectedTimesheetDate;
		global $selectedTimesheetAction;
		global $selectedTimesheetCrew;
		global $outputMessage;
		
		$timesheetDateTS = strtotime($selectedTimesheetDate);
		if($timesheetDateTS > 0) {
			$timesheetDate = date('m/d/y', $timesheetDateTS);
		}
		
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $timesheetDate . "' && `builder`='"
		       . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $selectedTimesheetAction
		       . "' && `crew_name`='" . $selectedTimesheetCrew . "'";
		       
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(strcmp($outputMessage, "  ")) {
			$sql = "delete from " . $databaseName . ".masonrytimesheets where `date`='" . $timesheetDate . "' && `builder`='" . $builder
			       . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $selectedTimesheetAction
			       . "' && `crew_name`='" . $selectedTimesheetCrew . "'";
			if(mysqli_query($this->con, $sql)) {
				// Delete Worker Times from workers table.
				$deleteWorkersQuery = "delete from " . $databaseName . ".workers where `date`='" . $timesheetDate . "' && `builder`='" . $builder
			       . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $selectedTimesheetAction
			       . "' && `crew_name`='" . $selectedTimesheetCrew . "'";
				if(!mysqli_query($this->con, $deleteWorkersQuery)) {
					$outputMessage = 'Unable to delete workers.';
				}
				
				// Check if there are timesheets associated with the lotstatus.
				$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" .
				 	   $subdivision . "' && `lot`='" . $lot . "'";
				$records = mysqli_query($this->con, $sql);
				$rows = mysqli_fetch_array($records);
				$selectedTimesheet=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
				// Delete lot status only if there are no timesheets associated.
				$lotStatusDeleted = false;
				if(!strcmp($selectedTimesheet, "  ")) {
					$sql = "select * from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" .
				 	   $subdivision . "' && `lot`='" . $lot . "'";
			 	   	$records = mysqli_query($this->con, $sql);
			 	   	$rows = mysqli_fetch_array($records);
					$selectedLotStatus=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
					if(strcmp($selectedLotStatus, "   ")) {
						$sql = "delete from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" .
					 	   $subdivision . "' && `lot`='" . $lot . "'";
					    if(!mysqli_query($this->con, $sql)) {
							$outputMessage = 'Unable to delete lot status.';
						} else {
							$outputMessage = 'Deleted Timesheet' . $builder . " " . $subdivision . " " . $lot . ' with ' .
										 $selectedTimesheetAction . ' and LotStatus';
							$lotStatusDeleted = true;
						}
					}
				}
				if(!$lotStatusDeleted) {
					$outputMessage = 'Deleted Timesheet successfully.';
				}
				clearSelectedTimesheet();
			} else {
				$outputMessage='Unable to delete Timesheet: ' . mysqli_error($this->con);
			}
		} else {
			$outputMessage = 'Timesheet does not exist.';
		}
	}
	
	// Save Workers
	function saveWorkers() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $selectedTimesheetDate;
		global $selectedTimesheetAction;
		global $selectedTimesheetCrew;
		
		$timesheetDateTS = strtotime($selectedTimesheetDate);
		if($timesheetDateTS > 0) {
			$timesheetDate = date('m/d/y', $timesheetDateTS);
		}
		
		$employeesArr = $this->getEmployees($selectedTimesheetCrew);
		for($j=0;$j<count($employeesArr);$j++) {
			$employee = $employeesArr[$j];
			$workerName = $employee->oName;
			
			$sql = "INSERT INTO " . $databaseName . ".workers (date, builder, subdivision, lot, action, crew_name,
				name) VALUES ('$timesheetDate', '$builder', '$subdivision', '$lot',
			 	'$selectedTimesheetAction', '$selectedTimesheetCrew', '$workerName')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Saved Worker successfully.';
			} else {
				$outputMessage='Unable to save Worker: ' . mysqli_error($this->con);
			}
		}
		
		return true;
	}
	
	// TODO This same API is defined in timesheetDAO. Make a common API.
	// Get employees ordered by Forman, Mason, Apprentice, Labor, Driver, Operator.
	function getEmployees($crewName) {
		global $databaseName;
		global $selectedTimesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		$employeesArr = array();
		
		$timesheetDateTS = strtotime($selectedTimesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}

		// Get Forman employees.
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' and `is_forman`='1'";
		$records = mysqli_query($this->con, $sql);
		
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employeeName = trim($rows['first']) . " " . trim($rows['last']);
			$employee->setName($employeeName);
			$employee->setWage($rows['wage']);
			array_push($employeesArr, $employee);
		}
		
		// Get Mason employees.
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' and `is_mason`='1'";
		$records = mysqli_query($this->con, $sql);
		
		// Get Employees from DB.
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employeeName = trim($rows['first']) . " " . trim($rows['last']);
			$employee->setName($employeeName);
			$employee->setWage($rows['wage']);
			
			if(!in_array($employee, $employeesArr)) {
				array_push($employeesArr, $employee);
			}
		}
		
		// Get Apprentice employees.
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' and `is_apprentice`='1'";
		$records = mysqli_query($this->con, $sql);
		
		// Get Employees from DB.
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employeeName = trim($rows['first']) . " " . trim($rows['last']);
			$employee->setName($employeeName);
			$employee->setWage($rows['wage']);
			
			if(!in_array($employee, $employeesArr)) {
				array_push($employeesArr, $employee);
			}
		}
		
		// Get Apprentice employees.
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' and `is_labor`='1'";
		$records = mysqli_query($this->con, $sql);
		
		// Get Employees from DB.
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employeeName = trim($rows['first']) . " " . trim($rows['last']);
			$employee->setName($employeeName);
			$employee->setWage($rows['wage']);
			
			if(!in_array($employee, $employeesArr)) {
				array_push($employeesArr, $employee);
			}
		}
		
		// Get Driver employees.
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' and `is_driver`='1'";
		$records = mysqli_query($this->con, $sql);
		
		// Get Employees from DB.
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employeeName = trim($rows['first']) . " " . trim($rows['last']);
			$employee->setName($employeeName);
			$employee->setWage($rows['wage']);
			
			if(!in_array($employee, $employeesArr)) {
				array_push($employeesArr, $employee);
			}
		}
		
		// Get Operator employees.
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' and `is_operator`='1'";
		$records = mysqli_query($this->con, $sql);
		
		// Get Employees from DB.
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employeeName = trim($rows['first']) . " " . trim($rows['last']);
			$employee->setName($employeeName);
			$employee->setWage($rows['wage']);
			
			if(!in_array($employee, $employeesArr)) {
				array_push($employeesArr, $employee);
			}
		}
		
		return $employeesArr;
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>