<?php
//index.php
include('config.php');
if (isset($_GET['location'])) {
	$currentLocationKey = $_GET['location'];
}
else {
	$currentLocationKey = '1';
}
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
		AND locationReadings.locationKey = "' . $currentLocationKey . '"
	ORDER BY
		recordedtimestamp ASC, locationKey ASC
';

$query1 = '
	SELECT
		UNIX_TIMESTAMP(locationReadingSets.recordedtimestamp) AS datetime
		, locationReadings.locationKey
		, locationReadingSets.ambientTemperature
		, locationReadingSets.ambientHumidity
		, locationReadings.temperature
		, locationReadings.moisture
	FROM
		locationReadings
		, locationReadingSets
	WHERE
		locationReadings.locationReadingSetsId = locationReadingSets.id
	ORDER BY
		recordedtimestamp DESC, locationKey ASC
	LIMIT 10
';
$query = '
	SELECT
		UNIX_TIMESTAMP(locationReadingSets.recordedtimestamp) AS datetime
		, locationReadingSets.ambientTemperature
	FROM
		locationReadingSets
	ORDER BY
		recordedtimestamp DESC
	LIMIT 10
';

/*
*/
$result = mysqli_query($connect, $queryo);
if (!$result) {
    die('Invalid query: ' . $queryo . mysqli_connect_error());
}

$newDataArray = "[\n['Time', 'Location $currentLocationKey Temp', 'AmbientTemp']";

while($row = mysqli_fetch_array($result))
{
	$i++;
	$newDataArray .= ",\n" . "['" . $row['recordedtimestamp'] . "'," . $row['temperature'] . "," . $row['ambientTemperature'] . "]";
}
$newDataArray .= "\n]";




$result = mysqli_query($connect, $query);
	if (!$result) {
	    die('Invalid query: ' . $query . mysqli_connect_error());
	}
$rows = array();
$table = array();

$table['cols'] = array(
 /*array(
  'label' => 'gardenKey', 
  'type' => 'number'
 ),*/
 array(
  //'label' => 'recordedtimestamp', 
  'type' => 'number'
 ),
 /*array(
  'label' => 'locationKey', 
  'type' => 'number'
 ),*/
 array(
  //'label' => 'ambientTemperature', 
  'type' => 'number'
 )/*,
 array(
  'label' => 'ambientHumidity', 
  'type' => 'number'
 ),
 array(
  'label' => 'temperature', 
  'type' => 'number'
 ),
 array(
  'label' => 'moisture', 
  'type' => 'number'
 ),*/
);

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 //$datetime = explode(".", $row["datetime"]);
 $sub_array[] =  array(
      "v" => floatval($row["datetime"])
     ); 
 /*$sub_array[] =  array(
      "v" => $row["gardenKey"]
     ); */
 /*$sub_array[] =  array(
      "v" => $row["locationKey"]
     );*/
 $sub_array[] =  array(
      "v" => floatval($row["ambientTemperature"])
     );
 /*$sub_array[] =  array(
      "v" => $row["ambientHumidity"]
     );
 $sub_array[] =  array(
      "v" => $row["temperature"]
     );
 $sub_array[] =  array(
      "v" => $row["moisture"]
     );*/

 $rows[] =  array(
     "c" => $sub_array
    );
}
$table['rows'] = $rows;
$jsonTable = json_encode($table);
// echo $jsonTable;
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
          title: 'Location <?php echo $currentLocationKey ?> Temperature (blue) vs. Ambient Temperature',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        var chart2 = new google.visualization.LineChart(document.getElementById('curve_chart2'));


        chart.draw(data, options);
        chart2.draw(data2, options);

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