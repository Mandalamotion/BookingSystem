<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="60"/>
<title>Traffic lights</title>
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
$stmt = $dbh->prepare($sql);
$stmt->execute();
$colcnt = $stmt->columncount();
$total = $stmt->rowCount();
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
				echo '<TD>'.$val .'Hrs'. '</TD>';
			break;
			case "For":
				echo "<TD class='bigger'>".$val .'</TD>';
			break;
			case "TUK":
				echo "<TD class='bigger'>".$val .'</TD>';
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