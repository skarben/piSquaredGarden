<html>
<head>
	<META  name="description" content="Samson Karben's Website.">
	<META  name="keywords" content="Samson Karben, Karben, arduino, raspberry pi, garden, automate">
	<title>P^2 Garden</title>
	<link rel="stylesheet" href="karben14.css" type="text/css" />
</head>
<body>

	<h1>echo result</h1>
	<?php
	include('config.php');
$connect = mysqli_connect($hostname, $username, $password, $database);
$query0 = "
SELECT JSON_ARRAYAGG(JSON_OBJECT(
	'gardenKey', locationReadingSets.gardenKey, 
	'locationKey', locationReadings.locationKey, 
	'ambientTemperature', locationReadingSets.ambientTemperature, 
	'ambientHumidity', locationReadingSets.ambientHumidity, 
	'temperature', locationReadings.temperature, 
	'moisture', locationReadings.moisture, 
	'Unix time', UNIX_TIMESTAMP(locationReadingSets.recordedtimestamp))) 
FROM 
	locationReadings, locationReadingSets
WHERE 
	locationReadings.locationReadingSetsId = locationReadingSets.id
ORDER BY 
	recordedtimestamp DESC, locationKey ASC
";
$query1 = '
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
	LIMIT
		10
';
$result0 = mysqli_query($connect, $query0);
$result1 = mysqli_query($connect, $query1);
$result = $result1;
/*$row = mysqli_fetch_array($result);
$row = mysqli_fetch_array($result);*/
// True because $a is empty

$rows = array();
$table = array();

$table['cols'] = array(
 array(
  'label' => 'gardenKey', 
  'type' => 'number'
 ),
 array(
  'label' => 'locationKey', 
  'type' => 'number'
 ),
 array(
  'label' => 'ambientTemperature', 
  'type' => 'number'
 ),
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
 ),
 array(
  'label' => 'recordedtimestamp', 
  'type' => 'datetime'
 )
);
//print_r($table);
while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 //$datetime = explode(".", $row["datetime"]);
 $sub_array[] =  array(
      "v" => $row["gardenKey"]
     ); 
 $sub_array[] =  array(
      "v" => $row["locationKey"]
     );
 $sub_array[] =  array(
      "v" => $row["ambientTemperature"]
     );
 $sub_array[] =  array(
      "v" => $row["ambientHumidity"]
     );
 $sub_array[] =  array(
      "v" => $row["temperature"]
     );
 $sub_array[] =  array(
      "v" => $row["moisture"]
     );
 $sub_array[] =  array(
      "v" => $row["datetime"]
     );
 $rows[] =  array(
     "c" => $sub_array
    );
}

//print_r($rows);
$table['rows'] = $rows;
//print_r($table);
//echo json_encode($table, JSON_PRETTY_PRINT);
echo json_encode($table);
//$jsonTable = json_encode($table, true);

if (empty($result)) {
  echo "Variable 'result' is empty.<br>";
}
// True because $a is set
if (isset($result)) {
  //echo "Variable 'result' is set";
  //print_r($result);
 // $jsonTable = json_encode(($result->fetch_assoc()));
  //echo $json;
}

mysqli_close($connect);
?>
</body>
</html>
