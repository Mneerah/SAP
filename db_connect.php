<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dbo";

        // Create connection
        global $conn ;
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
?>