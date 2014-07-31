<?php

//Connect to the database:
$db = new PDO("mysql:dbname=hotspots;host=localhost","root","");

$rows = $db->query("SELECT * FROM hotspots");

//Begin JSON response:
echo '{ "hotspots": [';

$firstEntry = true;

//Loop over results:
while ($result = $rows->fetch()){

	if ($firstEntry){
		$firstEntry = false;
	}

	else {
		echo ',';
	}

	echo '{';

	echo '"id": ' . $result["id"] . ',';
	echo '"name": "' . $result["name"] . '",';
	echo '"type": ' . $result["type"] . ',';
	echo '"message": "' . $result["message"] . '",';
	echo '"lat": ' . $result["lat"] . ',';
	echo '"long": ' . $result["long"];

	echo '}';
	
}

//End JSON response:
echo "]}";

?>