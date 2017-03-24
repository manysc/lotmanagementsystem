<?php

class monitorDAO {
	public $con=null;
	
	function connect() {
		global $databaseHostname;
		global $databaseId;
		global $databasePassword;
		global $databaseName;
		global $outputMessage;
		
		$this->con=mysqli_connect($databaseHostname,$databaseId,$databasePassword,$databaseName);
		
		if (mysqli_connect_errno($this->con)) {
			$outputMessage="Failed to connect to MySQL: " . mysqli_connect_error();
		}
	}
	
	function displayFormanList() {
		global $databaseName;
		
		$formanQuery = "select * from " . $databaseName . ".cameras order by forman";
		$formanRecords = mysqli_query($this->con, $formanQuery);
		while($formanRows = mysqli_fetch_array($formanRecords)){
			echo '<tr>';
			$forman = $formanRows['forman'];
			$cameraName = $formanRows['camera_name'];
			$viewUrl = 'http://' . $cameraName . '.savasys.com:8080';
			echo "<td style='font-size:23px' align='center'><a href=$viewUrl target='_blank'>$forman</a></td>";
			echo "<td style='font-size:23px' align='center'>$cameraName</td>";
			echo "</tr>";
		}
	}
	
	function displayFormanDropMenu() {
		global $databaseName;
		if(isset($_POST["selectedForman"])) {
			$selectedForman = $_POST["selectedForman"];
		}
		$formanQuery = "select * from " . $databaseName . ".employees where `is_forman`='1' order by first";
		$formanRecords = mysqli_query($this->con, $formanQuery);
		$count = 0;
		
		while($formanRows = mysqli_fetch_array($formanRecords)) {
			if($count == 0) {
				echo '<label class="formHeaderLabel">Forman: ';
				echo '<select name="formanDropMenu" id="formanDropMenu" style="font-size:23px" onclick="setSelectedForman()">';
				echo "<option value=''></option>";
			}
			
			$forman = trim($formanRows['first']) . " " . trim($formanRows['last']);
			
			if(isset($_POST["selectedForman"])) {
				if(!strcmp($_POST["selectedForman"], $forman)) {
					echo "<option value=$forman selected='selected'>$forman</option>";
				} else {
					echo "<option value=$forman>$forman</option>";
				}
			} else {
				echo "<option value=$forman>$forman</option>";
			}
			
			$count++;
		}
		if($count > 0) {
			echo '</select>';
		}
	}
	
	function saveForman() {
		global $databaseName;
		global $selectedForman;
		global $cameraName;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".cameras where `forman`='" . $selectedForman . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['forman'] . ' ' . $rows['camera_name'];
		if(!strcmp($outputMessage, " ")) {
			$sql = "INSERT INTO " . $databaseName . ".cameras (`forman`, `camera_name`) VALUES ('$selectedForman', '$cameraName')";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage = 'Saved Camera settings successfully for ' . $selectedForman . '.';
			} else {
				$outputMessage='Unable to save Camera settings for ' . $selectedForman . ': ' . mysqli_error($this->con);
			}
		} else {
			$updateFormanQuery = "update " . $databaseName . ".cameras set `camera_name`='" . $cameraName . "'
				 where `forman`='" . $selectedForman . "'";
			if(mysqli_query($this->con, $updateFormanQuery)) {
				$outputMessage = 'Updated Camera settings successfully for ' . $selectedForman . '.';
			} else {
				$outputMessage='Unable to update Camera settings for ' . $selectedForman . ': ' . mysqli_error($this->con);
			}
		}
	}
	
	function deleteForman() {
		global $databaseName;
		global $selectedForman;
		global $outputMessage;
		
		$sql = "select * from " . $databaseName . ".cameras where `forman`='" . $selectedForman . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$outputMessage=$rows['forman'] . ' ' . $rows['camera_name'];
		if(strcmp($outputMessage, " ")) {
			$sql = "delete from " . $databaseName . ".cameras where `forman`='" . $selectedForman . "'";
			if(mysqli_query($this->con, $sql)) {
				$outputMessage = 'Deleted Camera settings successfully for ' . $selectedForman . '.';
				$_POST["selectedForman"] = '';
				$_POST["cameraName"] = '';
			} else {
				$outputMessage='Unable to delete Camera settings for ' . $selectedForman . ': ' . mysqli_error($this->con);
			}
		} else {
			$outputMessage = "No Camera settings found for " . $selectedForman . '.';
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>