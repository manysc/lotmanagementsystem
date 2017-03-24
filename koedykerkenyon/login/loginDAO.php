<?php

include 'configuration.php';
include 'common/commonUtils.php';

class loginDAO {
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
	
	function forgotPassword() {
		global $databaseName;
		global $accountUsername;
		global $outputMessage;
		
		// Check username exists.
		$sql = "select * from " . $databaseName . ".accounts where `username`='" . $accountUsername . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$accountName = $rows['first'] . ' ' . $rows['last'];
		if(strcmp($accountName, " ")) {
			$accountEmail = $rows['email'];
			// Send email with username and password.
			$message = 'Sending forgotten password for ' . $accountUsername . ':' . $rows['password'];
			if(sendEmailTo($message, $accountEmail, $accountName)) {
				$outputMessage = 'Emailed password successfully.';
			}
		} else {
			$outputMessage = "Username doesn't exist. Try again?";
		}
	}
	
	function loginUser() {
		global $databaseName;
		global $accountUsername;
		global $userPassword;
		global $outputMessage;
		
		// Check username doesn't exist.
		$sql = "select * from " . $databaseName . ".accounts where `username`='" . $accountUsername . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$rowData = $rows['first'] . ' ' . $rows['last'];
		if(strcmp($rowData, " ")) {
			// Check password matches account password.
			if(!strcmp($userPassword, $rows['password'])) {
				return true;
			} else {
				$outputMessage = "Password doesn't match. Try again?";
			}
		} else {
			$outputMessage = "Account doesn't exist.";
		}
		
		return false;
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>