<?php

class StaffCalendarClass {

    var $day;
    var $month;
    var $year;
    var $date;

    var $StaffID;

//------------------------------CONSTRUCTOR---------------------------------
    function StaffCalendarClass ($StaffID, $day, $month, $year) {
        $this->StaffID = $StaffID;

        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
        $this->date = $this->showDate ($month, $day, $year);
    }
//---------------------------------------------------------------------------

//-------------------------------SHOW DATE FUNCTION ----------------------------
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
//------------------------------------------------------------------------

//----------------------------SHOW CALENDAR----------------------------------
    function showCalendar () {
        $Output = "";
        //$Output .= $this->buttonsWeek ($this->dia, $this->mes, $this->ano, $this->date["numDiasMes"]);
        $Output .= "<div style='width:92%'>";
        $Output .= 	$this->WeekTable 
                    ($this->date ["diaMes"], //day of month
                    	$this->date ["diaSemana"], //numiric representation of the day (1 for monday to 7 sunday)
                        $this->date["numDiasMes"], $this->date["nombreMes"],  //month name
                            $this->day, 
                            $this->month, 
                            $this->year);
        $Output .= "<span style='clear:both;'></span>";
        $Output .= $this->buttonsWeek ($this->day, $this->month, $this->year, $this->date["numDiasMes"]);

        $Output .= "</div>";
        return $Output;
    }
//--------------------------------------------------------------------------- 
 //------------------------WEEK BUTTONS FUNCTION---------------------------- 

    function buttonsWeek ($dia, $mes, $ano, $numDiasMes) {

        $thisMonth= $this->showDate($mes, $dia, $ano);
        $thisMontOne = $this->showDate($mes, 1, $ano);

        $previousMon = $this->previousMonth ($dia, $mes, $ano);
        $WeeksInMonth = $this->WeeksInMonth ($mes, $thisMonth["leapYear"], $thisMonth["diaSemana"]);

        $numberOfWeek = $this->numberOfWeek ($dia, $mes, $ano);      
        $diasRestan = (7 - $thisMonth["diaSemana"]);

//=======================================================================================
        // FIRST WEEK POST
//=======================================================================================
        if ($numberOfWeek == $WeeksInMonth)
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

        $FirstNextWeek= $this->showDate ($mes, $dia+7, $ano);
//=======================================================================================
        // SECOND WEEK POST
//=======================================================================================
        if (($numberOfWeek+1)/$WeeksInMonth == $WeeksInMonth)
        {
            $post2=1;
            $mesPost2=$mesPost+1;

            if ($mesPost == 12)
            {
                $anoPost2 = $anoPost+1;
                $mesPost2 = 1;
            } else {
                $anoPost2 = $anoPost;
            }

        }else{

            $post2=$dia+($diasRestan+2);
            $mesPost2=$mesPost;
            $anoPost2 = $anoPost;

            if($post2>$numDiasMes)
            {
                $post2=$post2-$numDiasMes;
                $mesPost2=$mesPost+1;
                if ($mesPost == 12)
                {
                    $anoPost2 = $anoPost+1;
                    $mesPost2 = 1;
                } else {
                    $anoPost2 = $anoPost;
                }
            }
            
        }

        $SecondNextWeek= $this->showDate ($mesPost2, $post+7, $anoPost2);
 //========================================================================================
        // THIRD WEEK POST
//=======================================================================================
        if (($numberOfWeek+2)/$WeeksInMonth == $WeeksInMonth)
        {
            $post3=1;
            $mesPost3=$mesPost2+1;

            if ($mesPost2 == 12)
            {
                $anoPost3 = $anoPost2+1;
                $mesPost3 = 1;
            } else {
                $anoPost3 = $anoPost2;
            }

        }else{

            $post3=		$dia+($diasRestan+3);
            $mesPost3=	$mesPost2;
            $anoPost3 = $anoPost2;

            if($post3>$numDiasMes)
            {
                $post3=$post3-$numDiasMes;
                $mesPost3=$mesPost2+1;
                if ($mesPost2 == 12)
                {
                    $anoPost3 = $anoPost2+1;
                    $mesPost3 = 1;
                } else {
                    $anoPost3 = $anoPost2;
                }
            }
            
        }

        $ThirdNextWeek= $this->showDate ($mesPost3, $post+14, $anoPost3);
 //========================================================================================
       if (($numberOfWeek+3)/$WeeksInMonth == $WeeksInMonth)
        {
            $post4=1;
            $mesPost4=$mesPost3+1;

            if ($mesPost3 == 12)
            {
                $anoPost4 = $anoPost3+1;
                $mesPost4 = 1;
            } else {
                $anoPost4 = $anoPost3;
            }

        }else{

            $post4=		$dia+($diasRestan+4);
            $mesPost4=	$mesPost3;
            $anoPost4 = $anoPost3;

            if($post4>$numDiasMes)
            {
                $post4=$post4-$numDiasMes;
                $mesPost4=$mesPost3+1;
                if ($mesPost3 == 12)
                {
                    $anoPost4 = $anoPost3+1;
                    $mesPost4 = 1;
                } else {
                    $anoPost4 = $anoPost3;
                }
            }
            
        }

        $ForthNextWeek= $this->showDate ($mesPost4, $post+21, $anoPost4);
 //----------------------------------------------------------------------------------------

        $Output = $this->WeekTable 
                    (	$FirstNextWeek["diaMes"], //day of month
                    	$FirstNextWeek ["diaSemana"], //numeric representation of the day (1 for monday to 7 sunday)
                        $FirstNextWeek["numDiasMes"], //days in month 
                        $FirstNextWeek["nombreMes"],  //month name
                        $post, 
                        $mesPost, 
                        $anoPost		);
		
		$Output .= $this->WeekTable 
                    (	$SecondNextWeek["diaMes"], //day of month
                    	$SecondNextWeek ["diaSemana"], //numeric representation of the day (1 for monday to 7 sunday)
                        $SecondNextWeek["numDiasMes"], //days in month 
                        $SecondNextWeek["nombreMes"],  //month name
                        $post2, 
                        $mesPost2, 
                        $anoPost2		);

        $Output .= $this->WeekTable 
                    (	$ThirdNextWeek["diaMes"], //day of month
                    	$ThirdNextWeek ["diaSemana"], //numeric representation of the day (1 for monday to 7 sunday)
                        $ThirdNextWeek["numDiasMes"], //days in month 
                        $ThirdNextWeek["nombreMes"],  //month name
                        $post3, 
                        $mesPost3, 
                        $anoPost3		);

		$Output .= $this->WeekTable 
                    (	$ForthNextWeek["diaMes"], //day of month
                    	$ForthNextWeek ["diaSemana"], //numeric representation of the day (1 for monday to 7 sunday)
                        $ForthNextWeek["numDiasMes"], //days in month 
                        $ForthNextWeek["nombreMes"],  //month name
                        $post4, 
                        $mesPost4, 
                        $anoPost4		);

        return $Output;
    
    }
//-------------------------------------------------------------------------
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
//-----------------------------------------------------------------------------

//-------------------------DAY NAME FUNCTION------------------------------
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
 //-----------------------------------------------------------------------   
 //------------------------PREV MONTH FUNCTION----------------------------       

    function previousMonth ($dia, $mes, $ano){
        $mes = $mes-1;
        $mes= $this->showDate ($mes, $dia, $ano);
        return $mes;
    }
 //-----------------------------------------------------------------------   
 //------------------------NEXT MONTH FUNCTION----------------------------           
    function nextMonth ($dia, $mes, $ano){
        $mes = $mes+1;
        $mes= $this->showDate ( $mes, 1, $ano);
        return $mes;
    }
 //-----------------------------------------------------------------------   
 //------------------------WEEKS FUNCTION----------------------------       
    function numberOfWeek ($dia, $mes, $ano) {
        $firstDay = $this->showDate ($mes, 1, $ano);
        $numberOfWeek = ceil (($dia + ($firstDay ["diaSemana"]-1)) / 7);
        return $numberOfWeek;
    }
 //-----------------------------------------------------------------------   
 //------------------------WEEK TABLE FUNCTION----------------------------       
    function WeekTable ($diaMes, $diaSemana, $numDiasMes, $nombreMes, $dia, $mes, $ano) {
        if ($diaSemana == 0)
        { 
            $diaSemana = 7; 
        }

        $n = 0;
        $resta = $diaSemana - 1;
        $diaMes = $diaMes - $resta;
        $Output ="<div>";
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
            //$countStaff=$this->countStaff($diaMes, $mesVar, $anoVar);
            $dayName = $this->dayName ($i);
            
             $Quote  ="'";
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
            $ImIn=$this->checkStaff($diaMes, $mesVar, $anoVar);
             $Output .= 
             			'<div id="'.$day.$month.$anoVar.'" class="staffCalDay '.$ImIn.'" 
                            onclick="AddDayToCal(this.id, '.$day.','.$month.','.$anoVar.')" > 

                        	 <b>'.$this->dayName ($i).'</b>
                        	 <br>'.$diaMes.' '.substr($nameAnterior,0,3).'

               			</div>';

                        //$jobs= $this->CallStocktakes ($diaMes, $mesVar, $anoVar);
                       // $BodyCal .=
                         //   "<span id='".$anoVar."-".$month."-".$day."' class='daylong' ondrop='drop(event)'
                         //ondragover='allowDrop(event)'>".$jobs."</span>";


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
            //$countStaff=$this->countStaff($diaMes, $mesVar, $anoVar);
            $ImIn=$this->checkStaff($diaMes, $mesVar, $anoVar);
            $Output .=
                    '<div id="'.$day.$month.$anoVar.'" class="staffCalDay '.$ImIn.'" 
                    		onclick="AddDayToCal(this.id, '.$day.','.$month.','.$anoVar.')" >
                        		<b>'.$this->dayName($diaSemana).' </b>
                        		<br>'.$diaMes.' '.substr($nameSiguiente,0,3).'
                    </div>';
                //$jobs= $this->CallStocktakes ($diaMes, $mesVar, $anoVar);
                //$BodyCal .=
                //"<span id='".$anoVar."-".$month."-".$day."' class='daylong' ondrop='drop(event)'
                  //       ondragover='allowDrop(event)'>".$jobs."</span>";

            $diaMes ++;
        }
        $Output .="<span style='clear:both;'></span>";
        
        //$Output .= $BodyCal."</div>";
        $Output .= "</div>";
        return $Output;
        mysqli_close($conn);
    }

 //----------------------------------------------------------------------- 
 //-----------------------check availability of staff in a day----------------------------
    function checkStaff($day, $month, $year) {
            
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
        require ("db_connect.php"); 

            $fullDate1 =$year."-".$month."-".$day." 00:00:00";
            $fullDate2 =$year."-".$month."-".$day." 23:59:59";

            $id= $this->StaffID;
            $sql = "SELECT count(*) as num  
                    FROM tblUserAvailability
                    WHERE AvailabilityDate  >= '$fullDate1'
                    AND   AvailabilityDate  <= '$fullDate2'
                    AND (UserId)=$id;";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            //echo $result;
            if (mysqli_num_rows($result)>0) 
            {
                $row = mysqli_fetch_assoc($result);
                //echo $row['num'];
                if($row['num']>=1)
                	return "IAmAvailable";
                else
                	return "IAmNotAvailable";
            } else {
                return "IAmNotAvailable";
            }
    }
 //-----------------------------------------------------------------------  
//--------------------------------------------------------------------------

}//End of Class
?>