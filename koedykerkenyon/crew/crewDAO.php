<?php

class crewDAO {
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
	
	function displayCrewDropMenu() {
		global $databaseName;
		global $crewName;
		$sql = "select * from " . $databaseName . ".crews order by crew_name";
		$records = mysqli_query($this->con, $sql);
		$count = 0; 
		
		while($rows = mysqli_fetch_array($records)) {
			if($count == 0) {
				echo '<select name="crewDropMenu" id="crewDropMenu" style="font-size:23px" onclick="setSelectedCrew()">';
				echo "<option value=''></option>";
			}
			
			$crewName = $rows['crew_name'];
			
			if(isset($_POST["selectedCrew"])) {
				if(!strcmp($_POST["selectedCrew"], $crewName)) {
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
	
	function displayWorkerDropMenu() {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $crewName;
		global $workerName;
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' order by last";
		$records = mysqli_query($this->con, $sql);
		$count = 0; 
		
		while($rows = mysqli_fetch_array($records)) {
			if($count == 0) {
				echo '<label style="font-size:24px">Delete Worker from selected Crew:</label><br/>';
				echo '<label style="font-size:24px"><strong>Worker:&nbsp;&nbsp;&nbsp;</strong></label>';
				echo '<select name="workerDropMenu" id="workerDropMenu" style="font-size:23px" onclick="setSelectedWorker()">';
				echo "<option value=''></option>";
			}
			
			$workerName = $rows['last'] . ', ' . $rows['first'];
			
			if(isset($_POST["selectedWorker"])) {
				if(!strcmp($_POST["selectedWorker"], $workerName)) {
					echo "<option value=$workerName selected='selected'>$workerName</option>";
				} else {
					echo "<option value=$workerName>$workerName</option>";
				}
			} else {
				echo "<option value=$workerName>$workerName</option>";
			}
			
			$count++;
		}
		if($count > 0) {
			echo '</select>';
			echo '&nbsp;<input class="sectionButton" type="submit" name="deleteWorker" id="deleteWorker" value="Delete">';
			echo '<br/><br/>';
		}
	}
	
	function displayEmployeeDropMenu() {
		global $databaseName;
		$sql = "select * from " . $databaseName . ".employees order by last";
		$records = mysqli_query($this->con, $sql);
		$count = 0; 
		
		while($rows = mysqli_fetch_array($records)) {
			if($count == 0) {
				echo '<label class="formLabel">Add employee to selected Crew:</label><br/>';
				echo '<label class="formLabel"><strong>Employee:&nbsp;&nbsp;</strong></label>';
				echo '<select name="employeeDropMenu" id="employeeDropMenu" style="font-size:23px" onclick="setSelectedEmployee()">';
				echo "<option value=''></option>";
			}
			
			$employeeName = $rows['last'] . ', ' . $rows['first']; 
			
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
			echo '&nbsp;<input class="sectionButton" type="submit" name="addEmployee" id="addEmployee" value="Add">';
			echo '<br/><br/>';
		}
	}
	
	function searchCrew() {
		global $databaseName;
		global $crewName;
		global $workerList;
		global $outputMessage;
		$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "' order by first";
		$records = mysqli_query($this->con, $sql);
		
		$count = 0;
		while($rows = mysqli_fetch_array($records)){
			// Display Header.
			if($count == 0) {
				echo "<br/><fieldset>";
				echo "<legend style='font-size:24px'><strong>Workers</strong></legend>";
				echo '<table border="0" cellpadding="1" cellspacing="20" class="db-table">';
				echo '<tr><th>Name</th><th>Title</th></tr>';
			}
			echo '<tr>';
			
			$title = "";
			$title = !strcmp($rows['is_supervisor'], 1) ? $title . "Supervisor " : $title;
			$title = !strcmp($rows['is_forman'], 1) ? $title . "Forman " : $title;
			$title = !strcmp($rows['is_mason'], 1) ? $title . "Mason " : $title;
			$title = !strcmp($rows['is_apprentice'], 1) ? $title . "Apprentice " : $title;
			$title = !strcmp($rows['is_labor'], 1) ? $title . "Labor " : $title;
			$title = !strcmp($rows['is_driver'], 1) ? $title . "Driver " : $title;
			$title = !strcmp($rows['is_operator'], 1) ? $title . "Operator " : $title;
			$title = !strcmp($rows['is_footer'], 1) ? $title . "Footer " : $title;
			
		    $workerName = $rows['first'] . " " . $rows['last'];
		    echo "<td align='left'>$workerName</td>";
		    echo "<td align='left'>$title</td>";
		    
		    echo "</tr>";
		    $count++;
		}
		echo "</table>";
		echo "</fieldset>";
		
		if($count == 0) {
			$outputMessage = "No workers have been assigned to " . $crewName;
		}
	}
	
	function saveCrew() {
		global $databaseName;
		global $date;
		global $crewName;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".crews where `crew_name`='" . $crewName . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['crew_name'];
		if(!strcmp($outputMessage, "")) {
			$sql = "INSERT INTO " . $databaseName . ".crews (date, crew_name) VALUES ('$date', '$crewName')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Saved Crew successfully.';
				$crewName = '';
			} else {
				$outputMessage='Unable to save employee: ' . mysqli_error($this->con);
			}
		} else {
			$outputMessage = $outputMessage . ' has already been saved.';
		}
	}
	
	function deleteCrew() {
		global $databaseName;
		global $date;
		global $crewName;
		global $outputMessage;
		$sql = "select * from " . $databaseName . ".crews where `crew_name`='" . $crewName . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['crew_name'];
		if(strcmp($outputMessage, " ")) {
			$sql = "delete from " . $databaseName . ".crews where `crew_name`='" . $crewName . "'";
			if(mysqli_query($this->con, $sql)) {
				$sql = "select * from " . $databaseName . ".employees where `crew_name`='" . $crewName . "'";
				$records = mysqli_query($this->con, $sql);
				while($rows = mysqli_fetch_array($records)){
					$sql = "update " . $databaseName . ".employees set `date`='" . $date . "', `crew_name`='' where `first`='" . $rows['first'] . "' && `last`='" . $rows['last'] . "'";
					if(mysqli_query($this->con, $sql)) {
						$outputMessage = 'Successfully removed ' . $rows['first'] . ' ' . $rows['last'] . ' from ' . $crewName . ' Crew.';
					} else {
						$outputMessage = 'Unable to remove ' . $rows['first'] . ' ' . $rows['last'] . ' from ' . $crewName . ' Crew.';;
					}
				}
				$outputMessage = 'Successfully deleted ' . $crewName . ' Crew.';
				clearCrew();
			} else {
				$outputMessage = 'Unable to delete ' . $crewName . ' Crew.';
			}
		} else {
			$outputMessage="Crew is not found!";
		}
	}
	
	function deleteWorker() {
		global $databaseName;
		global $date;
		global $crewName;
		global $workerName;
		global $outputMessage;
		
		list($last, $first) = explode(", ", $workerName);
		
		$sql = "select * from " . $databaseName . ".employees where `first`='" . $first . "' && `last`='" . $last . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['crew_name'];
		if(strcmp($outputMessage, "")) {
			$sql = "update " . $databaseName . ".employees set `date`='" . $date . "', `crew_name`='' where `first`='" . $first . "' && `last`='" . $last . "'";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage = 'Successfully deleted ' . $workerName . ' from ' . $crewName . ' Crew.';
				$workerName = '';
			} else {
				$outputMessage = 'Unable to delete ' . $workerName;
			}
		} else {
			$outputMessage="Worker is not found!";
		}
	}
	
	function addEmployee() {
		global $databaseName;
		global $date;
		global $crewName;
		global $employeeName;
		global $outputMessage;
		
		list($last, $first) = explode(", ", $employeeName);
		
		$sql = "select * from " . $databaseName . ".employees where `first`='" . $first . "' && `last`='" . $last . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['first'] . ' ' . $rows['last'];
		if(strcmp($outputMessage, " ")) {
			$sql = "update " . $databaseName . ".employees set `date`='" . $date . "', `crew_name`='" . $crewName . "' where `first`='" . $first . "' && `last`='" . $last . "'";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage = 'Successfully added ' . $employeeName . ' to ' . $crewName . ' Crew.';
				$_POST["selectedEmployee"] = '';
			} else {
				$outputMessage = 'Unable to add ' . $employeeName;
			}
		} else {
			$outputMessage="Employee is not found!";
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>