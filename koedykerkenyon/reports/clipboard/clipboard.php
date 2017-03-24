<?php
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	include '../../header.php';
?>
<html>
<head>
<title>Clipboard</title>

<style type="text/css">
.myTable { background-color:#0CA;border-collapse:collapse; }
.myTable th { background-color:#0CA;color:Black;width:5%; }
.myTable td, .myTable th { padding:5px;border:1px solid #000; }
</style>


<style>
#clipboardOutput {
	background-color: #0EA;
	border-color:transparent;
}

#lotStatusText {
	background-color: #0EA;
	border-color:transparent;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

<?php
include '../../common/commonStyle.php';
include 'clipboardUtils.php';
?>

<fieldset>
<legend class="formLegend">Clipboard</legend>
<form id="clipboard" name="clipboard" method="post" action="clipboard.php" onsubmit="setSelectedLot()">
  <textarea class="formOutput" name="clipboardOutput" id="clipboardOutput" cols="74" rows="1" readonly><?php echo $outputMessage; ?></textarea><br /><br />

  <fieldset>
  <label class="formHeaderLabel">Builder:
  	<input class="formMediumInput" name="builder" type="text" value="<?php if(isset($_POST['builder'])) { echo $_POST['builder']; } ?>"/>
  </label>
  <label class="formHeaderLabel">Subdivision:
  	<input class="formMediumInput" name="subdivision" type="text" value="<?php if(isset($_POST['subdivision'])) { echo $_POST['subdivision']; } ?>"/> 
  </label><br/><br/>
  <label class="formHeaderLabel">From:
  	<?php if($msie) : ?>
       <input placeholder="mm/dd/yyyy" type="text" name="fromDate" id="fromDate" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['fromDate'])) { echo $_POST['fromDate']; } ?>"/>
    <?php else : ?>
       <input name="fromDate" type=date size="25" align="left"style="font-size:23px" value="<?php if(isset($_POST['fromDate'])) { echo $_POST['fromDate']; } ?>"/>
    <?php endif; ?>
  </label>
  <label class="formHeaderLabel">To:
    <?php if($msie) : ?>
       <input placeholder="mm/dd/yyyy" type="text" name="toDate" id="toDate" readonly onClick="GetDate(this);" size="10" style="font-size:23px" value="<?php if(isset($_POST['toDate'])) { echo $_POST['toDate']; } ?>"/>
    <?php else : ?>
       <input name="toDate" type=date size="25" align="left"style="font-size:23px" value="<?php if(isset($_POST['toDate'])) { echo $_POST['toDate']; } ?>"/>
    <?php endif; ?>
  </label><br />
  </fieldset><br />
  
  <?php
  	displayLotStatuses();
  ?>
  
  <div align="center">
 	<input type="hidden" name="selectedLot" size="20" style="font-size:20px" value=''/>
    <input class="formButton" type="submit" name="searchLotStatus" id="searchLotStatus" value="Search"/>
    <input class="formButton" type="submit" name="saveLotStatus" id="saveLotStatus" value="Save"/>
    <input class="formButton" type="submit" name="clearLotStatus" id="clearLotStatus" value="Clear"/>
  </div>
  
<script type="text/javascript">
	function setSelectedLot() {
		var x=document.getElementById("lotDropMenu").selectedIndex;
		var y=document.getElementById("lotDropMenu").options;
		document.getElementsByName("selectedLot")[0].value = y[x].text;
	}

	document.getElementsByName("clipboardOutput")[0].value = '<?php echo $outputMessage; ?>';
</script>
  
</form>
</fieldset>

</body>
</html>