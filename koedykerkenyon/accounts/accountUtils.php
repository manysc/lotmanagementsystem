<?php

include 'accountsDAO.php';

$first='';
$last='';
$accountUsername='';
$passwordCode='';
$confirmPassword='';
$email='';
$outputMessage='';

if(isset($_POST['searchAccount'])) {
	searchAccount();
} else if(isset($_POST['saveAccount'])) {
	saveAccount();
} else if(isset($_POST['clearAccount'])) {
	clearAccount();
} else if(isset($_POST['deleteAccount'])) {
	deleteAccount();
} else if(isset($_POST['clearDeleteAccount'])) {
	clearDeleteAccount();
}

function validateName() {
	if (!empty($_POST)) {
		global $first;
		global $last;
		$first = trim($_POST["firstName"]);
		$last = trim($_POST["lastName"]);
		global $outputMessage;
		if(empty($first)) {
			$outputMessage='Please enter your first name.';
			return FALSE;
		} 
		if(empty($last)) {
			$outputMessage='Please enter your last name.';
			return FALSE;
		}
		
		return TRUE;
	}
	return FALSE;
}

function validateUsername() {
	if (!empty($_POST)) {
		global $accountUsername;
		$accountUsername = trim($_POST["accountUsername"]);
		global $outputMessage;
		if(empty($accountUsername)) {
			$outputMessage='Please enter a username.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validatePassword() {
	if (!empty($_POST)) {
		global $passwordCode;
		$passwordCode = $_POST["passwordCode"];
		global $outputMessage;
		if(empty($passwordCode)) {
			$outputMessage='Please enter a password.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateConfirmPassword() {
	if (!empty($_POST)) {
		global $confirmPassword;
		$confirmPassword = $_POST["confirmPassword"];
		global $outputMessage;
		if(empty($confirmPassword)) {
			$outputMessage='Please confirm your password.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function validateEmail() {
	if (!empty($_POST)) {
		global $email;
		$email = trim($_POST["emailAddress"]);
		global $outputMessage;
		if(empty($email)) {
			$outputMessage='Please enter an email address.';
			return FALSE;
		}
		return TRUE;
	}
	return FALSE;
}

function searchAccount() {
	if(validateUsername()) {
		$accountsDAO = new accountsDAO();
		$accountsDAO->connect();
		$accountsDAO->searchAccount();
		$accountsDAO->disconnect();
	}
}

function saveAccount() {
	if(validateName() && validateUsername() && validatePassword() && validateConfirmPassword() && validateEmail()) {
		$accountsDAO = new accountsDAO();
		$accountsDAO->connect();
		$accountsDAO->saveAccount();
		$accountsDAO->disconnect();
	}
}

function deleteAccount() {
	if(validateUsername() && validatePassword()) {
		$accountsDAO = new accountsDAO();
		$accountsDAO->connect();
		$accountsDAO->deleteAccount();
		$accountsDAO->disconnect();
		
		clearAccount();
	}
}

function clearAccount() {
	$_POST['firstName'] = '';
	$_POST['lastName'] = '';
	$_POST['accountUsername'] = '';
	$_POST['passwordCode'] = '';
	$_POST['confirmPassword'] = '';
	$_POST['emailAddress'] = '';
}

?>