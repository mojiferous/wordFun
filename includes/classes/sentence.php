<?php

/**
 * PHP class word
 * word.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */

require_once 'rest.php';
require_once 'word.php';

class sentence {
    public $raw_sentence;
    public $with_proper;
    public $all_parts;
    
    private $id;
    private $dbConnection;
    private $wordnik;

    //predefined values for the database//
    private $tableName  = "sentence";
    private $allRows    = "*";

    public function __construct($connection, $wordnik) {
        $this->dbConnection = $connection;
        $this->wordnik = $wordnik;
    }
    
    private function loadSentenceFromDBRows($retVal) {
        //set up a new sentence from a query
        $this->id = retVal($retVal, 'id');
        $this->raw_sentence = retVal($retVal, 'raw_sentence');
        $this->with_proper = retVal($retVal, 'with_proper');
        $this->all_parts = retVal($retVal, 'all_parts');
    }
    
    private function loadSentenceFromId($id) {
        //load a sentence from an id, useful for passed values
        $id = $this->dbConnection->cleanInput($id);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "id = '".$id."'");
        
        $this->loadSentenceFromDBRows($retVal);
    }
    
    private function loadSentenceFromSentence($sentence) {
        //load a sentence from a extant sentence
        $thisSentence = $this->dbConnection->cleanInput($sentence);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "raw_sentence = '".$sentence."'");
        
        $this->loadSentenceFromDBRows($retVal);
    }
    
    private function addSentence() {
        //adds a sentence to the database
        
        $rsentence = $this->raw_sentence;
        $psentence = $this->with_proper;
        $asentence = $this->all_parts;
        
        //add the sentence
        $this->dbConnection->insertQuery(
            $this->tableName,
            "raw_sentence, with_proper, all_parts",
            "'".$rsentence."','".$psentence."','".$asentence."'");
    }
    
    private function cleanWord($word, $retType = 0) {
        $newWord = str_ireplace('"', '', $word);
        $newWord = str_ireplace("'", '', $word);
        $newWord = str_ireplace(',', '', $word);
        $newWord = str_ireplace('(', '', $word);
        $newWord = str_ireplace(')', '', $word);
        
        if ($retType == 0) {
            //return only the newWord
            return $newWord;
        } else {
            return str_ireplace($newWord, '{'.$newWord.'}', $word);
        }
    }
    
    public function returnSentence($sentence) {
        //check a sentence against the database, and either return
        //the database query or the wordnik definition
        $this->loadSentenceFromSentence($sentence);
        
        if ($this->id > 0) {
            //the sentence exists in the database, return it
        } else {
            //the sentence does not exist, chop it up and make it an array
            $raw = $sentence;
            $withProp = $sentence;
            $allParts = $sentence;
            
            $sentArray = explode(" ", $sentence);
            
            foreach ($sentArray as $indWord) {
                $searchWord = $this->cleanWord($indWord);
                $replWord = $this->cleanWord($indWord,1);
                
                $thisWord = new word($this->dbConnection, $this->wordnik);
                $thisWord = $thisWord->returnWord($searchWord);
                
                $finalWord = str_ireplace("{".$indWord."}", "{".$thisWord->speechPart."}", $replWord);
                if ($thisWord->speechPart != 'proper noun') {
                    //the word is not a proper noun, replace it in $withProp
                    $withProp = str_ireplace($indWord, $finalWord, $withProp);
                }
                $allParts = str_ireplace($indWord, $finalWord, $allParts);                
            }
            
            $this->raw_sentence = $raw;
            $this->with_proper = $withProp;
            $this->all_parts = $allParts;
            
            $this->addSentence();
        }
        
        return $this->raw_sentence;
    }
    
}

?>
