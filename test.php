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

/*$pos1 = new HotSpot(2,3);
$pos2 = new HotSpot(5,4);
$pos3 = new HotSpot(9,6);
$pos4 = new HotSpot(4,7);
$pos5 = new HotSpot(8,1);
$pos6 = new HotSpot(7,2);*/

//Cory Hall:
$pos1 = new HotSpot(37.875473, -122.257271, false);
$pos1->setName("Cory Hall");

//Ihouse:
$pos2 = new HotSpot(37.869525, -122.252152, false);
$pos2->setName("Ihouse");

//Shattuck:
$pos3 = new HotSpot(37.867340, -122.267773, false);
$pos3->setName("Shattuck");

//Downtown:
$pos4 = new HotSpot(37.870335, -122.267792, false);
$pos4->setName("Downtown");

//??:
$pos5 = new HotSpot(37.808390, -122.409854, false);
$pos5->setName("?? SF ??");

//Beverly Hills:
$pos6 = new HotSpot(34.076506, -118.399534, false);
$pos6->setName("Beverly Hills");

//Forever 21:
$pos7 = new HotSpot(37.784957, -122.407769, false);
$pos7->setName("Forever 21");
$pos7->setMessage("Shop now at Forever 21!");
$pos7->setType(HotSpot::HOTSPOT_TYPE_AD);

//Starbucks:
$pos8 = new HotSpot(37.786527, -122.408205, false);
$pos8->setName("Starbucks");
$pos8->setMessage("Start your day with a special cup of coffee from Starbucks!");
$pos8->setType(HotSpot::HOTSPOT_TYPE_AD);

//Victoria's Secret:
$pos9 = new HotSpot(37.787686, -122.408439, false);
$pos9->setName("Victoria's Secret");
$pos9->setMessage("Get the finest perfumes from Victoria's Secret!");
$pos9->setType(HotSpot::HOTSPOT_TYPE_AD);

//$posArray = array($pos1, $pos2, $pos3, $pos4, $pos5, $pos6);
$posArray = array($pos7, $pos8, $pos9);

$kdtree = kdtree($posArray, 0);

$s = serialize($kdtree);


$nearestNeighbor = nearestNeighborSearch($queryPoint, $kdtree);
$distanceToNn = pow(pow($nearestNeighbor->getHotSpot()->getX() - $queryPoint->getX(), 2) + pow($nearestNeighbor->getHotSpot()->getY() - $queryPoint->getY(), 2), 0.5);

//Begin JSON response:
echo "{";

if ($distanceToNn < 0.1) {
	echo '"nearest_hotspot" : "' . $nearestNeighbor->getHotSpot()->getName() . '",';
	echo "\"lat\" : \"" . $nearestNeighbor->getHotSpot()->getLat() . "\",";
	echo "\"lng\" : \"" . $nearestNeighbor->getHotSpot()->getLong() . "\",";
	echo "\"hotspot_radius\" : \"" . 100 . "\",";
	echo '"message" : "' . $nearestNeighbor->getHotSpot()->getMessage() . '",';
	echo "\"status\" : \"true\",";

	//Rerouting:
	echo '"reroute":"true",';
	echo '"lats":"[37.778413, 37.785435, 37.786791]",';
	echo '"lngs":"[-122.415149, -122.416222, -122.404764]",';
	
	//Testing purposes:
	echo "\"server_distance\" : \"" . $distanceToNn . "\"";
}

else {
	echo '"nearest_hotspot" : "' . $nearestNeighbor->getHotSpot()->getName() . '",';
	echo "\"lat\" : \"0.0\",";
	echo "\"lng\" : \"0.0\",";
	echo "\"hotspot_radius\" : \"0.0\",";
	echo "\"status\" : \"false\",";
	
	//Rerouting:
	echo '"reroute":"false",';
	echo '"lats":"[]",';
	echo '"lngs":"[]",';
	
	//Testing purposes:
	echo "\"server_distance\" : \"" . $distanceToNn . "\"";
}

//End JSON response:
echo "}";

?>