<?php

session_start(); // Starting Session
require ("includes/db_connect.php");
$hint = "";
$username=$_POST["username"];
$password=$_POST["password"];
/*=============================================================
					SQL INJECTION PREVENTION
===============================================================*/
$PRElist = array();
$PREsql = "SELECT Username, Password FROM tblUsers ;";
$PREresult = mysqli_query($conn, $PREsql);
if (mysqli_num_rows($PREresult) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($PREresult)) {
    	$PRElist[]= strtolower($row['Username']);
    	$PRElist[strtolower($row['Username'])]=$row['Password'];
    }
}//to prevent sql injection
//=======================START LOOKING UP THE USER==================
if ((in_array(strtolower($username), $PRElist))&&($PRElist[strtolower($username)]==$password)) 
	{
		$sql = "SELECT UserId, Username, Password FROM tblUsers where Username='$username' AND Password='$password'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
		    // output data of each row
		    while($row = mysqli_fetch_assoc($result)) {
		        $hint="";		            //initialize the hint string.. 
                if (strtolower($username)==strtolower($row["Username"])){
                	$userID= $row["UserId"];
                	$sql = "SELECT GroupId FROM tblUserGroups where UserId='$userID'";
                	$result = mysqli_query($conn, $sql);

					if (mysqli_num_rows($result) > 0) {
					    // output data of each row
					    while($row = mysqli_fetch_assoc($result)) {
					    	switch ($row["GroupId"]) {
					    		case '1':
					    			    header("location: home.php"); // Redirecting To Other Page
					                    $hint="<span style='color:green'> This username is registered </span>";
					                    $_SESSION['login_user']=$username; // Initializing Session
					                    $_SESSION['login_pass']=$password; // Initializing Session# code...
					                    $_SESSION['userID']=$userID; // Initializing Session# code...
					    			break;
					    		case '2':
					    			    header("location: Team_Home.php"); // Redirecting To Other Page
					                    $hint="<span style='color:green'> This username is registered </span>";
					                    $_SESSION['login_user']=$username; // Initializing Session
					                    $_SESSION['login_pass']=$password; // Initializing Session# code...
					                    $_SESSION['userID']=$userID; // Initializing Session# code...
					    			break;
					    		case '3':
					    			    header("location: Staff_Home.php"); // Redirecting To Other Page
					                    $hint="<span style='color:green'> This username is registered </span>";
					                    $_SESSION['login_user']=$username; // Initializing Session
					                    $_SESSION['login_pass']=$password; // Initializing Session# code...
					                    $_SESSION['userID']=$userID; // Initializing Session# code...
					    			break;
					    		default:
					    				$hint="<span style='color:red'>Not registered...</span>";
                    					header("location: index.php"); // Redirecting To Other Page
					    			break;
					    	}
					    }
					}


                }
                else
                {
                    $hint="<span style='color:red'>Not registered...</span>";
                    header("location: index.php"); // Redirecting To Other Page

                }
		    }
		} 
	}
	else{
        header("location: index.php"); // Redirecting To Other Page
        $hint="<span style='color:red'>Not registered...</span>";
    }
    echo $hint;
	mysqli_close($conn);
?>