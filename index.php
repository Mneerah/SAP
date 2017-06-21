<!DOCTYPE html>
<head>
	<title>SAP Login</title>

	<meta charset="utf-8">
	<meta name="description" content="slick Login">
	<meta name="author" content="Webdesigntuts+">

	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/form.css" />

	<script type="text/javascript" 	src="js/jquery-latest.min.js"></script>
	<script type="text/javascript" 	src="js/placeholder.js"></script>
	<script 						src="js/jquery.min.js"></script>

	<script language="javascript">

		function showHint(str){
			//if there is no text, delete the hint and return.
			if (str.length==0){ 
				document.getElementById("txtHint").innerHTML="";
				return;
			}

			//---------TO  BE REMOVED ----------------------
			$.ajax({ 
					url: 'databaseClass.php',
			        data: 
			        {
			        	action: 'loginVerify', 
			        	input:str
			        },
			        type: 'post',
			        success: function(output) {
						document.getElementById("txtHint").innerHTML=output;

			        }
			});
			
		} //end method showHint
	</script>

</head>
<body><?php $error="";?>

<div id="header">
			<img src="images/logo.png"  /> 		
</div>

<form id="slick-login" action="login.php" autocomplete="off" method="post">
	<span>use Google Chrome <br></span><br>

	<span id="txtHint"></span>

	<label for="username">username</label>
	<input type="text" id="txt1" name="username" class="placeholder" placeholder="username" onkeyup="showHint(this.value)"/>

	
	<label for="password">password</label>
	<input type="password" name="password" class="placeholder" placeholder="password"/>
	
	<br><span><?php echo $error; ?></span><br>

	<input type="submit" value="Log In"/>

</form>
</body>
</html>