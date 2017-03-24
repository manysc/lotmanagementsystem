<?php

class weekCostDAO {
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
	
	function searchWeekCost() {
		global $databaseName;
		global $outputMessage;
		
		$count = 0;
		$dateTime = new DateTime();
		$date = $_POST['fromDate'];
		$week = (int)date('W', strtotime($date));
		$year = date('Y', strtotime($date));
  		$weekStart = $dateTime->setISODate($year, $week);
  		$weekStart = $dateTime->format('m/d/y'); // Call to a member function format() on a non-object. 
  		$weekEnd = $dateTime->modify('+6 days');
  		$weekEnd = $dateTime->format('m/d/y'); // Call to a member function format() on a non-object.
  		
		$dateTS = strtotime($date);
		if($dateTS > 0) {
			$date = date('m/d/y', $dateTS);
		}
		//$weekCostQuery = "select * from " . $databaseName . ".masonrytimesheets where `date`>='" . $weekStart .
		//	   "' and `date`<='" . $weekEnd . "' and `action_cost`>0 order by crew_name,date,CAST(lot as decimal)";
		$weekCostQuery = "SELECT date, builder, subdivision, lot, action, crew_name, SUM(action_cost) as action_cost from " . $databaseName . ".masonrytimesheets where `date`>='" . $weekStart .
			   "' and `date`<='" . $weekEnd . "' and `action_cost`>0 group by crew_name,builder,subdivision,CAST(lot as decimal)";
		$weekCostRecords = mysqli_query($this->con, $weekCostQuery);
		
		$lastBuilder = '';
		$lastSubdivision = '';
		$lastCrew = '';
		$totalCost = 0;
		$weekCost = 0;
		while($weekCostRows = mysqli_fetch_array($weekCostRecords)){
			$date = $weekCostRows['date'];
			$builder = $weekCostRows['builder'];
			$subdivision = $weekCostRows['subdivision'];
			$lot = $weekCostRows['lot'];
			$action = $weekCostRows['action'];
			$crewName = $weekCostRows['crew_name'];
			$cost = $weekCostRows['action_cost'];
			
			if($crewName != $lastCrew) {
				if($lastCrew != '') {
					echo "<tr>";
					echo "<td align='center' style='background-color:#0BA;'></td>";
					echo "<td align='center' style='background-color:#0BA;'></td>";
					echo "<td align='center' style='background-color:#0BA;'></td>";
					echo "<td align='center' style='background-color:#0BA;'></td>";
					echo "<td align='center' style='background-color:#0BA;'>$lastCrew</td>";
					echo "<td align='center' style='background-color:#0BA;'><strong>Total</strong></td>";
					echo "<td align='center' style='background-color:#0BA;'><strong>$totalCost</strong></td>";
					echo "</tr>";
				}
				
				$lastCrew = $crewName;
				$weekCost+=$totalCost;
				$totalCost=0;
			}
			
			echo '<tr>';
			echo "<td align='center'>$date</td>";
			echo "<td align='center'>$builder</td>";
			echo "<td align='center'>$subdivision</td>";
			echo "<td align='center'>$lot</td>";
			echo "<td align='center'>$action</td>";
			echo "<td align='center'>$crewName</td>";
			echo "<td align='center'>$cost</td>";
			echo "</tr>";
			
			$totalCost+=$cost;
			
			$count++;
		}
		
		if($count == 0) {
			$outputMessage = 'No Timesheets found during ' . $date;
		} else { // Display Total for last Crew.
			echo "<tr>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'>$lastCrew</td>";
			echo "<td align='center' style='background-color:#0BA;'><strong>Total</strong></td>";
			echo "<td align='center' style='background-color:#0BA;'><strong>$totalCost</strong></td>";
			echo "</tr>";
			
			// Include Total from last Crew.
			$weekCost+=$totalCost;
			
			// Display Week Total.
			echo "<tr>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'></td>";
			echo "<td align='center' style='background-color:#0BA;'><strong>Week Total</strong></td>";
			echo "<td align='center' style='background-color:#0BA;'><strong>$weekCost</strong></td>";
			echo "</tr>";
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>