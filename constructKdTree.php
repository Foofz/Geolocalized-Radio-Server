<?php

require("utils.php");
require("position.php");
require("treenode.php");
require("kdtree_utils.php");
require("hotspot.php");

//Connect to the database:
$db = new PDO("mysql:dbname=hotspots;host=localhost","root","");

$rows = $db->query("SELECT * FROM hotspots");

//echo $rows->rowCount();

$hotSpots = [];
$i = 0;

while ($result = $rows->fetch()){
	$hotSpots[$i] = new HotSpot($result["lat"], $result["lng"], false);
	$hotSpots[$i]->setName($result["name"]);
	$hotSpots[$i]->setMessage($result["message"]);

	if ($result["type"] == 0){
		$hotSpots[$i]->setType(HotSpot::HOTSPOT_TYPE_TRAFFIC);
	}

	else {
		$hotSpots[$i]->setType(HotSpot::HOTSPOT_TYPE_AD);
	}

	$hotSpots[$i]->setRerouteLats(explode(",", $result["rerouteLats"]));
	$hotSpots[$i]->setRerouteLngs(explode(",", $result["rerouteLngs"]));

	$i++;
}

$kdtree = kdtree($hotSpots, 0);

$s = serialize($kdtree);

file_put_contents("kdtree", $s);

?>