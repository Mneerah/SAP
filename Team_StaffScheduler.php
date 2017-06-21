<!DOCTYPE HTML>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="screen" href="css/form.css">
	</head>
	<body id="body">
		<div id="header">
			<img src="images/logo.png"  /> &nbsp;&nbsp;
			<a class="logout" style="float:right; " href="logout.php"> Logout</a> 
			<ul class="navigation">
			  <li><a href="Team_Home.php" class="activeTab">Team Jobs</a></li>
			  <li></li>
			  <li><a href="Staff_Home.php" class="activeTab">Availability</a></li>
			</ul>			
		</div>
		<span style="clear:both;"><br></span>
		<div id="normaldiv" >
			<span id="todayDate" hidden>
				<?php echo $_GET['year']."-".$_GET['month']."-".$_GET['day'] ?>
			</span>
			<div id="calbox" >
				<?php
					//include and create the object !!
					include ("includes/StaffScheduler.php");
					$calendar = new dayJobs ($_GET['dayName'], $_GET['day'],$_GET['month'], $_GET['year']);
					echo $calendar->showCalendarReadOnly ($_GET['id']);
				?>		
			</div>
		</div>
	</body>
</html>