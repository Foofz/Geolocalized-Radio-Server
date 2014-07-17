<?php

require("utils.php");
require("position.php");
require("treenode.php");
require("kdtree_utils.php");

if (isset($_GET["lat"]) && isset($_GET["long"])){
	$lat = $_GET["lat"];
	$long = $_GET["long"];
	$queryPoint = new Position($lat, $long, false);
}

else {
	$queryPoint = new Position(1, 2);
}

/*$pos1 = new Position(2,3);
$pos2 = new Position(5,4);
$pos3 = new Position(9,6);
$pos4 = new Position(4,7);
$pos5 = new Position(8,1);
$pos6 = new Position(7,2);*/

//Cory Hall:
$pos1 = new Position(37.875473, -122.257271, false);
$pos1->name = "Cory Hall";

//Ihouse:
$pos2 = new Position(37.869525, -122.252152, false);
$pos2->name = "Ihouse";

//Shattuck:
$pos3 = new Position(37.867340, -122.267773, false);
$pos3->name = "Shattuck";

//Downtown:
$pos4 = new Position(37.870335, -122.267792, false);
$pos4->name = "Downtown";

//??:
$pos5 = new Position(37.808390, -122.409854, false);
$pos5->name = "?? SF ??";

//Beverly Hills:
$pos6 = new Position(34.076506, -118.399534, false);
$pos6->name = "Beverly Hills";

//Forever 21:
$pos7 = new Position(37.784957, -122.407769, false);
$pos7->name = "Forever 21";
$pos7->message = "Shop now at Forever 21!";

//Starbucks:
$pos8 = new Position(37.786527, -122.408205, false);
$pos8->name = "Starbucks";
$pos8->message = "Start your day with a special cup of coffee from Starbucks!";

//Victoria's Secret:
$pos9 = new Position(37.787686, -122.408439, false);
$pos9->name = "Victoria's Secret";
$pos9->message = "Get the finest perfumes from Victoria's Secret!";

//$posArray = array($pos1, $pos2, $pos3, $pos4, $pos5, $pos6);
$posArray = array($pos7, $pos8, $pos9);

$kdtree = kdtree($posArray, 0);

$s = serialize($kdtree);


$nearestNeighbor = nearestNeighborSearch($queryPoint, $kdtree);
$distanceToNn = pow(pow($nearestNeighbor->getPosition()->getX() - $queryPoint->getX(), 2) + pow($nearestNeighbor->getPosition()->getY() - $queryPoint->getY(), 2), 0.5);

//Begin JSON response:
echo "{";

if ($distanceToNn < 0.1) {
	echo '"nearest_hotspot" : "' . $nearestNeighbor->getPosition()->name . '",';
	echo "\"lat\" : \"" . $nearestNeighbor->getPosition()->getLat() . "\",";
	echo "\"lng\" : \"" . $nearestNeighbor->getPosition()->getLong() . "\",";
	echo "\"hotspot_radius\" : \"" . 100 . "\",";
	echo '"message" : "' . $nearestNeighbor->getPosition()->message . '",';
	echo "\"status\" : \"true\",";

	//Rerouting:
	echo '"reroute":"true",';
	echo '"lats":"[37.778413, 37.785435, 37.786791]",';
	echo '"lngs":"[-122.415149, -122.416222, -122.404764]",';
	
	//Testing purposes:
	echo "\"server_distance\" : \"" . $distanceToNn . "\"";
}

else {
	echo '"nearest_hotspot" : "' . $nearestNeighbor->getPosition()->name . '",';
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