<?php
	include '../header.php';
?>
<html>
<style type="text/css">

#createAccount {
	width :50%;
	padding : 0;
	margin : 0;
	display : inline-block;
	background-color: #0EA;
}

</style>
<head>

<title>K&K Accounts</title>

</head>

<body>

<?php
	include '../common/commonStyle.php';
	include 'accountUtils.php';
?>
	<form name="accounts" id="accounts" action="accounts.php" method="post">
		<textarea class="formOutput" name="accountsOutput" id="accountsOutput" cols="75" rows="1" readonly><?php echo $outputMessage; ?></textarea><br/><br/>
		<div align="center">
			<fieldset id="createAccount">
				<div align="left">
					<?php
				       if(strcasecmp($_SESSION['username'], 'Administrator')) {
				          echo '<legend class="formLegend">Update Account</legend><br/>';
				       } else {
				          echo '<legend class="formLegend">Create/Update Account</legend><br/>';
				       }
      				?>
					<label class="formLabel">Name</label><br/>
					<input class="formMediumInput" placeholder="First" type="text" name="firstName" value="<?php if(isset($_POST['firstName'])) { echo $_POST['firstName']; } ?>">
					<input class="formMediumInput" placeholder="Last" type="text" name="lastName" value="<?php if(isset($_POST['lastName'])) { echo $_POST['lastName']; } ?>"><br/><br/>
					<label class="formLabel">Choose User Name</label><br/>
					<input class="formExtraLargeInput" type="text" name="accountUsername" value="<?php if(isset($_POST['accountUsername'])) { echo $_POST['accountUsername']; } ?>"><br/><br/>
					<label class="formLabel">Enter Password</label><br/>
					<input class="formExtraLargeInput" type="password" name="passwordCode" value="<?php if(isset($_POST['passwordCode'])) { echo $_POST['passwordCode']; } ?>"><br/><br/>
					<label class="formLabel">Confirm Password</label><br/>
					<input class="formExtraLargeInput" type="password" name="confirmPassword" value="<?php if(isset($_POST['confirmPassword'])) { echo $_POST['confirmPassword']; } ?>"><br/><br/>
					<label class="formLabel">Enter Email Address</label><br/>
					<input class="formExtraLargeInput" type="text" name="emailAddress" value="<?php if(isset($_POST['emailAddress'])) { echo $_POST['emailAddress']; } ?>"><br/><br/>
				</div>
				
				<?php
					if(!strcasecmp($_SESSION['username'], 'Administrator')) {
						echo '<input class="formButton" type="submit" name="searchAccount" id="searchAccount" value="Search">';
					}
				?>
				
				<input class="formButton" type="submit" name="saveAccount" id="saveAccount" value="Save">
	  			<input class="formButton" type="submit" name="deleteAccount" id="deleteAccount" value="Delete">
	  			<input class="formButton" type="submit" name="clearAccount" id="clearAccount" value="Clear">
			</fieldset>
		</div>
	</form>
</body>

</html>