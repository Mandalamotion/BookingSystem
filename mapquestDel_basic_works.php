<!DOCTYPE html >
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta content="6000" http-equiv="refresh" />
<title>Mapquest - JavaScript Test</title>
<link href="Main.css" rel="stylesheet" type="text/css" />
<script src="http://www.mapquestapi.com/sdk/js/v7.2.s/mqa.toolkit.js?key=j9gWLd09sw2pgf4l5f9OFnGqGczGG5fe"></script>
<?php
try{
    //Connect get qryGetJobDetails for outputting to the map as Lat and Long coordinates
	$MAXDATA = 10;
	$dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=d:\Data\Dan\TRANSIT UK\BookingSystem\TUKDBTables.accdb");
	$sql = "SELECT top ".$MAXDATA ." * from TMPDeliveryPoints";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$colcnt = $stmt->columncount();
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
 
  // An example of using the MQA.EventUtil to hook into the window load event and execute the defined
  // function passed in as the last parameter. You could alternatively create a plain function here and
  // have it executed whenever you like (e.g. <body onload="yourfunction">).
 
  MQA.EventUtil.observe(window, 'load', function() {
 
    // create an object for options
    var options = {
      elt: document.getElementById('map'),       // ID of map element on page
      zoom: 6,                                  // initial zoom level of the map
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
    		 
	   //First pin
	   
		 var info1 = new MQA.Poi({ lat: 53.8833, lng: 17.72 });
		info1 .setRolloverContent('TUQ8676 PL - GB.  K&N(L)');  
		//info1 .setIcon(spaceFreight);
		map.addShape(info1);
		//Second Pin
		var info2 = new MQA.Poi({ lat: 53.8833, lng: 17.82 });
		info2 .setRolloverContent('TUQ8677 PL - GB.  K&N(L)');  
		//info2 .setIcon(t75);
		map.addShape(info2);

 });  //Final EventUtil observe

 
  
		
		
		
		
		   
</script>
</head>

<body>

<div id="map" style="width: 1200px; height: 800px;">
</div>

</body>

</html>
