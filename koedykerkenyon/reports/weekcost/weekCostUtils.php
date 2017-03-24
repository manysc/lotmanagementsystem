<?php
include 'weekCostDAO.php';

$outputMessage='';

function searchWeekCost() {
	echo "<fieldset>";
		echo '<table style="border:5px solid black;" border-style:"outset" border="1" cellpadding="0" cellspacing="12" class="myTable">';
		echo '<tr><th>Date</th><th>Builder</th><th>Subdivision</th><th>Lot</th><th>Action</th><th>Crew</th><th>Cost</th></tr>';
		$weekCostDAO = new weekCostDAO();
		$weekCostDAO->connect();
		$weekCostDAO->searchWeekCost();
		$weekCostDAO->disconnect();
		echo "</table>";
	echo "</fieldset><br />";
}

?>