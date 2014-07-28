<?php

class Position {
    protected $x;
    protected $y;
    protected $lat;
    protected $long;
    //protected $z;

    const EARTH_RADIUS = 6371.0;

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

        $this->long = rad2deg(atan2($y, $x));

        /****** NOTE! *******

        Due to insufficient info, namely the sine of the lat, the below formula
        always returns a positive lat, whereas in fact it could be negative. However,
        since we always just use the cosine of the lat, it doesn't matter for our purposes.

        */
        $this->lat = rad2deg(acos($x/(cos(deg2rad($this->long))*Position::EARTH_RADIUS)));
    } 
    
    function __construct3($a1,$a2,$isCartesian) 
    { 
        if ($isCartesian){
            $this->x = $a1;
            $this->y = $a2;

            $this->long = rad2deg(atan2($y, $x));

            /****** NOTE! *******

            Due to insufficient info, namely the sine of the lat, the below formula
            always returns a positive lat, whereas in fact it could be negative. However,
            since we always just use the cosine of the lat, it doesn't matter for our purposes.

            */
            $this->lat = rad2deg($x/(cos(deg2rad($this->long))*Position::EARTH_RADIUS));
        }

        else {
            $this->x = cos(deg2rad($a1)) * cos(deg2rad($a2)) * Position::EARTH_RADIUS;
            $this->y = cos(deg2rad($a1)) * sin(deg2rad($a2)) * Position::EARTH_RADIUS;

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

?>