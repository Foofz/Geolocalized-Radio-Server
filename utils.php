<?php

function compareX($position1, $position2){
	if ($position1->getX() == $position2->getX()){
		return 0;
	}
	else if ($position1->getX() > $position2->getX()){
		return 1;
	}
	else {
		return -1;
	}
}

function compareY($position1, $position2){
	if ($position1->getY() == $position2->getY()){
		return 0;
	}
	else if ($position1->getY() > $position2->getY()){
		return 1;
	}
	else {
		return -1;
	}
}

function array_subset($array, $startIndex, $endIndex){
	if ($startIndex < 0 || $endIndex < $startIndex || $endIndex >= count($array)){
		return $array;
	}
	else {
		return array_splice($array, $startIndex, $endIndex - $startIndex + 1);
	}
}

?>