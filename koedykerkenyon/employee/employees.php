<?php
	include '../header.php';
?>
<html>
<head><title>Employees</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head> 

<body>

<?php
include '../common/commonStyle.php';
include 'employeeUtils.php';
?>

<fieldset>
<legend class="formLegend">Employees</legend>
<form name="employee" id="employee" action="employees.php" method="post">
<textarea class="formOutput" name="employeeOutput" id="employeeOutput" cols="75" rows="1" readonly><?php echo $outputMessage; ?></textarea><br/><br/>
	<fieldset>
    	<label class="formLabel">First:</label>
    		<input type="text" class="formMediumInput" name="firstName" value="<?php if(isset($_POST['firstName'])) { echo $_POST['firstName']; } ?>">
		<label class="formLabel">Last:</label>
			<input type="text" class="formMediumInput"  name="lastName" value="<?php if(isset($_POST['lastName'])) { echo $_POST['lastName']; } ?>">
		<label class="formLabel">Wage:</label>
			<input type="text" class="formSmallInput" name="wage" value="<?php if(isset($_POST['wage'])) { echo $_POST['wage']; } ?>"><br/><br/>
		<label class="formLabel">Crew:</label>
		<input type="text" name="crewName" class="formLargeInput" value="<?php  echo $crewName; ?>" readonly>
		&nbsp;&nbsp;
		<label class="formLabel">Email:</label>
		<input type="text" name="email" class="formLargeInput" value="<?php  echo $email; ?>" ><br/><br/>
		
        <div align="center">
        &nbsp;&nbsp;
        <input type="checkbox" class="checkbox" name="supervisor" <?= (isset($_POST['supervisor']) ? strcmp($_POST["supervisor"], "") ? 'checked' : '' : '') ?> /> 
    	<label for="supervisor" class="formLabel">Supervisor</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="checkbox" class="checkbox" name="forman" <?= (isset($_POST['forman']) ? strcmp($_POST["forman"], "") ? 'checked' : '' : '') ?> /> 
    	<label for="forman" class="formLabel">Forman</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" class="checkbox" name="mason" id="mason" <?= (isset($_POST['mason']) ? strcmp($_POST["mason"], "") ? 'checked' : '' : '') ?> >
    	<label for="mason" class="formLabel">Mason</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" class="checkbox" name="apprentice" id="apprentice" <?= (isset($_POST['apprentice']) ? strcmp($_POST["apprentice"], "") ? 'checked' : '' : '') ?> >
    	<label for="apprentice" class="formLabel">Apprentice</label>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" class="checkbox" name="labor" id="labor" <?= (isset($_POST['labor']) ? strcmp($_POST["labor"], "") ? 'checked' : '' : '') ?> >
    	<label for="labor" class="formLabel">Labor</label> <br /> <br/>
        <input type="checkbox" class="checkbox" name="driver" id="driver" <?= (isset($_POST['driver']) ? strcmp($_POST["driver"], "") ? 'checked' : '' : '') ?> >
    	<label for="driver" class="formLabel">Driver</label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" class="checkbox" name="operator" id="operator" <?= (isset($_POST['operator']) ? strcmp($_POST["operator"], "") ? 'checked' : '' : '') ?> >
    	<label for="operator" class="formLabel">Operator</label>&nbsp;&nbsp;
    	<input type="checkbox" class="checkbox" name="footer" id="footer" <?= (isset($_POST['footer']) ? strcmp($_POST["footer"], "") ? 'checked' : '' : '') ?> >
    	<label for="footer" class="formLabel">Footer</label>
        </div>
	</fieldset><br/><br/>
	
	<div align="center">
        <input type="submit" class="formButton" name="searchEmployee" id="searchEmployee" value="Search">
        <input type="submit" class="formButton" name="saveEmployee" id="saveEmployee" value="Save">
        <input type="submit" class="formButton" name="deleteEmployee" id="deleteEmployee" value="Delete">
        <input type="submit" class="formButton" name="clearEmployee" id="clearEmployee" value="Clear">
  </div>

<script type="text/javascript">
	document.getElementsByName("crewOutput")[0].value = '<?php echo $outputMessage; ?>';
</script>

</form>
</fieldset>
</body>
</html>
