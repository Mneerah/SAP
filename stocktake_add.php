<!DOCTYPE HTML>
<html>
	<head>
		  <link rel="stylesheet" type="text/css" media="screen" href="css/form.css">

		<script>
		    window.onunload = refreshParent;
		    function refreshParent() {
		      window.opener.location.reload();
				showTime();
			}

		    function showTime() {
			    var x = document.getElementById("timeSlider").value;
			    if (x<10) x="0"+x;
			    document.getElementById("time").value = x+":00";
			}
		</script>
	</head>
	<body>
		<div id="header">
			<img src="images/logo.png" width="30%"/>
		</div>

		<span style="clear:both;"><br></span>


		<form id="onediv" action="includes/Functions.php" method="post">
			<input type="text" name="action" value="UpdateStockTake" hidden>
		<h3> Modify Stocktake </h3>
						<?php 
							require ("includes/db_connect.php");

							$id= $_GET['id'];
							if(isset($_GET['date']))
								$date = $_GET['date'];
							else
								$date = null;
								
							$sql = "SELECT 		tblStockTakes.StockTakeId AS ST_ID, 
					                            tblStockTakes.StockTakeStatus AS ST_STATUS,
					                            tblStockTakes.CustomerStoreId AS S_ID,
					                            tblStockTakes.SupervisorUserId as SUP_ID,
					                            tblStockTakes.StockTakeDate as ST_DATE,
					                            tblStockTakes.approxTime as approxTime,
					                            tblStockTakes.Comments as Comments,

					                            tblCustomerStores.StoreName AS S_NAME,
					                            tblCustomerStores.Address1 AS S_ADD, 
					                            tblCustomerStores.Town AS S_TOWN,
					                            tblCustomerStores.CountyId AS S_County

					                    FROM    tblCustomerStores, tblStockTakes
					                    WHERE   tblStockTakes.CustomerStoreId= tblCustomerStores.CustomerStoreId 
					                    and     tblStockTakes.StockTakeId  ='$id';";
					        $Region="";

							$Connacht = array(33, 34, 37, 53, 60);
							$Munster=	array(35, 38, 39, 46, 48, 55);
							$Leinster= 	array(36, 40,41,42,43,44, 47, 54,56,57,63,64);
							$Ulster= 	array(45, 49,50,51,52, 58,59,61,62);

							$SupervisorIsSet="<b>&#10006;</b> No Team Leader assigned yet.";
							$result = mysqli_query($conn, $sql);
							//echo $result;
							if (mysqli_num_rows($result) > 0) {
								while($row = mysqli_fetch_assoc($result)) {
							    // output data of each row
									if (in_array(($row["S_County"]), $Connacht)) $Region= "Connacht";
							    	if (in_array(($row["S_County"]), $Munster)) $Region= "Munster";
							    	if (in_array(($row["S_County"]), $Leinster)) $Region= "Leinster";
							    	if (in_array(($row["S_County"]), $Ulster)) $Region= "Ulster";

									if($row["SUP_ID"]!= null)
										$SupervisorIsSet="<b>&#10004;</b> Team Leader is assigned!";

									$mysqltime= "12:00";

									if($date==null) {
										$date= $row["ST_DATE"];
										$mysqltime = "07:00";
									}

									$phpdate = strtotime( $row["ST_DATE"] );
									$mysqltime = date( 'H:i', $phpdate );
									if($mysqltime =='00:00') {
										$mysqltime = "07:00";
									}
									$isChecked="";
									if($row["approxTime"]=="1")	$isChecked="checked";								 


							        echo'
										<div>
											<span class= "buttonfield" style="float:right;"> 
					        					Upload CSV File 
					        				</span>
					        				<label id="'. $row["ST_ID"].'" 
												class="btn '.$Region.'" style="width:50%; float:left;cursor:auto;">'  
					        			 		. $row["S_NAME"].'<br>
					        			 	</label> 
					        				
										</div>
										<span style="clear:both;"><br></span><br><br><br>
				        				<div> 
				        					<i>'.$SupervisorIsSet.'</i>	<br>
				        				</div>

					        			  <input  value="'.$row["ST_ID"].'" type="text" name="id" hidden>	
					        			  <br><b>Stocktake date and time</b><br>	
					        			  <input class="inputstyle" style="width:68%;" type="date" name="date" value="'.$date.'">
										  <input type="time" id="time" class="inputstyle" style="width:29%;" disabled value="'.$mysqltime.'">
										  <p style="float:right;">
										  	<input type="checkbox" name="approxTime" value="1" '.$isChecked.'> Approximate?
										  	</p>
										  <input class=" inputstyle" style="width:68%;" type="range" max="24" id="timeSlider" name="time" 
										  			 onchange="showTime()" value="'.$mysqltime.'">
										  <br><b>Comments</b> 			
										  <br><textarea class="inputstyle" name="comment">'.$row["Comments"].'</textarea>
					        			  ' ;
							    }
							} else {
							    echo "0 results";
							}

							mysqli_close($conn);

						?>
				
  			<DIV STYLE="text-align:center;">
				<input class="threebuttonstyle white" type="submit" name="mybutton" value="Save Details"> 
	  			<input class="threebuttonstyle orange" type="submit" name="mybutton" value="Send Offer"> 
				<input class="threebuttonstyle green" type="submit" name="mybutton" value="Confirm">

				 <input class="threebuttonstyle grey" type="submit" name="mybutton" value="Reset"> 
				<input class="threebuttonstyle grey" type="submit" onclick="javascript:window.close()" value="Cancel">
				</DIV>

			
		</form>
				

		<div id="footer">
			
		</div>
	</body>
</html>