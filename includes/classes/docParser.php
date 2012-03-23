<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * PHP class docParser
 * docParser.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */

require_once 'rest.php';
require_once 'sentence.php';

class docParser {
    private $file; /**< the file passed to the docParser */
    private $dbConnection; /**< the database connection */
    private $wordnik; /**< the wordnik class */

    public function __construct($connection, $gWordnik) {
        /**
         * instantiate a new doc parser
         * @param $connection dbConnection
         * @param $gWordnik wordnik class 
         */
        $this->dbConnection = $connection;
        $this->wordnik = $gWordnik;
    }
    
    private function parseArray($passArray) {
        /**
         * parse an array passed from parseDoc, the array is the document
         * chopped up into different sentences
         * @param $passArray array of sentences
         */
        $retArray = array();
        foreach ($passArray as $line) {
            $q_expl = explode("?", $line);
            if(count($q_expl) > 1) {
                
                for($n=0; $n<(count($q_expl)-1); $n++) {
                    $q_expl[$n] = $q_expl[$n]."?";
                }
            }
            
            foreach ($q_expl as $qs) {
                $b_expl = explode("!", $qs);
                if(count($b_expl) > 1){
                    
                    for($n=0; $n<(count($b_expl)-1); $n++) {
                        $b_expl[$n] = $b_expl[$n]."!";
                    }
                }
                
                foreach ($b_expl as $final) {
                    $lastChar = substr($final, -1,1);
                    if($lastChar != '?' && $lastChar != '!') {
                        $final .= ".";
                    }
                    
                    $newSent = new sentence($this->dbConnection, $this->wordnik);
                    
                    $final = str_ireplace(chr(10), " ", $final);
                    $final = str_ireplace(chr(13), " ", $final);
                    $final = str_ireplace("-", " ", $final);
                    $final = str_ireplace("  ", " ", $final);
                    $final = str_ireplace("  ", " ", $final);
                    $retArray[] = $newSent->returnSentence($final);

                }
            }
        }
        
        return $retArray;
    }
    
    public function parseDoc($filename) {
        /**
         * parse the passed document into lines separated by a period
         * @param $filename string filename 
         * @return array of sentences parsed by the sentence parser
         */
        $retVal = array();
        if(file_exists($filename)) {
            $file = fopen($filename, "r");
            
            $this->file = $file;
            
            while(!feof($file)) {
                $new_line = fgets($file);
                $new_line = str_ireplace("...", "-", $new_line);
                
                $this_line .= $new_line;
            }
            
            fclose($file);
            
            $parsed_file = explode(".", $this_line);
            $retVal[] = $this->parseArray($parsed_file);
        }
        
        return $retVal;
    }
}

?>
