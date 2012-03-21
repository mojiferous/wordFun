<?php

/**
 * PHP class defaults
 * defaults.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */
class defaults {
    public $settings; /**< the instantiated settings */
    
    public function __construct() {
        /**
         * instantiate the defaults object 
         */
        $this->settings = $this->readDefaults();
    }
    
    private function readDefaults(){
        /**
         * loads the defaults.ini file, called from __construct()
         * @return array of ini file 
         */
        $newSets = parse_ini_file("defaults.ini",true);        
        return $newSets;
    }
}

?>