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
			//Display staff list at dropdown list
			function showstafflist(){
				document.getElementById('staffSelect').hidden=false;
				$.ajax({ 
					url: 'includes/Functions.php',
			        data: 
			        {
			        	action: 'showStaffSelect'
			        },
			        type: 'post',
			        success: function(output) {
			            document.getElementById('staffSelect').innerHTML=
			            	"<option selected disabled>-- Select Staff --</option>"
			            	+output;
			            document.getElementById('showButton').hidden=true;
		        	}
				});
			}

			//add day to availability table in datatbase :AddDayToCal(29,03,2017)
			function AddDayToCal(data, day, month, year){
				//add or remove flag
				AvailabilityStatus= '1';
				//delete entry
			    if ( document.getElementById(data).classList.contains('IAmAvailable'))
			    {					    	
			    	document.getElementById(data).classList.add('IAmNotAvailable');
			    	document.getElementById(data).classList.remove('IAmAvailable');
			    	AvailabilityStatus= '0';
			    }
			    // add entry
				else if ( document.getElementById(data).classList.contains('IAmNotAvailable'))
			    {
			    	document.getElementById(data).classList.remove('IAmNotAvailable');
			    	document.getElementById(data).classList.add('IAmAvailable');
			    	AvailabilityStatus= '1';
			    }
			    //IAmAvailable or "IAmNotAvailable 

				date= year+"-"+month+"-"+day+" 00:00:00";
				userid= document.getElementById("staffSelect").value;
				
				$.ajax({ 
					url: 'includes/Functions.php',
			        data: 
			        {
			        	action: 'addNewDate', 
				        userid: userid, 
				       	date:date, 
				       	flag:AvailabilityStatus
			        },
			        type: 'post',
			        success: function(output) {
			            //alert(output);
						document.getElementById('alert').style.visibility='visible'; //change visibility style
						document.getElementById('alertmsg').innerHTML=output; 		//display message	
						setTimeout(hideMsg, 2000);			        	
					}
				});
			}
			function hideMsg(){
				document.getElementById('alert').style.visibility='hidden'; //change visibility style
			}

			//show staff calendar
			function CalendarFunction(){
			//Get the user id and month/year date to display
				userid= document.getElementById("staffSelect").value;
				document.getElementById("dateSelect").hidden= false;
				date=  document.getElementById("dateSelect").value;
				$.ajax({ 
					url: 'includes/Functions.php',
			        data: 
			        {
					    action: 'displayCalendar', 
					    userid: userid, 
					    date:date 
					},
					type: 'post',
					success: function(output) {
					  	document.getElementById("StaffCalendar").innerHTML= output;
					    	//Print the output text(the calendar)
					}
				});
			}
			//turn on/off staff availability website
			function TurnOff(){
				$.ajax({ 
					url: 'includes/Functions.php',
					data: 
			   		{
				  	action: 'updateState'
			    	},
			    type: 'post',
			    success: function(output) {
			        //alert(output);
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
			  <li ><a href="home.php"  >Home</a></li>
			  <li><a href="StaffAvailabilityHome.php" class="activeTab">Staff Availability</a></li>
			</ul>			
		</div>

		<span style="clear:both;"><br></span>

		<div id="normaldiv">	
			<section class="checklistbox" style="float:left; width:15%; text-align: center;">
				<h4>Staff Availability <BR>(Turn ON/OFF)</h4>
				<form>
					<!--
						SLIDER - TURN ON/OFF STAFF WEBSITE		
					 -->
					 <label id="slider" class="switch" >
						  	<?php			  		
						  		echo '<input id="websiteState" type="checkbox" onchange="TurnOff()"';
						  	//==================================================
			  				// check if the staff availabiity is online or offline  

			  					$_POST ['action']='TurnoffORon';
								include ("includes/Functions.php") ;
							//==================================================
								echo '>';
						  	?>
					  	<div class="slider round"></div>
					</label>
				</form><br>		
			</section> 
			<section class="checklistbox" style="float:right; width:60%; text-align: left;">
				<div id="alert" class="alert" style="visibility:hidden;">
				  <span class="closebtn" onclick="this.parentElement.style.visibility='hidden';">&times;</span> 
				  <span id="alertmsg"></span>
				</div>
				<h3>Availability for Staff<br><br>
					<input class="searchfield" id="showButton" type="button" onclick="showstafflist()" value="Change Staff Availability">
					<Select id="staffSelect" class="searchfield" onchange="CalendarFunction()" hidden >
					  	<option selected disabled>-- Select Staff --</option>
					</Select>
					<input class="searchfield" style='max-width:87%;' id= "dateSelect" type="date"  value="2017-06-01" hidden onchange="CalendarFunction()" />
				</h3>
				<div id="StaffCalendar">
				</div>
			</section> 

			<div style="clear:both; ">	</div >
		</div>				
	</body>
</html>
<?php   
		}//close else  
?>