<?php

class accountsDAO {
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
	
	function searchAccount() {
		global $accountUsername;
		global $databaseName;
		global $outputMessage;
		
		// Check username doesn't exist.
		$sql = "select * from " . $databaseName . ".accounts where `username`='" . $accountUsername . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$rowData = $rows['first'] . ' ' . $rows['last'];
		if(strcmp($rowData, " ")) {
			$_POST['firstName'] = $rows['first'];
			$_POST['lastName'] = $rows['last'];
			$_POST['emailAddress'] = $rows['email'];
		} else {
			$outputMessage = "Account doesn't exist.";
		}
	}
	
	function saveAccount() {
		global $first;
		global $last;
		global $databaseName;
		global $accountUsername;
		global $passwordCode;
		global $confirmPassword;
		global $email;
		global $outputMessage;
		
		// Get current date.
		date_default_timezone_set('MST');
		$date = date('m/d/y');
		
		// Check username doesn't exist.
		$sql = "select * from " . $databaseName . ".accounts where `username`='" . $accountUsername . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$rowData = $rows['first'] . ' ' . $rows['last'];
		if(!strcmp($rowData, " ")) {
			// Only Administrator can create a new account.
			if(!strcasecmp($_SESSION['username'], 'Administrator')) {
				// Check password matches confirmed password.
				if(!strcmp($passwordCode, $confirmPassword)) {
					// Save account to database.
					$sql = "INSERT INTO " . $databaseName . ".accounts (date, first, last, username, password, email) 
						VALUES ('$date', '$first', '$last', '$accountUsername', '$passwordCode', '$email')";
					if(mysqli_query($this->con, $sql)) {
						$outputMessage = 'Saved account successfully.';
					} else {
						$outputMessage = 'Unable to save employee: ' . mysqli_error($this->con);
					}
				} else {
					$_POST['"passwordCode"'] = '';
					$_POST['confirmPassword'] = '';
					$outputMessage = "Passwords don't match. Try again?";
				}
	      	} else {
	      		$outputMessage = "Account doesn't exist, contact Administrator.";
	      	}
		} else {
			// Only Administrator or logged in User can update existing account.
			if(!strcasecmp($_SESSION['username'], 'Administrator') || !strcmp($_SESSION['username'], $accountUsername)) {
				// Check password matches confirmed password.
				if(!strcmp($passwordCode, $confirmPassword)) {
					$updateAccountQuery = "update " . $databaseName . ".accounts set `first`='" . $first . "', `last`='" .
										 $last .  "', `password`='" . $passwordCode .  "', `email`='" . $email .
										  "' where `username`='" . $accountUsername . "'";
					if(mysqli_query($this->con, $updateAccountQuery)) {
						$outputMessage = 'Updated account successfully.';
					} else {
						$outputMessage='Unable to udpdate Account: ' . mysqli_error($this->con);
					}
				} else {
					$_POST['passwordCode'] = '';
					$_POST['confirmPassword'] = '';
					$outputMessage = "Passwords don't match. Try again?";
				}
			} else {
				$outputMessage = "Cannot modify existing Account. Contact Administrator.";
			}
		}
	}
	
	function deleteAccount() {
		global $databaseName;
		global $accountUsername;
		global $passwordCode;
		global $outputMessage;
		
		// Check username exists.
		$sql = "select * from " . $databaseName . ".accounts where `username`='" . $accountUsername . "'";
		$records = mysqli_query($this->con, $sql);
		$rows = mysqli_fetch_array($records);
		$rowData = $rows['first'] . ' ' . $rows['last'];
		if(strcmp($rowData, " ")) {
			// Check Passwords match.
			if(!strcmp($passwordCode, $rows['password'])) {
				$deleteAccountQuery= "delete from " . $databaseName . ".accounts where `username`='" . $accountUsername . "'";
				if(mysqli_query($this->con, $deleteAccountQuery)) {
					// If account which user is logged in was deleted successfully,
					// logout automatically.
					if(!strcmp($_SESSION['username'], $accountUsername)) {
						$url = '/index.php';
						echo "<script>window.location='" . $url . "'</script>";
					}
					$outputMessage = 'Successfully deleted Account.';
				} else {
					$outputMessage = 'Unable to delete Account.';
				}
			} else {
				$_POST['deletePassword'] = '';
				$outputMessage = 'Incorrect password. Try again?';
			}
		} else {
			$outputMessage = "Account doesn't exist.";
		}
	}
	
	function disconnect() {
		mysqli_close($this->con);
	}
}

?>