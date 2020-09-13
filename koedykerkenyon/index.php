<?php
	session_start();
?>
<html>
<style type="text/css">
body,td,th {
	font-family: "Arial", cursive;
	font-size: 20px;
	margin-top: 50px;
	margin-right: 100px;
	margin-bottom: 10px;
	margin-left: 100px;
	background-color: #0EA;
}

#index #loginOutput {
	background-color: #0EA;
	border-color:transparent;
}

#index #loginFieldset {
	background-color: #0EA;
	border-color:transparent;
}

</style>
<head>

<title>MMS Login</title>

</head>
<body>
	<?php
		session_unset();
		include 'login/loginUtils.php';
	?>
	<form name="index" id="index" action="index.php" method="post">
		<div align="center" style="font-size:50px">
			<label style="font-size:40px"><strong>Masonry Management System</strong></label>
		</div>
		<textarea name="loginOutput" id="loginOutput" cols="75" rows="1" style="font-size:22px" readonly><?php echo $outputMessage; ?></textarea>
		<fieldset id="loginFieldset">
			<div align="center">
				<legend style="font-size:25px" align="center">Welcome back!</legend>
				<br/>
				<input placeholder="Username" type="text" name="accountUsername" size="15" style="font-size:25px" value="<?php if(isset($_POST['accountUsername'])) { echo $_POST['accountUsername']; } ?>"><br/>
				<input placeholder="Password" type="password" name="userPassword" size="15" style="font-size:25px" value="<?php if(isset($_POST['userPassword'])) { echo $_POST['userPassword']; } ?>"><br/>
				<br/>
				<input type="submit" name="login" id="login" value="Login" style="font-size:26px; font-family: 'Arial', cursive; border: 6pt ridge lightgrey; height: 50px; width: 90px; left: 110; top: 110; ">
				<br/><br/>
				<input type="submit" name="forgotPassword" id="forgotPassword" value="Forgot password?" style="font-size:18px; font-family: 'Arial', cursive; border-color:transparent; height: 30px; width: 160px; "><br/><br/>
			</div>
		</fieldset>
	</form>
</body>
</html>
