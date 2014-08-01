<?php

//Validate input:
if (!isset($_GET["name"])){
	echo "Failed at name";
	die();
}

else if (!isset($_GET["type"]) || !preg_match("/^[0-1]$/", $_GET["type"])){
	echo "Failed at type";
	die();
}

else if (!isset($_GET["message"])){
	echo "Failed at message";
	die();
}

else if (!isset($_GET["lat"]) || !preg_match("/^(\+|-)?[0-9]+(.[0-9]+)?$/", $_GET["lat"])){
	echo "Failed at lat";
	die();
}

else if(!isset($_GET["long"]) || !preg_match("/^(\+|-)?[0-9]+(.[0-9]+)?$/", $_GET["long"])){
	echo "Failed at long";
	die();
}

//All OK:
echo "All OK!";

$name = $_GET["name"];
$type = $_GET["type"];
$message = $_GET["message"];
$lat = $_GET["lat"];
$long = $_GET["long"];

?>