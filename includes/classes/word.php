<?php

/**
 * PHP class word
 * word.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */

require_once 'rest.php';

class word {
    public $word;
    public $definition;
    public $speechPart;
    
    private $id;
    private $dbConnection;
    private $wordnik;

    //predefined values for the database//
    private $tableName  = "word";
    private $allRows    = "*";

    public function __construct($connection, $wordnik) {
        $this->dbConnection = $connection;
        $this->wordnik = $wordnik;
    }
    
    private function loadWordFromDBRows($retVal) {
        //set up a new word from a query
        $this->id = retVal($retVal, 'id');
        $this->word = retVal($retVal, 'word');
        $this->definition = unserialize(retVal($retVal, 'definition'));
        $this->speechPart = retVal($retVal, 'speechPart');
    }
    
    private function loadWordFromId($id) {
        //load a word from an id, useful for passed queries on the website
        $id = cleanInput($id);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "id = '".$id."'");
        
        $this->loadWordFromDBRows($retVal);
    }
    
    private function loadWordFromWord($word) {
        //load a word from a word
        $thisWord = cleanInput($word);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "word = '".$word."'");
        
        $this->loadWordFromDBRows($retVal);
    }

    private function addWord() {
        //adds a word to the database
        
        //clean the query
        $word = $this->word;
        $definition = serialize($this->definition);
        $speechPart = $this->speechPart;
        
        //add the word
        $this->dbConnection->insertQuery(
            $this->tableName,
            "word, definition, speechPart",
            "'".$word."','".$definition."','".$speechPart."'");
    }
    
    public function returnWord($word) {
        //check a word against the database, and either return
        //the database query or the wordnik definition
        $this->loadWordFromWord($word);
        
        if ($this->id > 0) {
            //the word exists in the database, return the word
            
        } else {
            //the word does not exist, query wordnik and then add it to the database
            $retVal = $this->wordnik->returnDefinition($word);
            
            if (isset($retVal[0])) {
                //the word exists in wordnik
                $this->word = $word;
                $this->speechPart = $retVal[0]->partOfSpeech;
                $this->definition = cleanInput(implode(",", get_object_vars($retVal[0])));
                
                $this->addWord();
            } else {
                //the word is a proper noun
                $this->word = $word;
                $this->speechPart = "proper noun";
                $this->definition = '';
                
                $this->addWord();
            }
        }
        
        return $this;
        
    }
}

?>
