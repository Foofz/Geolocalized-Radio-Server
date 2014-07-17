<?php

class HotSpot extends Position {
	const HOTSPOT_TYPE_TRAFFIC = 0;
	const HOTSPOT_TYPE_AD = 1;

	protected $name;
	protected $message;
	protected $type;

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
        parent::__construct0();

        $this->name = "";
        $this->message = "";
        $this->type = -1;
    }

    function __construct2($x, $y){
    	parent::__construct2($x, $y);

    	$this->name = "";
        $this->message = "";
        $this->type = -1;
    }

    function __construct3($a1, $a2, $isCartesian){ 
        parent::__construct3($a1, $a2, $isCartesian);

    	$this->name = "";
        $this->message = "";
        $this->type = -1;
    }

    function __construct5($x, $y, $name, $message, $type){
        parent::__construct2($x, $y);

        $this->name = $name;
        $this->message = $message;
        $this->type = $type;
    }

    function __construct6($a1, $a2, $isCartesian, $name, $message, $type){
        parent::__construct3($a1, $a2, $isCartesian);

        $this->name = $name;
        $this->message = $message;
        $this->type = $type;
    }

    function getName(){
        return $this->name;
    }

    function getMessage(){
        return $this->message;
    }

    function getType(){
        return $this->type;
    }

    function setName($name){
        $this->name = $name;
    }

    function setMessage($message){
        $this->message = $message;
    }

    function setType($type){
        $this->type = $type;
    }
}

?>