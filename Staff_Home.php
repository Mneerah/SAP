<?php
	//-----------------------------------------
    //----  Check if the user is logged in 
	//-----------------------------------------
	session_start();
	if (!isset($_SESSION['login_user'])||!isset($_SESSION['login_pass'])) {
	  //redirect user to the login page  
		header("Location: index.php");
	} else {
	//------------------------------------------
	// -----  Start loading the page ----------
?>
<!DOCTYPE HTML>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="screen" href="css/form.css">
		<script src="js/jquery.min.js"></script>
		<script>
		/*-------------------------------------------------------------------------
		 ---------  Add new availability date to the user calendar ----------------
		 --------------------------------------------------------------------------*/
			function AddDayToCal(data, day, month, year){   //AddDayToCal(29,03,2017)
				$.ajax({ 
					url: 'includes/Functions.php',
			        data: 
			        {
			        	action: 'websiteStatus'
			        },
			        type: 'post',
			        success: function(output) {
			            if(output=="Online"){
							AvailabilityStatus= '1'; //add or remove flag
						    if ( document.getElementById(data).classList.contains('IAmAvailable'))
						    {	//delete day (change style)				    	
						    	document.getElementById(data).classList.add('IAmNotAvailable');
						    	document.getElementById(data).classList.remove('IAmAvailable');
						    	AvailabilityStatus= '0';
						    }
							else if ( document.getElementById(data).classList.contains('IAmNotAvailable'))
						    {	// add day (change the style)
						    	document.getElementById(data).classList.remove('IAmNotAvailable');
						    	document.getElementById(data).classList.add('IAmAvailable');
						    	AvailabilityStatus= '1';
						    }	
							date= year+'-'+month+'-'+day+' 00:00:00'; //format the date
							userid= document.getElementById('UserId').value; //get the user id
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
						        	document.getElementById('alert').style.visibility='visible'; //change visibility style
									document.getElementById('alertmsg').innerHTML=output; 		//display message
						     	}//close success function
							});//close ajax
						}//close if online
					}// close success function
				});// close ajax
			}//close the function

		/*-------------------------------------------------------------------------
		  ---------  show availability calender of the user -----------------------
		  --------------------------------------------------------------------------*/
			function CalendarFunction(){
				//Get the user id and month/year date to display
				userid= document.getElementById("UserId").value;
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
			        	document.getElementById("StaffCalendar").innerHTML=output;
			        	//Print the output text(the calendar)
			        }
			    });
			}
		</script>
	</head>
	<body id="body">

		<div id="header">
			<img src="images/logo.png"  /> 
			<a class="logout" style="float:right; " href="logout.php"> Logout</a> 
			<ul class="navigation">
			  <?php
			  //-------------------------------------------------------------------
			  //--------- Ckeck user type, to decide the needed pages  ------------
			  //-------------------------------------------------------------------
			  	$_POST ['action']='UserGroupHeader';
				include ("includes/Functions.php") ;
			  //-------------------------------------------------------------------
				?>
			</ul>			
		</div>

		<span style="clear:both;"><br></span>

		<div id="normaldiv">	
			<section class="checklistbox" style="float:left; width:8%; text-align: center;">
				<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>	
			</section> 
			<section class="checklistbox" style="float:left; width:60%; text-align: left;">
				<div id="alert" class="alert" style="visibility:hidden;">
				  <span class="closebtn" onclick="this.parentElement.style.visibility='hidden';">&times;</span> 
				  <span id="alertmsg"></span>
				</div>
				<h4>Your availability schedule<br>
				 <?php echo "<input id='UserId' type='text' value='".$_SESSION['userID']."' hidden  />";	?>	
					<input class="searchfield" style="max-width:30%; text-align: center;" id= "dateSelect" type="date"   onchange="CalendarFunction()" />
				</h4>
				<div id="StaffCalendar" >
				</div>
			</section> 

			<div style="clear:both; ">	</div >

		</div>				
	</body>
</html>
<?php
	}// close the else 
?>