<!DOCTYPE html >
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta content="6000" http-equiv="refresh" />
<title>Delivery points - max top 900</title>
<link href="Main.css" rel="stylesheet" type="text/css" />
<script src="http://www.mapquestapi.com/sdk/js/v7.2.s/mqa.toolkit.js?key=j9gWLd09sw2pgf4l5f9OFnGqGczGG5fe"></script>
<?php
try{
    //Connect get qryGetJobDetails for outputting to the map as Lat and Long coordinates
	$MAXDATA = 900;  //Records in the table - can't output all at once
	$STARTPOINT = 1;
	$ENDPOINT = $MAXDATA - $STARTPOINT;
	//Office connection string  - MAKE INTO MYSQL AS WELL
	//$dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=d:\Data\Dan\TRANSIT UK\BookingSystem\TUKDBTables.accdb");
	$dbh = new PDO('mysql:host=192.168.0.5;dbname=transituk;charset=utf8', 'root', 'peaches1');

	//Home connection string :-(
//	$dbh = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'peaches');
	
	//$sql = "SELECT top ".$MAXDATA ." * from TMPDeliveryPoints";
	//$sql = "SELECT * from TMPDeliveryPoints LIMIT " . $STARTPOINT . "," . $ENDPOINT;
	$sql = "SELECT * FROM `tmpdelmore` LIMIT " . $STARTPOINT . "," . $ENDPOINT;
////echo "SQL IS: " . $sql;
	
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$colcnt = $stmt->columncount();
	$row_count = $stmt->rowCount();
	////echo "RowCount: " . $row_count;
	//$Job_Values;
	$itemCnt = 0;   
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		// 	$strrow = "";
		//foreach($row as $key=>$val){
		//$Job_Values .= '["'. implode('","',array_values($row)) . '"]';
		
		$Job_Values[$itemCnt] = $row;
		//$Job_Values .= '+++';
	   //$Job_Keys .= '["'  . implode('","',array_keys($row)) . '"]';
		 	
		 	//}
	 $itemCnt++;
   //  $Job_Keys  = '["'  . implode('","',array_keys($row)) . '"]';
    }
    
    
    
  // $Job_Values .= '"]';
        $stmt = null;
		$dbh = null;
		  
	
}
catch(PDOException $e){
    echo $e->getMessage();
}
?>

<script type="text/javascript">
// setting only the rollover content
	 	var spaceFreight = new MQA.Icon('https://www.mapquestapi.com/staticmap/geticon?uri=poi-green_1.png', 20, 29);
	 	var t75 = new MQA.Icon('https://www.mapquestapi.com/staticmap/geticon?uri=poi-red_1.png', 20, 29);
		var x = 40.9206;
		var y = 5.0;
		var jsI = 0;	
 
  // An example of using the MQA.EventUtil to hook into the window load event and execute the defined
  // function passed in as the last parameter. You could alternatively create a plain function here and
  // have it executed whenever you like (e.g. <body onload="yourfunction">).
 
  MQA.EventUtil.observe(window, 'load', function() {
 
    // create an object for options
    var options = {
      elt: document.getElementById('map'),       // ID of map element on page
      zoom: 4,                                  // initial zoom level of the map
      latLng: { lat: 50.0, lng: 4.0 },  // center of map in latitude/longitude
      mtype: 'map',                              // map type (map, sat, hyb); defaults to map
      bestFitMargin: 0,                          // margin offset from map viewport when applying a bestfit on shapes
      zoomOnDoubleClick: true                    // enable map to be zoomed in when double-clicking
    };
    
    
 
    // construct an instance of MQA.TileMap with the options object
    window.map = new MQA.TileMap(options);
    
   
  	// download the modules
		MQA.withModule('largezoom', 'traffictoggle', 'viewoptions', 'geolocationcontrol', 'insetmapcontrol', 'mousewheel', function() {
 
  		// add the Large Zoom control
  		map.addControl(
    	new MQA.LargeZoom(),
		    new MQA.MapCornerPlacement(MQA.MapCorner.TOP_LEFT, new MQA.Size(5,5))
		  );
		 
		  // add the Traffic toggle button
		  map.addControl(new MQA.TrafficToggle());
		 
		  // add the Map/Satellite toggle button
		  map.addControl(new MQA.ViewOptions());
		 
		  // add the Geolocation button
		  map.addControl(
		    new MQA.GeolocationControl(),
		    new MQA.MapCornerPlacement(MQA.MapCorner.TOP_RIGHT, new MQA.Size(10,50))
		  );
		 
		  // add the small Inset Map with custom options
		  map.addControl(
		    new MQA.InsetMapControl({
		      size: { width: 150, height: 125 },
		      zoom: 3,
		      mapType: 'map',
		      minimized: true
		    }),
		    new MQA.MapCornerPlacement(MQA.MapCorner.BOTTOM_RIGHT)
		  );
		 
		  // enable zooming with your mouse
		  map.enableMouseWheelZoom();
		});
    	/*
		//First pin
		 var info1 = new MQA.Poi({ lat: 53.8833, lng: 17.72 });
		info1 .setRolloverContent('TUQ8676 PL - GB.  K&N(L)');  
		info1 .setIcon(spaceFreight);
		map.addShape(info1);
		//Second Pin
		var info2 = new MQA.Poi({ lat: 53.8833, lng: 17.82 });
		info2 .setRolloverContent('TUQ8677 PL - GB.  K&N(L)');  
		info2 .setIcon(t75);
		map.addShape(info2);
		*/
//Okay to here		
		
<?PHP
 	for($i = 0; $i< $row_count; $i++){
	//Output PHP String
?>
		var tstval = window['info' + jsI];
		var latlongTo =  <?php echo json_encode($Job_Values[$i]['LatLongTo']); ?>;
		var latfrom  = latlongTo.slice(0,latlongTo.search(",")-1);
		var longfrom =  latlongTo.slice(latlongTo.search(",")+1,latlongTo.length );
        
		var latlongFrom =  <?php echo json_encode($Job_Values[$i]['LatLongFrom']); ?>;
		var country_code  = <?php echo json_encode($Job_Values[$i]['generaldelivery']); ?>;
		//Build up the information string for the push pin
		var infoString =  "TUK" + <?php echo json_encode($Job_Values[$i]['TUK Number']); ?>+ " " + <?php echo json_encode($Job_Values[$i]['companyname']); ?>  +
		" £" + <?php echo json_encode($Job_Values[$i]['GBPQuotePrice']); ?> + 
		" " + <?php echo json_encode($Job_Values[$i]['mileage']); ?> + "miles";
		//Add in detail later.
		/*" City:" + <?php echo json_encode($Job_Values[$i]['City']); ?> +
		" Zip Code:" + <?php echo json_encode($Job_Values[$i]['ZIP/Postal Code']); ?> + */
		tstval = new MQA.Poi( {lat: latfrom, lng: longfrom});
		//tstval = new MQA.Poi( {lat: 51.54354, lng: 0.7086505});  //TEST LOC

		tstval .setRolloverContent(infoString);  
		if(country_code == 'GB')
			tstval.setIcon(spaceFreight);
		else
			tstval.setIcon(t75);
		
		
		map.addShape(tstval);
		
		jsI++;
<?php } ?>			

//map.bestFit();	
	
 });  //Final EventUtil observe
		
		
		
		   
</script>
<style type="text/css">
.auto-style1 {
	margin-bottom: 87px;
}
 p.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	line-height:115%;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	}
</style>
</head>

<body>
<div>
	<ul>
		<p class="MsoNormal" style="mso-margin-top-alt:auto;margin-bottom:12.0pt;
line-height:normal"><span>Current Summary Of Issues </span>01/03/2016&nbsp; All 
		services are up and running normally</p>
	</ul>
</div>
<div id="map" style="width: 1158px; height: 800px;" class="auto-style1">
</div>

</body>

</html>
