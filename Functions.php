<?php
/**========================================================

 databaseClass V 1.0. A class that holds database queries *

 ==========================================================**/

/*
----------------------------------------------------------
                       POST PHP
----------------------------------------------------------
*/
if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
}

/*
==========================================================
                       SWITCH Actions
----------------------------------------------------------
*/
    switch($action) {

        case 'loginVerify':
            loginVerify();
            break;

        case 'updateState' : 
            updateState();
            break;

       case 'websiteStatus' : 
            websiteStatus();
            break;

        case 'showStaffSelect' : //not used
            showStaffSelect();
            break;

        case 'addNewDate' : 
            addNewDate();
            break;
        
        case 'displayCalendar' :
            displayCalendar();
            break;

        case 'UserGroupHeader' : 
            UserGroupHeader();
            break;

        case 'TurnoffORon' :
            TurnoffORon();
            break;

        case 'alertDelete':
            alertDelete();
            break;

        case 'assignStaffStocktake':
            assignStaffStocktake();
            break;

        case 'CallStaff':
            CallStaff();
            break;

        case 'ShowStores':
            CallStores();
            break;

        case 'AddNewStockTakes':
            AddNewStockTakes();
            break;

        case 'searchCustomer':
            searchCustomer();
            break;

        case 'UpdateStockTake':
            UpdateStockTake();
            break;

        case 'DeleteStockTake':
            DeleteStockTake();
            break;

        case 'CallStockTakes':
            CallStockTakes();
            break;
    }
/*
------------------------------------------------------------
============================================================
*/


//--------------- database connection--------------------
    function databaseConnection () {
        require ("db_connect.php");
        return $conn;
    }
//---------------------------------------------------------------------------

/*  
-------------------------------------------------------------------------------------
----    details : login verify 
----    used by : index.php
-------------------------------------------------------------------------------------
*/
    function loginVerify(){
        $conn= databaseConnection();
        $hint = "";
        $input=$_POST["input"];
        $sql = "SELECT Username, Password
                FROM tblUsers where Username='$input'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if (strlen($input) > 0) {
                    $hint="";
                    for($i=0; $i<count($row["Username"]); $i++){
                        if (strtolower($input)==strtolower($row["Username"])){
                            $hint="<span style='color:green'> Registered.. </span>";
                        }
                    }
                }   
            }
        } 
        if ($hint == ""){
            $response="<span style='color:red'>Not registered!</span>";
        }
        else{
            $response=$hint;
        }
        echo $response;
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : Turn ON/OFF staffAvailability Home page by admin 
----    used by : StaffAvailabilityHome.php
-------------------------------------------------------------------------------------
*/
    function updateState(){
        $conn= databaseConnection();
        $state="";
        $sql = "SELECT Status   FROM tblOnlineForStaff  WHERE OnlineID=1 ;";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $state= $row ["Status"];
            }
        }
        if ($state=="Offline" ) $state="Online";
        else if ($state=="Online" ) $state="Offline";
        $updateSql = "UPDATE tblOnlineForStaff  SET Status = '$state'  WHERE OnlineID=1 ;";
        if ($conn->query($updateSql) !== TRUE)  echo "-Error: " . $conn->error;
        mysqli_close($conn);
    }
/*  
-------------------------------------------------------------------------------------
----    details : show Staff list in dropdown list by admin 
----    used by : StaffAvailabilityHome.php
-------------------------------------------------------------------------------------
*/
    function showStaffSelect(){
        $conn= databaseConnection();
        $sql =  "SELECT UserId,FirstName,LastName FROM tblUsers;";
        //add call to user rates and car groups 
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo'<option id="'. $row["UserId"].'" value="'. $row["UserId"].'">'. $row["FirstName"].' '.$row["LastName"].'</option>';
            }
        } else {
            echo "0 results";
        }
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : return websiteStatus by staff 
----    used by : Staff_Home.php
-------------------------------------------------------------------------------------
*/
    function websiteStatus(){
        $conn= databaseConnection();
        $state="";
        $sql = "SELECT Status   FROM tblOnlineForStaff  WHERE OnlineID=1 ;";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $state= $row ["Status"];
            }
        }
        echo $state; 
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : return website Status to admin to display correct value at the Turn ON-OFF button
----    used by : StaffAvailabilityHome.php
-------------------------------------------------------------------------------------
*/
    function TurnoffORon(){
        $conn= databaseConnection();
        $sql = "SELECT Status
                FROM tblOnlineForStaff
                WHERE OnlineID=1 ;";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
           if ($row ["Status"]=="Online")
               echo "checked";
          }
        } 
        mysqli_close($conn);
    }

/*
---------------------------------------------------------------------------------------------
----    details : add New available Date by staff 
----    used by : Staff_Home.php
-------------------------------------------------------------------------------------
*/
    function addNewDate(){
        $conn= databaseConnection();
        $userid= $_POST['userid'];
        $date = $_POST['date'];
        $flag = $_POST['flag'];
        if($flag!='0')
        {
            $sql = "INSERT INTO `tblUserAvailability` (`UserAvailabilityId`, `UserId`, `AvailabilityDate`, `IsAvailable`) 
                    VALUES (NULL, '$userid', '$date', 1);";
            if ($conn->query($sql) === TRUE) 
                echo " <b>Success!</b> (".substr($date,0,-8).") has been added..";
            else 
                echo "-Error updating record: " . $conn->error."<br>";
        }
        else
        {
            $sql = "DELETE FROM tblUserAvailability
                    WHERE AvailabilityDate  = '$date'
                    AND UserId='$userid';";
            if ($conn->query($sql) === TRUE) 
                echo " <b>Success!</b> (".substr($date,0,-8).") has been removed..";
            else
                echo "-Error updating record: " . $conn->error."<br>";
        }       
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : return calendar by staff (not used)
----    used by : Staff_Home.php
-------------------------------------------------------------------------------------
*/
    function displayCalendar(){
        $conn= databaseConnection();

        $dia = date ("d"); $mes = date ("n"); $ano = date ("Y");
        if($dia[0]=='0') $dia=substr($dia, -1);

        if (isset($_POST["userid"])){
            $userid= ($_POST["userid"]);
        } 

        //(YYYY-MM-DD)
        if (isset($_POST["date"])){
            //$dia = substr(($_GET["date"]),8,2);
            $dia = "01";
            $mes = substr(($_POST["date"]),5,2);
            $ano = substr(($_POST["date"]),0,4);
        } 

        //include the WeeklyCalClass and create the object !!
        include ("StaffCalendar.php");
        $calendar = new StaffCalendarClass ($userid, $dia, $mes, $ano);
        echo $calendar->showCalendar ();

        mysqli_close($conn);
    }

    /*  
-------------------------------------------------------------------------------------
----    details : Ckeck user type, to decide the needed pages  
----    used by : Staff_Home.php
-------------------------------------------------------------------------------------
*/
    function UserGroupHeader(){
        $conn= databaseConnection();        
        $userID = $_SESSION['userID'];
        $sql = "SELECT GroupId FROM tblUserGroups where UserId='$userID'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
        // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                    switch ($row["GroupId"]) {
                        case '2':
                            echo '<li><a href="Team_Home.php" class="activeTab">Team Jobs</a></li>
                          <li></li>
                          <li><a href="Staff_Home.php" class="activeTab">Availability</a></li>';                
                        break;
                        case '3':
                            echo '<li></li>
                          <li><a href="Staff_Home.php" class="activeTab">Availability</a></li>
                          <li ></li>';
                        break;
                        default:
                            echo '<li></li>
                          <li><a href="Staff_Home.php" class="activeTab">Home</a></li>
                          <li ></li>';
                        break;
                    }
            }
            mysqli_close($conn);
        }
    }
/*
-------------------------------------------------------------------------------------
----    details : show stores list for admin 
----    used by : AddNewStockTakes.php
-------------------------------------------------------------------------------------

*/
    function CallStores(){
        $list=  json_decode(stripslashes($_POST['list']));
        $storeslist="<BR>";
        if(count($list)==0)
        {
            echo "<br> NO STORES TO DISPLAY x<br><br>";
        }
        else
        {
            foreach($list as $d){            
                require ("db_connect.php");
                $sql = "SELECT  CustomerStoreId, CustomerId, StoreName, Address1, Town  
                        FROM    tblCustomerStores 
                        WHERE CustomerId= $d;";
                $result = mysqli_query($conn, $sql); 
                //if sql succeed
                if (mysqli_num_rows($result) > 0) 
                {
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                        $storeslist .=  '<p id="">
                                                <input type="checkbox" name="Stores[]"  
                                                        value="'. $row["CustomerStoreId"].'"> '
                                                . $row["StoreName"].' '. $row["Address1"].', '.$row["Town"]
                                                .'</p>';
                    }
                }
                else
                {
                    $storeslist .= "0 results";
                }   
            }//close for loop 
            mysqli_close($conn);
            echo $storeslist.'<br>';
        }
    }

/*
-------------------------------------------------------------------------------------
----     details : call staff list to staff scheduler by admin
----     used by : Admin_StaffSceduler.php
-------------------------------------------------------------------------------------
*/
    function CallStaff(){
        $conn= databaseConnection(); $carGroup="";   $sql="";
        if(isset($_POST["date"])) $date= $_POST["date"]; 
        if(isset($_POST["day"]) &&isset($_POST['month']) &&isset($_POST['year'])) {
            $day = $_POST['day'];  $month = $_POST['month']; $year = $_POST['year'];
            if ($day<10){
                switch ($day) {
                    case 1: $day="01"; break; case 2: $day="02"; break; case 3: $day="03"; break;
                    case 4: $day="04"; break; case 5: $day="05"; break; case 6: $day="06"; break;
                    case 7: $day="07"; break; case 8: $day="08"; break; case 9: $day="09"; break;
                }
            }
            if ($month<10){
                switch ($month) {
                    case 1: $month="01"; break; case 2: $month="02"; break; case 3: $month="03"; break;
                    case 4: $month="04"; break; case 5: $month="05"; break; case 6: $month="06"; break;
                    case 7: $month="07"; break; case 8: $month="08"; break; case 9: $month="09"; break;
                }
            }
            $fullDate1 =$year."-".$month."-".$day." 00:00:00";
            $fullDate2 =$year."-".$month."-".$day." 23:59:59";
        }else{
            $fullDate1 =$date." 00:00:00";  $fullDate2 =$date." 23:59:59";
        }
        if(isset($_POST["CarGroup"])) $carGroup= $_POST["CarGroup"]; 
        if($carGroup !=""){
            $sql =  "SELECT AvailabilityDate, UserPayRateId, PayRate,
                            tblUserAvailability.UserId as UserId,
                            FirstName,LastName, CanDrive, CarGroup, DefaultUserPayRateId 

                        FROM tblUserAvailability, tblUsers, tblUserPayRates
                        WHERE DefaultUserPayRateId=UserPayRateId 
                        AND tblUserAvailability.UserId = tblUsers.UserId
                        AND CarGroup = '$carGroup' 
                        AND AvailabilityDate >= '$fullDate1'
                        AND AvailabilityDate <= '$fullDate2'
                        ORDER BY FirstName;";
        }
        else{
            $sql =  "SELECT AvailabilityDate, UserPayRateId, PayRate, 
                            tblUserAvailability.UserId as UserId,
                            FirstName,LastName, CanDrive, CarGroup, DefaultUserPayRateId
                            
                        FROM tblUserAvailability, tblUsers, tblUserPayRates
                        WHERE DefaultUserPayRateId=UserPayRateId 
                        and tblUserAvailability.UserId = tblUsers.UserId
                        AND AvailabilityDate >= '$fullDate1'
                        AND AvailabilityDate <= '$fullDate2'
                        ORDER BY FirstName;";
        }
        $sqlBusyStaff =  "SELECT UserId
                            FROM tblStockTakes, tblStockTakeUsers
                            WHERE tblStockTakes.StockTakeId=tblStockTakeUsers.StockTakeId 
                            AND StockTakeDate >= '$fullDate1'
                            AND StockTakeDate <= '$fullDate2'

                        UNION

                            SELECT SupervisorUserId as UserId
                            FROM tblStockTakes
                            where StockTakeDate >= '$fullDate1'
                            AND StockTakeDate <= '$fullDate2';";
        $busyFlag="";
        $resultBusyStaff = mysqli_query($conn, $sqlBusyStaff);      
        $result_list = array();
        while($busyStaffRows = mysqli_fetch_assoc($resultBusyStaff)) {
            $result_list[] = $busyStaffRows['UserId'];
        }
    /*
        members €9.50   ||  TD (deputies) €10   ||   Team leader €11 or €15
    */
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $staffInfo="";  $Classes ="staffList ";
                //=========================
                if($row["CanDrive"]==1)
                {
                    $staffInfo .="[D]";     $Classes .="D";
                }
                //==========================
                if($row["PayRate"]==10)
                {
                    $staffInfo .="[TD]";    $Classes .="TD";
                }
                else if($row["PayRate"]>=11)
                {
                    $staffInfo .="[TL]";    $Classes .="TL";
                }
                else
                {
                    $Classes .=" Member";
                }
                //=============================
                if($Classes=="")
                    $Classes= "Member";
                //=====================================
                $busyFlag="";
                if(in_array($row["UserId"],$result_list,'false'))
                {
                    $busyFlag='style="background-color:orange;"';
                }
                echo '<label id="'. $row["UserId"].'" class="btn '.$Classes
                    .'" draggable="true" '.$busyFlag.'ondragstart="drag(event)" style="cursor:move">'
                    . $row["FirstName"].' '.$row["LastName"].'<span style="float:right;">'.$staffInfo.'</span>'
                    .'<br><p style="float:right;">'.' Car Group: '.$row["CarGroup"]
                    .'</p></label> ';
            }
        } 
        else echo "0 results";
    }

/*  
-------------------------------------------------------------------------------------
----    details : Alert delete staff from job by admin 
----    used by : Admin_StaffScheduler.php
-------------------------------------------------------------------------------------
*/
    function alertDelete(){
        $conn= databaseConnection();
        $staff=$_POST["staff"]; 
        $stocktake= $_POST["stocktake"];
        $isLeader= $_POST["isLeader"];
        //echo $isLeader;
        if($isLeader=='true')
        {   
            $sql =  "UPDATE tblStockTakes
                     SET SupervisorUserId=null
                      WHERE StockTakeId='$stocktake';";
        } 
        else if($isLeader=='false')
        {   
            $sql = "DELETE FROM tblStockTakeUsers 
                    WHERE StockTakeId ='$stocktake'
                    AND UserId ='$staff';";
        }
        $result = mysqli_query($conn, $sql);
        if ($conn->query($sql) === TRUE) {
            echo "Staff Deleted successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : assign staff to job by admin 
----    used by : Admin_StaffScheduler.php
-------------------------------------------------------------------------------------
*/
    function assignStaffStocktake(){
        $conn= databaseConnection();
        $staff= $_POST['staff'];
        $stocktake = $_POST['stocktake'];
        if($_POST['mybutton'] == 'Add as Team Leader')
        {
            $sql = "UPDATE tblStockTakes
                    SET SupervisorUserId='$staff'
                    WHERE StockTakeId='$stocktake';";
        }
        if ($_POST['mybutton'] == 'Add as Member')
        {
            $sql = "INSERT INTO tblStockTakeUsers (StockTakeUserId ,StockTakeId,  UserId, UserPayRateId, IsDriver)
                    VALUES (NULL, $stocktake, $staff, null, null); ";
        }
        if ($_POST['mybutton'] == 'Add as Driver')
        {
            $sql = "INSERT INTO tblStockTakeUsers (StockTakeUserId ,StockTakeId,  UserId, UserPayRateId, IsDriver)
                    VALUES (NULL, $stocktake, $staff, null, '1'); ";
        }
        /*
        StockTakeUserId |   StockTakeId |  UserId | UserPayRateId | IsDriver

        */
        
        $button= '<input class="buttonstyle" type="submit" onclick="javascript:window.close()" value="Close">';

        if ($conn->query($sql) === TRUE) {
            echo "<script>window.close();</script>";
            //echo "- Record updated successfully <br>".$button;
        } else {
            echo "-Error updating record: " . $conn->error."<br>".$button;
        }

        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : Add New StockTakes by admin 
----    used by : AddNewStockTakes.php
-------------------------------------------------------------------------------------
*/
    function AddNewStockTakes(){
        $conn= databaseConnection();
        $list=  json_decode(stripslashes($_POST['list']));
        $counter=0;
        if(count($list)==0)
        {
            echo "";
        }
        else
        {
            foreach($list as $d){            
                $presql = "SELECT 
                                CustomerStoreId, CustomerId, StoreName, Address1, Town  
                                FROM    tblCustomerStores 
                                WHERE CustomerStoreId= $d;";
                    
                $result = mysqli_query($conn, $presql); 
                //if sql succeed
                if (mysqli_num_rows($result) > 0) 
                {
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                        $sql = "INSERT INTO `tblStockTakes` 
                                            (`StockTakeId`, `CustomerId`, `CustomerStoreId`, 
                                                `StockTakeStatus`, `SupervisorUserId`, `StockTakeName`, 
                                                    `StockTakeDate`, `StockTakeDuration`, `Comments`) 
                                    VALUES 
                                        (NULL, '". $row['CustomerId']
                                                 . "', '"
                                                 . $row['CustomerStoreId']
                                                 ."', 'New', NULL, NULL, NULL, NULL, NULL);";

                        if ($conn->query($sql) === TRUE) {
                            echo "- Record updated successfully <br>";
                            $counter++;
                        } else {
                            echo "-Error updating record: " . $conn->error."<br>";
                        }
                    }
                }
                else
                {
                    echo "0 results";
                }   
            }//close for loop 
        }
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : searchCustomer to show checklist by admin 
----    used by : AddNewStockTakes.php
-------------------------------------------------------------------------------------
*/
    function searchCustomer(){
        $conn= databaseConnection();
        if(isset($_POST['hint']) && ($_POST['hint']!=""))
        {
            $hint =$_POST['hint'];
            $sql = "SELECT          
                    CustomerId,
                    CustomerName1,
                    CustomerName2

                        FROM    tblCustomers where CustomerName1 LIKE '".$hint."%' ORDER BY CustomerName1;";
        } else {
            $sql = "SELECT          
                            CustomerId,
                            CustomerName1,
                            CustomerName2

                        FROM    tblCustomers ORDER BY CustomerName1;";
        }
        $result = mysqli_query($conn, $sql);
        //if sql succeed
        if (mysqli_num_rows($result) > 0) 
        {
            while($row = mysqli_fetch_assoc($result)) 
            {
                echo
                    '<input type="checkbox" name="Customers[]" 
                            onchange="ChangeStoresList()" 
                            value="'. $row["CustomerId"].'"> '
                            . $row["CustomerName1"].' '
                            . $row["CustomerName2"]
                            .'<br>';

            }
        }
        else
        {
            echo "0 results";
        }
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : UpdateStockTake info by admin 
----    used by : home.php
-------------------------------------------------------------------------------------
*/
    function UpdateStockTake(){
        $conn= databaseConnection();
            $id= $_POST['id'];
                    $date = $_POST['date'];
                    $time = $_POST['time'];
                    $comment= $_POST['comment'];
                    $approxTime=$_POST['approxTime'];
                    if($approxTime!="1") $approxTime="0";
                    $date .=" ".$time;
                    if($_POST['mybutton'] == 'Send Offer')
                    {
                        
                        //header("location: mailto:");
                        //echo "<a href='mailto:" . $to . "?body=" . $body . "'></a>";
                      $status="Pending";
                    }
                    if($_POST['mybutton'] == 'Save Details')
                    {
                      $status="Temp";
                    }
                    if ($_POST['mybutton'] == 'Confirm')
                    {
                      $status="Confirmed";
                    }
                    if ($_POST['mybutton'] == 'Reset')
                    {
                        $status="New";
                        $date = null;
                    }

                    //----------------------[START]----------------------
                    //---- delete staff information from stocktake ----
                    //-------------------------------------------------
                    $resetSQL = "DELETE FROM tblStockTakeUsers
                                    WHERE StockTakeId='$id';";
                    if ($conn->query($resetSQL) === TRUE) {
                    } else {
                        echo "-Error updating record: " . $conn->error."<br>".$button;
                    }

                    $resetLeaderSQL = "UPDATE tblStockTakes
                                        SET SupervisorUserId=null
                                    WHERE StockTakeId='$id';";
                    if ($conn->query($resetLeaderSQL) === TRUE) {
                    } else {
                        echo "-Error updating record: " . $conn->error."<br>".$button;
                    }
                    //-----------------------------------------------------
                    //----------------------[END]------------------------

                    //----------------------[START]----------------------
                    //----------- update stocktake information ----------
                    //---------------------------------------------------
                    $sql = "UPDATE tblStockTakes
                            SET StockTakeStatus='$status', StockTakeDate='$date', Comments='$comment', approxTime='$approxTime'
                            WHERE StockTakeId='$id';";
                    
                    $button= '<input class="buttonstyle" type="submit" onclick="javascript:window.close()" value="Close">';

                    if ($conn->query($sql) === TRUE) {
                        //echo "- Record updated successfully <br>".$button;
                        echo "<script>window.close();</script>";
                    } else {
                        echo "-Error updating record: " . $conn->error."<br>".$button;
                    }
                    //-----------------------------------------------------
                    //----------------------[END]--------------------------
        mysqli_close($conn);
    }

/*  
-------------------------------------------------------------------------------------
----    details : Delete StockTake info by admin 
----    used by : home.php
-------------------------------------------------------------------------------------
*/
    function DeleteStockTake(){
        $conn= databaseConnection();
        $ID=$_POST["id"];
        $sql = "
        DELETE FROM `tblStockTakes` 
        WHERE `tblStockTakes`.`StockTakeId` ='$ID'";

        $result = mysqli_query($conn, $sql);
        if ($conn->query($sql) === TRUE) {
            echo "Stocktake Deleted successfully";
            //echo "<script>window.close();</script>";
        } else {
            echo "<h3>Database Error!</h3><h4>You can't delete a stocktake that has staff members assigned.</h4><br> Error updating record: " . $conn->error;
        }
    mysqli_close($conn);
    }

/*--------------------------------------------------------------------

---------------------------------------------------------------------*/
    function CallStockTakes(){
        $conn= databaseConnection();
        if(isset($_POST['hint']) && ($_POST['hint']!="")) {
            $hint =$_POST['hint'];
            $sql = "SELECT  tblStockTakes.StockTakeId AS ST_ID, 
                            tblStockTakes.StockTakeStatus AS ST_STATUS,
                            tblStockTakes.CustomerStoreId AS S_ID,

                            tblCustomerStores.StoreName AS S_NAME,
                            tblCustomerStores.Address1 AS S_ADD, 
                            tblCustomerStores.Town AS S_TOWN,
                            tblCustomerStores.CountyId AS S_County

                    FROM    tblCustomerStores, tblStockTakes
                    WHERE   tblStockTakes.CustomerStoreId= tblCustomerStores.CustomerStoreId 
                    AND tblCustomerStores.StoreName 
                    LIKE '".$hint."%' order by tblCustomerStores.StoreName;"; 
        } else {
                $sql = "SELECT      tblStockTakes.StockTakeId AS ST_ID, 
                                    tblStockTakes.StockTakeStatus AS ST_STATUS,
                                    tblStockTakes.CustomerStoreId AS S_ID,

                                    tblCustomerStores.StoreName AS S_NAME,
                                    tblCustomerStores.Address1 AS S_ADD, 
                                    tblCustomerStores.Town AS S_TOWN,
                                    tblCustomerStores.CountyId AS S_County


                            FROM    tblCustomerStores, tblStockTakes
                            WHERE   tblStockTakes.CustomerStoreId= tblCustomerStores.CustomerStoreId 
                            order by tblCustomerStores.StoreName;";
        }
        $result = mysqli_query($conn, $sql);
        $Region="";
        $Connacht = array(33, 34, 37, 53, 60);
        $Munster=   array(35, 38, 39, 46, 48, 55);
        $Leinster=  array(36, 40,41,42,43,44, 47, 54,56,57,63,64);
        $Ulster=    array(45, 49,50,51,52, 58,59,61,62);

        /*
        ACCOURDING TO YOUR DATABASE 
        ID FOR COUNTIES THAT ARE IN SAME REGION
        Connacht  33, 34, 37, 53, 60 
        Munster 35, 38, 39, 46, 48, 55
        Leinster 36, 40,41,42,43,44, 47, 54,56,57,63,64
        Ulster 45, 49,50,51,52, 58,59,61,62
        */
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                if (in_array(($row["S_County"]), $Connacht)) $Region= "Connacht";
                if (in_array(($row["S_County"]), $Munster)) $Region= "Munster";
                if (in_array(($row["S_County"]), $Leinster)) $Region= "Leinster";
                if (in_array(($row["S_County"]), $Ulster)) $Region= "Ulster";

                if($row["ST_STATUS"] == "New")
                    echo'<label id="'. $row["ST_ID"].'" class="btn '.$row["ST_STATUS"].' '.$Region.'"
                                draggable="true" ondragstart="drag(event)">

                            <span onclick="openstocktake('. $row["ST_ID"].', this.parentNode.parentNode.id)">'
                             . $row["S_NAME"].', <br>'.$row["S_TOWN"].'
                            </span>

                            <span class="deleteX" onclick="DeleteStockTake('. $row["ST_ID"].')"> X </span>
                        </label>';
            }
        } else {
            echo "0 results";
        }
        mysqli_close($conn);
    }
?>