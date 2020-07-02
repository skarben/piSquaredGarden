<html>
<head>
	<META  name="description" content="Samson Karben's Website.">
	<META  name="keywords" content="Samson Karben, Karben, arduino, raspberry pi, garden, automate">
	<title>P^2 Garden</title>
	<link rel="stylesheet" href="karben14.css" type="text/css" />
</head>
<body>
<?php 
	include('config.php');
		
	if (isset($_GET['garden'])) {
		$currentGardenKey = $_GET['garden'];
	}
	else {
		$currentGardenKey = '1';
	}
		
	if (isset($_GET['location'])) {
		$currentLocationKey = $_GET['location'];
	}
	else {
		$currentLocationKey = '1';
	}
	
	if ($currentGardenKey == 'all') {
		$gardenSQL = '';
	}
	else {
		$gardenSQL = " AND locationReadingSets.gardenKey = '$currentGardenKey'";
	}
	if ($currentLocationKey == 'all') {
		$locationSQL = '';
	}
	else {
		$locationSQL = " AND locationReadings.locationKey = '$currentLocationKey'";
	}

	$connection = mysqli_connect($hostname, $username, $password, $database);
	if (!$connection) {
	    die('Not connected : ' . mysqli_connect_error());
	}

	$query = "
	SELECT
		locationReadingSets.gardenKey
		, locationReadings.locationKey
		, locationReadingSets.ambientTemperature
		, locationReadingSets.ambientHumidity
		, locationReadings.temperature
		, locationReadings.moisture
		, locationReadingSets.recordedtimestamp
	FROM
		locationReadings
		, locationReadingSets
	WHERE
		locationReadings.locationReadingSetsId = locationReadingSets.id
		$gardenSQL
		$locationSQL
		;
	";
	
	$googleQuery = "
	SELECT
		locationReadingSets.gardenKey
		, locationReadings.locationKey
		, locationReadingSets.ambientTemperature
		, locationReadingSets.ambientHumidity
		, locationReadings.temperature
		, locationReadings.moisture
		, locationReadingSets.recordedtimestamp
	FROM
		locationReadings
		, locationReadingSets
	WHERE
		locationReadings.locationReadingSetsId = locationReadingSets.id
		;
	";

	$result = mysqli_query($connection, $query);
	$googleResult = mysqli_query($connection, $googleQuery);
	if (!$result) {
	    die('Invalid query: ' . $query . mysqli_connect_error());
	}
	
	$table = array();
	$table[‘cols’] = array(
	//Labels for the chart, these represent the column titles
	array(‘id’ => ”, ‘label’ => ‘gardenKey’, ‘type’ => ‘number’),
	array(‘id’ => ”, ‘label’ => ‘locationKey’, ‘type’ => ‘number’),
	array(‘id’ => ”, ‘label’ => ‘ambientTemperature’, ‘type’ => ‘number’),
	array(‘id’ => ”, ‘label’ => ‘ambientHumidity’, ‘type’ => ‘number’),
	array(‘id’ => ”, ‘label’ => ‘temperature’, ‘type’ => ‘number’),
	array(‘id’ => ”, ‘label’ => ‘moisture’, ‘type’ => ‘number’),
	array(‘id’ => ”, ‘label’ => ‘recordedtimestamp’, ‘type’ => ‘datetime’)
	);
	
	/*$table[‘cols’] = array(
	//Labels for the chart, these represent the column titles
	array(‘label’ => ‘gardenKey’, ‘type’ => ‘number’),
	array(‘label’ => ‘locationKey’, ‘type’ => ‘number’),
	array(‘label’ => ‘ambientTemperature’, ‘type’ => ‘number’),
	array(‘label’ => ‘ambientHumidity’, ‘type’ => ‘number’),
	array(‘label’ => ‘temperature’, ‘type’ => ‘number’),
	array(‘label’ => ‘moisture’, ‘type’ => ‘number’),
	array(‘label’ => ‘recordedtimestamp’, ‘type’ => ‘datetime’)
	);*/

	$rows = array();
	foreach($googleResult as $row){
		$temp = array();

		//Values
		$temp[] = array(‘v’ => (float) $row[‘gardenKey’]);
		$temp[] = array(‘v’ => (float) $row[‘locationKey’]);
		$temp[] = array(‘v’ => (float) $row[‘ambientTemperature’]);
		$temp[] = array(‘v’ => (float) $row[‘ambientHumidity’]);
		$temp[] = array(‘v’ => (float) $row[‘temperature’]);
		$temp[] = array(‘v’ => (float) $row[‘moisture’]);
		$temp[] = array(‘v’ => (string) $row[‘recordedtimestamp’]);
		$rows[] = array(‘c’ => $temp);
	}
	
	$googleResult->free();

	$table[‘rows’] = $rows;

	$jsonTable = json_encode($table, true);
	//echo $jsonTable;
	echo ‘<pre>’;
	echo json_encode($table, JSON_PRETTY_PRINT);
	echo ‘</pre>’;
	
/*	$testJson = {
	"cols": [
  {"id":"","label":"gardenKey","pattern":"","type":"number"},
  {"id":"","label":"locationKey","pattern":"","type":"number"}
  {"id":"","label":"ambientTemperature","pattern":"","type":"number"}
  {"id":"","label":"ambientHumidity","pattern":"","type":"number"}
  {"id":"","label":"temperature","pattern":"","type":"number"}
  {"id":"","label":"moisture","pattern":"","type":"number"}
  {"id":"","label":"recordedtimestamp","pattern":"","type":"string"}
  ],
	"rows": [
  {"c":[{"v":1,"f":null},{"v":1,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.56,"f":null},{"v":509.0,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":2,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":22.37,"f":null},{"v":517.0,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":3,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.00,"f":null},{"v":522.0,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":4,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.25,"f":null},{"v":533,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":5,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.19,"f":null},{"v":527,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":6,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":22.94,"f":null},{"v":536,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":7,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.00,"f":null},{"v":538,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":8,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":22.69,"f":null},{"v":536,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":9,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.19,"f":null},{"v":538,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":10,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":22.81,"f":null},{"v":540,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":11,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.00,"f":null},{"v":530,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":12,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.19,"f":null},{"v":531,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":13,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.25,"f":null},{"v":493,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":14,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":22.81,"f":null},{"v":458,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":15,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":23.37,"f":null},{"v":421,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  {"c":[{"v":1,"f":null},{"v":16,"f":null},{"v":24.81,"f":null},{"v":81.24,"f":null},{"v":-127.00,"f":null},{"v":370,"f":null},{"v":"2020-06-23 19:15:11","f":null}]},
  ]
	}
	echo json_encode($testJson, true);*/
	
/*	echo "<h1>Garden " .$currentGardenKey . " Sensor Report</h1>";
	echo "<p><a href='./index.php?garden=all&location=all'>Get all data</a></p>";
		
	echo "<table border='1' cellspacing='0' cellpadding='3'><tr><th>Time</th><th>Garden</th><th>Location</th><th>Ambient Temp</th><th>Ambient Humidity</th><th>Soil Temp</th><th>Moisture</th></tr>";
	while ($row = @mysqli_fetch_assoc($result)) {
		$items[] = $row;
	}
	$items = array_reverse($items ,true);
	foreach($items as $item){
		echo "<tr><td>" . $item['recordedtimestamp'] . "</td><td>" . $item['gardenKey'] . "</td><td>" . $item['locationKey'] . "</td><td>" . $item['ambientTemperature'] . "</td><td>" . $item['ambientHumidity'] . "</td><td>" . $item['temperature'] . "</td><td>" . $item['moisture'] . "</td></tr>";
	}

	echo "</table>";
	
	
		
		date_default_timezone_set('UTC');
		$logfilepath = '/Users/alan/Sites/_k14/karben14/pi2/logs/log_'.date("Y.m.d").'.txt';
		$logfilepath = '/var/www/karben14/pi2/logs/log_'.date("Y.m.d").'.txt';
		date_default_timezone_set('America/Los_Angeles');
		
		function tailShell($filepath, $lines = 1) {
			ob_start();
			passthru('tail -'  . $lines . ' ' . escapeshellarg($filepath));
			return trim(ob_get_clean());
		}
*/
	
	
	mysqli_close($connection);
	
	?>



</body>
</html>
