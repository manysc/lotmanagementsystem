<?php
	include '../header.php';
?>
<html>
<head>
<title>Crews</title>
<style>
#crew #crewOutput {
	background-color: #0EA;
	border-color:transparent;
}
#crew #workerListText {
	background-color: #0EA;
	border-color:transparent;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>

<?php
	include 'crewUtils.php';
	include '../common/commonStyle.php';
?>
<br/>
<form name="crew" id="crew" action="crews.php" method="post">
<textarea class="formOutput" name="crewOutput" id="crewOutput" cols="75" rows="1" readonly><?php echo $outputMessage; ?></textarea><br/><br/>
	<fieldset>
		<legend class="formLegend">Crew</legend>
		<br/>
		<label class="formLabel">Define name and save new Crew:</label><br/>
		<label class="formLabel"><strong>Name:</strong></label>
		<input type="text" name="crewName" size="45" style="font-size:20px" value="<?php if(isset($_POST['crewName'])) { echo $_POST['crewName']; } ?>">
		<input class="sectionButton" type="submit"  name="saveCrew" id="saveCrew" value="Save">
		<input class="sectionButton" type="submit" name="clearCrew" id="clearCrew" value="Clear">
		
		<br/><br/>
		<label class="formLabel">Select an exisiting Crew:</label><br/>
		<label class="formLabel"><strong>Existing:</strong></label>
		<?php
			displayCrewDropMenu();
		?>
		
		<input type="hidden" name="selectedCrew" size="20" style="font-size:20px" value=''>
		<input class="sectionButton" type="submit"  name="searchCrew" id="searchCrew" value="Search">
		<input class="sectionButton" type="submit" name="deleteCrew" id="deleteCrew" value="Delete">
		
		<input type="hidden" name="selectedWorker" size="20" style="font-size:20px" value=''>
		<input type="hidden" name="selectedEmployee" size="20" style="font-size:20px" value=''>
    </fieldset>
    
    <?php
		if(isset($_POST['searchCrew'])) {
			searchCrew();
		} else if(isset($_POST['saveCrew'])) {
			saveCrew();
		} else if(isset($_POST['deleteWorker'])) {
			deleteWorker();
		} else if(isset($_POST['addEmployee'])) {
			addEmployee();
		}
		
		global $crewName;
		if((isset($_POST['selectedCrew']) || strcmp($crewName, "")) && (!isset($_POST['clearCrew'])) && (!isset($_POST['saveCrew']))) {
			echo '<br/><br/>';
			displayWorkerDropMenu();
			displayEmployeeDropMenu();
			if(!isset($_POST['searchCrew'])) {
				searchCrew();
			}
		}
	?>
    
<script type="text/javascript">
function setSelectedCrew() {
	var x=document.getElementById("crewDropMenu").selectedIndex;
	var y=document.getElementById("crewDropMenu").options;
	document.getElementsByName("selectedCrew")[0].value = y[x].text;
}

function setSelectedWorker() {
	setSelectedCrew();
	var x=document.getElementById("workerDropMenu").selectedIndex;
	var y=document.getElementById("workerDropMenu").options;
	document.getElementsByName("selectedWorker")[0].value = y[x].text;
}

function setSelectedEmployee() {
	setSelectedCrew();
	var x=document.getElementById("employeeDropMenu").selectedIndex;
	var y=document.getElementById("employeeDropMenu").options;
	document.getElementsByName("selectedEmployee")[0].value = y[x].text;
}
</script>

<script type="text/javascript">
	document.getElementsByName("crewOutput")[0].value = '<?php echo $outputMessage; ?>';
</script>
    
</form>
</body>
</html>