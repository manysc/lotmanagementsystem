<?php
	$msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
	include 'configuration.php';
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

.checkbox  {
	 -ms-transform: scale(2); /* IE */
 	 -moz-transform: scale(2); /* FF */
  	 -webkit-transform: scale(2); /* Safari and Chrome */
  	 -o-transform: scale(2); /* Opera */
}

#leftHeader {
	width :20%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
	background-color: #0EA;
}

#centerHeader {
	width :50%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
	background-color: #0EA;
}

#rightHeader {
	width :20%;
	padding : 0;
	margin : 0;
	display : inline-block;
	border-color:transparent;
	background-color: #0EA;
}

</style>

<head>
<!-- Menu Bar -->
<script src=<?php echo $sitePath . "/SpryAssets/SpryMenuBar.js"; ?> type="text/javascript"></script>
<?php if($msie) : ?>
	<link href=<?php echo $sitePath . "/SpryAssets/SpryMenuBarHorizontal.css"; ?> rel="stylesheet" type="text/css">
<?php else : ?>
    <link href=<?php echo $sitePath . "/SpryAssets/ChromeMenuBarHorizontal.css"; ?> rel="stylesheet" type="text/css">
<?php endif; ?>

<!-- Date Input for IE -->
<script src=<?php echo $sitePath . "/libraries/datePicker/htmlDatePicker.js"; ?> type="text/javascript"></script>
<link href=<?php echo $sitePath . "/libraries/datePicker/htmlDatePicker.css"; ?> rel="stylesheet">
</head> 

<body>
	<form id="header" name="header" method="post" action="header.php">
		<div align="center"><fieldset id="leftHeader"></fieldset>
		<fieldset id="centerHeader">
			<div align="center"><label style="font-size:40px"><strong>Masonry Management System</strong></label></div>
		</fieldset>
		<fieldset id="rightHeader">
			<a style="font-size:25px" href=<?php echo $sitePath . "/index.php"; ?> >Logout</a>
		</fieldset></div>
	</form>
	
<ul id="HomeMenu" class="MenuBarHorizontal">
	<li><a class="MenuBarItemSubmenu" <?php echo "href=" . $sitePath . "/employee/employees.php"; ?> >Employees</a>
    <ul>
      <?php
      	if(!strcasecmp($_SESSION['username'], 'Administrator')) {
      		echo "<li><a href=" . $sitePath . "/accounts/accounts.php>Accounts</a></li>";
      	}
      ?>
	<li><a <?php echo "href=" . $sitePath . "/crew/crews.php"; ?> >Crews</a></li>
	<li><a <?php echo "href=" . $sitePath . "/vendors/vendors.php"; ?> >Vendors</a></li>
    </ul>
  </li>
  <li><a <?php echo "href=" . $sitePath . "/layout/layouts.php"; ?> >Layouts</a></li>
  <li><a <?php echo "href=" . $sitePath . "/timesheet/masonryTimeSheet.php"; ?> >Timesheet</a>
      <ul>
  	    <li><a <?php echo "href=" . $sitePath . "/materialsheet/materialSheet.php"; ?> >Material Sheet</a></li>
      </ul>
   </li>
  
  <li><a class="MenuBarItemSubmenu">Reports</a>
	  <ul>
	  	<li><a <?php echo "href=" . $sitePath . "/reports/clipboard/clipboard.php"; ?> >Clipboard</a></li>
	  	<li><a <?php echo "href=" . $sitePath . "/reports/costsheet/costSheet.php"; ?> >Costsheet</a></li>
	  	<li><a <?php echo "href=" . $sitePath . "/reports/weekcost/weekCost.php"; ?> >WeekCost</a></li>
	  </ul>
  </li>
  <?php
      	if(!strcasecmp($_SESSION['username'], 'Administrator')) {
			echo "<li><a href=" . $sitePath . "/monitor/monitor.php>Monitor</a></li>";
      	}
  ?>
</ul>

<script type="text/javascript">
	var HomeMenu = new Spry.Widget.MenuBar("HomeMenu", {imgDown:"/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"/SpryAssets/SpryMenuBarRightHover.gif"});
</script>

<?php
include 'common/commonUtils.php';
include 'common/dateTimeUtils.php';

if(!isset($_SESSION['username'])) {
	$url = $sitePath . '/index.php'; 
	echo "<script>window.location='" . $url . "'</script>";
}

$date='';
date_default_timezone_set('MST');

function updateDate() {
	global $date;
	$date = date("D m/d/Y H:i:s");
}
?>

<br/>
<br/>
<br/>
<div align="center">
	<a style="font-size:25px" <?php echo "href=" . $sitePath . "/accounts/accounts.php"; ?> ><?php echo $_SESSION['username']; ?></a><br/>
	<label><strong><?php updateDate(); echo $date; ?></strong></label>
</div>

</body>
</html>