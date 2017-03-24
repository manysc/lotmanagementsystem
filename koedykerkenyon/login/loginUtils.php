<?php

include 'loginDAO.php';

$outputMessage = '';
$accountUsername = '';
$userPassword = '';

if(isset($_POST['login'])) {
	loginUser();
} else if(isset($_POST['forgotPassword'])) {
	forgotPassword();
}

function validateLogin() {
	if (!empty($_POST)) {
		global $accountUsername;
		global $userPassword;
		global $outputMessage;
		
		$accountUsername = $_POST["accountUsername"];
		if(empty($accountUsername)) {
			$outputMessage='Please enter Username.';
			return FALSE;
		}
		
		$userPassword = $_POST["userPassword"];
		if(empty($userPassword)) {
			$outputMessage='Please enter User Password.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateUsername() {
	if (!empty($_POST)) {
		global $accountUsername;
		global $outputMessage;
		
		$accountUsername = $_POST["accountUsername"];
		if(empty($accountUsername)) {
			$outputMessage='Please enter Username.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function loginUser() {
	global $accountUsername;
	
	if(validateLogin()) {
		$loginDAO = new loginDAO();
		$loginDAO->connect();
		
		if($loginDAO->loginUser()) {
			unset($_POST['logout']);
			$_SESSION['username'] = $accountUsername;
			$url = 'layout/layouts.php';
			echo "<script>window.location='" . $url . "'</script>";
		}
		
		$loginDAO->disconnect();
	}
}

function forgotPassword() {
	if(validateUsername()) {
		$loginDAO = new loginDAO();
		$loginDAO->connect();
		$loginDAO->forgotPassword();
		$loginDAO->disconnect();
	}
}

?>
