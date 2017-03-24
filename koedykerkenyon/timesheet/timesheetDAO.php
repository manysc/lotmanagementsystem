<?php

class timesheetDAO {
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
	
	// Get employees ordered by Forman, Mason, Apprentice, Labor, Driver, Operator.
	function getEmployees($crewName) {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		$employeesArr = array();
		
		$timesheetDateTS = strtotime($timesheetDate);
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
	
	function getWorkers() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		
		$timesheetDateTS = strtotime($timesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		$employeesArr = array();
		
		// Get workers from workers table using timesheet date.
		$workersQuery = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" .
				 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" .
				 $action . "' && `crew_name`='" . $timesheetCrew . "'";
		$workerRecords = mysqli_query($this->con, $workersQuery);
		while($workerRows = mysqli_fetch_array($workerRecords)) {
			$employee = new Employee();
			$workerName = $workerRows['name'];
			$employee->setName($workerName);
			$employee->setStartTime($workerRows['start_time']);
			$employee->setStopTime($workerRows['stop_time']);
			$employee->setTotalTime($workerRows['total_time']);
			$employee->setCost($workerRows['cost']);
			
			// Get employee wage only when 'Save' button is pressed as it's only used for recalculating worker cost.
			if(isset($_POST['saveWorkerTime'])) {
				// Get Worker wage from Employees table.
				// Note that the wage might have been updated and the lot cost might be changed in different dates.
				$employeeWageQuery = "select * from " . $databaseName . ".employees where concat(TRIM(`first`), ' ', TRIM(`last`))='" .
								  $workerName . "'";
			    $employeeWageRecord = mysqli_query($this->con, $employeeWageQuery);
			    $employeeWageRows = mysqli_fetch_array($employeeWageRecord);
				$employeeWageRow = $employeeWageRows['first'];
				if(strcmp($employeeWageRow, '')) {
					$employee->setWage($employeeWageRows['wage']);
				}
			}
			
			array_push($employeesArr, $employee);
		}
		return $employeesArr;
	}
	
	function displayCrewDropMenu() {
		global $databaseName;
		if(isset($_POST["timesheetCrew"])) {
			$selectedCrew = $_POST["timesheetCrew"];
		}
		// Crew might have been deleted after Timesheet is created. Display the existing selected Crew.
		if(isset($selectedCrew) && strcmp($selectedCrew, '')) {
			$crewExistQuery = "select * from " . $databaseName . ".crews where `crew_name`='" . $selectedCrew . "'";
			$crewExistRecords = mysqli_query($this->con, $crewExistQuery);
			$crewExistRow = mysqli_fetch_array($crewExistRecords);
			$existingCrew = $crewExistRow['crew_name'];
			if(!strcmp($existingCrew, '')) {
				echo '<select name="crewDropMenu" id="crewDropMenu" style="font-size:22px" onclick="setSelectedCrew()">';
				echo "<option value='$selectedCrew'>$selectedCrew</option>";
				echo '</select>';
				return;
			}
		}
		
		$sql = "select * from " . $databaseName . ".crews";
		$records = mysqli_query($this->con, $sql);
		$count = 0;
		
		while($rows = mysqli_fetch_array($records)) {
			if($count == 0) {
				echo '<select name="crewDropMenu" id="crewDropMenu" style="font-size:22px" onclick="setSelectedCrew()">';
				echo "<option value=''></option>";
			}
			
			$crewName = $rows['crew_name'];
			
			if(isset($_POST["timesheetCrew"])) {
				if(!strcmp($_POST["timesheetCrew"], $crewName)) {
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
	
	function getWorkerCost($workerDate, $builder, $subdivision, $lot, $action, $workerName, $totalTime) {
		global $databaseName;
		// Calculate Overtime Cost
		// 1) Get Week dates.
		// 2) Get Worker hours from $weekStart to $timesheetDate.
  		// 3) Add up total number of hours excluding the hours for the current Timesheet being updated.
  		// 4) Check how many hours the worker has worked so far vs. the hours to start overtime wage.
		$dateTime = new DateTime();
		$week = (int)date('W', strtotime($workerDate));
		$year = date('Y', strtotime($workerDate));
  		$weekStart = $dateTime->setISODate($year, $week);
  		$weekStart = $dateTime->format('m/d/y'); // Call to a member function format() on a non-object. 
  		$weekEnd = $dateTime->modify('+6 days');
  		$weekEnd = $dateTime->format('m/d/y'); // Call to a member function format() on a non-object.
  		
		$timesheetDateTS = strtotime($workerDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		$wage = 0;
		
		// Get worker wage from Employee table.
		$employeeWageQuery = "select * from " . $databaseName . ".employees where concat(TRIM(`first`), ' ', TRIM(`last`))='" .
						  $workerName . "'";
	    $employeeWageRecord = mysqli_query($this->con, $employeeWageQuery);
	    $employeeWageRows = mysqli_fetch_array($employeeWageRecord);
		$employeeWageRow = $employeeWageRows['first'];
		if(strcmp($employeeWageRow, '')) {
			$wage = $employeeWageRows['wage'];
		}
		
		$idQuery = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `name`='" . $workerName . "' &&
			`builder`= '" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' &&
			`action`='" . $action . "'";
		$idRecord = mysqli_query($this->con, $idQuery);
		$idRow = mysqli_fetch_array($idRecord);
		
		// Calculate left hours for overtime cost.
		$sql = "select * from " . $databaseName . ".workers where `name`='" . $workerName . "' and `date`>='" . $weekStart .
			   "' and `date`<='" . $date . "' order by date";
		$records = mysqli_query($this->con, $sql);
	
		// Get Worker total hours.
		$totalHours = 0;
		$hoursToReachOvertime = WEEK_HOURS_FOR_NORMAL_WAGE;
		$reachedOvertime = false;
		while($rows = mysqli_fetch_array($records)) {
			// Don't count the hours for the same timesheet.
			if(strcmp($rows['date'], $date) || strcasecmp(trim($rows['builder']), trim($builder)) ||
			   strcasecmp(trim($rows['subdivision']), trim($subdivision)) || strcasecmp(trim($rows['lot']), trim($lot)) ||
			   strcmp($rows['action'], $action)) {
			   $totalHours += $rows['total_time'];
			}
		}
		
		if($totalHours >= WEEK_HOURS_FOR_NORMAL_WAGE) {
			$wage = $wage * OVERTIME_RATE;
			$hoursToReachOvertime = 0;
			$reachedOvertime = true;
			// Check Worker will reach overtime.
		} else if(($totalHours + $totalTime) > WEEK_HOURS_FOR_NORMAL_WAGE) {
			$hoursToReachOvertime = WEEK_HOURS_FOR_NORMAL_WAGE - $totalHours;
		}
		
		// Check if worker worked more than WEEK_HOURS_FOR_NORMAL_WAGE.
		if(!$reachedOvertime) {
			if($totalTime <= $hoursToReachOvertime) {
				$cost = $totalTime * $wage;
			} else {
				$cost = $hoursToReachOvertime * $wage;
				$overtimeHours = $totalTime - $hoursToReachOvertime;
				$wage = $wage * OVERTIME_RATE;
				$cost += $overtimeHours * $wage;
			}
		} else {
			$cost = $totalTime * $wage;
		}
		
		return $cost;
	}
	
	function displayWorkersAndUpdateCost($employeesArr) {
		global $msie;
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $actionCost;
		global $outputMessage;
		
		$actionCost = 0;
		
		// FIXME Avoid repeated code ($this->getWorkerCost(...)).
		// Calculate Overtime Cost
		// 1) Get Week dates.
		// 2) Get Worker hours from $weekStart to $timesheetDate.
  		// 3) Add up total number of hours excluding the hours for the current Timesheet being updated.
  		// 4) Check how many hours the worker has worked so far vs. the hours to start overtime wage.
		$dateTime = new DateTime();
		$week = (int)date('W', strtotime($timesheetDate));
		$year = date('Y', strtotime($timesheetDate));
  		$weekStart = $dateTime->setISODate($year, $week);
  		$weekStart = $dateTime->format('m/d/y'); // Call to a member function format() on a non-object.
  		$weekEnd = $dateTime->modify('+6 days');
  		$weekEnd = $dateTime->format('m/d/y'); // Call to a member function format() on a non-object.
  		
		$timesheetDateTS = strtotime($timesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		$count = 0;
		for($j=0;$j<count($employeesArr);$j++) {
			$employee = $employeesArr[$j];
			$workerName = $employee->oName;
			$wage = $employee->oWage;
			
			$idQuery = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `name`='" . $workerName . "' &&
				`builder`= '" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' &&
				`action`='" . $action . "'";
			$idRecord = mysqli_query($this->con, $idQuery);
			$idRow = mysqli_fetch_array($idRecord);
			
			// Calculate left hours for overtime cost.
			$sql = "select * from " . $databaseName . ".workers where `name`='" . $workerName . "' and `date`>='" . $weekStart .
				   "' and `date`<='" . $date . "' order by date";
			$records = mysqli_query($this->con, $sql);
		
			// Get Worker total hours.
			$totalHours = 0;
			$hoursToReachOvertime = WEEK_HOURS_FOR_NORMAL_WAGE;
			$reachedOvertime = false;
			while($rows = mysqli_fetch_array($records)) {
				// Don't count the hours for the same timesheet.
				if(strcmp($rows['date'], $date) || strcasecmp(trim($rows['builder']), trim($builder)) ||
				   strcasecmp(trim($rows['subdivision']), trim($subdivision)) || strcasecmp(trim($rows['lot']), trim($lot)) ||
				   strcmp($rows['action'], $action)) {
				   $totalHours += $rows['total_time'];
				}
			}
			
			if($totalHours >= WEEK_HOURS_FOR_NORMAL_WAGE) {
				$wage = $wage * OVERTIME_RATE;
				$hoursToReachOvertime = 0;
				$reachedOvertime = true;
			}
						
			$startTime = 0;
			$stopTime = 0;
			$totalTime = 0;
			
			if($count == 0) {
				echo '<table id="workerTimesTable" style="border:5px solid black;" width="90%" border="5" cellpadding="1" cellspacing="5" class="db-table">';
				echo '<tr><th style="border:0px solid black;">Name</th><th style="border:0px solid black;">Start</th><th style="border:0px solid black;">End</th><th style="border:0px solid black;">Total</th><th style="border:0px solid black;">Cost</th></tr>';
			}
			echo '<tr>';
			echo "<td id='workerName' style='border:0px solid black;font-size:18px;' width='40%' align='left'>$workerName</td>";
			
			$sql = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
						 "' && `lot`='" . $lot . "' && `action`='" . $action . "' && `name`='" . $workerName . "'";
			$workerRecords = mysqli_query($this->con, $sql);
			$workerRows = mysqli_fetch_array($workerRecords);
			$dbWorkerName=$workerRows['name'];
			if(strcmp($dbWorkerName, '')) {
				$startTime = $workerRows['start_time'];
				if(!isset($_POST['print'])) {
					if($msie) {
						if($startTime > 0) {
							list($startHour, $startMinute) = split(":", $startTime);
						} else {
							$startHour = 0;
							$startMinute = 0;
						}
						echo "<td align='center'>" . createHours('startHour', $startHour) . ':' . createMinutes('startMinute', $startMinute) . "</td>";
					} else {
						echo "<td align='center'><input placeholder='hh:mm' name='startTime' id='startTime' type='time' size='6' align='left' style='font-size:18px' value='$startTime'/></td>";
					}
				} else {
					echo "<td align='center'>$startTime</td>";
				}
				
				$stopTime = $workerRows['stop_time'];
				if(!isset($_POST['print'])) {
					if($msie) {
						if($stopTime > 0) {
							list($stopHour, $stopMinute) = split(":", $stopTime);
						} else {
							$stopHour = 0;
							$stopMinute = 0;
						}
						echo "<td align='center'>" . createHours('stopHour', $stopHour) . ':' . createMinutes('stopMinute', $stopMinute) . "</td>";
					} else {
						echo "<td align='center'><input placeholder='hh:mm' name='stopTime' id='stopTime' type='time' size='6' align='left' style='font-size:18px' value='$stopTime'/></td>";
					}
				} else {
					echo "<td align='center'>$stopTime</td>";
				}
				$totalTime = $workerRows['total_time'];
				
				if($totalTime > 0) {
					echo "<td align='center' style='font-size:18px'>$totalTime</td>";
				} else {
					echo "<td align='center'></td>";
				}
			} else {
				if(!isset($_POST['print'])) {
					echo "<td align='center'><input placeholder='hh:mm' name='startTime' id='startTime' type='time' size='6' align='left' style='font-size:18px' value='$startTime'/></td>";
					echo "<td align='center'><input placeholder='hh:mm' name='stopTime' id='stopTime' type='time' size='6' align='left' style='font-size:18px' value='$stopTime'/></td>";
				} else {
					echo "<td align='center' style='font-size:18px'></td>";
					echo "<td align='center' style='font-size:18px'></td>";
				}
				echo "<td align='center' style='font-size:18px'></td>";
			}
			
			// Check Worker will reach overtime.
			if(($totalHours + $totalTime) > WEEK_HOURS_FOR_NORMAL_WAGE) {
				$hoursToReachOvertime = WEEK_HOURS_FOR_NORMAL_WAGE - $totalHours;
			}
			
			if($totalTime > 0) {
				// Check if worker worked more than WEEK_HOURS_FOR_NORMAL_WAGE.
				if(!$reachedOvertime) {
					if($totalTime <= $hoursToReachOvertime) {
						$cost = $totalTime * $wage;
					} else {
						$cost = $hoursToReachOvertime * $wage;
						$overtimeHours = $totalTime - $hoursToReachOvertime;
						$wage = $wage * OVERTIME_RATE;
						$cost += $overtimeHours * $wage;
					}
				} else {
					$cost = $totalTime * $wage;
				}

				echo "<td align='center'>$cost</td>";
				
				// Persist cost.
				$updateCostQuery = "update " . $databaseName . ".workers set `cost`='" . $cost . "' where `date`='" . $date . "' && `name`='" . $workerName . "' &&
				`builder`= '" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' &&
				`action`='" . $action . "'";
				
				mysqli_query($this->con, $updateCostQuery);
				
				$actionCost += $cost;
			} else {
				echo "<td align='center'></td>";
				
				// Update cost as the number of hours have changed.
				if($workerRows['cost'] > 0) {
					$updateCostQuery = "update " . $databaseName . ".workers set `cost`=NULL where `date`='" . $date . "' && `name`='" . $workerName . "' &&
					`builder`= '" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' &&
					`action`='" . $action . "'";
					mysqli_query($this->con, $updateCostQuery);
				}
			}
			
	        echo "</tr>";
			$count++;
		}
		if($count == 0) {
			if(strcmp($timesheetCrew, '')) {
				echo "No workers assigned to " . $timesheetCrew;
			} else {
				echo "No workers assigned to this Timesheet.";
			}
		}
		
		echo "</table>";
		
		// Update Timesheet Action Cost.
		$this->updateCrewActionCost();
	}
	
	function displayExistingWorkers($employeesArr) {
		global $msie;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $actionCost;
		global $outputMessage;
		
		$actionCost = 0;
		$count = 0;
		for($j=0;$j<count($employeesArr);$j++) {
			$employee = $employeesArr[$j];
			$workerName = $employee->oName;
			$startTime = $employee->oStartTime;
			$stopTime = $employee->oStopTime;
			$totalTime = $employee->oTotalTime;
			$cost = $employee->oCost;
			
			$timesheetDateTS = strtotime($timesheetDate);
			if($timesheetDateTS > 0) {
				$date = date('m/d/y', $timesheetDateTS);
			}
			
			if($count == 0) {
				echo '<table id="workerTimesTable" style="border:5px solid black;" width="90%" border="5" cellpadding="1" cellspacing="5" class="db-table">';
				echo '<tr><th style="border:0px solid black;">Name</th><th style="border:0px solid black;">Start</th><th style="border:0px solid black;">End</th><th style="border:0px solid black;">Total</th><th style="border:0px solid black;">Cost</th></tr>';
			}
			
			echo '<tr>';
			echo "<td id='workerName' style='border:0px solid black;font-size:18pt;' width='40%' align='left'>$workerName</td>";
			
			// Get worker from workers table using timesheet date.
			if(!isset($_POST['print'])) {
				if($msie) {
					if($startTime > 0) {
						list($startHour, $startMinute) = split(":", $startTime);
					} else {
						$startHour = 0;
						$startMinute = 0;
					}
					echo "<td align='center'>" . createHours('startHour', $startHour) . ':' . createMinutes('startMinute', $startMinute) . "</td>";
				} else {
					echo "<td align='center'><input placeholder='hh:mm' name='startTime' id='startTime' type='time' size='6' align='left' style='font-size:18px' value='$startTime'/></td>";
				}
			} else {
				echo "<td align='center' style='font-size:18px'>$startTime</td>";
			}
			
			if(!isset($_POST['print'])) {
				if($msie) {
					if($stopTime > 0) {
						list($stopHour, $stopMinute) = split(":", $stopTime);
					} else {
						$stopHour = 0;
						$stopMinute = 0;
					}
					echo "<td align='center'>" . createHours('stopHour', $stopHour) . ':' . createMinutes('stopMinute', $stopMinute) . "</td>";
				} else {
					echo "<td align='center'><input placeholder='hh:mm' name='stopTime' id='stopTime' type='time' size='6' align='left' style='font-size:18px' value='$stopTime'/></td>";
				}
			} else {
				echo "<td align='center' style='font-size:18px'>$stopTime</td>";
			}
			
			if($totalTime > 0) {
				echo "<td align='center' style='font-size:18px'>$totalTime</td>";
			} else {
				echo "<td align='center' style='font-size:18px'></td>";
			}
				
			if($totalTime > 0) {
				echo "<td align='center' style='font-size:18px'>$cost</td>";
				$actionCost += $cost;
			} else {
				echo "<td align='center' style='font-size:18px'></td>";
			}
				
	        echo "</tr>";
			$count++;
		}
		if($count == 0) {
			echo "No workers assigned to " . $timesheetCrew;
		}
		
		echo "</table>";
	}
	
	function displayEmployeesToAddDelete() {
		global $databaseName;
		global $outputMessage;
		
		// Display Employee drop box.
		$sql = "select * from " . $databaseName . ".employees order by last";
		$records = mysqli_query($this->con, $sql);
		$count = 0; 
		
		while($rows = mysqli_fetch_array($records)) {
			if($count == 0) {
				echo '<br/>';
				echo '<select name="employeeDropMenu" id="employeeDropMenu" style="font-size:22px" onclick="setSelectedEmployee()">';
				echo "<option value=''></option>";
			}
			
			$first = $rows['first'];
			$last = $rows['last'];
			$employeeName = trim($first) . " " . trim($last);
			
			if(isset($_POST["selectedEmployee"])) {
				if(!strcmp($_POST["selectedEmployee"], $employeeName)) {
					echo "<option value=$employeeName selected='selected'>$employeeName</option>";
				} else {
					echo "<option value=$employeeName>$employeeName</option>";
				}
			} else {
				echo "<option value=$employeeName>$employeeName</option>";
			}
			
			$count++;
		}
		if($count > 0) {
			echo '</select>';
		}
		
		// Display delete button.
	}
	
	function displayWorkers() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			   "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		if(strcmp($timesheetCrew, '')) {
			$timesheetDateTS = strtotime($timesheetDate);
			if($timesheetDateTS > 0) {
				$date = date('m/d/y', $timesheetDateTS);
			}
			
			$employeesArr = array();
			// 1) Try to get exiting workers from workers table.
			$employeesArr = $this->getWorkers();
					 
			// 2) Get Workers from Employee table when there are no Workers persisted in workers table. 
			if(sizeof($employeesArr) == 0) {
				$employeesArr = $this->getEmployees($timesheetCrew);
			}
			
			// Calculate cost only when pressing 'Save' on Timesheet worker hours.
			if(isset($_POST['saveWorkerTime'])) {
				// Display workers and update their cost.
				$this->displayWorkersAndUpdateCost($employeesArr);
			} else {
				// Display workers with existing calculated cost.
				$this->displayExistingWorkers($employeesArr);
			}
		} else {
			$outputMessage = "Crew not assigned yet.";
		}
	}
	
	function getCrewActionCost() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $outputMessage;
		
		$crewCost = 0;
		$sql = "select * from " . $databaseName . ".workers where `date`='" . $timesheetDate . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			   "' && `lot`='" . $lot  . "' && `action`='" . $action . "' && `crew_name`='" . $timesheetCrew . "'";
		$records = mysqli_query($this->con, $sql);
		while($rows = mysqli_fetch_array($records)) {
			$cost = $rows['cost'];
			$crewCost += $cost;
		}
		
		if($crewCost == 0) {
			$crewCost = '';
		}
		return $crewCost;
	}
	
	function updateCrewActionCost() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $actionCost;
		global $outputMessage;
		
		// Format date.
		$timesheetDateTS = strtotime($timesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		$sql = "update " . $databaseName . ".masonrytimesheets set `action_cost`='" . $actionCost . "' where `date`='" .
 			$date . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
 			"' && `lot`='" . $lot . "' && `action`='" . $action . "' && `crew_name`='" . $timesheetCrew . "'";
		if(!mysqli_query($this->con, $sql)) {
			$outputMessage='Unable to save Timesheet Action Cost: ' . mysqli_error($this->con);
		}
	}
	
	function searchTimeSheet() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $actionCost;
		global $backWallLF;
		global $bwRetainerLF;
		global $rbwCourses;
		global $leftWallLF;
		global $lwRetainerLF;
		global $rlwCourses;
		global $rightWallLF;
		global $rwRetainerLF;
		global $rrwCourses;
		global $rightReturnLF;
		global $leftReturnLF;
		global $perimeterBWLF;
		global $perimeterLWLF;
		global $perimeterRWLF;
		global $viewBWLF;
		global $viewLWLF;
		global $viewRWLF;
		global $extensionBWLF;
		global $extensionLWLF;
		global $extensionRWLF;
		global $layoutFound;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
		       $subdivision . "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage = $rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(!strcmp($outputMessage, "  ")) {
			$outputMessage="No layout found!";
			$layoutFound = 0;
		} else {
			$layoutFound = 1;
			
			# Get data from Layout.
			$_POST["courses"] = $rows['courses'];
			$_POST["supervisor"] = $rows['supervisor'];
			$_POST["backWall"] = strcmp($rows['lotBW'], "0") ? true : "";
			$_POST["bwRetainer"] = strcmp($rows['retainerBW'], "0") ? true : "";
			$_POST["rightWall"] = strcmp($rows['lotRW'], "0") ? true : "";
			$_POST["rwRetainer"] = strcmp($rows['retainerRW'], "0") ? true : "";
			$_POST["leftWall"] = strcmp($rows['lotLW'], "0") ? true : "";
			$_POST["lwRetainer"] = strcmp($rows['retainerLW'], "0") ? true : "";
			$_POST["rightReturn"] = strcmp($rows['returnRR'], "0") ? true : "";
			$_POST["leftReturn"] = strcmp($rows['returnLR'], "0") ? true : "";
			$backWallLF = $rows['lotBW'];
			$bwRetainerLF = $rows['retainerBW'];
			$rbwCourses = $rows['rbw_courses'];
			$rightWallLF = $rows['lotRW'];
			$rwRetainerLF = $rows['retainerRW'];
			$rrwCourses = $rows['rrw_courses'];
			$leftWallLF = $rows['lotLW'];
			$lwRetainerLF = $rows['retainerLW'];
			$rlwCourses = $rows['rlw_courses'];
			$rightReturnLF = $rows['returnRR'];
			$leftReturnLF = $rows['returnLR'];
			$_POST["handDig"] = strcmp($action, 'Hand Dig') ? "" : true;
			$_POST["handPour"] = strcmp($rows['hand_pour'], "0") ? true : "";
			$_POST["upHillSide"] = strcmp($rows['up_hill_side'], "0") ? true : "";
			$_POST["caps"] = strcmp($rows['caps'], "0") ? true : "";
			$_POST["wheelBarrow"] = strcmp($rows['wheel_barrow'], "0") ? true : "";
			$_POST["airCond"] = $rows['extraAC'] > 0 ? true : "";
			$_POST["courtYard"] = $rows['extraCY'] > 0 ? true : "";
			$_POST["mailbox"] = strcmp($rows['mailbox'], "0") ? true : "";
			$_POST["rockVeneer"] = strcmp($rows['rock_veneer'], "0") ? true : "";
			$_POST["clean"] = strcmp($action, 'Clean') ? "" : true;
			$_POST["groutAndCaps"] = strcmp($action, 'Grout and Caps') ? "" : true;
			$_POST["warranty"] = strcmp($action, 'Warranty') ? "" : true;
			$_POST["purchaseOrder"] = strcmp($action, 'PO') ? "" : true;
			$_POST["footerDug"] = strcmp($action, 'Dug') ? "" : true;
			$_POST["footerSet"] = strcmp($action, 'Set') ? "" : true;
			$_POST["footerPoured"] = strcmp($action, 'Pour') ? "" : true;
			$_POST["block"] = strcmp($action, 'Block') ? "" : true;
			$_POST["repair"] = strcmp($rows['repair'], "0") ? true : "";
			$_POST["groutRetainer"] = strcmp($rows['grout_retainer'], "0") ? true : "";
			$perimeterBWLF = $rows['perimeterBW'];
			$perimeterLWLF = $rows['perimeterLW'];
			$perimeterRWLF = $rows['perimeterRW'];
			$viewBWLF = $rows['viewBW'];
			$viewLWLF = $rows['viewLW'];
			$viewRWLF = $rows['viewRW'];
			$extensionBWLF = $rows['extraBW'];
			$extensionLWLF = $rows['extraLW'];
			$extensionRWLF = $rows['extraRW'];
			
			$timesheetDateTS = strtotime($timesheetDate);
			if($timesheetDateTS > 0) {
				$date = date('m/d/y', $timesheetDateTS);
			}
			
			#Get data from Timesheet.
			$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $date . "' && `builder`='"
			       . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='"
			       . $action . "' && `crew_name`='" . $timesheetCrew . "'";
			$records = mysqli_query($this->con, $sql);
			$rows = mysqli_fetch_array($records);
			$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];;
			if(strcmp($outputMessage, "   ")) {
				$_POST["forman"] = $timesheetCrew;
				$_POST["backWallActualLF"] = $rows['back_wall_actual_size'];
				$_POST["bwRetainerActualLF"] = $rows['bw_retainer_actual_size'];
				$_POST["leftWallActualLF"] = $rows['left_wall_actual_size'];
				$_POST["lwRetainerActualLF"] = $rows['lw_retainer_actual_size'];
				$_POST["rightWallActualLF"] = $rows['right_wall_actual_size'];
				$_POST["rwRetainerActualLF"] = $rows['rw_retainer_actual_size'];
				$_POST["leftReturnActualLF"] = $rows['left_return_actual_size'];
				$_POST["rightReturnActualLF"] = $rows['right_return_actual_size'];
				
				$_POST["timesheetDate"] = $rows['date'];
				if($_POST["timesheetDate"] != '') {
					$_POST["timesheetDate"] = date("m/d/Y", strtotime($_POST["timesheetDate"]));
					$timesheetDate = $_POST["timesheetDate"];
				}
				
				$_POST["handDigDate"] = $rows['hand_dig_date'];
				if($_POST["handDigDate"] > 0) {
					$_POST["handDigDate"] = date("m/d/Y", strtotime($_POST["handDigDate"]));
				} 
				
				$_POST["handDigLF"] = $rows['hand_dig_size'];
				$_POST["handPourLF"] = $rows['hand_pour_size'];
				$_POST["upHillSideLF"] = $rows['up_hillside_size'];
				$_POST["wheelBarrowLF"] = $rows['wheel_barrow_size'];
				$_POST["airCondLF"] = $rows['ac_size'];
				$_POST["capsLF"] = $rows['caps_size'];
				$_POST["courtYardLF"] = $rows['courtyard_size'];
				$_POST["mailboxLF"] = $rows['mailbox_size'];
				$_POST["rockVeneerSF"] = $rows['rock_veneer_size'];
				$_POST["purchaseOrderNumber"] = $rows['purchase_order_number'];
				
				$_POST["footerDugDate"] = $rows['footer_dug_time'];
				if($_POST["footerDugDate"] > 0) {
					$_POST["footerDugDate"] = date("m/d/Y", strtotime($_POST["footerDugDate"]));
				}
				
				$_POST["footerSetTime"] = $rows['footer_set_time'];
				if($_POST["footerSetTime"] > 0) {
					$_POST["footerSetTime"] = date("m/d/Y", strtotime($_POST["footerSetTime"]));
				}
				
				$_POST["footerPouredTime"] = $rows['footer_poured_time'];
				if($_POST["footerPouredTime"] > 0) {
					$_POST["footerPouredTime"] = date("m/d/Y", strtotime($_POST["footerPouredTime"]));
				}
				
				$_POST["blockTime"] = $rows['block_time'];
				if($_POST["blockTime"] > 0) {
					$_POST["blockTime"] = date("m/d/Y", strtotime($_POST["blockTime"]));
				}
				
				$_POST["wallCompleteTime"] = $rows['wall_complete_time'];
				if($_POST["wallCompleteTime"] > 0) {
					$_POST["wallCompleteTime"] = date("m/d/Y", strtotime($_POST["wallCompleteTime"]));
				}
				
				$wallCompleteTimeTS = strtotime($_POST["wallCompleteTime"]);
				if($wallCompleteTimeTS > 0) {
					$_POST['wallComplete'] = true;
				}
				
				$_POST["groutAndCapsTime"] = $rows['grout_and_caps_time'];
				if($_POST["groutAndCapsTime"] > 0) {
					$_POST["groutAndCapsTime"] = date("m/d/Y", strtotime($_POST["groutAndCapsTime"]));
				}
				
				$groutAndCapsTimeTS = strtotime($_POST["groutAndCapsTime"]);
				if($groutAndCapsTimeTS > 0) {
					$_POST['groutAndCaps'] = true;
				}
				
				$_POST["warrantyTime"] = $rows['warranty_time'];
				if($_POST["warrantyTime"] > 0) {
					$_POST["warrantyTime"] = date("m/d/Y", strtotime($_POST["warrantyTime"]));
				}
				
				$warrantyTimeTS = strtotime($_POST["warrantyTime"]);
				if($warrantyTimeTS > 0) {
					$_POST['warranty'] = true;
				}
				
				$_POST["poTime"] = $rows['po_time'];
				if($_POST["poTime"] > 0) {
					$_POST["poTime"] = date("m/d/Y", strtotime($_POST["poTime"]));
				}
				
				$poTimeTS = strtotime($_POST["poTime"]);
				if($poTimeTS > 0) {
					$_POST['purchaseOrder'] = true;
				}
				
				$_POST["perimeterBW"] = $rows['perimeter_bw'];
				$_POST["perimeterLW"] = $rows['perimeter_lw'];
				$_POST["perimeterRW"] = $rows['perimeter_rw'];
				$_POST["viewBW"] = $rows['view_bw'];
				$_POST["viewLW"] = $rows['view_lw'];
				$_POST["viewRW"] = $rows['view_rw'];
				$_POST["extensionBW"] = $rows['extension_bw'];
				$_POST["extensionLW"] = $rows['extension_lw'];
				$_POST["extensionRW"] = $rows['extension_rw'];
				$_POST["extras"] = $rows['extras'];
				$_POST["materialAtSite"] = (strcmp($rows['material_at_site'], "0") && strcmp($rows['material_at_site'], '')) ? true : "";
				$_POST["waitForAnything"] = (strcmp($rows['wait_for_anything'], "0") && strcmp($rows['wait_for_anything'], '')) ? true : "";
				$_POST["waitForAnythingTime"] = $rows['wait_for_anything_time'];
				$_POST["concreteLate"] = (strcmp($rows['concrete_late'], "0") && strcmp($rows['concrete_late'], '')) ? true : "";
				$_POST["cleanUp"] = (strcmp($rows['clean_up'], "0") && strcmp($rows['clean_up'], '')) ? true : "";
				$_POST["concreteLateTime"] = $rows['concrete_late_time'];
				$_POST["concrete"] = $rows['concrete'];
				$_POST["cement"] = $rows['cement'];
				$_POST["rebar"] = $rows['rebar'];
				$_POST["blockType"] = $rows['block_type'];
				$_POST["lime"] = $rows['lime'];
				$_POST["miscellaneous"] = $rows['miscellaneous'];
				$_POST["grout"] = $rows['grout'];
				$_POST["others"] = $rows['others'];
				$_POST["workDone"] = (strcmp($rows['work_done'], "0") && strcmp($rows['work_done'], '')) ? true : "";
				$_POST["partialWork"] = (strcmp($rows['partial_work'], "0") && strcmp($rows['partial_work'], '')) ? true : "";
				$_POST["nothingDone"] = (strcmp($rows['nothing_done'], "0") && strcmp($rows['nothing_done'], '')) ? true : "";
				$_POST["supervisorInitials"] = $rows['supervisor_initials'];
				
				$outputMessage="Timesheet found!";
			} else {
				$outputMessage="Timesheet not found!";
			}
		}
	}
	
	function saveTimeSheet() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $leftWallActualLF;
		global $lwRetainerActualLF;
		global $rlwActualCourses;
		global $leftReturnActualLF;
		global $backWallActualLF;
		global $bwRetainerActualLF;
		global $rbwActualCourses;
		global $rightWallActualLF;
		global $rwRetainerActualLF;
		global $rrwActualCourses;
		global $rightReturnActualLF;
		global $handDigDate;
		global $handDigLF;
		global $handPour;
		global $handPourLF;
		global $upHillSide;
		global $upHillSideLF;
		global $wheelBarrow;
		global $wheelBarrowLF;
		global $airCond;
		global $airCondLF;
		global $caps;
		global $capsLF;
		global $courtYard;
		global $courtYardLF;
		global $mailbox;
		global $mailboxLF;
		global $rockVeneer;
		global $rockVeneerSF;
		global $clean;
		global $purchaseOrderNumber;
		global $footerDugDate;
		global $footerSetTime;
		global $footerPouredTime;
		global $blockTime;
		global $wallComplete;
		global $wallCompleteTime;
		global $groutAndCaps;
		global $groutAndCapsTime;
		global $warranty;
		global $warrantyTime;
		global $poTime;
		global $repair;
		global $groutRetainer;
		global $perimeterBW;
		global $perimeterLW;
		global $perimeterRW;
		global $viewBW;
		global $viewLW;
		global $viewRW;
		global $extensionBW;
		global $extensionLW;
		global $extensionRW;
		global $extras;
		global $materialAtSite;
		global $waitForAnything;
		global $waitForAnythingTime;
		global $concreteLate;
		global $concreteLateTime;
		global $cleanUp;
		global $concrete;
		global $cement;
		global $rebar;
		global $blockType;
		global $lime;
		global $miscellaneous;
		global $grout;
		global $others;
		global $workDone;
		global $partialWork;
		global $nothingDone;
		global $supervisorInitials;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".layouts where `builder`='" . $builder . "' && `subdivision`='" .
		       $subdivision . "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		if(strcmp($outputMessage, "  ")) {
			global $date;
			global $backWallLF;
			global $bwRetainerLF;
			global $rbwCourses;
			global $leftWallLF;
			global $lwRetainerLF;
			global $rlwCourses;
			global $rightWallLF;
			global $rwRetainerLF;
			global $rrwCourses;
			global $rightReturnLF;
			global $leftReturnLF;
			global $perimeterBWLF;
			global $perimeterLWLF;
			global $perimeterRWLF;
			global $viewBWLF;
			global $viewLWLF;
			global $viewRWLF;
			global $extensionBWLF;
			global $extensionLWLF;
			global $extensionRWLF;
			
			# Get data from Layout.
			$_POST["courses"] = $rows['courses'];
			$_POST["supervisor"] = $rows['supervisor'];
			$_POST["backWall"] = strcmp($rows['lotBW'], "0") ? true : "";
			$_POST["bwRetainer"] = strcmp($rows['retainerBW'], "0") ? true : "";
			$_POST["rightWall"] = strcmp($rows['lotRW'], "0") ? true : "";
			$_POST["rwRetainer"] = strcmp($rows['retainerRW'], "0") ? true : "";
			$_POST["leftWall"] = strcmp($rows['lotLW'], "0") ? true : "";
			$_POST["lwRetainer"] = strcmp($rows['retainerLW'], "0") ? true : "";
			$_POST["rightReturn"] = strcmp($rows['returnRR'], "0") ? true : "";
			$_POST["leftReturn"] = strcmp($rows['returnLR'], "0") ? true : "";
			$backWallLF = $rows['lotBW'];
			$bwRetainerLF = $rows['retainerBW'];
			$rbwCourses = $rows['rbw_courses'];
			$rightWallLF = $rows['lotRW'];
			$rwRetainerLF = $rows['retainerRW'];
			$rrwCourses = $rows['rrw_courses'];
			$leftWallLF = $rows['lotLW'];
			$lwRetainerLF = $rows['retainerLW'];
			$rlwCourses = $rows['rlw_courses'];
			$rightReturnLF = $rows['returnRR'];
			$leftReturnLF = $rows['returnLR'];
			$_POST["handDig"] = strcmp($action, 'Hand Dig') ? "" : true;
			$_POST["handPour"] = strcmp($rows['hand_pour'], "0") ? true : "";
			$_POST["upHillSide"] = strcmp($rows['up_hill_side'], "0") ? true : "";
			$_POST["caps"] = strcmp($rows['caps'], "0") ? true : "";
			$_POST["wheelBarrow"] = strcmp($rows['wheel_barrow'], "0") ? true : "";
			$_POST["airCond"] = $rows['extraAC'] > 0 ? true : "";
			$_POST["courtYard"] = $rows['extraCY'] > 0 ? true : "";
			$_POST["mailbox"] = strcmp($rows['mailbox'], "0") ? true : "";
			$_POST["rockVeneer"] = strcmp($rows['rock_veneer'], "0") ? true : "";
			$_POST["clean"] = strcmp($action, 'Clean') ? "" : true;
			$_POST["groutAndCaps"] = strcmp($action, 'Grout and Caps') ? "" : true;
			$_POST["warranty"] = strcmp($action, 'Warranty') ? "" : true;
			$_POST["purchaseOrder"] = strcmp($action, 'PO') ? "" : true;
			$_POST["footerDug"] = strcmp($action, 'Dug') ? "" : true;
			$_POST["footerSet"] = strcmp($action, 'Set') ? "" : true;
			$_POST["footerPoured"] = strcmp($action, 'Pour') ? "" : true;
			$_POST["block"] = strcmp($action, 'Block') ? "" : true;
			$_POST["repair"] = strcmp($rows['repair'], "0") ? true : "";
			$_POST["groutRetainer"] = strcmp($rows['grout_retainer'], "0") ? true : "";
			$perimeterBWLF = $rows['perimeterBW'];
			$perimeterLWLF = $rows['perimeterLW'];
			$perimeterRWLF = $rows['perimeterRW'];
			$viewBWLF = $rows['viewBW'];
			$viewLWLF = $rows['viewLW'];
			$viewRWLF = $rows['viewRW'];
			$extensionBWLF = $rows['extraBW'];
			$extensionLWLF = $rows['extraLW'];
			$extensionRWLF = $rows['extraRW'];
			
			// Format Dates.
			$timsheetDateTS = strtotime($timesheetDate);
			if($timsheetDateTS > 0) {
				$timesheetDate = date('m/d/y', $timsheetDateTS);
			}
			$handDigDateTS = strtotime($handDigDate);
			if($handDigDateTS > 0) {
				$handDigDate = date('m/d/y', $handDigDateTS);
			}
			$footerDugDateTS = strtotime($footerDugDate);
			if($footerDugDateTS > 0) {
				$footerDugDate = date('m/d/y', $footerDugDateTS);
			}
			$footerSetTimeTS = strtotime($footerSetTime);
			if($footerSetTimeTS > 0) {
				$footerSetTime = date('m/d/y', $footerSetTimeTS);
			}
			$footerPouredTimeTS = strtotime($footerPouredTime);
			if($footerPouredTimeTS > 0) {
				$footerPouredTime = date('m/d/y', $footerPouredTimeTS);
			}
			$blockTimeTS = strtotime($blockTime);
			if($blockTimeTS > 0) {
				$blockTime = date('m/d/y', $blockTimeTS);
			}
			$wallCompleteTimeTS = strtotime($wallCompleteTime);
			if($wallCompleteTimeTS > 0) {
				$wallCompleteTime = date('m/d/y', $wallCompleteTimeTS);
			}
			$groutAndCapsTimeTS = strtotime($groutAndCapsTime);
			if($groutAndCapsTimeTS > 0) {
				$groutAndCapsTime = date('m/d/y', $groutAndCapsTimeTS);
			}
			
			$warrantyTimeTS = strtotime($warrantyTime);
			if($warrantyTimeTS > 0) {
				$warrantyTime = date('m/d/y', $warrantyTimeTS);
			}
			
			$poTimeTS = strtotime($poTime);
			if($poTimeTS > 0) {
				$poTime = date('m/d/y', $poTimeTS);
			}
			
			$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $timesheetDate . "' && `builder`='" . $builder . "' && `subdivision`='" .
				   $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action . "' && `crew_name`='" . $timesheetCrew . "'";
			$records = mysqli_query($this->con, $sql);
			$rows = mysqli_fetch_array($records);
			$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];
			if(!strcmp($outputMessage, "   ")) {
				$sql = "INSERT INTO " . $databaseName . ".masonrytimesheets (`date`, `builder`, `subdivision`, `lot`, `action`, `crew_name`,
				        `left_wall_actual_size`, `lw_retainer_actual_size`, `rlw_actual_courses`, `left_return_actual_size`, `back_wall_actual_size`,
				        `bw_retainer_actual_size`, `rbw_actual_courses`, `right_wall_actual_size`, `rw_retainer_actual_size`, `rrw_actual_courses`, `right_return_actual_size`,
				        `hand_dig_date`, `hand_dig_size`, `hand_pour_size`, `up_hillside_size`, `wheel_barrow_size`, `ac_size`,
				        `caps_size`, `courtyard_size`, `mailbox_size`, `rock_veneer_size`, `purchase_order_number`, `footer_dug_time`,
				        `footer_set_time`, `footer_poured_time`, `block_time`, `wall_complete_time`, `grout_and_caps_time`, `warranty_time`, `po_time`, `perimeter_bw`, `perimeter_lw`, `perimeter_rw`, `view_bw`,
				        `view_lw`, `view_rw`, `extension_bw`, `extension_lw`, `extension_rw`, `extras`, `material_at_site`, `wait_for_anything`, `wait_for_anything_time`,
				        `concrete_late`, `concrete_late_time`, `clean_up`, `concrete`, `cement`, `rebar`, `block_type`, `lime`, `miscellaneous`,
				        `grout`, `others`, `work_done`, `partial_work`, `nothing_done`, `supervisor_initials`) VALUES ('$timesheetDate', '$builder',
				        '$subdivision', '$lot', '$action', '$timesheetCrew', '$leftWallActualLF', '$lwRetainerActualLF', '$rlwActualCourses', '$leftReturnActualLF', '$backWallActualLF', 
				        '$bwRetainerActualLF', '$rbwActualCourses', '$rightWallActualLF', '$rwRetainerActualLF', '$rrwActualCourses', '$rightReturnActualLF', '$handDigDate',
				        '$handDigLF', '$handPourLF', '$upHillSideLF', '$wheelBarrowLF', '$airCondLF', '$capsLF', '$courtYardLF',
				        '$mailboxLF', '$rockVeneerSF', '$purchaseOrderNumber', '$footerDugDate', '$footerSetTime', '$footerPouredTime',
				        '$blockTime', '$wallCompleteTime', '$groutAndCapsTime', '$warrantyTime', '$poTime', '$perimeterBW', '$perimeterLW', '$perimeterRW', '$viewBW', '$viewLW', '$viewRW',
				        '$extensionBW', '$extensionLW', '$extensionRW', '$extras', '$materialAtSite', '$waitForAnything', '$waitForAnythingTime',
				        '$concreteLate', '$concreteLateTime', '$cleanUp', '$concrete', '$cement', '$rebar', '$blockType', '$lime', '$miscellaneous',
				        '$grout', '$others', '$workDone', '$partialWork', '$nothingDone', '$supervisorInitials')";
				if(mysqli_query($this->con, $sql)) {
					# Save into lotstatuses table.
					$sql = "select * from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" .
				           $subdivision . "' && `lot`='" . $lot . "'";
				   	$records = mysqli_query($this->con, $sql);
					$rows = mysqli_fetch_array($records);
					$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
					if(!strcmp($outputMessage, "  ")) {
						$sql = "INSERT INTO " . $databaseName . ".lotstatuses (`date`, `builder`, `subdivision`, `lot`, `hand_dig_date`,
							   `footer_dug_date`, `footer_set_date`, `footer_pour_date`, `block_date`, `wall_complete_date`, `grout_and_caps_date`,
							   `warranty_date`, `po_date`) VALUES ('$timesheetDate', '$builder', '$subdivision', '$lot', '$handDigDate', '$footerDugDate',
							   '$footerSetTime', '$footerPouredTime', '$blockTime', '$wallCompleteTime', '$groutAndCapsTime', '$warrantyTime', '$poTime')";
						if(!mysqli_query($this->con, $sql)) {
							$outputMessage='Unable to send query for saving lot status: ' . mysqli_error($this->con);
						} else {
							$outputMessage='Saved timesheet successfully.';
						}
					}
				} else {
					$outputMessage='Unable to send query for saving timesheet: ' . mysqli_error($this->con);
				}
			} else {
				$sql = "update " . $databaseName . ".masonrytimesheets set `left_wall_actual_size`='" . $leftWallActualLF . "',
				    `lw_retainer_actual_size`='" . $lwRetainerActualLF . "', `rlw_actual_courses`='" . $rlwActualCourses . "', `left_return_actual_size`='" . $leftReturnActualLF . "',
				    `back_wall_actual_size`='" . $backWallActualLF . "', `bw_retainer_actual_size`='" . $bwRetainerActualLF . "', `rbw_actual_courses`='" . $rbwActualCourses . "',
					`right_wall_actual_size`='" . $rightWallActualLF . "', `rw_retainer_actual_size`='" . $rwRetainerActualLF . "', `rrw_actual_courses`='" . $rrwActualCourses . "',
					`right_return_actual_size`='" . $rightReturnActualLF . "', `hand_dig_date`='" . $handDigDate . "', `hand_dig_size`='" . $handDigLF . "',
					`hand_pour_size`='" . $handPourLF . "', `up_hillside_size`='" . $upHillSideLF . "',
					`wheel_barrow_size`='" . $wheelBarrowLF . "', `ac_size`='" . $airCondLF . "', `caps_size`='" . $capsLF . "',
					`courtyard_size`='" . $courtYardLF . "', `mailbox_size`='" . $mailboxLF . "', `rock_veneer_size`='" . $rockVeneerSF . "',
					`purchase_order_number`='" . $purchaseOrderNumber . "',	`footer_dug_time`='" . $footerDugDate . "',
					`footer_set_time`='" . $footerSetTime . "', `footer_poured_time`='" . $footerPouredTime . "',
					`block_time`='" . $blockTime . "', `wall_complete_time`='" . $wallCompleteTime . "', `grout_and_caps_time`='" . $groutAndCapsTime . "',
					`warranty_time`='" . $warrantyTime . "', `po_time`='" . $poTime . "', `perimeter_bw`='" . $perimeterBW . "', `perimeter_lw`='" . $perimeterLW . "',
					`perimeter_rw`='" . $perimeterRW . "', `view_bw`='" . $viewBW . "', `view_lw`='" . $viewLW . "', `view_rw`='" . $viewRW . "',
					`extension_bw`='" . $extensionBW . "', `extension_lw`='" . $extensionLW . "', `extension_rw`='" . $extensionRW . "',
					`extras`='" . $extras . "', `material_at_site`='" . $materialAtSite . "', `wait_for_anything`='" . $waitForAnything . "',
					`wait_for_anything_time`='" . $waitForAnythingTime . "', `concrete_late`='" . $concreteLate . "',
					`concrete_late_time`='" . $concreteLateTime . "', `clean_up`='" . $cleanUp . "', `concrete`='" . $concrete . "', `cement`='" . $cement . "',
					`rebar`='" . $rebar . "', `block_type`='" . $blockType . "', `lime`='" . $lime . "',
					`lime`='" . $lime . "',	`miscellaneous`='" . $miscellaneous . "', `grout`='" . $grout . "', `others`='" . $others . "',
					`work_done`='" . $workDone . "', `partial_work`='" . $partialWork  . "', `nothing_done`='" . $nothingDone . "',
					`supervisor_initials`='" . $supervisorInitials . 
					"' where `date`='" . $timesheetDate . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' &&
					`lot`='" . $lot . "' && `action`='" . $action . "' && `crew_name`='" . $timesheetCrew . "'";
			
				$updateLotStatusQuery = '';
				if(mysqli_query($this->con, $sql)) {
					if(!strcmp($action, "Hand Dig")) {
						if($handDigDate > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `hand_dig_date`='" . $handDigDate .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `hand_dig_date`=''
								 where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					} else if(!strcmp($action, "Dug")) {
						if($footerDugDate > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_dug_date`='" . $footerDugDate .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_dug_date`=''
								 where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					} else if(!strcmp($action, "Set") || !strcmp($action, "Pour")) { // Note that Set and Pour are often made the same day.
						if($footerSetTime > 0 && $footerPouredTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_set_date`='" . $footerSetTime .
								   "', `footer_pour_date`='" . $footerPouredTime . "' where `builder`='" . $builder .
								   "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else if($footerSetTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_set_date`='" . $footerSetTime .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else if($footerPouredTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_pour_date`='" . $footerPouredTime .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else if($footerSetTime <= 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_set_date`=''
								where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else if($footerPouredTime <= 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `footer_pour_date`=''
								where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					} else if(!strcmp($action, "Block")) {
						if($blockTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `block_date`='" . $blockTime .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `block_date`=''
								 where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					} else if(!strcmp($action, "Grout and Caps")) {
						if($groutAndCapsTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `grout_and_caps_date`='" . $groutAndCapsTime .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `grout_and_caps_date`=''
								 where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					} else if(!strcmp($action, "Warranty")) {
						if($warrantyTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `warranty_date`='" . $warrantyTime .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `warranty_date`=''
								 where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					} else if(!strcmp($action, "PO")) {
						if($poTime > 0) {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `po_date`='" . $poTime .
								   "' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						} else {
							$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `po_date`=''
								 where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
						}
					}
					
					if(strcmp($updateLotStatusQuery, '')) {
						// Check if lot status hasn't been created yet.
						// This could be the case when a Timesheet is created from Layouts.
						$checkLotStatusExistQuery = "select * from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" .
			            $subdivision . "' && `lot`='" . $lot . "'";
					   	$checkLotStatusExistRecords = mysqli_query($this->con, $checkLotStatusExistQuery);
						$checkLotStatusExistRows = mysqli_fetch_array($checkLotStatusExistRecords);
						$checkLotStatusExistData=$checkLotStatusExistRows['builder'] . ' ' . $checkLotStatusExistRows['subdivision'] . ' ' . $checkLotStatusExistRows['lot'];
						if(!strcmp($checkLotStatusExistData, "  ")) {
							$createLotStatusQuery = "INSERT INTO " . $databaseName . ".lotstatuses (`date`, `builder`, `subdivision`, `lot`) VALUES
							   ('$timesheetDate', '$builder', '$subdivision', '$lot')";
							mysqli_query($this->con, $createLotStatusQuery);
						}
						
						if(mysqli_query($this->con, $updateLotStatusQuery)) {
							if($wallCompleteTime > 0) {
								$updateWallCompleteQuery = "update " . $databaseName . ".lotstatuses set `wall_complete_date`='" . $wallCompleteTime . "' where `builder`='" . $builder . "'
									    && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
								mysqli_query($this->con, $updateWallCompleteQuery);
							}
							
							$outputMessage='Timesheet updated successfully.';
							
							// Check Dug, Set, Pour, and Block have a date.
							$lotStatusQuery = "select * from " . $databaseName . ".lotstatuses where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
							$lotStatusRecords = mysqli_query($this->con, $lotStatusQuery);
							$lotStatusRow = mysqli_fetch_array($lotStatusRecords);
							$lotStatusFound = $lotStatusRow['builder'] . ' ' . $lotStatusRow['subdivision'] . ' ' . $lotStatusRow['lot'];
							if(strcmp($lotStatusFound, '  ')) {
								// Check wall complete has been notified already.
								if($lotStatusRow['wall_notified'] != 1) {
									// Check Wall has a date.
									$wallCompleteTimeTS = strtotime($lotStatusRow['wall_complete_date']);
									if($wallCompleteTimeTS > 0) {
										$message = $builder . ' ' . $subdivision . ' ' . $lot . ' Wall has completed.';
										// Send notification.
										// FIXME Send notification in a separate Task.
										if(sendEmail($message)) {
											$sql = "update " . $databaseName . ".lotstatuses set `wall_notified`= '" . '1' . "' where `builder`='" . $builder . "'
						    				&& `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";;
											if(!mysqli_query($this->con, $sql)) {
												$outputMessage='Unable to update lotstatuses: ' . mysqli_error($this->con);
											}
										}
									}
								}
								// Check PO complete has been notified already.
								if($lotStatusRow['po_notified'] != 1) {
									// Check Wall has a date.
									$poTimeTS = strtotime($lotStatusRow['po_date']);
									if($poTimeTS > 0) {
										$message = $builder . ' ' . $subdivision . ' ' . $lot . ' PO has completed.';
										// Send notification.
										// FIXME Send notification in a separate Task.
										if(sendEmail($message)) {
											$sql = "update " . $databaseName . ".lotstatuses set `po_notified`= '" . '1' . "' where `builder`='" . $builder . "'
						    				&& `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";;
											if(!mysqli_query($this->con, $sql)) {
												$outputMessage='Unable to update lotstatuses: ' . mysqli_error($this->con);
											}
										}
									}
								}
							}
						} else {
							$outputMessage='Unable to edit lot status: ' . mysqli_error($this->con);
						}
					} // Nothing to update on lotstatus.
				} else {
					$outputMessage='Unable to edit timesheet: ' . mysqli_error($this->con);
				}
			}
		} else {
			$outputMessage='No layout found.'; 
		}
	}
	
	function saveWorkerTimes() {
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $workerTimes;
		global $workerName;
		global $startTime;
		global $stopTime;
		global $outputMessage;
		
		$workerTimesArr = split(";", $workerTimes);
		$invalidWorkerTimes = false;
		$invalidWorkerTimeName = '';
		for ($i = 0; $i < sizeof($workerTimesArr) - 1; $i++) {
			list($workerName, $startTime, $stopTime) = split(",", $workerTimesArr[$i]);
			if(!$this->saveWorkerTime()) {
				$invalidWorkerTimes = true;
				$invalidWorkerTimeName = $workerName;
			}
		}
		
		if($invalidWorkerTimes) {
			$outputMessage = 'Invalid worker hours for ' . $invalidWorkerTimeName;
		}
	}
	
	function saveWorkerTime() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $workerName;
		global $startTime;
		global $stopTime;
		global $actionCostTemp;
		global $outputMessage;
		
		$totalTime = strtotime($stopTime) - strtotime($startTime);
		// Convert time from seconds to hours with 2 decimals.
		$totalTime = number_format(($totalTime / 60 / 60), 2);
		
		// Invalid number of hours.
		if($totalTime < 0) {
			return false;
		}
		
		$timsheetDateTS = strtotime($timesheetDate);
		if($timsheetDateTS > 0) {
			$timesheetDate = date('m/d/y', $timsheetDateTS);
		}
		
		$sql = "select * from " . $databaseName . ".workers where `date`='" . $timesheetDate . "' && `builder`='" . $builder . "' && `subdivision`='" .
			   $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action . "' && `name`='" . $workerName . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];
		if(!strcmp($outputMessage, "   ")) {
			$sql = "INSERT INTO " . $databaseName . ".workers (date, builder, subdivision, lot, action, crew_name,
				name, start_time, stop_time, total_time) VALUES ('$timesheetDate', '$builder', '$subdivision', '$lot',
			 	'$action', '$timesheetCrew', '$workerName', '$startTime', '$stopTime', '$totalTime')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Saved Worker times successfully.';
			} else {
				$outputMessage='Unable to save Worker: ' . mysqli_error($this->con);
			}
		} else {
			$sql = "update " . $databaseName . ".workers set `start_time`='" . $startTime . "',
			`stop_time`='" . $stopTime . "', `total_time`='" . $totalTime . "' where `date`='" . $timesheetDate . "' &&
			`name`='" . $workerName . "' &&	`builder`= '" . $builder . "' && `subdivision`='" . $subdivision . "' &&
			`lot`='" . $lot . "' &&	`action`='" . $action . "'";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Worker times updated successfully.';
			} else {
				$outputMessage='Unable to send query: ' . mysqli_error($this->con);
			}
		}
		return true;
	}
	
	function deleteTimeSheet() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $outputMessage;
		
		$timesheetDateTS = strtotime($timesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $date . "' && `builder`='" .
				 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action .
				 "' && `crew_name`='" . $timesheetCrew . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];
		if(strcmp($outputMessage, "   ")) {
			$deleteTimesheetQuery= "delete from " . $databaseName . ".masonrytimesheets where `date`='" . $date . "' && `builder`='" . $builder . "' && `subdivision`='" .
					$subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action . "' && `crew_name`='" . $timesheetCrew . "'";
			if(!mysqli_query($this->con, $deleteTimesheetQuery)) {
				$outputMessage = 'Unable to delete timesheet.';
			} else {
				// Delete Worker Times from workers table.
				$deleteWorkersQuery = "delete from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" . $builder
			       . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action
			       . "' && `crew_name`='" . $timesheetCrew . "'";
				if(!mysqli_query($this->con, $deleteWorkersQuery)) {
					$outputMessage = 'Unable to delete workers.';
				}
				
				// Only reset lotstatus date when there are no more timesheets with the corresponding action.
				$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" .
				       $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action . "'";
				$records = mysqli_query($this->con, $sql);
				$rows = mysqli_fetch_array($records);
				$queryData=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];
				if(!strcmp($queryData, "   ")) {
					if(!strcmp($action, "Hand Dig")) {
						$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `date`='" . $date . "', `hand_dig_date`='" . '' .
						"' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
					} else if(!strcmp($action, "Dug")) {
						$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `date`='" . $date . "', `hand_dug_date`='" . '' .
							"' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
					} else if(!strcmp($action, "Set")) {
						$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `date`='" . $date . "', `hand_set_date`='" . '' .
							"' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
					} else if(!strcmp($action, "Pour")) {
						$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `date`='" . $date . "', `hand_pour_date`='" . '' .
							"' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
					} else if(!strcmp($action, "Block")) {
						$updateLotStatusQuery = "update " . $databaseName . ".lotstatuses set `date`='" . $date . "', `hand_block_date`='" . '' .
							"' where `builder`='" . $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "'";
					}
				
					if(isset($updateLotStatusQuery)) {
						if(!mysqli_query($this->con, $updateLotStatusQuery)) {
							$outputMessage = 'Unable to update lot status.';
						}
					}
				}
				
				$outputMessage = 'Successfully deleted ' . $builder . " " . $subdivision . " " . $lot . ' with ' .
								 $action . ' Action';
				clearTimeSheet();
			}
		} else {
			$outputMessage="Timesheet is not found!";
		}
		
		// Check if there are timesheets associated with the lotstatus.
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" .
		 	   $subdivision . "' && `lot`='" . $lot . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$selectedTimesheet=$rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'];
		// Delete lot status only if there are no timesheets associated.
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
					clearTimeSheet();
					$outputMessage = 'Successfully deleted ' . $builder . " " . $subdivision . " " . $lot . ' with ' .
								 $action . ' Action and LotStatus';
				}
			}
		}
	}
	
	function addEmployee() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $selectedEmployee;
		global $outputMessage;
		
		// Parse date.
		$timesheetDateTS = strtotime($timesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		// Check if Timesheet exists.
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $date . "' && `builder`='" .
				 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$queryData = $rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];
		if(strcmp($queryData, "   ")) {
			// Check if there are any workers already persisted corresponding to this timesheet.
			$checkWorkersQuery = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" .
					 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" .
					 $action . "' && `crew_name`='" . $timesheetCrew . "'";
			$checkWorkersRecords = mysqli_query($this->con, $checkWorkersQuery);
			$checkWorkersRows = mysqli_fetch_array($checkWorkersRecords);
			$checkWorkerData = $checkWorkersRows['builder'] . ' ' . $checkWorkersRows['subdivision'] . ' ' .
						   $checkWorkersRows['lot'] . ' ' . $checkWorkersRows['action'];
		   if(strcmp($checkWorkerData, "   ")) {
				// Add Employee to Workers table if it doesn't exist and there are already workers in workers table.
				$checkWorkerQuery = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" .
						 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" .
						 $action . "' && `crew_name`='" . $timesheetCrew . "' && `name`='" . $selectedEmployee . "'";
				$checkWorkerRecords = mysqli_query($this->con, $checkWorkerQuery);
				$checkWorkerRows = mysqli_fetch_array($checkWorkerRecords);
				$checkWorkerData = $checkWorkerRows['builder'] . ' ' . $checkWorkerRows['subdivision'] . ' ' .
							   $checkWorkerRows['lot'] . ' ' . $checkWorkerRows['action'];
				if(!strcmp($checkWorkerData, "   ")) {
					$addWorkerQuery = "INSERT INTO " . $databaseName . ".workers (date, builder, subdivision, lot, action, crew_name, name)
					 VALUES ('$date', '$builder', '$subdivision', '$lot', '$action', '$timesheetCrew', '$selectedEmployee')";
					if(!mysqli_query($this->con, $addWorkerQuery)) {
						$outputMessage='Unable to save Worker: ' . mysqli_error($this->con);
					}
				} else {
					$outputMessage="Worker already exists.";
				}
		   }
			
			// Update Employee's Crew
			$updateEmployeeQuery = "update  " . $databaseName . ".employees set `crew_name`='" . $timesheetCrew . "'
				 where concat(TRIM(`first`), ' ', TRIM(`last`))='" . $selectedEmployee . "'";
			if(mysqli_query($this->con, $updateEmployeeQuery)) {
				$outputMessage='Added Employee to Crew successfully.';
				$_POST["selectedEmployee"] = '';
			} else {
				$outputMessage='Unable to update Employee Crew: ' . mysqli_error($this->con);
			}
		} else {
			$outputMessage="Timesheet not found!";
		}
	}
	
	function deleteEmployee() {
		global $databaseName;
		global $timesheetDate;
		global $builder;
		global $subdivision;
		global $lot;
		global $action;
		global $timesheetCrew;
		global $selectedEmployee;
		global $outputMessage;
		
		// Parse date.
		$timesheetDateTS = strtotime($timesheetDate);
		if($timesheetDateTS > 0) {
			$date = date('m/d/y', $timesheetDateTS);
		}
		
		// Check if Timesheet exists.
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `date`='" . $date . "' && `builder`='" .
				 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" . $action . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$queryData = $rows['builder'] . ' ' . $rows['subdivision'] . ' ' . $rows['lot'] . ' ' . $rows['action'];
		if(strcmp($queryData, "   ")) {
			// Update Employee's Crew
			$updateEmployeeQuery = "update  " . $databaseName . ".employees set `crew_name`=''
			 	where concat(TRIM(`first`), ' ', TRIM(`last`))='" . $selectedEmployee . "'";
			if(!mysqli_query($this->con, $updateEmployeeQuery)) {
				$outputMessage='Unable to update Employee Crew: ' . mysqli_error($this->con);
				return;
			} else {
				$outputMessage = 'Removed employee from Crew successfully';
			}
			$_POST["selectedEmployee"] = '';
			
			// Delete Employee from Workers table if it exists.
			$checkWorkerQuery = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" .
					 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" .
					 $action . "' && `crew_name`='" . $timesheetCrew . "' && `name`='" . $selectedEmployee . "'";
			$checkWorkerRecords = mysqli_query($this->con, $checkWorkerQuery);
			$checkWorkerRows = mysqli_fetch_array($checkWorkerRecords);
			$checkWorkerData = $checkWorkerRows['builder'] . ' ' . $checkWorkerRows['subdivision'] . ' ' .
						   $checkWorkerRows['lot'] . ' ' . $checkWorkerRows['action'];
			if(strcmp($checkWorkerData, "   ")) {
				$deleteWorkerQuery = "delete from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" .
					 $builder . "' && `subdivision`='" . $subdivision . "' && `lot`='" . $lot . "' && `action`='" .
					 $action . "' && `crew_name`='" . $timesheetCrew . "' && `name`='" . $selectedEmployee . "'";
				if(mysqli_query($this->con, $deleteWorkerQuery)) {
					$outputMessage='Deleted Worker times successfully.';
				} else {
					$outputMessage='Unable to delete Worker: ' . mysqli_error($this->con);
				}
			}
		} else {
			$outputMessage="Timesheet not found!";
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>