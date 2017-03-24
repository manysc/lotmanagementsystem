<?php

class employeeDAO {
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
	
	function query($query) {
		mysqli_query($this->con,$query);
	}
	
	function queryEmployees() {
		global $databaseName;
		$sql = "select * from " . $databaseName . ".employees";
		$records = mysqli_query($this->con, $sql); 
		while($rows = mysqli_fetch_array($records)){
			echo "<br /><b>First:</b>" . $rows['first'];
			echo "<b> Last:</b>" . $rows['last'];
			echo "<b> Wage:<b>" . $rows['wage'] . '<br />';
		}
	}
	
	function searchEmployee() {
		global $databaseName;
		global $first;
		global $last;
		global $crewName;
		global $outputMessage;
		$sql = "select * from " . $databaseName . ".employees where `first`='" . $first . "' && `last`='" . $last . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['first'] . ' ' . $rows['last'] . ' ' . $rows['wage'];
		if(!strcmp($outputMessage, "  ")) {
			$outputMessage=$first . ' ' . $last . " not found in the list of employees.";
			clearEmployeeExcludingName();
		} else {
			$outputMessage="Employee found!";
			$_POST["wage"] = $rows['wage'];
			$_POST["email"] = $rows['email'];
			$_POST["supervisor"] = !strcmp($rows['is_supervisor'], 1) ? $rows['is_supervisor'] : "";
			$_POST["forman"] = !strcmp($rows['is_forman'], 1) ? $rows['is_forman'] : "";
			$_POST["mason"] = !strcmp($rows['is_mason'], 1) ? $rows['is_mason'] : "";
			$_POST["apprentice"] = !strcmp($rows['is_apprentice'], 1) ? $rows['is_apprentice'] : "";
			$_POST["labor"] = !strcmp($rows['is_labor'], 1) ? $rows['is_labor'] : "";
			$_POST["driver"] = !strcmp($rows['is_driver'], 1) ? $rows['is_driver'] : "";
			$_POST["operator"] = !strcmp($rows['is_operator'], 1) ? $rows['is_operator'] : "";
			$_POST["footer"] = !strcmp($rows['is_footer'], 1) ? $rows['is_footer'] : "";
			$crewName = $rows['crew_name'];
		}
	}
	
	function saveEmployee() {
		global $databaseName;
		global $date;
		global $first;
		global $last;
		global $wage;
		global $email;
		global $isSupervisor;
		global $isForman;
		global $isMason;
		global $isApprentice;
		global $isLabor;
		global $isDriver;
		global $isOperator;
		global $isFooter;
		global $outputMessage;
		global $crewName;
		
		$sql = "select * from " . $databaseName . ".employees where `first`='" . $first . "' && `last`='" . $last . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['first'] . ' ' . $rows['last'] . ' ' . $rows['wage'];
		if(!strcmp($outputMessage, "  ")) {
			$sql = "INSERT INTO " . $databaseName . ".employees (date, first, last, wage, email, is_supervisor, is_forman, is_mason, is_apprentice,
				   is_labor, is_driver, is_operator, is_footer) VALUES ('$date', '$first', '$last', '$wage', '$email', '$isSupervisor', '$isForman',
				   '$isMason', '$isApprentice', '$isLabor', '$isDriver', '$isOperator', '$isFooter')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Saved employee successfully.';
			} else {
				$outputMessage='Unable to save employee: ' . mysqli_error($this->con);
			}
		} else {
			$crewName = $rows['crew_name'];
			$sql = "update " . $databaseName . ".employees set `date`='" . $date . "', `wage`='" . $wage . "', `email`='" . $email . "', `is_supervisor`='" .
			 	   $isSupervisor . "', `is_forman`='" . $isForman . "', `is_mason`='" . $isMason . "', `is_apprentice`='" . $isApprentice . "', `is_labor`='" .
				   $isLabor . "', `is_driver`='" . $isDriver . "', `is_operator`='" . $isOperator . "', `is_footer`='" .
				   $isFooter . "' where `first`='" . $first . "' && `last`='" . $last . "'";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage='Updated employee successfully.';
			} else {
				$outputMessage='Unable to update employee: ' . mysqli_error($this->con);
			}
		}
	}
	
	function deleteEmployee() {
		global $databaseName;
		global $first;
		global $last;
		global $outputMessage;
		$sql = "select * from " . $databaseName . ".employees where `first`='" . $first . "' && `last`='" . $last . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['first'] . ' ' . $rows['last'] . ' ' . $rows['wage'];
		if(strcmp($outputMessage, "  ")) {
			$sql = "delete from " . $databaseName . ".employees where `first`='" . $first . "' && `last`='" . $last . "'";
			mysqli_query($this->con, $sql);
			$outputMessage = 'Successfully deleted ' . $first . " " . $last;
			clearEmployee();
		} else {
			$outputMessage="Employee is not found!";
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>