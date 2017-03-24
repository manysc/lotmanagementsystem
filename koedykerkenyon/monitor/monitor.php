<?php
	include '../header.php';
	include '../common/commonStyle.php';
	include 'monitorUtils.php';
?>

<html>

<head>
<title>K&K Monitor</title>

<style type="text/css">
.myTable { background-color:#0CA;border-collapse:collapse; }
.myTable th { background-color:#0CA;color:Black;width:10%; }
.myTable td, .myTable th { padding:5px;border:1px solid #000; }

#monitor #monitorOutput {
	background-color: #0EA;
	border-color:transparent;
}

</style>
</head>

<body>
<form id="monitor" name="monitor" method="post" action="monitor.php" onsubmit="setSelectedForman()">
<fieldset>
<legend class="formLegend">Lot Monitor</legend>
<textarea class="formOutput" name="monitorOutput" id="monitorOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br/><br/>
<?php
	displayFormanList();
	displayFormanDropBox();
?>

<input type="hidden" name="selectedForman" size="20" style="font-size:20px" value=''>
&nbsp;
<label class="formHeaderLabel">Camera
   <input class="formMediumInput" name="cameraName" type="text" value="<?php if(isset($_POST['cameraName'])) { echo $_POST['cameraName']; } ?>"/> 
</label>
&nbsp;&nbsp;&nbsp;

<input class="sectionButton" type="submit" name="saveForman" id="saveForman" value="Save">
<input class="sectionButton" type="submit" name="deleteForman" id="deleteForman" value="Delete">
<input class="sectionButton" type="submit" name="clearForman" id="clearForman" value="Clear">

<script type="text/javascript">
function setSelectedForman() {
	var x=document.getElementById("formanDropMenu").selectedIndex;
	var y=document.getElementById("formanDropMenu").options;
	document.getElementsByName("selectedForman")[0].value = y[x].text;
}

document.getElementsByName("monitorOutput")[0].value = '<?php echo $outputMessage; ?>';
</script>
</fieldset>
</form>
</body>

</html>