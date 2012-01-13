<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * PHP class wordnikClass
 * wordnikClass.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */

require_once 'Wordnik.php';

class wordnikClass {
    
    private $apiKey;
    private $wordnik;
    
    public function __construct($settings) {
        $this->apiKey = $settings['apiKey'];
        $this->wordnik = Wordnik::instance($this->apiKey);
    }
    
    public function returnDefinition($word) {
        $retVal = $this->wordnik->getDefinitions($word);
        
        return $retVal;
    }
}

?>
