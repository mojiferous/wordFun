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
    public $word; /**< the individual word */
    public $definition; /**< the definition from wordnik */
    public $speechPart; /**< the part of speech from wordnik */
    
    private $id; /**< the id in the database */
    private $dbConnection; /**< a dbConnection object */
    private $wordnik; /**< a passed wordnik object */

    //predefined values for the database//
    private $tableName  = "word"; /**< the default table name for the object */
    private $allRows    = "*"; /**< the all rows value for this object */

    public function __construct($connection, $wordnik) {
        /**
         * instantiate the object
         * @param $connection dbConnection object
         * @param $wordnik wordnik object 
         */
        $this->dbConnection = $connection;
        $this->wordnik = $wordnik;
    }
    
    private function loadWordFromDBRows($retVal) {
        /**
         * set up a new word from a query
         * @param $retval dbQuery object from database
         */
        $this->id = retVal($retVal, 'id');
        $this->word = retVal($retVal, 'word');
        $this->definition = unserialize(retVal($retVal, 'definition'));
        $this->speechPart = retVal($retVal, 'speechPart');
    }
    
    private function loadWordFromId($id) {
        /**
         * load a word from an id, useful for passed queries on the website
         * @param $id int id from database
         */
        $id = cleanInput($id);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "id = '".$id."'");
        
        $this->loadWordFromDBRows($retVal);
    }
    
    private function loadWordFromWord($word) {
        /**
         * load a word from a word
         * @param $word string to load from database
         */
        $thisWord = cleanInput($word);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "word = '".$word."'");
        
        $this->loadWordFromDBRows($retVal);
    }

    private function addWord() {
        /**
         * adds a word to the database
         */
        
        //clean the query
        if(trim($this->word) != '') {
            $word = $this->word;
            $definition = serialize($this->definition);
            $speechPart = $this->speechPart;

            //add the word
            $this->dbConnection->insertQuery(
                $this->tableName,
                "word, definition, speechPart",
                "'".$word."','".$definition."','".$speechPart."'");
        }  
    }
    
    public function randomWordOfType($type) {
        /**
         * sets the word to a random word of the passed type
         * @param type string
         */
        
        $retVal = $this->dbConnection->selectQuery(
                'id',
                $this->tableName,
                "speechPart = '".$type."'",
                1);
        
        $allIds = array();
        while($row = mysql_fetch_array($retVal)) {
            $id = $row['id'];
            $allIds[] = $id;
        }
        
        shuffle($allIds);
        $this->loadWordFromId($allIds[0]);
    }
    
    public function returnWord($word) {
        /**
         * check a word against the database, and return the word object, either from
         * the database query or the wordnik definition
         * @param $word string to query
         * @return word object
         */
        $this->loadWordFromWord($word);
        if ($this->id > 0) {
            //the word exists in the database, return the word
            
        } else {
            
            //the word does not exist, query wordnik and then add it to the database
            if(!is_null($word) && trim($word) != '') {
                echo "word: ".$word."<br/>";
                $retVal = $this->wordnik->returnDefinition($word);
            }
            
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
