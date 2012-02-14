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
    private $word;
    private $file;
    private $dbConnection;
    private $wordnik;

    public function __construct($connection, $gWordnik) {
        $this->dbConnection = $connection;
        $this->wordnik = $gWordnik;
    }
    
    private function parseArray($passArray) {
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
                    
                    $final = str_ireplace("'", "", $final);
                    
                    //pass to sentence and make sure you parse out non-letters
                    //echo $final."<br/>";
                }
                
            }
        }
        
        return $retArray;
    }
    
    public function parseDoc($filename) {
        if(file_exists($filename)) {
            $file = fopen($filename, "r");
            
            while(!feof($file)) {
                $new_line = fgets($file);
                $new_line = str_ireplace("...", "-", $new_line);
                
                $this_line .= $new_line;
                //echo $this_line." || <br/>";
            }
            
            fclose($file);
            
            $parsed_file = explode(".", $this_line);
            $this->parseArray($parsed_file);
            //print_r($parsed_file);
        }
    }
}

?>
