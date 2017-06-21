<!DOCTYPE HTML>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="screen" href="css/form.css">
		<script>
		    window.onunload = refreshParent;
		    function refreshParent() {
		      window.opener.location.reload();
		    }
		</script>
	</head>
	<body>
		<form id="smalldiv" action="includes/Functions.php" method="post" >
			<?php 
				$staff= $_GET['staff'];
				$stocktake= $_GET['stocktake'];
				echo'<input  value="'.$staff.'" type="text" name="staff" hidden>	
				     <input  value="'.$stocktake.'" type="text" name="stocktake" hidden>
				     <input  value="assignStaffStocktake" type="text" name="action" hidden>' ;
			?>
		   	<div style="text-align:center;">
				<input class="threebuttonstyle green" style="width:100%;" type="submit" name="mybutton" value="Add as Team Leader"> 
				<input class="threebuttonstyle orange" style="width:100%;" type="submit" name="mybutton" value="Add as Driver"> 
				<input class="threebuttonstyle grey" style="width:100%;" type="submit" name="mybutton" value="Add as Member"> 
				<input class="threebuttonstyle white" style="width:100%;" type="submit" onclick="javascript:window.close()" value="Cancel">
			</div>
		</form>
	</body>
</html>