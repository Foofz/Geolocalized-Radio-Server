<?php

class TreeNode {
	protected $hotSpot;
	protected $leftChild;
	protected $rightChild;
	protected $axis;
	protected $visited = false;

	public function getHotSpot(){
		return $this->hotSpot;
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

	public function setHotSpot($hotSpot){
		$this->hotSpot = $hotSpot;
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

?>