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
		<script src="js/jquery.min.js"></script>

			<script>
				function allowDrop(ev) {
				    ev.preventDefault();
				}

				function drag(ev) {
				    ev.dataTransfer.setData( Text, ev.target.id);
				}

				function drop(ev) {
				    var data = ev.dataTransfer.getData(Text);
				    if( ev.target.className=='daylong'){
					    ev.target.appendChild(document.getElementById(data));

					    if ( document.getElementById(data).classList.contains('New'))
					    	document.getElementById(data).classList.remove('New');
						if( document.getElementById(data).classList.contains('Confirmed'))
							document.getElementById(data).classList.remove('Confirmed');
					    if(document.getElementById(data).classList.contains('Pending'))
					    	document.getElementById(data).classList.remove('Pending');
					    document.getElementById(data).classList.add('Temp')
					}					    
					ev.preventDefault();

				}
				

				function DisplayDayJobs(dayName,day,month, year)
				{
					window.open('Admin_StaffScheduler.php?dayName='+dayName+'&day='+day+'&month='+month+'&year='+year,"_self" );
				}
				function openstocktake(st, date){
        			if (date!=null)
						window.open('stocktake_add.php?id='+st+'&date='+date, "ADD-STOCKTAKE", "width=507,height=700");
					else
						window.open('stocktake_add.php?id='+st+'&date=?', "ADD-STOCKTAKE", "width=507,height=700");
				}
				function DeleteStockTake(id) {
					$.ajax({ 
						url: 'includes/Functions.php',
				        data: 
				        {
				        	action: 'DeleteStockTake', 
				        	id: id
				        },
				        type: 'post',
				        success: function(output) {
				        	location.reload();				   
				        }
				    });
				}
				/*=========================================================================
				------------------------- DISPLAY REGIONS ---------------------------------
				==========================================================================*/
				function onlyConnacht(){ 
					regionVisibility('Connacht','block');
					regionVisibility('Leinster','none');
					regionVisibility('Munster','none');
					regionVisibility('Ulster','none');
				}
				function onlyLeinster(){ 
					regionVisibility('Leinster','block');
					regionVisibility('Connacht','none');
					regionVisibility('Munster','none');
					regionVisibility('Ulster','none');
				}
				function onlyMunster(){ 
					regionVisibility('Munster','block');
					regionVisibility('Connacht','none');
					regionVisibility('Leinster','none');
					regionVisibility('Ulster','none');
				}
				function displayAllRegions(){ 
					regionVisibility('Munster','block');
					regionVisibility('Connacht','block');
					regionVisibility('Leinster','block');
					regionVisibility('Ulster','block');
				}
				//----------------main diplay hide method------------------------------------
				function regionVisibility(classname, value){
					var cols = document.getElementsByClassName(classname);
					  for(i=0; i<cols.length; i++) {
						    cols[i].style.display = value;
				  } 
				}
				/*=============================================================================

				                        ------ SEARCH CLIENT ------

				===============================================================================*/
				function searchClient(){
					var hint = document.getElementById("searchBOX").value;
					$.ajax({ 
						url: 'includes/Functions.php',
				        data: 
				        {
				        	action: 'CallStockTakes', 
				        	hint: hint
				        },
				        type: 'post',
				        success: function(output) {
				        	document.getElementById("tasks").innerHTML=output;				   
				        }
				    });
				}
				//---------------------------------------------------------------------------
			</script>
	</head>
	<body id="body">

		<div id="header">
			<img src="images/logo.png"  /> &nbsp;&nbsp;
			<a class="logout" style="float:right; " href="logout.php"> Logout</a> 
			<ul class="navigation">
			  <li><a href="AddNewStockTakes.php"  >New Stocktakes</a></li>
			  <li ><a href="home.php"  class="activeTab">Home</a></li>
			  <li><a href="StaffAvailability.php" >Staff Availability</a></li>
			</ul>		
		</div>
		<span style="clear:both;"><br></span>
		<div id="leftdiv">
			<div style="text-align:center;">
				<a href="#Connacht" id="ConnachtLink" onclick="onlyConnacht()">Connacht</a> |
				<a href="#Leinster" id="LeinsterLink" onclick="onlyLeinster()">Leinster</a> | 
				<a href="#Munster" id="MunsterLink" onclick="onlyMunster()">Munster</a> | 
				<a href="#All" id="AllRegionsLink" onclick="displayAllRegions()">All</a>
		</div>				

		<!-- ========================================================== -->
		<input type="text" placeholder="client name" class="searchfield" id="searchBOX" onkeyup="searchClient()" />
		<!-- to be implemented with jquery -->
		<!-- ========================================================== -->

			<h4> New Stocktakes</h4>
			<div id="tasks">
				<?php
					$_POST['action']='CallStockTakes';
					include('includes/Functions.php');
				?>
			</div>
		</div>

		<div id="rightdiv" >
			<div id="calbox" >
					<?php
						$dia = date ("d"); $mes = date ("n"); $ano = date ("Y");
						if($dia[0]=='0') $dia=substr($dia, -1);
						if (isset($_GET["dia"])){
							$dia = $_GET["dia"]; $mes = $_GET["mes"]; $ano = $_GET["ano"];
						} 
						//create weekly calendar object !!
						include ("includes/StocktakeScheduler.php");
						$calendar = new WeeklyCalendar ($dia, $mes, $ano);
						echo $calendar->showCalendar ();
					?>		
			</div>
		</div>
	</body>
</html>
<?php
}
?>