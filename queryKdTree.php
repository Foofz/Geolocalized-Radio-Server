<?php

require("utils.php");
require("position.php");
require("treenode.php");
require("kdtree_utils.php");
require("hotspot.php");


if (isset($_GET["lat"]) && isset($_GET["long"])){
	$lat = $_GET["lat"];
	$long = $_GET["long"];
	$queryPoint = new HotSpot($lat, $long, false);
}

else {
	$queryPoint = new HotSpot(1, 2);
}

$kdtree = unserialize(file_get_contents("kdtree"));

$nearestNeighbor = nearestNeighborSearch($queryPoint, $kdtree);
$distanceToNn = pow(pow($nearestNeighbor->getHotSpot()->getX() - $queryPoint->getX(), 2) + pow($nearestNeighbor->getHotSpot()->getY() - $queryPoint->getY(), 2), 0.5);

//Begin JSON response:
echo "{";

if ($distanceToNn < 0.1) {
	echo '"nearest_hotspot" : "' . $nearestNeighbor->getHotSpot()->getName() . '",';
	echo "\"lat\" : " . $nearestNeighbor->getHotSpot()->getLat() . ",";
	echo "\"lng\" : " . $nearestNeighbor->getHotSpot()->getLong() . ",";
	echo "\"hotspot_radius\" : " . 100 . ",";
	echo '"message" : "' . $nearestNeighbor->getHotSpot()->getMessage() . '",';
	if ($nearestNeighbor->getHotSpot()->getType() == HotSpot::HOTSPOT_TYPE_TRAFFIC){
		echo "\"status\" : \"hotspot\",";
	}

	else {
		echo "\"status\" : \"ads\",";
	}
	
	//Rerouting:
	echo '"reroute": "true",';
	echo '"lats": [' . implode(",", $nearestNeighbor->getHotSpot()->getRerouteLats()) . '],';
	echo '"lngs": [' . implode(",", $nearestNeighbor->getHotSpot()->getRerouteLngs()) . '],';
	
	//Testing purposes:
	echo "\"server_distance\" : " . $distanceToNn;
}

else {
	echo '"nearest_hotspot" : "' . $nearestNeighbor->getHotSpot()->getName() . '",';
	echo "\"lat\" : 0.0,";
	echo "\"lng\" : 0.0,";
	echo "\"hotspot_radius\" : 0.0,";
	echo "\"status\" : \"false\",";
	
	//Rerouting:
	echo '"reroute": "false",';
	echo '"lats": [],';
	echo '"lngs": [],';
	
	//Testing purposes:
	echo "\"server_distance\" : " . $distanceToNn;
}

//End JSON response:
echo "}";

?>