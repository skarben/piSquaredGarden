<?php
//index.php
include('config.php');
$connect = mysqli_connect($hostname, $username, $password, $database);
$queryo = '
	SELECT
		locationReadingSets.gardenKey
		, locationReadings.locationKey
		, locationReadingSets.ambientTemperature
		, locationReadingSets.ambientHumidity
		, locationReadings.temperature
		, locationReadings.moisture
		, UNIX_TIMESTAMP(locationReadingSets.recordedtimestamp) AS datetime
	FROM
		locationReadings
		, locationReadingSets
	WHERE
		locationReadings.locationReadingSetsId = locationReadingSets.id
	ORDER BY
		recordedtimestamp DESC, locationKey ASC
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
echo $jsonTable;
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

	var jsonTable0 = <?php echo json_encode($table); ?>;
	var jsonTable = JSON.parse(jsonTable0);
	function drawMyChart(){
    
    var data = new google.visualization.DataTable(jsonTable);
    var options = {
     title:'Sensors Data',
     legend:{position:'bottom'},
     chartArea:{width:'95%', height:'65%'}
    };
   google.charts.setOnLoadCallback(drawMyChart);
    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, {width: 400, height: 240});
   }

  </script>
 </head>  
 <body>
	<h2 align="center">Display Google Line Chart with JSON PHP & Mysql</h2>
	<div id="curve_chart" style="width: 900px; height: 500px"></div>
  </div>
 </body>
</html>
