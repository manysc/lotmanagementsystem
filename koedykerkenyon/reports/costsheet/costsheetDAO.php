<?php

class costsheetDAO {
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
	
	function getWorkers($date, $builder, $subdivision, $lot, $action, $crewName) {
		global $databaseName;
		$employeesArr = array();
		$sql = "select * from " . $databaseName . ".workers where `date`='" . $date . "' && `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			   "' && `lot`='" . $lot  . "' && `action`='" . $action . "' && `crew_name`='" . $crewName . "'";
		$records = mysqli_query($this->con, $sql);
		while($rows = mysqli_fetch_array($records)) {
			$employee = new Employee();
			$employee->setName($rows['name']);
			$employee->setTotalTime($rows['total_time']);
			$employee->setCost($rows['cost']);
			array_push($employeesArr, $employee);
		}
		
		return $employeesArr;
	}
	
	function displayWorkers() {
		global $action;
		$lotCost = 0;
		
		if(!empty($action)) {
			$lotCost += $this->displayWorkersByAction($action);
			return;
		}
		
		$lotCost += $this->displayWorkersByAction('Hand Dig');
		$lotCost += $this->displayWorkersByAction('Dug');
		$lotCost += $this->displayWorkersByAction('Set');
		$lotCost += $this->displayWorkersByAction('Pour');
		$lotCost += $this->displayWorkersByAction('Block');
		$lotCost += $this->displayWorkersByAction('Caps');
		$lotCost += $this->displayWorkersByAction('Backfill');
		$lotCost += $this->displayWorkersByAction('Clean');
		$lotCost += $this->displayWorkersByAction('Warranty');
		$lotCost += $this->displayWorkersByAction('PO');
		
		echo "<fieldset>";
		echo "<legend class='sectionLegend'>Total Lot Cost</legend>";
		echo "<label class='formHeaderLabel'>Lot Cost: $lotCost";
		echo "</fieldset><br />";
	}
	
	function displayWorkersByAction($action) {
		global $databaseName;
		global $builder;
		global $subdivision;
		global $lot;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".masonrytimesheets where `builder`='" . $builder . "' && `subdivision`='" . $subdivision .
			   "' && `lot`='" . $lot . "' && `action`='" . $action . "' order by date";
		$records = mysqli_query($this->con, $sql);
		$actionCost = 0;
		while($rows = mysqli_fetch_array($records)) {
			$crewName = $rows['crew_name'];
			$date = $rows['date'];
			$employeesArr = $this->getWorkers($date, $builder, $subdivision, $lot, $action, $crewName);
			$count = 0; 
			$crewCost = 0;
			for($j=0;$j<count($employeesArr);$j++) {
				$employee = $employeesArr[$j];
				$workerName = $employee->oName;
				$totalTime = $employee->oTotalTime;
				$cost = $employee->oCost;
				
				if($totalTime == 0) {
					$totalTime = '';
				}
				if($cost == 0) {
					$cost = '';
				}
				
				if($count == 0) {
					echo "<fieldset>";
					echo "<legend class='sectionLegend'>$date $action Crew</legend>";
					echo "<fieldset>";
					echo '<table width="70%" border="5" cellpadding="1" cellspacing="20" class="db-table">';
					echo '<tr><th style="border:0px solid black;">Name</th><th style="border:0px solid black;">Total</th><th style="border:0px solid black;">Cost</th></tr>';
				}
				echo '<tr>';
				echo "<td width='50%' style='border:0px solid black;' align='left'>$workerName</td>";
				
				echo "<td align='center'>$totalTime</td>";
				
				if($totalTime > 0) {
					echo "<td align='center'>$cost</td>";
					$crewCost = $crewCost + $cost;
				} else {
					echo "<td align='center'></td>";
				}
				
		        echo "</tr>";
				$count++;
			}
			$actionCost += $crewCost;
			
			if($count > 0) {
				echo "</table>";
				echo "</fieldset>";
				echo "<br/>";
				echo "<label class='formHeaderLabel'>Crew Cost: $crewCost";
				echo "</fieldset><br />";
			}
		}
		return $actionCost;
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>