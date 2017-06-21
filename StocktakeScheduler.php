<?php
/**
 *           Print Weekly Scheduler with the assigned stocktakes
 *           ===================================================
 *             this code used part of the EasyWeeklyCal Class 
 *             by Ruben Crespo Alvarez [rumailster@gmail.com] 
 *                     http://peachep.wordpress.com
 *           ===================================================
 */

class WeeklyCalendar {
    var $dia;
    var $mes;
    var $ano;
    var $date;
//==========================================================================
//------------------------------CONSTRUCTOR---------------------------------
    function WeeklyCalendar ($dia, $mes, $ano) {
        $this->dia = $dia;
        $this->mes = $mes;
        $this->ano = $ano;
        $this->date = $this->showDate ($mes, $dia, $ano);
    }
//==========================================================================
//-------------------------------SHOW DATE FUNCTION ------------------------
    function showDate ($mes, $dia, $ano){
        $fecha = mktime ("00", "00", "00", $mes, $dia, $ano);
        //mktime(hour,minute,second,month,day,year) : Returns an integer Unix timestamp
        $cal ["diaMes"] = date ("d", $fecha);
        //d - The day of the month (from 01 to 31)
        $cal ["nombreMes"] = date ("F", $fecha); 
        //F - A full textual representation of a month (January through December)
        $cal ["numDiasMes"] = date ("t", $fecha); 
        //t - The number of days in the given month
        if (date ("w", $fecha) == "0")
            //w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
            { $cal ["diaSemana"] = 7; } 
        else    
            { $cal ["diaSemana"] = date ("w", $fecha); }  
        $cal ["nombreDiaSem"] = date ("l", $fecha);
        //l (lowercase 'L') - A full textual representation of a day
        $cal ["leapYear"] = date ("L", $fecha);  
        //L - Whether it's a leap year (1 if it is a leap year, 0 otherwise)
        return $cal;
    }   
//===============================================================================
//-------------------------------SHOW CALENDAR-----------------------------------
    function showCalendar () {
        $Output = "";
        $Output .= $this->buttonsWeek ($this->dia, $this->mes, $this->ano, $this->date["numDiasMes"]);
        $Output .= "<div id='calBox' border='3' width='100%'>";
        $Output .= $this->WeekTable 
                    ($this->date ["diaMes"], $this->date ["diaSemana"], 
                        $this->date["numDiasMes"], $this->date["nombreMes"], 
                            $this->dia, $this->mes, $this->ano);
        $Output .= "</div>";
        return $Output;
    }
//=========================================================================== 
//------------------------WEEK BUTTONS FUNCTION------------------------------
    function buttonsWeek ($dia, $mes, $ano, $numDiasMes) {
        $thisMonth= $this->showDate ( $mes, $dia, $ano);
        $thisMontOne = $this->showDate ( $mes, 1, $ano);
        $previousMon = $this->previousMonth ($dia, $mes, $ano);
        $WeeksInMonth = $this->WeeksInMonth ($mes, $thisMonth["leapYear"], $thisMonth["diaSemana"]);
        $numberOfWeek = $this->numberOfWeek ($dia, $mes, $ano);      
        $diasRestan = (7 - $thisMonth["diaSemana"]);//days left
        //BOTON ANT : previous week
        if (($dia-($thisMonth["diaSemana"])+1) <=7) {
            $ant = $previousMon["numDiasMes"] - ($thisMontOne["diaSemana"]-1)+1;
            $mesAnt = $mes - 1;  
            if ($mes == 1) {
                $anoAnt = $ano-1;   $mesAnt = 12;
            } else {
                $anoAnt = $ano;
            }
        }else {
            $ant = $dia - ($thisMonth["diaSemana"] + 6);
            $mesAnt= $mes;
            $anoAnt = $ano;
        }
        //BOTON POST: next week
        if ($numberOfWeek > $WeeksInMonth)
        {
            $post=1;
            $mesPost=$mes+1;
            if ($mes == 12)
            {
                $anoPost = $ano+1;
                $mesPost = 1;
            } else {
                $anoPost = $ano;
            }
        }else{
            $post=$dia+($diasRestan+1);
            $mesPost=$mes;
            $anoPost = $ano;
            if($post>$numDiasMes)
            {
                $post=$post-$numDiasMes;
                $mesPost=$mes+1;
                if ($mes == 12)
                {
                    $anoPost = $ano+1;
                    $mesPost = 1;
                } else {
                    $anoPost = $ano;
                }
            }     
        }
        $Output = "<p>
            <a style='text-align: left;' href='".$_SERVER['PHP_SELF']."?dia=".$ant."&mes=".$mesAnt."&ano=".$anoAnt."'>&laquo; Previous Week |</a> 
            <a style='text-align: right; float:right;' href='".$_SERVER['PHP_SELF']."?dia=".$post."&mes=".$mesPost."&ano=".$anoPost."'>| Next Week &raquo;</a>
            <span style='clear:both;'></span> </p>";
        return $Output;
    }
//=========================================================================== 
//--------------------------Weeks in month-----------------------------------      
    function WeeksInMonth ($mes, $leapYear, $firstDay){
        if ($mes == 1 or $mes == 3 or $mes == 5 or $mes == 7 or $mes == 8 or $mes == 10 or $mes == 12) {
            if ($firstDay > 5) { return 6;} 
            else { return 5;}
        } else if ($mes == 4 or $mes == 6 or $mes == 9 or $mes == 11) {
            if ($firstDay == 7) {return 6;} 
            else {return 5;}  
        } else if ($mes == 2) {      
            if ($leapYear == "0" and $firstDay == 1) {return 4;}
            else {return 5;}
        }
    }
//=========================================================================== 
//----------------------------DAY NAME FUNCTION------------------------------
    function dayName ($dia) {
        if ($dia == 1)
                            {$Output = "Mon";} 
        else if ($dia == 2) 
                            {$Output = "Tue";} 
        else if ($dia == 3) 
                            {$Output = "Wed";} 
        else if ($dia == 4) 
                            {$Output = "Thu";} 
        else if ($dia == 5) 
                            {$Output = "Fri";} 
        else if ($dia == 6) 
                            {$Output = "Sat";} 
        else if ($dia == 7) 
                            {$Output = "Sun";}
        return $Output;
    }
//=========================================================================== 
 //--------------------------PREV MONTH FUNCTION----------------------------       
    function previousMonth ($dia, $mes, $ano){
        $mes = $mes-1;
        $mes= $this->showDate ($mes, $dia, $ano);
        return $mes;
    }
//=========================================================================== 
 //--------------------------NEXT MONTH FUNCTION----------------------------           
    function nextMonth ($dia, $mes, $ano){
        $mes = $mes+1;
        $mes= $this->showDate ( $mes, 1, $ano);
        return $mes;
    }
//=========================================================================== 
 //------------------------NUM OF WEEKS FUNCTION----------------------------       
    function numberOfWeek ($dia, $mes, $ano) {
        $firstDay = $this->showDate ($mes, 1, $ano);
        $numberOfWeek = ceil (($dia + ($firstDay ["diaSemana"]-1)) / 7);
        return $numberOfWeek;
    }
//=========================================================================== 
 //---------------------PRINT WEEK TABLE FUNCTION----------------------------       
    function WeekTable ($diaMes, $diaSemana, $numDiasMes, $nombreMes, $dia, $mes, $ano) {
        if ($diaSemana == 0)
        { 
            $diaSemana = 7; 
        }
        $n = 0;
        $resta = $diaSemana - 1;
        $diaMes = $diaMes - $resta;
        $Output ="<div width='100%'>";
        $cambio=0;
        $previousMonth="";
        $BodyCal="";
        $previousMonth = $this->previousMonth ($dia, $mes, $ano);

        //Hasta llegar al dia seleccionado - until the selected day
        for ($i=1; $i < $diaSemana; $i++) {
            if ($diaMes < 1) {
                $previousMonth = $this->previousMonth ($dia, $mes, $ano);
                $diasAnterior = $previousMonth ["numDiasMes"];
                $nameAnterior = $previousMonth ["nombreMes"];
                if ($mes == 1) {
                    $mesVar = 12;
                    $anoVar = $ano - 1;   
                } else {
                    $mesVar = $mes - 1;
                    $anoVar = $ano;
                }
                $cambio = 1;
                $diaMes = $diasAnterior + $diaMes;      
            } else {
                if ($cambio != 1) {
                    $nameAnterior = $nombreMes;
                    $mesVar = $mes;
                    $anoVar = $ano;
                }
            }
            $countStaff=$this->countStaff($diaMes, $mesVar, $anoVar);
            $dayName = $this->dayName ($i);
            
             $Quote  ="'";
             $day= $diaMes; 
             $month= $mesVar; 

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
             $Output .= 
             '<div class="calday" 
                            onclick="DisplayDayJobs('.$Quote.$dayName.$Quote.','.$diaMes.','.$mesVar.','.$anoVar.')" > 
                        <span class="daylable"> <b>'.$this->dayName ($i).'</b><br>'
                        .$diaMes.' '.substr($nameAnterior,0,3).'</span><span class="staffcount">
                        <img width="20%" src="images/person.png"/>
                        <div>[ '.$countStaff.' ]</div>
                        </span></div>';
                        $jobs= $this->CallStocktakes ($diaMes, $mesVar, $anoVar);
                        $BodyCal .=
                            "<span id='".$anoVar."-".$month."-".$day."' class='daylong' ondrop='drop(event)'
                         ondragover='allowDrop(event)'>".$jobs."</span>";
            if ($diaMes == $previousMonth["numDiasMes"]) {
                $diaMes = 1;
                $cambio = 0;
            }else{
                $diaMes ++;
            }
        }
        //Seguimos a partir del dia seleccionado
        for ($diaSemana; $diaSemana <= 7; $diaSemana++) {
            if ($diaMes > $numDiasMes) {
                $mesS = $this->nextMonth ($dia, $mes, $ano);
                $nameSiguiente = $mesS ["nombreMes"];
                if ($mes == 12) {
                    $mesVar = 1;
                    $anoVar = $ano + 1;
                } else {
                    $mesVar = $mes + 1;
                }
                $cambio = 1;
                $diaMes = 1;

            } else {

                if ($cambio != 1)
                {
                    $nameSiguiente = $nombreMes;
                    $mesVar = $mes;
                    $anoVar = $ano;
                }
            }
            $day= $diaMes; 
             $month= $mesVar; 

             if ($day<10){
                switch ($day) {
                    case 1: $day="01"; break;
                    case 2: $day="02"; break;
                    case 3: $day="03"; break;
                    case 4: $day="04"; break;
                    case 5: $day="05"; break;
                    case 6: $day="06"; break;
                    case 7: $day="07"; break;
                    case 8: $day="08"; break;
                    case 9: $day="09"; break;
                }
            }
            if ($month<10){
                switch ($month) {
                    case 1: $month="01"; break;
                    case 2: $month="02"; break;
                    case 3: $month="03"; break;
                    case 4: $month="04"; break;
                    case 5: $month="05"; break;
                    case 6: $month="06"; break;
                    case 7: $month="07"; break;
                    case 8: $month="08"; break;
                    case 9: $month="09"; break;
                }
            }
            $Quote  ="'";
            $dayName=$this->dayName($diaSemana);
            $countStaff=$this->countStaff($diaMes, $mesVar, $anoVar);
            $Output .=
                    '<div class="calday" onclick="DisplayDayJobs('.$Quote.$dayName.$Quote.','.$diaMes.','.$mesVar.','.$anoVar.')" >
                        <span class="daylable"><b>'.$this->dayName($diaSemana).'
                            </b><br>'.$diaMes.' '.substr($nameSiguiente,0,3).'
                        </span> 
                        <span class="staffcount">
                            <img width=20% src="images/person.png"/>
                            <div>[ '.$countStaff.' ]</div>
                        </span>
                    </div>';
                $jobs= $this->CallStocktakes ($diaMes, $mesVar, $anoVar);
                $BodyCal .=
                "<span id='".$anoVar."-".$month."-".$day."' class='daylong' ondrop='drop(event)'
                         ondragover='allowDrop(event)'>".$jobs."</span>";

            $diaMes ++;
        }
        $Output .="</div><span style='clear:both;'></span><div width='100%'>";
        
        $Output .= $BodyCal."</div>";
        return $Output;
        mysqli_close($conn);
    }
//=========================================================================== 
//------------------------- COUNT STAFF IN A DAY ----------------------------
    function countStaff($day, $month, $year) {     
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
        require ("db_connect.php"); 

        $fullDate1 =$year."-".$month."-".$day." 00:00:00";
        $fullDate2 =$year."-".$month."-".$day." 23:59:59";

//-------------------------------------------------------------
//-----------------count all staff ----------------------------
//-------------------------------------------------------------
            $sql = "SELECT COUNT(UserId) AS num 
                    FROM tblUserAvailability
                    WHERE AvailabilityDate  >= '$fullDate1'
                    AND   AvailabilityDate  <= '$fullDate2';";
            $result = mysqli_query($conn, $sql);
//---------------------------------------------------------------
//------------------count TL only -------------------------------
//---------------------------------------------------------------
            $sqlLeaders = "SELECT COUNT(tblUserAvailability.UserId) AS num 
                            FROM tblUserAvailability, tblUsers
                            WHERE tblUserAvailability.UserId= tblUsers.UserId
                            AND   tblUserAvailability.AvailabilityDate  >= '$fullDate1'
                            AND   tblUserAvailability.AvailabilityDate  <= '$fullDate2'
                            AND tblUsers.DefaultUserPayRateId >= 4
                            AND tblUsers.DefaultUserPayRateId <> 6
                            AND tblUsers.DefaultUserPayRateId <> 8;";
                            //for stored payrates 11, 13, 15
                            //SKIP 6 FOR $10, AND 8 FOR $10.5$

            $resultLeaders = mysqli_query($conn, $sqlLeaders);
//-----------------------------------------------------------------
            if (mysqli_num_rows($result)>0 ) 
                if (mysqli_num_rows($resultLeaders)>0)
            {
                $row = mysqli_fetch_assoc($result);
                $rowLeaders = mysqli_fetch_assoc($resultLeaders);
                return $rowLeaders["num"].' : '.$row["num"];
            } else {
                return null;
            }
    }
//=========================================================================== 
//---------------------------  CALL STOCKTAKES  -----------------------------
    function CallStocktakes ($day, $month, $year) {
            
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

            require ("db_connect.php"); 
            $sql = "SELECT 
                            tblStockTakes.StockTakeId as ST_ID, 
                            tblStockTakes.CustomerStoreId AS S_ID,
                            tblStockTakes.StockTakeStatus AS ST_STATUS,
                            tblStockTakes.SupervisorUserId as SUP_ID,
                            tblStockTakes.StockTakeDate as date,

                            tblCustomerStores.StoreName AS S_NAME,
                            tblCustomerStores.Address1 AS S_ADD, 
                            tblCustomerStores.Town AS S_TOWN,
                            tblCustomerStores.CountyId AS S_County

                    FROM    tblCustomerStores, tblStockTakes
                    where   tblStockTakes.CustomerStoreId= tblCustomerStores.CustomerStoreId 

                    and     tblStockTakes.StockTakeDate >='$fullDate1'
                    and     tblStockTakes.StockTakeDate <='$fullDate2'

                    ORDER BY tblStockTakes.StockTakeDate ASC  ;";

            
            $result = mysqli_query($conn, $sql);
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
            $currentjobs="";
            if (mysqli_num_rows($result)>=0) {
                while($row = mysqli_fetch_assoc($result)) {
                    if (in_array(($row["S_County"]), $Connacht)) $Region= "Connacht";
                    if (in_array(($row["S_County"]), $Munster)) $Region= "Munster";
                    if (in_array(($row["S_County"]), $Leinster)) $Region= "Leinster";
                    if (in_array(($row["S_County"]), $Ulster)) $Region= "Ulster";
                    $time= substr($row["date"], -8, 5);
                    $status= $row["ST_STATUS"];
                    if($status == "New") $status= "Temp";
                    $currentjobs.= '<label id="'. $row["ST_ID"].'" class="btn '.$status.' '.$Region
                                    .'" draggable="true" ondragstart="drag(event)" onclick="openstocktake(this.id, this.parentNode.id)">'
                                    .$time.'<br>'. $row["S_NAME"].'<br>'.$row["S_TOWN"].'</label>';
                }                
            } 
            else {
                return "0 results".$fullDate1;
            }
            return $currentjobs;
            mysqli_close($conn);
    }


//==============================================================================================================
//--------------------------------------------------------------------------------------------------------------
//                            READ ONLY METHODS FOR TEAM LEADERS SCHEDULERS
//--------------------------------------------------------------------------------------------------------------
//==============================================================================================================




//================================================================================================ 
//--------------------------------- SHOW CALENDAR READ ONLY --------------------------------------
    function showCalendarReadOnly ($UserId) {
        $Output = "";
        $Output .= $this->buttonsWeek ($this->dia, $this->mes, $this->ano, $this->date["numDiasMes"]);
        $Output .= "<div id='calBox' border='3' width='100%'>";
        $Output .= $this->WeekTableReadOnly
                    ($UserId, $this->date ["diaMes"], $this->date ["diaSemana"], 
                        $this->date["numDiasMes"], $this->date["nombreMes"], 
                            $this->dia, $this->mes, $this->ano);
        $Output .= "</div>";
        return $Output;
    }

//================================================================================================ 
 //-------------------------------------- WEEK TABLE FUNCTION ------------------------------------       
    function WeekTableReadOnly ($UserId, $diaMes, $diaSemana, $numDiasMes, $nombreMes, $dia, $mes, $ano) {
        if ($diaSemana == 0)
        { 
            $diaSemana = 7; 
        }
        $n = 0;
        $resta = $diaSemana - 1;
        $diaMes = $diaMes - $resta;
        $Output ="<div width='100%'>";
        $cambio=0;
        $previousMonth="";
        $BodyCal="";
        $previousMonth = $this->previousMonth ($dia, $mes, $ano);
        //Hasta llegar al dia seleccionado - until the selected day
        for ($i=1; $i < $diaSemana; $i++) {
            if ($diaMes < 1) {
                $previousMonth = $this->previousMonth ($dia, $mes, $ano);
                $diasAnterior = $previousMonth ["numDiasMes"];
                $nameAnterior = $previousMonth ["nombreMes"];
                if ($mes == 1) {
                    $mesVar = 12;
                    $anoVar = $ano - 1;   
                } else {
                    $mesVar = $mes - 1;
                    $anoVar = $ano;
                }
                $cambio = 1;
                $diaMes = $diasAnterior + $diaMes;      
            } else {
                if ($cambio != 1) {
                    $nameAnterior = $nombreMes;
                    $mesVar = $mes;
                    $anoVar = $ano;
                }
            }
            $countStaff=$this->countStaff($diaMes, $mesVar, $anoVar);
            $dayName = $this->dayName ($i); 
            $Quote  ="'";
            $day= $diaMes; 
            $month= $mesVar; 
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
            $Output .= 
             '<div class="calday" onclick="DisplayDayJobs('.$Quote.$dayName.$Quote.','.$diaMes.','.$mesVar.','.$anoVar.')" > 
                <span class="daylable"> <b>'.$this->dayName ($i).'</b><br>' .$diaMes.' '.substr($nameAnterior,0,3)
                .'</span></div>';
            $jobs= $this->CallStocktakesReadOnly ($UserId, $diaMes, $mesVar, $anoVar);
            $BodyCal .="<span id='".$anoVar."-".$month."-".$day."' class='daylong' >".$jobs."</span>";
            if ($diaMes == $previousMonth["numDiasMes"]) {
                $diaMes = 1;
                $cambio = 0;
            }else{
                $diaMes ++;
            }
        }
        //Seguimos a partir del dia seleccionado
        for ($diaSemana; $diaSemana <= 7; $diaSemana++) {
            if ($diaMes > $numDiasMes) {
                $mesS = $this->nextMonth ($dia, $mes, $ano);
                $nameSiguiente = $mesS ["nombreMes"];
                if ($mes == 12) {
                    $mesVar = 1;
                    $anoVar = $ano + 1;
                } else {
                    $mesVar = $mes + 1;
                }
                $cambio = 1;
                $diaMes = 1;
            } else {

                if ($cambio != 1)
                {
                    $nameSiguiente = $nombreMes;
                    $mesVar = $mes;
                    $anoVar = $ano;
                }
            }
            $day= $diaMes; 
            $month= $mesVar; 
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
            $Quote  ="'";
            $dayName=$this->dayName($diaSemana);
            $countStaff=$this->countStaff($diaMes, $mesVar, $anoVar);
            $Output .=
                    '<div class="calday" onclick="DisplayDayJobs('.$Quote.$dayName.$Quote.','.$diaMes.','.$mesVar.','.$anoVar.')" >
                        <span class="daylable"><b>'.$this->dayName($diaSemana).'
                            </b><br>'.$diaMes.' '.substr($nameSiguiente,0,3).'
                        </span> 
                    </div>';
                $jobs= $this->CallStocktakesReadOnly ($UserId, $diaMes, $mesVar, $anoVar);
                $BodyCal .=
                "<span id='".$anoVar."-".$month."-".$day."' class='daylong' ondrop='drop(event)'
                         ondragover='allowDrop(event)'>".$jobs."</span>";

            $diaMes ++;
        }
        $Output .="</div><span style='clear:both;'></span><div width='100%'>";
        $Output .= $BodyCal."</div>";
        return $Output;
        mysqli_close($conn);
    }
//================================================================================================ 
//                                READ ONLY DISPLAY TEAM LEADER STOCKTAKES
   function CallStocktakesReadOnly ($UserId, $day, $month, $year) {
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
        require ("db_connect.php"); 
        $sql = "SELECT 
                            tblStockTakes.StockTakeId as ST_ID, 
                            tblStockTakes.CustomerStoreId AS S_ID,
                            tblStockTakes.StockTakeStatus AS ST_STATUS,
                            tblStockTakes.SupervisorUserId as SUP_ID,
                            tblStockTakes.StockTakeDate as date,

                            tblCustomerStores.StoreName AS S_NAME,
                            tblCustomerStores.Address1 AS S_ADD, 
                            tblCustomerStores.Town AS S_TOWN,
                            tblCustomerStores.CountyId AS S_County

                    FROM    tblCustomerStores, tblStockTakes
                    where   tblStockTakes.CustomerStoreId= tblCustomerStores.CustomerStoreId 
                    and tblStockTakes.SupervisorUserId= '$UserId'
                    and     tblStockTakes.StockTakeDate >='$fullDate1'
                    and     tblStockTakes.StockTakeDate <='$fullDate2'

                    ORDER BY tblStockTakes.StockTakeDate ASC  ;";
        $result = mysqli_query($conn, $sql);
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
            $currentjobs="";
            if (mysqli_num_rows($result)>=0) {
                 //output data of each 
                while($row = mysqli_fetch_assoc($result)) {
                    if (in_array(($row["S_County"]), $Connacht)) $Region= "Connacht";
                    if (in_array(($row["S_County"]), $Munster)) $Region= "Munster";
                    if (in_array(($row["S_County"]), $Leinster)) $Region= "Leinster";
                    if (in_array(($row["S_County"]), $Ulster)) $Region= "Ulster";
                    $time= substr($row["date"], -8, 5);
                    $status= $row["ST_STATUS"];
                    if($status == "New") $status= "Temp";
                        $currentjobs.= '<label id="'. $row["ST_ID"].'"
                                         class="btn '.$status.' '.$Region.'" >'
                                        .$time.'<br>'. $row["S_NAME"].'<br>'.$row["S_TOWN"]
                                        .'</label>';
                }                
            } else {
                return "0 results".$fullDate1;
            }
            return $currentjobs;
            mysqli_close($conn);
    }
//================================================================================================ 
}//End of Class
?>