<?php

/**
 * PHP class defaults
 * defaults.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */
class defaults {
    public $settings;
    
    public function __construct() {
        $this->settings = $this->readDefaults();
    }
    
    function readDefaults(){
        $newSets = parse_ini_file("defaults.ini",true);        
        return $newSets;
    }
}

?>