<html>
<head>
	<META  name="description" content="Alan Karben's Website.">
	<META  name="keywords" content="Alan Karben, Karben, XML, SportsML, NewsML, NITF, online news, sports">
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
		$currentLocationKey = '3';
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
		, locationReadingSets.ambientTemperature
		, locationReadingSets.ambientHumidity
		, locationReadings.locationKey
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

	$result = mysqli_query($connection, $query);
	if (!$result) {
	    die('Invalid query: ' . $query . mysqli_connect_error());
	}
	
	echo "<h1>Garden Sensor Report</h1>";
	echo "<p><a href='./index.php?garden=all&location=all'>get all data</a></p>";
		
	echo "<table border='1' cellspacing='0' cellpadding='3'><tr><th>Time</th><th>Garden</th><th>Location</th><th>Ambient Temp</th><th>Ambient Humidity</th><th>Soil Temp</th><th>Moisture</th></tr>";
	while ($row = @mysqli_fetch_assoc($result)) {
			 echo "<tr><td>" . $row['recordedtimestamp'] . "</td><td>" . $row['gardenKey'] . "</td><td>" . $row['locationKey'] . "</td><td>" . $row['ambientTemperature'] . "</td><td>" . $row['ambientHumidity'] . "</td><td>" . $row['temperature'] . "</td><td>" . $row['moisture'] . "</td></tr>";
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
		
		// first "encode" your data string
		// then tack it on the end of this URL:
		// http://karben14.com/pi2/index.php?data=12345
		


	
	
	
	
	if (isset($_GET['data'])) {
		$dataString = $_GET['data'];
		echo "<h1>This data was sent:</h1>";
		echo "<pre>" . $dataString . "</pre>";
		
		
		
		
		
		$returnCode = file_put_contents('/var/www/karben14/pi2/logs/log_'.date("Y.m.d").'.txt', $dataString . "\n", FILE_APPEND);
		echo "<h1>Characters written to logfile: [$returnCode]</h1>";
		
		
		$dataArray = explode(",", $dataString);

		$setInsertionQuery = 'INSERT INTO locationReadingSets (gardenKey, recordedTimestamp, ambientTemperature, ambientHumidity) VALUES ("1","' . date("Y-m-d H:i:s",$dataArray[0]) . '","' . $dataArray[33] . '","' . $dataArray[34] . '");';
	
	
		$result = mysqli_query($connection, $setInsertionQuery);
		if (!$result) {
		    die('Invalid query: ' . $query . mysqli_connect_error());
		}
	
		$getLatestIdQuery = 'SELECT LAST_INSERT_ID() AS theID FROM locationReadingSets LIMIT 1;';
	
		$result = mysqli_query($connection, $getLatestIdQuery);
		if (!$result) {
		    die('Invalid query: ' . $query . mysqli_connect_error());
		}

		while ($row = @mysqli_fetch_assoc($result)) {
				 $latestID = $row['theID'];
		}
	
		echo "<h1>My Latest ID: $latestID</h1>";
	
		$readingInsertionQueries = '';
		for ($i=1;$i < 17; $i++) {
			$readingInsertionQueries = 'INSERT INTO locationReadings (locationReadingSetsId, locationKey, temperature, moisture) VALUES ("' . $latestID . '","' . $i . '","' . $dataArray[$i] . '","' . $dataArray[$i+16] . '");' . "\n";
			$result = mysqli_query($connection, $readingInsertionQueries);
			if (!$result) {
			    die('Invalid query: ' . $query . mysqli_connect_error());
			}
			echo "<pre>" . $readingInsertionQueries . "</pre>";
		}
	
	
	
		
		
	}
	else {
		echo "<h1>No new data has been sent.</h1>";
	}

		mysqli_close($connection);
	
	?>



</body>
</html>
