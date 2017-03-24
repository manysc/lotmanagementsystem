<?php
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	include '../../header.php';
?>

<html>
<head>
<title>Week Cost</title>

<style type="text/css">
.myTable { background-color:#0CA;border-collapse:collapse; }
.myTable th { background-color:#0CA;color:Black;width:5%; }
.myTable td, .myTable th { padding:5px;border:1px solid #000; }

#weekCostOutput {
	background-color: #0EA;
	border-color:transparent;
}

</style>
</head>

<body>

<?php
	include '../../common/commonStyle.php';
	include 'weekCostUtils.php';
?>

<fieldset>
<legend class="formLegend">Week Cost</legend>
<form id="weekCost" name="weekCost" method="post" action="weekCost.php">
  <textarea class="formOutput" name="weekCostOutput" id="weekCostOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br /><br />

  <fieldset>
  <label class="formHeaderLabel">Date:
  	<?php if($msie) : ?>
       <input placeholder="mm/dd/yyyy" type="text" name="fromDate" id="fromDate" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['fromDate'])) { echo $_POST['fromDate']; } ?>"/>
    <?php else : ?>
       <input name="fromDate" type=date size="25" align="left"style="font-size:23px" value="<?php if(isset($_POST['fromDate'])) { echo $_POST['fromDate']; } ?>"/>
    <?php endif; ?>
  </label>
  <input class="formButton" type="submit" name="searchWeekCost" id="searchWeekCost" value="Search"/>
  </fieldset><br/>
  
  <?php 
  	if(isset($_POST['searchWeekCost'])) {
		searchWeekCost();
	}
  ?>
  
  <script type="text/javascript">
  	document.getElementsByName("weekCostOutput")[0].value = '<?php echo $outputMessage; ?>';
  </script>
  
</form>
</fieldset>
</body>
</html>