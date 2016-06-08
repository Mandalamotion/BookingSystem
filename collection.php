<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=8859-1" />
<meta http-equiv="refresh" content="60"/>
<title>COLLECTION Traffic lights</title>
<link href="Main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
try{
   // Connect
$dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=d:\Data\Dan\TRANSIT UK\BookingSystem\TUKDBTables.accdb;charset=8859-1");
$sql = "SELECT top 1 * from qryTrafficLightCollection";
//$result = $dbh->query($sql);
//$row = $result->fetchAll(PDO::FETCH_ASSOC);
//var_dump($row);
//Flag image base Directory

$Flag_base_dir = "images/CountryFlags/";
$flag_default_ext = ".png";

//2016-04-18 Added in outputting the 'funny characters' mb_convert_encoding function - PDO ODBC O/P limitations

//set locale
date_default_timezone_set('Europe/London');
setlocale(LC_ALL, array('en_GB.UTF8','en_GB@euro','en_GB','english'));
$stmt = $dbh->prepare($sql);
$stmt->execute();
$colcnt = $stmt->columncount();
//$total = $stmt->rowCount();
//Set system time
//print_r(getdate());
$now = time(); //Unix time stamp
$current =date_create("$now[year]$now[month]$now[mday]$now[hour]$now[min]");
$nowy =date("Y-m-d H:i",$now);
echo "Server time: " . $nowy;
//End set system time
$stmt = $dbh->prepare($sql);
$stmt->execute();
$colcnt = $stmt->columncount();
$reccnt = 0;
echo '<Div>';  //Content
	echo  "<div class='header'>";
		echo '  Collection Status for jobs.';
	echo '</Div>';
echo '<table><thead>';

 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
 echo '<Tr>';
    foreach($row as $key=>$val)
    {
    //echo '<strong>'.$key.'</strong> - '.$val . ',';
	  if ($key != 'Banner' && $key != 'Unit name' && $key != 'Loaded' && $key != 'ComboGroupID' && $key != 'impexplu' && $key != 'CollFlag' && $key != 'DelFlag' && $key != 'To'){
		 echo '<th>'.$key.'</th>';	 
	  }
		if ($key == 'Unit name'){
			echo '<th>Coll Address</th>';
		}
		if ($key == 'ComboGroupID'){
			echo '<th>C</th>';
		}
		if ($key == 'impexplu'){
			echo '<th>Job</th>';
		}
		if ($key == 'CollFlag'){
			echo '<th>CF</th>';
		}
		if ($key == 'DelFlag'){
			echo '<th>DF</th>';
		}
		if ($key == 'To'){
			echo "<th class='LHSLine'>".$key."</th>";		}



		if ($key == 'Loaded'){
			echo "<th>".$key."</th>";		}

    }
 echo '</Tr>';
   }
   echo '</thead>';
 $sql = "SELECT  * from qryTrafficLightCollection";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$colcnt = $stmt->columncount();


echo '<tbody>';
 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
echo '<TR>';
    foreach($row as $key=>$val)
    {
		switch ($key)
		{
			case "Booked":
			if ($val == 0){
					echo "<TD class='warning'>NOT BOOKED OUT!</TD>";
				}
				else{
					echo "<TD class='info'>Booked</TD>";
				}
			break;
			case "CollPoint":
			if ($val == 0){
					echo "<TD class='warning'>NC</TD>";
				}
				else{
				//was infoColl
					echo "<TD class='infoColl'>On site</TD>";
				}
			break;
			case "Loaded":
			if ($val == 0){
					echo "<TD class='warning'>NC</TD>";
				}
				else{
				//Was infoLoaded
					echo "<TD class='info'>Loaded</TD>";
				}
			break;
			
			//Build in continual time checking and mark if getting close to this time
			case "Coll time":
			/* TEST ON COLLECTION FIRST
			Want to work out warnings for collections that are imminent - only if collection has not 
			already occurred
			2015-10-21 SVC
			*/
			$isLoaded = $row['CollPoint'];
			//2014-04-20 SVC get the later time now r.t. from time  Assume all same day
			$nextTime = $row['Coll time'];
			$h = substr($nextTime,8,2);
			$min = substr($nextTime,11,2);
			
			//get date
			$next = $row['Coll date'];
			$d = substr($next,-2);
			$m = substr($next,5,2);
			$y = substr($next,0,4);
			//echo "Is loaded Date format: " . $next . "d " .$d . " m " . $m . " y " . $y;

			$next = date_create($y."-".$m ."-" . $d . " " . $h . ":" . $min); //reassigned to $next
			//What is difference?
			$diff = date_diff($next,$current);
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;				
			//end collection section
			if($isLoaded == 0){
				if($next > $current || $next->format('u') > $current->format('u')){ //Not sure need both checks, leave in anyway
				//Now check on how long.
					switch(True){
					
						case $total < 120 && $total >= 60  :
							echo '<TD class ="yellow"><div style="width: 150px" >'.$val .'Hrs. Due in ' .$diff->format("%h hour %i Minutes").'</div></TD>';
	
						break;
						case $total < 60 && $total >= 30  :
							echo '<TD class ="amber"><div style="width: 150px" >'.$val .'Hrs. Due in ' .$diff->format("%i Minutes").'</div></TD>';
	
						break;
						case $total < 30  :
							echo '<TD class ="warning"><div style="width: 150px" >'.$val .'Hrs. Due in ' .$diff->format("%i Minutes").'</div></TD>';
	
						break;
						default:
							echo '<TD><div style="width: 150px" >'.$val .'Hrs' .'</div></TD>';

					}
  					
  					
  				}
				else{
				//Loaded but system time bigger than current therfore have passed the date. Still want to warn? 
				// Just make red
					echo '<TD class ="warning"><div style="width: 150px" >'.$val .'Hrs. Overdue by ' .$diff->format("%R %d days, %h hour(s) %i Minute(s)").'</div></TD>';

				}
			}
			else{
			//Loaded is tick, all okay
				echo '<TD>'.$val .'Hrs' .'</TD>';
			} //EO if loaded 
			
			break;
			
			
			//2016-04-18 Add in country flags
			case "CollFlag":
				if ($val <> "britain"){
					echo "<TD><img class ='flagSize' alt='". $val ."' src='" . $Flag_base_dir .$val .$flag_default_ext . "'/></TD>";
				   }
				else{
					echo '<TD>&nbsp;</TD>';
				}
			break;
			
	    	case "DelFlag":
				if ($val <> "britain"){
					echo "<TD><img class ='flagSize' alt='". $val ."' src='" . $Flag_base_dir .$val .$flag_default_ext . "'/></TD>";
				   }
				else{
					echo '<TD>&nbsp;</TD>';
				}
			break;
			
			case "To":
			echo "<td class='LHSLine'>".$val."</td>";
			break;

			
			
			case "For":
				echo "<TD class='bigger'>".$val .'</TD>';
			break;

			
			case "Unit name":
			//Want to mark dummy Collection / Delivery addresses
			$mark = substr($val,0,5);
			if(strtoupper($mark) == 'T3MP0' || strtoupper($mark) == 'DUMMY'){
				echo "<TD class='warning'>Del TBA</TD>";
			}
			else{
				$val = mb_convert_encoding($val, "UTF-8", "Windows-1252");

				echo '<TD>'.$val . '</TD>';
			}
			break;
			case "Banner":
			    //This works since banner is first column in the query
				//echo "<TD class='bigger'>".$val .'</TD>';
				//Check whether the banner has a message
				if ($val ==''){
					$issue = 'False';
				}
				else{
					$issue = $val;
				}
			break;
			case "TUK":
			//Check if issue flag is set and colour item accordingly
				if ($issue == 'False'){
					echo "<TD class='bigger'>".$val .'</TD>';
				}
				else{
					echo "<TD class='otherwarning'>".$val . '<br /> ' .$issue.'</TD>';
				}	
			break;
			
			case "Coll date":
			$next = $row['Coll date'];
			$d = substr($next,-2);
			$m = substr($next,5,2);
			$y = substr($next,0,4);

			echo '<TD>'.$d.'/'.$m.'/'.$y . '</TD>';

			break;
			
			//1=IM;2=Ex;3=Int;4=RoundTrip;5=UK
			case "impexplu":			
				if ($val == 1){
					$output = "IM";
					echo '<TD style="background-color:#ff0000">'.$output . '</TD>';

				}
				else if ($val == 2){
					$output = "EX";
					echo '<TD style="background-color:cornflowerblue">'.$output . '</TD>';
					//echo '<TD>&nbsp;</TD>';

				}
				else if ($val == 3){
					$output = "INT";
					echo '<TD style="background-color:#fccd98">'.$output . '</TD>';

				}
				else if ($val == 5){
				//Green for UK
					$output = "UK";
					echo '<TD style="background-color:#009933">'.$output . '</TD>';
				}

				else{
					$output = "";
					echo '<TD>&nbsp;</TD>';
				}
			
			break;

			case "ComboGroupID":
			//Want to use same colour for same combos
			$basicColour = "cccccc";
			if ($val == 0){
				//Don't need to output value as not a combo group
				echo '<TD>&nbsp;</TD>';

			}
			else
			{
				$ColourOp = $val%3; //Mod 3 plus  get unique value use for which RGB component to modify
				
				if($ColourOp == 0) $ColourOp = 3;
				
				if ($ColourOp == 1){
					$basicColour = $val . $val . 'f' . 'f' . $val . $val;
				}
				if ($ColourOp == 2){
					$basicColour =  'f' . 'f' . $val . $val. $val . $val;
				}
				if ($ColourOp == 3){
					$basicColour =  $val . $val. $val . $val . 'f' . 'f' ;
				}
				
				
				//echo $val . " " . $basicColour . "/// ";
				
				echo "<TD style='background:#" . $basicColour .";'>".$val . "</TD>";
			}		
			break;
			default:
				$val = mb_convert_encoding($val, "UTF-8", "Windows-1252");

				echo '<TD>'.$val . '</TD>';
			break;
		}
    }
	echo '</TR>';
	
	$reccnt++;
   } 
  echo '<TR><TD class ="bottomMsg" colspan = "100%"> <strong>C</strong> = Combo jobs flag;   <strong>Dir</strong> = Direction of job Flag either (IM)port, (Ex)port, (INT)ernal Europe or UK.  Check times compared to GMT/BST;   <strong>NC</strong> = Not Completed.</TD</TR>'; 
  
   echo '<tbody>';
echo '</table>'; 
echo '<h3>  ' .$reccnt . ' Jobs</h3>';  
echo '</div>';  //EOF Content
    $stmt = null;
	$dbh = null;
}
catch(PDOException $e){
    echo $e->getMessage();
}
?>
</body>
</html>