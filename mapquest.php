<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="6000"/>
<title>Basic Mapquest</title>
<link href="Main.css" rel="stylesheet" type="text/css" />
<script src="http://www.mapquestapi.com/sdk/js/v7.2.s/mqa.toolkit.js?key=j9gWLd09sw2pgf4l5f9OFnGqGczGG5fe"></script>
 
<?php
try{
    //Connect get qryGetJobDetails for outputting to the map as Geocodes
	$dbh = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=d:\Data\Dan\TRANSIT UK\BookingSystem\TUKDBTables.accdb");
	$sql = "SELECT  top 100 * from qryGetJobDetails";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$colcnt = $stmt->columncount();
}
catch(PDOException $e){
    echo $e->getMessage();
}
?>



</head>

<body>
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
    		 
	 	// setting only the rollover content
	 	var spaceFreight = new MQA.Icon('https://www.mapquestapi.com/staticmap/geticon?uri=poi-green_1.png', 20, 29);
	 	var t75 = new MQA.Icon('https://www.mapquestapi.com/staticmap/geticon?uri=poi-red_1.png', 20, 29);
		var x = 40.9206;
		var y = 5.0;
		
				
		// download the geocoder module
		MQA.withModule('geocoder', function() {
		// executes a geocode and adds the result to the map		
        //map.geocodeAndAddLocations('gb ss1 2ds')
		 		   

  

		
		

		
	<?php
		try{
			 
			 while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
 				$strrow = "";
 				foreach($row as $key=>$val)
			    {
			    
				    $strrow .= $val . " "; 
				    //map.geocodeAndAddLocations(<?=$val['Country']. " ".$val['PC']);
				
	     		}
	     		//$strrow .= "<br />";
	     		
	     		//echo $strrow;
	    		 echo    	map.geocodeAndAddLocations( "gb hp16 0lf");
		
	  ?>     
	  <?php
		     	$strrow = "";
			}
			$stmt = null;
		 	$dbh = null;
	    }
		catch(PDOException $e){
		    echo $e->getMessage();
		}
	?>  

});
	
		//map.bestFit();	 // automatically zoom and center the map using the bestFit method
});
		   
</script>		  
		
<div id="map" style="width:1200px; height:800px;"></div>

</body>
</html>