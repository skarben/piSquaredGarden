<?php
//index.php
include('config.php');
if (isset($_GET['location'])) {
	$currentLocationKey = $_GET['location'];
}
else {
	$currentLocationKey = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15';
}
$locationsArray = explode(",", $currentLocationKey);

$connect = mysqli_connect($hostname, $username, $password, $database);
$queryo = '
	SELECT
		locationReadingSets.gardenKey
		, locationReadings.locationKey
		, locationReadingSets.ambientTemperature
		, locationReadingSets.ambientHumidity
		, locationReadings.temperature
		, locationReadings.moisture
		, locationReadingSets.recordedtimestamp
		, UNIX_TIMESTAMP(locationReadingSets.recordedtimestamp) AS datetime
	FROM
		locationReadings
		, locationReadingSets
	WHERE
		locationReadings.locationReadingSetsId = locationReadingSets.id
	ORDER BY
		recordedtimestamp ASC, locationKey ASC
';

$result = mysqli_query($connect, $queryo);

if (!$result) {
    die('Invalid query: ' . $queryo . mysqli_connect_error());
}
$newDataArray = "[\n['Time', ";
foreach ($locationsArray as $locNum){
	$newDataArray .= " 'Location " . $locNum . " Temp', "; 
}
$newDataArray .= "'AmbientTemp']";

//$newDataArray = str_replace("'Location ". "2" . " Temp', ", "", $newDataArray);
$oldRow;
while($row = mysqli_fetch_array($result))
{
	$i++;
	if ($oldRow != $row['recordedtimestamp']){
		$newDataArray .= ",\n" . "['" . $row['recordedtimestamp'] . "',";	
		$oldRow = $row['recordedtimestamp'];
	}

	if (in_array($row['locationKey'], $locationsArray)){
		$newDataArray .= $row['temperature'] . ",";	
	}

	if ($row['locationKey']== end($locationsArray)){
		$newDataArray .= $row['ambientTemperature'] . "]";
	}
	
}
$newDataArray .= "\n]";


	if (isset($_GET['data']) AND strlen($_GET['data']) > 150 AND explode(",", $_GET['data'])[35] == $inputPassword) {
		$dataString = $_GET['data'];
		echo "<h1>This data was sent:</h1>";
		echo "<pre>" . $dataString . "</pre>";
		
		// explode(",", $_GET['data'])[35] == $inputPassword
		
		
		
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


mysqli_close($connect);

?>
<html>
 <head>
 <META  name="description" content="Samson Karben's Website.">
 <META  name="keywords" content="Samson Karben, Karben, arduino, raspberry pi, garden, automate">
 <title>P^2 Garden</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
   google.charts.load('current', {'packages':['corechart']});
   google.charts.setOnLoadCallback(drawChart);

//	var jsonTable0 = <?php echo json_encode($table); ?>;
//	var jsonTable = JSON.parse(jsonTable0);

	function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo $newDataArray ?>
			
			);
        var data2 = google.visualization.arrayToDataTable(<?php echo $newDataArray ?>
			
			);
        var options = {
          title: 'Location(s) <?php echo $currentLocationKey ?> Temperature vs. Ambient Temperature',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));


        chart.draw(data, options);

	}
/*    var options = {
     title:'Sensors Data',
     legend:{position:'bottom'},
     chartArea:{width:'95%', height:'65%'}
    };
    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, {width: 400, height: 240});
   }
*/
  </script>
 </head>  
 <body>
	<h2 align="center">Display Google Line Chart with JSON PHP & Mysql</h2>
	<div id="curve_chart" style="width: 900px; height: 500px"></div>
	<div id="curve_chart2" style="width:800px; height: 600px"></div>
  </div>
  <hr/>
  <pre>
	  <?php
	  echo $jsonTable;
		 
	  echo "<hr/>";
	  echo $newDataArray;
	  ?>

  </pre>
 </body>
</html>
<!--
			[
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]
-->