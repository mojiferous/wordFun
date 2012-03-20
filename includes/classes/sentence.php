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
    public $raw_sentence; /**< the raw, unparsed sentence */
    public $with_proper; /**< the sentence with everything parsed by proper nouns */
    public $all_parts; /**< sentence with all language parts replaced */
    
    private $id; /**< the id of the sentence in the database */
    private $dbConnection; /**< the default database connection */
    private $wordnik; /**< the wordnik class, to determine word part of speech */

    //predefined values for the database//
    private $tableName  = "sentence"; /**< the table name of this object */
    private $allRows    = "*"; /**< the allrows value for this object */

    public function __construct($connection, $wordnik) {
        /**
         * instantiate a new sentence object
         * @param $connection dbConnection object
         * @param $wordnik wordnik object 
         */
        $this->dbConnection = $connection;
        $this->wordnik = $wordnik;
    }
    
    private function loadSentenceFromDBRows($retVal) {
        /**
         * set up a new sentence from a query
         * @param $retVal database object
         */
        $this->id = retVal($retVal, 'id');
        $this->raw_sentence = retVal($retVal, 'raw_sentence');
        $this->with_proper = retVal($retVal, 'with_proper');
        $this->all_parts = retVal($retVal, 'all_parts');
    }
    
    private function loadSentenceFromId($id) {
        /**
         * load a sentence from an id, useful for passed values
         * @param $id int value of id in database
         */
        $id = cleanInput($id);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "id = '".$id."'");
        
        $this->loadSentenceFromDBRows($retVal);
    }
    
    private function loadSentenceFromSentence($sentence) {
        /**
         * load a sentence from a extant sentence, 
         * finds the sentence in the database and returns it
         * @param $sentence string of sentence
         */
        $thisSentence = cleanInput($sentence);
        
        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "raw_sentence = '".$sentence."'");
        
        $this->loadSentenceFromDBRows($retVal);
    }
    
    private function addSentence() {
        /**
         * adds a sentence to the database
         */
        
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
        /**
         * cleans individual words of invalid characters
         * @param $word string of word
         * @param $retType int sets what value to return, 0 returns only the cleaned word, 1 returns cleaned word with {} surrounding 
         */
        $newWord = $word;
        for($n=0; $n<65; $n++) {
            //replace NUL through @
            $newWord = str_ireplace(chr($n), "", $newWord);
        }
        for($n=91; $n<97; $n++) {
            //replace [ through `
            $newWord = str_ireplace(chr($n), "", $newWord);
        }
        for($n=123; $n<128; $n++) {
            //replace { through DEL
            $newWord = str_ireplace(chr($n), "", $newWord);
        }
        $newWord = trim($newWord);

        if ($retType == 0) {
            //return only the newWord
            return $newWord;
        } else {
            return str_ireplace($newWord, '{'.$newWord.'}', $newWord);
        }
    }
    
    public function returnSentence($sentence) {
        /**
         * check a sentence against the database, and either return
         * the database query or the wordnik definition
         * @param $sentence string of sentence
         * @return string of raw sentence
         */

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
                $thisWord = $thisWord->returnWord(strtolower($searchWord));
                
                $finalWord = str_ireplace("{".$searchWord."}", "{".$thisWord->speechPart."}", $replWord);
                if ($thisWord->speechPart != 'proper noun' 
                        && $thisWord->speechPart != 'preposition' 
                        && $thisWord->speechPart != 'definite-article' 
                        && $thisWord->speechPart != 'conjunction') {
                    //the word is not a proper noun, replace it in $withProp
                    $withProp[] = $finalWord;
                } else {
                    $withProp[] = $indWord;
                }
                $allParts[] =$finalWord;                
            }
            
            $this->raw_sentence = $raw;
            $this->with_proper = implode(" ", $withProp);
            $this->all_parts = implode(" ", $allParts);
            
            $this->addSentence();
        }
        
        return $this->raw_sentence;
    }
    
}

?>
