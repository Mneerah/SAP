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
				    if( ev.target.className=='daylist'){
					    ev.target.appendChild(document.getElementById(data));
					    openStaffStocktake(data, ev.target.id );
					}					    
					ev.preventDefault();
				}
				function alertDelete(staff, stocktake, flag){
					$.ajax({ 
						url: 'includes/Functions.php',
				        data: 
				        {
				        	action: 'alertDelete', 
				        	staff: staff, 
				        	stocktake:stocktake, 
				        	isLeader:flag
				        },
				        type: 'post',
				        success: function(output) {
				        	location.reload();
				        	//alert(output);
				        }
				    });
				}

				function openStaffStocktake(staff, stocktake){
					window.open('add_staffStocktake.php?staff='+staff+'&stocktake='+stocktake, "ADD-STOCKTAKE", "width=300,height=320");
					/*$.ajax({ 
						url: 'includes/Functions.php',
				        data: 
				        {
				        	action: 'addStaffStocktake', 
				        	staff: staff, 
				        	stocktake:stocktake
				        },
				        type: 'post',
				        success: function(output) {
				        	location.reload();
				        }
				    });*/
				}
				
				//-------------------------display regions ---------------------------------
				function onlyTL(){ 
					staffVisibility('Member','none');
					staffVisibility('D','none');
					staffVisibility('TD','none');
					staffVisibility('DTD','none');
					staffVisibility('TL','block');
					staffVisibility('DTL','block');
				}
				function onlyTD(){ 
					staffVisibility('Member','none');
					staffVisibility('D','none');
					staffVisibility('TL','none');
					staffVisibility('DTL','none');
					staffVisibility('TD','block');
					staffVisibility('DTD','block');
				}
				function onlyD(){ 
					staffVisibility('Member','none');
					staffVisibility('TL','none');
					staffVisibility('TD','none');
					staffVisibility('D','block');
					staffVisibility('DTD','block');
					staffVisibility('DTL','block');
				}
				function displayAll(){ 
					staffVisibility('Member','block');
					staffVisibility('TL','block');
					staffVisibility('TD','block');
					staffVisibility('D','block');
					staffVisibility('DTD','block');
					staffVisibility('DTL','block');
				}
				//----------------main diplay hide method------------------------------------
				function staffVisibility(classname, value){
					var cols = document.getElementsByClassName(classname);
					  for(i=0; i<cols.length; i++) {
					    cols[i].style.display = value;
					  } 
				}
				//---------------------------------------------------------------------------
				function CarGroup(){				
					var date = document.getElementById("todayDate").innerHTML;
					var hint = document.getElementById("CarGroup").value;
					$.ajax({ 
						url: 'includes/Functions.php',
				        data: 
				        {
				        	action: 'CallStaff', 
				        	CarGroup: hint,
				        	date: date
				        },
				        type: 'post',
				        success: function(output) {
				        	document.getElementById("AvailableStaff").innerHTML=output;
				        }
				    });
				}			
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
				<a href="#TeamLeadersLink" id="TeamLeadersLink" onclick="onlyTL()">TL</a> |
				<a href="#TeamDeputiesrLink" id="TeamDeputiesrLink" onclick="onlyTD()">TD</a> | 
				<a href="#DriversLink" id="DriversLink" onclick="onlyD()">D</a> | 
				<a href="#AllStaff" id="AllStaff" onclick="displayAll()">All</a>
			</div>				
			<!-- ===================SEARCH CAR GROUP======================= -->
			<input type="text" placeholder="Car Group" 
					class="searchfield" id="CarGroup" onkeyup="CarGroup()" />
			<!-- ========================================================== -->
			<h4> Available Staff </h4>
			<div id="tasks">
				<div id='AvailableStaff'>
					<?php	    
						$_POST['action']="CallStaff";
						$_POST['date']=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
						include('includes/Functions.php');	
					?>
				</div>
				<span id="todayDate" hidden>
					<?php echo $_GET['year']."-".$_GET['month']."-".$_GET['day'] ?>
				</span>
			</div>
		</div>

		<div id="rightdiv" >
			<div id="calbox" >
					<?php
						include ("includes/StaffScheduler.php");
						$calendar = new DayJobs ($_GET['dayName'], $_GET['day'],$_GET['month'], $_GET['year']);
						echo $calendar->showCalendar ();
					?>		

			</div>
		</div>
	</body>
</html>