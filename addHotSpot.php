<?php

//Validate input:
if (!isset($_GET["name"])){
	die();
}

else if (!isset($_GET["type"]) || !preg_match("/^[0-1]$/", $_GET["type"])){
	die();
}

else if (!isset($_GET["message"])){
	die();
}

else if (!isset($_GET["lat"]) || !preg_match("/^(\+|-)?[0-9]+(.[0-9]+)?$/", $_GET["lat"])){
	die();
}

else if(!isset($_GET["long"]) || !preg_match("/^(\+|-)?[0-9]+(.[0-9]+)?$/", $_GET["long"])){
	die();
}

//All OK:
//echo "All OK!";

$name = $_GET["name"];
$type = (int) $_GET["type"];
$message = $_GET["message"];
$lat = (double) $_GET["lat"];
$long = (double) $_GET["long"];

//Connect to the database:
//$db = new PDO("mysql:dbname=hotspots;host=localhost","root","");
$db = mysqli_connect('localhost', 'root', '', 'hotspots');

//Make the query:
$q = "INSERT INTO hotspots (name, type, message, lat, lng) VALUES (?, ?, ?, ?, ?)";

//Prepare the statement:
$stmt = mysqli_prepare($db, $q);

//Bind the variables:
mysqli_stmt_bind_param($stmt, 'sisdd', $name, $type, $message, $lat, $long);

//Execute the query:
mysqli_stmt_execute($stmt);

//Reconstruct the kd-tree:
require("constructKdTree.php");

?>