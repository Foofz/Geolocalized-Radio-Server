<?php

$earthRadius = 6371.0;

class Position {
	protected $x;
	protected $y;
	protected $lat;
	protected $long;
	public $name;
	//protected $z;

	function __construct() {
        $a = func_get_args(); 
        $i = func_num_args(); 
        if (method_exists($this,$f='__construct'.$i)) { 
            call_user_func_array(array($this,$f),$a); 
        }
        else {
        	__construct0();
        } 
    }

    function __construct0(){
        $this->x = 0;
        $this->y = 0;

        $this->lat = 0;
        $this->long = 0;
    }

    function __construct2($x,$y) 
    { 
        $this->x = $x;
        $this->y = $y;
    } 
    
    function __construct3($a1,$a2,$isCartesian) 
    { 
        if ($isCartesian){
        	$this->x = $a1;
        	$this->y = $a2;
        }

        else {
        	$this->x = cos(deg2rad($a1)) * cos(deg2rad($a2)) * $GLOBALS['earthRadius'];
        	$this->y = cos(deg2rad($a1)) * sin(deg2rad($a2)) * $GLOBALS['earthRadius'];
        	$this->lat = $a1;
        	$this->long = $a2;
        }
    }

	public function getX () {
		return $this->x;
	}

	public function getY () {
		return $this->y;
	}

	public function getLat () {
		return $this->lat;
	}

	public function getLong () {
		return $this->long;
	}

	/*public function getZ () {
		return $this->z;
	}*/

	public function setX ($x) {
		$this->x = $x;
	}

	public function setY ($y) {
		$this->y = $y;
	}

	/*public function setZ ($z) {
		$this->z = $z;
	}*/

}

class TreeNode {
	protected $position;
	protected $leftChild;
	protected $rightChild;
	protected $axis;
	protected $visited = false;

	public function getPosition(){
		return $this->position;
	}

	public function getLeftChild(){
		return $this->leftChild;
	}

	public function getRightChild(){
		return $this->rightChild;
	}

	public function getAxis(){
		return $this->axis;
	}

	public function isVisited(){
		return $this->visited;
	}

	public function setPosition($position){
		$this->position = $position;
	}

	public function setLeftChild($leftChild){
		$this->leftChild = $leftChild;
	}

	public function setRightChild($rightChild){
		$this->rightChild = $rightChild;
	}

	public function setAxis($axis){
		$this->axis = $axis;
	}

	public function setVisited($visited){
		$this->visited = $visited;
	}
}

function kdtree ($posArray, $depth) {

	// Select axis based on depth so that axis cycles through all valid values
	$axis = $depth%2;

	if (count($posArray) == 1){
		$node = new TreeNode;
		$node->setPosition($posArray[0]);
		$node ->setLeftChild(NULL);
		$node ->setRightChild(NULL);
		$node ->setAxis($axis);
		return $node;
	}
	
	// Sort point list and choose median as pivot element
	if ($axis == 0) {
		usort($posArray, "compareX");
	}

	else {
		usort($posArray, "compareY");
	}

	$medianIndex = (count($posArray)%2==0?count($posArray)/2 - 1:(count($posArray)-1)/2);

	$median = $posArray[$medianIndex];

	//var_dump($median);

	// Create node and construct subtrees
	$node = new TreeNode;
	$node->setPosition($median);
	$node ->setAxis($axis);

	//var_dump($node);
	if ($medianIndex == 0){
		$node->setLeftChild(NULL);
	}
	else {
		$node->setLeftChild(kdtree(array_subset($posArray, 0, $medianIndex - 1), $depth+1));
	}
	$node->setRightChild(kdtree(array_subset($posArray, $medianIndex + 1, count($posArray) - 1), $depth+1));
	return $node;

}

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

function nearestNeighborSearch($queryPoint, $treeNode){

	/*echo var_dump($treeNode);
	echo "<br/><br/>";*/

	//Leaf node:
	if (is_null($treeNode->getLeftChild()) && is_null($treeNode->getRightChild())){
		$treeNode->setVisited(true);
		return $treeNode;
	}

	//Non-leaf node:
	if (!$treeNode->isVisited()){
		//Check whether to recurse on left subtree or right
		$queryPointAxisValue = ($treeNode->getAxis()==0?$queryPoint->getX():$queryPoint->getY());
		$nodeAxisValue = ($treeNode->getAxis()==0?$treeNode->getPosition()->getX():$treeNode->getPosition()->getY());

		//Search left subtree
		if ($queryPointAxisValue < $nodeAxisValue){
				if (!is_null($treeNode->getLeftChild())){
					$nearestNeighbor = nearestNeighborSearch($queryPoint, $treeNode->getLeftChild());
					$checkNode = true;
				}
				else {
					$nearestNeighbor = $treeNode;
					$nnDistanceSquared = pow(($queryPoint->getX() - $treeNode->getPosition()->getX()),2) + pow(($queryPoint->getY() - $treeNode->getPosition()->getY()),2);
					$checkNode = false;
				}
		}
		//Search right subtree
		else{
				if (!is_null($treeNode->getRightChild())){
					$nearestNeighbor = nearestNeighborSearch($queryPoint, $treeNode->getRightChild());
					$checkNode = true;
				}
				else {
					$nearestNeighbor = $treeNode;
					$nnDistanceSquared = pow(($queryPoint->getX() - $treeNode->getPosition()->getX()),2) + pow(($queryPoint->getY() - $treeNode->getPosition()->getY()),2);
					$checkNode = false;
				}
		}

		if ($checkNode){
			//Compute the squared distance
			$nnDistanceSquared = pow(($queryPoint->getX() - $nearestNeighbor->getPosition()->getX()),2) + pow(($queryPoint->getY() - $nearestNeighbor->getPosition()->getY()),2);

			//Check if the current node is close
			$nodeDistanceSquared = pow(($queryPoint->getX() - $treeNode->getPosition()->getX()),2) + pow(($queryPoint->getY() - $treeNode->getPosition()->getY()),2);

			if ($nodeDistanceSquared < $nnDistanceSquared){
				$nearestNeighbor = $treeNode;
				$nnDistanceSquared = $nodeDistanceSquared;
			}
		}

		//Check whether need to check unvisited subtree
		$distanceToPlaneSquared = ($treeNode->getAxis()==0?pow($queryPoint->getX() - $treeNode->getPosition()->getX(),2):pow($queryPoint->getY() - $treeNode->getPosition()->getY(),2));
		if ($distanceToPlaneSquared < $nnDistanceSquared){
			//Visit left subtree
			if (!is_null($treeNode->getLeftChild()) && !$treeNode->getLeftChild()->isVisited()){
				$potentialNn = nearestNeighborSearch($queryPoint, $treeNode->getLeftChild());
			}
			else if(!is_null($treeNode->getRightChild())) {
				$potentialNn = nearestNeighborSearch($queryPoint, $treeNode->getRightChild());
			}

			//Check whether potential nn is indeed an nn
			$distanceToPotNnSquared = pow(($queryPoint->getX() - $potentialNn->getPosition()->getX()),2) + pow(($queryPoint->getY() - $potentialNn->getPosition()->getY()),2);

			if ($distanceToPotNnSquared < $nnDistanceSquared){
				$nearestNeighbor = $potentialNn;
			}
		}

		//Set this node to visited
		$treeNode->setVisited(true);

		//Return the nearest neighbor
		return $nearestNeighbor;

	}
}

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

$posArray = array($pos1, $pos2, $pos3, $pos4, $pos5, $pos6);

$kdtree = kdtree($posArray, 0);

$s = serialize($kdtree);


$nearestNeighbor = nearestNeighborSearch($queryPoint, $kdtree);
$distanceToNn = pow(pow($nearestNeighbor->getPosition()->getX() - $queryPoint->getX(), 2) + pow($nearestNeighbor->getPosition()->getY() - $queryPoint->getY(), 2), 0.5);

//Begin JSON response:
echo "{";

if ($distanceToNn < 0.1) {
	echo "\"lat\" : \"" . $nearestNeighbor->getPosition()->getLat() . "\",";
	echo "\"lng\" : \"" . $nearestNeighbor->getPosition()->getLong() . "\",";
	echo "\"hotspot_radius\" : \"" . 100 . "\",";
	echo "\"instruction_url\" : \"" . $nearestNeighbor->getPosition()->name . "Warning. Accident up ahead.\",";
	echo "\"status\" : \"true\",";

	//Rerouting:
	echo '"reroute":"true",';
	echo '"lats":"[37.778413, 37.785435, 37.786791]",';
	echo '"lngs":"[-122.415149, -122.416222, -122.404764]",';
	
	//Testing purposes:
	echo "\"server_distance\" : \"" . $distanceToNn . "\"";
}

else {
	echo "\"lat\" : \"0.0\",";
	echo "\"lng\" : \"0.0\",";
	echo "\"hotspot_radius\" : \"0.0\",";
	echo "\"instruction_url\" : \"" . $nearestNeighbor->getPosition()->name . "\",";
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