<?php

/**
 * PHP class wordnikClass
 * wordnikClass.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */

require_once 'wordnik/api/APIClient.php';

class wordnikClass {
    
    private $apiKey; /**< the api key from the defaults.ini */
    private $wordnik; /**< the wordnik.php class */
    
    public function __construct($settings) {
        /**
         * instantiate the wordnik object
         * @param $settings array of settings from default.php 
         */
        $this->apiKey = $settings['apiKey'];
        $this->wordnik = new APIClient($this->apiKey, 'http://api.wordnik.com/v4');
    }
    
    public function returnDefinition($word) {
        /**
         * return a parsed definition object from wordnik
         * @param $word string form of word
         * @return array wordnik definition 
         */
        
        $wordAPI = new WordAPI($this->wordnik);
        $input = new WordDefinitionsInput();
        
        $input->word = $word;
        $input->limit = 1;
        $retVal = $wordAPI->getDefinitions($input);

        return $retVal;
    }
}

?>
