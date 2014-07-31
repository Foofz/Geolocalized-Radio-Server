<?php

include "position.php";
include "hotspot.php";

//Connect to the database:
$db = new PDO("mysql:dbname=hotspots;host=localhost","root","");

$rows = $db->query("SELECT * FROM hotspots");

//echo $rows->rowCount();

$hotSpots = [];
$i = 0;

while ($result = $rows->fetch()){
	$hotSpots[$i] = new HotSpot($result["lat"], $result["long"], false);
	$hotSpots[$i]->setName($result["name"]);
	$hotSpots[$i]->setMessage($result["message"]);

	if ($result["type"] == 0){
		$hotSpots[$i]->setType(HotSpot::HOTSPOT_TYPE_TRAFFIC);
	}

	else {
		$hotSpots[$i]->setType(HotSpot::HOTSPOT_TYPE_AD);
	}

	$i++;
}

?>