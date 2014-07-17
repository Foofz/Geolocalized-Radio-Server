<?php

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

?>