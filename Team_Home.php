<?php
	session_start();
	if (!isset($_SESSION['login_user'])||!isset($_SESSION['login_pass'])) {
	  //snter the home page  
		header("Location: index.php");
	} else {
	 
?>
<!DOCTYPE HTML>
<html>
	<head>
	  	<link rel="stylesheet" type="text/css" media="screen" href="css/form.css">
		<script>
			function DisplayDayJobs(dayName,day,month, year)
			{
				UserId= document.getElementById("UserId").value;
				window.open('Team_StaffScheduler.php?id='+UserId+'&dayName='+dayName+'&day='+day+'&month='+month+'&year='+year,"_self" );
			}
		</script>
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
			<div id="calbox" >
				<?php
					$dia = date ("d"); $mes = date ("n"); $ano = date ("Y");
					if($dia[0]=='0') $dia=substr($dia, -1);
					if (isset($_GET["dia"])){
						$dia = $_GET["dia"]; $mes = $_GET["mes"]; $ano = $_GET["ano"];
					} 
					echo "<input id='UserId' value='".$_SESSION['userID']."' hidden/>";
					//include the Class and create the object !!
					include ("includes/StocktakeScheduler.php");
					$calendar = new WeeklyCalendar ($dia, $mes, $ano);
					echo $calendar->showCalendarReadOnly($_SESSION['userID']);
				?>		
			</div>
		</div>
	</body>
</html>
<?php
}//close else
?>