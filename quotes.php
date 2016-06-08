<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="60"/>
<title>Quotes test Traffic lights</title>
<link href="Main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
try{
   // Connect
$dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=d:\Data\Dan\TRANSIT UK\BookingSystem\TUKDBTables.accdb");
$sql = "SELECT top 1 * from qryTrafficLight";
//$result = $dbh->query($sql);
//$row = $result->fetchAll(PDO::FETCH_ASSOC);
//var_dump($row);

//set locale
date_default_timezone_set('Europe/London');
setlocale(LC_ALL, array('en_GB.UTF8','en_GB@euro','en_GB','english'));
$stmt = $dbh->prepare($sql);
$stmt->execute();
$colcnt = $stmt->columncount();
$total = $stmt->rowCount();
//Set system time
//print_r(getdate());
$now = time(); //Unix time stamp
$current =date_create("$now[year]$now[month]$now[mday]$now[hour]$now[min]");
$nowy =date("Y-m-d H:i",$now);
echo $nowy;
//End set system time

echo '<Div>';  //Content
	echo  "<div class='header'>";
		echo 'Jobs status';
	echo '</Div>';
echo '<table><thead>';

 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
 echo '<Tr>';
    foreach($row as $key=>$val)
    {
    //echo '<strong>'.$key.'</strong> - '.$val . ',';
	echo '<th>'.$key.'</th>';
    }
 echo '</Tr>';
   }
   echo '</thead>';
 $sql = "SELECT  * from qryTrafficLight";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$colcnt = $stmt->columncount();
$total = $stmt->rowCount();
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
					echo "<TD class='warning'>NOT Completed</TD>";
				}
				else{
					echo "<TD class='info'>At Site</TD>";
				}
			break;
			case "Loaded":
			if ($val == 0){
					echo "<TD class='warning'>NOT Completed</TD>";
				}
				else{
					echo "<TD class='info'>Loaded</TD>";
				}
			break;
			case "DelPoint":
			if ($val == 0){
					echo "<TD class='warning'>NOT Completed</TD>";
				}
				else{
					echo "<TD class='info'>At Delivery Site</TD>";
				}
			break;
			case "Unloaded":
				if ($val == 0){
					echo "<TD class='warning'>NOT Completed</TD>";
				}
				else{
					echo "<TD class='info'>Unloaded</TD>";
				}
			break;
			case "CMR":
				if ($val == 0){
					echo "<TD class='warning'>NOT Completed</TD>";
				}
				else{
					echo "<TD class='info'>Have CMR</TD>";
				}
			break;
			case "Collection time":
			/* TEST ON COLLECTION FIRST
			Want to work out warnings for collections that are imminent - only if collection has not 
			already occurred
			2015-10-21 SVC
			*/
			$isLoaded = $row['CollPoint'];
			//get time
			$nextTime = $row['Collection time'];
			$h = substr($nextTime,0,2);
			$min = substr($nextTime,3,2);
			//get date
			$next = $row['Collection date'];
			$d = substr($next,-4);
			$m = substr($next,3,2);
			$y = substr($next,0,2);
			
			$next = date_create($y."-".$m ."-" . $d . " " . $h . ":" . $min); //reassigned to $next
			//What is difference?
			$diff = date_diff($next,$current);
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;				
			//end collection section
			if($isLoaded == 0){
				if($next > $current || $next->format('u') > $current->format('u')){ //Not sure need both checks, leave in anyway
				//Now check on how long.
					switch(True){
					
						case $total < 60 && $total >= 30  :
							echo '<TD class ="yellow">'.$val .'Hrs. Due in ' .$diff->format("%i Minutes").'</TD>';
	
						break;
						case $total < 30 && $total >= 15  :
							echo '<TD class ="amber">'.$val .'Hrs. Due in ' .$diff->format("%i Minutes").'</TD>';
	
						break;
						case $total < 15  :
							echo '<TD class ="warning">'.$val .'Hrs. Due in ' .$diff->format("%i Minutes").'</TD>';
	
						break;
						default:
							echo '<TD>'.$val .'Hrs' .'</TD>';

					}
  					
  					
  				}
				else{
				//Loaded but system time bigger than current therfore have passed the date. Still want to warn? 
				// Just make red
					echo '<TD class ="warning">'.$val .'Hrs. Overdue by ' .$diff->format("%R %d days, %h hours, %i Minutes").'</TD>';

				}
			}
			else{
			//Loaded is tick, all okay
				echo '<TD>'.$val .'Hrs' .'</TD>';
			} //EO if loaded 
			
			break;
			case "For":
				echo "<TD class='bigger'>".$val .'</TD>';
			break;
			case "TUK":
			
			echo "<TD class='bigger'>".$val . "</TD>";
			break;

			default:
				echo '<TD>'.$val . '</TD>';
			break;
		}
    }
	echo '</TR>';
   } 
   echo '<tbody>';
echo '</table>';   
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