<?php
class DayJobs {
    var $dayName;
    var $day ;
    var $month; 
    var $year;
    var $date;
//==========================================================================
//------------------------------CONSTRUCTOR---------------------------------
    function DayJobs ($dayName, $day, $month, $year) {
        $this->dayName = $dayName;
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
        $this->date = $day.'-'.$month.'-'.$year;
    }
//==========================================================================
//----------------------------SHOW CALENDAR----------------------------------
    function showCalendar () {
        $Output = "";
        $Output .= "<div id='calBox' border='3' width='1000px' style='overflow-y: hidden;' >";
        $Output .= $this->dayTable($this->dayName, $this->day, $this->month, $this->year);
        $Output .= "</div>";
        return $Output;
    }
//==========================================================================
 //------------------------WEEK TABLE FUNCTION ----------------------------       
   function dayTable ( $dayName, $day, $month, $year) {
        $Output ='<span >
                        <br> <b>'.$dayName.' : ('.$this->date.')</b> <br>
                  </span> ';
    //-------------------DAY NAME AND DATE POSTED-----------------------------
        $Output .="<div class='dayJobsBox'>";
                $jobs= $this->CallStocktakes ($day, $month, $year);
        $Output .= $jobs."</div>";
        return $Output;
    //-------------------DAY JOBS AND STAFF POSTED-----------------------------
    }
//==========================================================================
//-----------------------------Call Stocktakes------------------------------
    function CallStocktakes ($day, $month, $year) {
            require ("db_connect.php");
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
            $job_staff="";
            $assigned_staff="";
            $supervisor="";
            $currentjobs="";
            $JobStaff="";
            $drivers=""; $staffMem="";
            $Region="";
            /*
                ACCOURDING TO YOUR DATABASE 
                ID FOR COUNTIES THAT ARE IN SAME REGION
                Connacht  33, 34, 37, 53, 60 
                Munster 35, 38, 39, 46, 48, 55
                Leinster 36, 40,41,42,43,44, 47, 54,56,57,63,64
                Ulster 45, 49,50,51,52, 58,59,61,62
            */
//------------------------------------------------------------------------
            $Connacht = array(33, 34, 37, 53, 60);
            $Munster=   array(35, 38, 39, 46, 48, 55);
            $Leinster=  array(36, 40,41,42,43,44, 47, 54,56,57,63,64);
            $Ulster=    array(45, 49,50,51,52, 58,59,61,62);
//------------------------------------------------------------------------
            $sql = "SELECT 
                            tblStockTakes.StockTakeId as ST_ID, 
                            tblStockTakes.CustomerStoreId AS S_ID,
                            tblStockTakes.StockTakeDate as ST_Date,
                            tblStockTakes.SupervisorUserId as SUP_ID,
                            tblCustomerStores.StoreName AS S_NAME,
                            tblCustomerStores.Address1 AS S_ADD, 
                            tblCustomerStores.Town AS S_TOWN,
                            tblCustomerStores.CountyId AS S_County

                    FROM    tblCustomerStores, tblStockTakes   
                    where   tblStockTakes.CustomerStoreId
                    =       tblCustomerStores.CustomerStoreId 
                    and     tblStockTakes.StockTakeDate >='$fullDate1'
                    and     tblStockTakes.StockTakeDate <='$fullDate2';";

            $result = mysqli_query($conn, $sql);
            //==================================  start loop ONE on $result  =====================================
            if (mysqli_num_rows($result)>0) {
                //----------------loop in stores stocktakes ----------------------
                while($storeRows = mysqli_fetch_assoc($result)) {
                    $time=substr($storeRows["ST_Date"], -8, 5);
                    if (in_array(($storeRows["S_County"]), $Connacht)) $Region= "Connacht";
                    if (in_array(($storeRows["S_County"]), $Munster)) $Region= "Munster";
                    if (in_array(($storeRows["S_County"]), $Leinster)) $Region= "Leinster";
                    if (in_array(($storeRows["S_County"]), $Ulster)) $Region= "Ulster";
                    $currentjobs.= '<div id="job'. $storeRows["ST_ID"].'" class="jobStaffCal '.$Region.'">' .$time.'<br> '
                                . $storeRows["S_NAME"].'<br>'. $storeRows["S_ADD"].'<br>'.$storeRows["S_TOWN"].' </div>';
                    $JobStaff .='<DIV id="'.$storeRows["ST_ID"].'" class="daylist"  ondrop="drop(event)"  ondragover="allowDrop(event)">';
                    $stocktakeID=$storeRows["ST_ID"];
                    //---------------------------SELECT SUPERVISOR INFO--------------------------------
                    $supervisor=$storeRows["SUP_ID"];
                    $sql_2 = "SELECT UserId,FirstName,LastName, CanDrive, CarGroup FROM tblUsers where UserId='$supervisor'";
                    $result_2 = mysqli_query($conn, $sql_2);
                    //$currentjobs.="         <H6>Supervisor</H6> ";//open supervisor
                    if (mysqli_num_rows($result_2)>0){
                        $supervisorRow = mysqli_fetch_assoc($result_2);
                        $flag='true';
                        $JobStaff.= '        <span id="'. $supervisorRow["UserId"].'" class="jobAssignedStaff deleteStaff" 
                                                    draggable="true" ondragstart="drag(event)" 
                                                    onclick="alertDelete('. $supervisorRow["UserId"].', '.$stocktakeID.','.$flag.')" title="Delete Supervisor?"> <b>' 
                                                    .$supervisorRow["FirstName"].' '.$supervisorRow["LastName"].'<br>(Team Leader)  </span><br><br>';
                    }
                    //------------------------------------supervisor added-------------------------------------
                    //--------------------------------------add staff------------------------------------------
                    $sql_3="SELECT  UserId, IsDriver from tblStockTakeUsers where StockTakeId='$stocktakeID';";
                    $result_3 = mysqli_query($conn, $sql_3);
                    if (mysqli_num_rows($result_3)>0)
                    {
                       while($staffRows = mysqli_fetch_assoc($result_3)) 
                       {
                            //-------------select staff info-----------------------
                            $staffID=$staffRows["UserId"];
                            $sql_4 = "SELECT FirstName,LastName, CanDrive, CarGroup FROM tblUsers where UserId='$staffID' ;";
                            $result_4 = mysqli_query($conn, $sql_4);
                            while($assignedRows = $result_4->fetch_assoc())
                            {
                                $flag= 'false'; 
                                $staffInfo="";
                                if($staffRows ["IsDriver"]==1)
                                {
                                    $staffInfo .="<br> &nbsp &nbsp(Driver)";
                                    $drivers.='<span id="'. $staffID.'" class="jobAssignedStaff deleteStaff " draggable="true" ondragstart="drag(event)" onclick="alertDelete('.$staffID.', '.$stocktakeID.', '.$flag.')" title="Delete Staff?">' . $assignedRows["FirstName"].' '.$assignedRows["LastName"].$staffInfo.'</span><br>';
                                }
                                else 
                                {
                                    $staffMem.='<span id="'. $staffID.'" class="jobAssignedStaff deleteStaff " draggable="true" ondragstart="drag(event)" onclick="alertDelete('.$staffID.', '.$stocktakeID.', '.$flag.')" title="Delete Staff?">' . $assignedRows["FirstName"].' '.$assignedRows["LastName"].$staffInfo.'</span><br>';
                                }  
                            }
                        }   //------------finish select staff info-----------------
                    }
                    //--------------------------------------staff added----------------------------------------
                    $JobStaff .=$drivers.'<br>'.$staffMem.'</DIV>';//</DIV> FOR STAFF LIST
                    $drivers=""; $staffMem= ""; 
                }//close while loop
            //===========================================END LOOP ONE =============================================
            $currentjobs.="<SPAN style='clear:both;'></SPAN><br>".$JobStaff;
            } 
            else {
                return "0 results";
            }
        return $currentjobs;
        mysqli_close($conn);
    }


//================================================================================================================================================
//------------------------------------------------------------------------------------------------------------------------------------------------

//                                              READ ONLY JOBS WITH ASSIGNED STAFF FOR TEAM LEADERS 

//------------------------------------------------------------------------------------------------------------------------------------------------
//================================================================================================================================================

//==========================================================================
//----------------------------SHOW CALENDAR----------------------------------
    function showCalendarReadOnly ($UserId) {
        $Output = "";
        $Output .= "<div id='calBox' border='3' width='1000px' style='overflow-y: hidden;' >";
        $Output .= $this->dayTableReadOnly($UserId, $this->dayName, $this->day, $this->month, $this->year);
        $Output .= "</div>";
        return $Output;
    }
//========================================================================== 
 //------------------------WEEK TABLE FUNCTION ----------------------------       
   function dayTableReadOnly ( $UserId, $dayName, $day, $month, $year) {
        $Output ='<span >
                        <br> <b>'.$dayName.' : ('.$this->date.')</b> <br>
                  </span> ';
    //-------------------DAY NAME AND DATE POSTED-----------------------------
        $Output .="<div class='dayJobsBox'>";
                $jobs= $this->CallStocktakesReadOnly ($UserId, $day, $month, $year);
        $Output .= $jobs."</div>";
        return $Output;
    //-------------------DAY JOBS AND STAFF POSTED-----------------------------
    }

 //==========================================================================   
//------------------------Call Stocktakes------------------------------------
    function CallStocktakesReadOnly ($UserId, $day, $month, $year) {
            
            require ("db_connect.php");

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
            $job_staff="";
            $assigned_staff="";
            $supervisor="";
            $currentjobs="";
            $JobStaff="";
            $drivers=""; $staffMem="";
            $Region="";
            /*
                ACCOURDING TO YOUR DATABASE 
                ID FOR COUNTIES THAT ARE IN SAME REGION
                Connacht  33, 34, 37, 53, 60 
                Munster 35, 38, 39, 46, 48, 55
                Leinster 36, 40,41,42,43,44, 47, 54,56,57,63,64
                Ulster 45, 49,50,51,52, 58,59,61,62
            */
//------------------------------------------------------------------------
            $Connacht = array(33, 34, 37, 53, 60);
            $Munster=   array(35, 38, 39, 46, 48, 55);
            $Leinster=  array(36, 40,41,42,43,44, 47, 54,56,57,63,64);
            $Ulster=    array(45, 49,50,51,52, 58,59,61,62);
//------------------------------------------------------------------------
            $sql = "SELECT 
                            tblStockTakes.StockTakeId as ST_ID, 
                            tblStockTakes.CustomerStoreId AS S_ID,
                            tblStockTakes.StockTakeDate as ST_Date,
                            tblStockTakes.SupervisorUserId as SUP_ID,
                            tblCustomerStores.StoreName AS S_NAME,
                            tblCustomerStores.Address1 AS S_ADD, 
                            tblCustomerStores.Town AS S_TOWN,
                            tblCustomerStores.CountyId AS S_County

                    FROM    tblCustomerStores, tblStockTakes   
                    where   tblStockTakes.CustomerStoreId
                    =       tblCustomerStores.CustomerStoreId 
                    and     tblStockTakes.SupervisorUserId = '$UserId'
                    and     tblStockTakes.StockTakeDate >='$fullDate1'
                    and     tblStockTakes.StockTakeDate <='$fullDate2';";

            $result = mysqli_query($conn, $sql);
            //==================================start loop ONE on $result========================================================
            if (mysqli_num_rows($result)>0) {
                //----------------loop in stores stocktakes ----------------------
                while($storeRows = mysqli_fetch_assoc($result)) {
                    //echo implode( ", ", $storeRows );
                    $time=substr($storeRows["ST_Date"], -8, 5);
                    if (in_array(($storeRows["S_County"]), $Connacht)) $Region= "Connacht";
                    if (in_array(($storeRows["S_County"]), $Munster)) $Region= "Munster";
                    if (in_array(($storeRows["S_County"]), $Leinster)) $Region= "Leinster";
                    if (in_array(($storeRows["S_County"]), $Ulster)) $Region= "Ulster";
                    $currentjobs.= '<div id="job'. $storeRows["ST_ID"].'" class="jobStaffCal '.$Region.'">' .$time.'<br> '
                                . $storeRows["S_NAME"].'<br>'. $storeRows["S_ADD"].'<br>'.$storeRows["S_TOWN"].'
                                        </div>';
                    $JobStaff .='<DIV id="'.$storeRows["ST_ID"].'" class="daylist"  ondrop="drop(event)"  ondragover="allowDrop(event)">';
                    $stocktakeID=$storeRows["ST_ID"];
                    //---------------------------SELECT SUPERVISOR INFO--------------------------------
                    //CONTINUE HERE
                    $supervisor=$storeRows["SUP_ID"];
                    $sql_2 = "SELECT UserId,FirstName,LastName, CanDrive, CarGroup FROM tblUsers where UserId='$supervisor'";
                    $result_2 = mysqli_query($conn, $sql_2);
                    //$currentjobs.="         <H6>Supervisor</H6> ";//open supervisor
                    if (mysqli_num_rows($result_2)>0){
                        $supervisorRow = mysqli_fetch_assoc($result_2);
                        $flag='true';
                        $JobStaff.= '        <span id="'. $supervisorRow["UserId"].'" class="jobAssignedStaff" 
                                                    draggable="true" ondragstart="drag(event)" 
                                                    onclick="alertDelete('. $supervisorRow["UserId"].', '.$stocktakeID.','.$flag.')" > <b>' 
                                                    .$supervisorRow["FirstName"].' '.$supervisorRow["LastName"].'<br>(Team Leader)  </span><br><br>';
                                                
                    }                    
                    //------------------------------------supervisor added-------------------------------------
                    //--------------------------------------add staff------------------------------------------
                    $sql_3="SELECT  UserId, IsDriver from tblStockTakeUsers where StockTakeId='$stocktakeID';";
                    $result_3 = mysqli_query($conn, $sql_3);
                    if (mysqli_num_rows($result_3)>0)
                    {
                       while($staffRows = mysqli_fetch_assoc($result_3)) 
                       {
                            //-------------select staff info-----------------------
                            $staffID=$staffRows["UserId"];
                            $sql_4 = "SELECT FirstName,LastName, CanDrive, CarGroup FROM tblUsers where UserId='$staffID' ;";
                            $result_4 = mysqli_query($conn, $sql_4);
                            while($assignedRows = $result_4->fetch_assoc())
                            {
                                $flag= 'false'; 
                                $staffInfo="";
                                if($staffRows ["IsDriver"]==1)
                                {
                                    
                                    $staffInfo .="<br> &nbsp &nbsp(Driver)";
                                    $drivers.='<span id="'. $staffID.'" class="jobAssignedStaff " draggable="true" ondragstart="drag(event)" onclick="alertDelete('.$staffID.', '.$stocktakeID.', '.$flag.')" >' . $assignedRows["FirstName"].' '.$assignedRows["LastName"].$staffInfo.'</span><br>';
                                }
                                else 
                                {
                                    $staffMem.='<span id="'. $staffID.'" class="jobAssignedStaff " draggable="true" ondragstart="drag(event)" onclick="alertDelete('.$staffID.', '.$stocktakeID.', '.$flag.')" >' . $assignedRows["FirstName"].' '.$assignedRows["LastName"].$staffInfo.'</span><br>';
                                }
                            }
                        }   //------------finish select staff info-----------------
                    }
                    //--------------------------------------staff added----------------------------------------
                    $JobStaff .=$drivers.'<br>'.$staffMem.'</DIV>';//</DIV> FOR STAFF LIST
                $drivers=""; $staffMem= ""; 
            }
            //===========================================END LOOP ONE =====================================================================
        $currentjobs.="<SPAN style='clear:both;'></SPAN><br>".$JobStaff;
        } else {
            return "0 results";
        }
        return $currentjobs;
        mysqli_close($conn);
    }
//==========================================================================
}//End of Class
?>