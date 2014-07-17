<?php

class HotSpot extends Position {
	static const $HOTSPOT_TYPE_TRAFFIC = 0;
	static const $HOTSPOT_TYPE_AD = 1;

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

    function __construct3($a1, $a2, $isCartesian) { 
        parent::__construct3($a1, $a2, $isCartesian);

    	$this->name = "";
        $this->message = "";
        $this->type = -1;
    }
}

?>