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
    
    public function loadSentenceFromId($id) {
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
    
    public function returnAllSentenceIds() {
        /**
         * return all sentences from the database as a database object
         * @return array as database query
         */
        
        $retVal = $this->dbConnection->selectQuery(
                'id',
                $this->tableName,
                '',
                1);
        
        return $retVal;
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
    
    private function loadSentenceFromProper($withProper) {
        /**
         * check the database for a similar existing sentence structure
         * @param $withProper string of the with_proper variable 
         */
        
        $retVal = $this->dbConnection->selectQuery(
                $this->allRows,
                $this->tableName,
                "with_proper = '".$withProper."'");
        
        $this->loadSentenceFromDBRows($retVal);
    }
    
    private function addSentence() {
        /**
         * adds a sentence to the database
         */
        
        $rsentence = trim($this->raw_sentence);
        $psentence = trim($this->with_proper);
        $asentence = trim($this->all_parts);
        
        $this->loadSentenceFromProper($psentence);
        
        if($this->id < 1) {
            //add the sentence, but only if the with_proper sentence is new
            $this->dbConnection->insertQuery(
                $this->tableName,
                "raw_sentence, with_proper, all_parts",
                "'".$rsentence."','".$psentence."','".$asentence."'");
        }
        
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
            $withProp = array();
            $allParts = array();
            
            $sentArray = explode(" ", $sentence);
            
            foreach ($sentArray as $indWord) {
                $searchWord = $this->cleanWord($indWord);
                $replWord = $this->cleanWord($indWord,1);
                
                $thisWord = new word($this->dbConnection, $this->wordnik);
                $thisWord = $thisWord->returnWord(strtolower($searchWord));
                
                $finalWord = str_ireplace("{".$searchWord."}", "{".$thisWord->speechPart."}", $replWord);
                if ($this->checkSpeechPart($thisWord->speechPart,$searchWord)) {
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
            
            if(count($allParts) > 2 && count($allParts) < 15) {
                //only add the sentence to the database if it is longer than one word or shorter than 15
                $this->addSentence();
            }
            
        }
        
        return $this->raw_sentence;
    }
    
    private function checkSpeechPart($speechPart,$word) {
        /**
         * returns a boolean to filter speech parts
         * @return boolean 
         */
        switch ($speechPart) {
//            case 'proper noun':
//                return false;
//                break;
            case 'preposition':
                return false;
                break;
            case 'definite-article':
                return false;
                break;
            case 'conjuntion':
                return false;
                break;
            case 'pronoun':
                return false;
                break;
            case 'adjective':
                return false;
                break;
            case 'adverb':
                return false;
                break;
            default:
                //check for certain words that make more sense included
                switch(strtolower($word)) {
                    case 'i':
                        return false;
                        break;
                    case 'had':
                        return false;
                        break;
                    case 'was':
                        return false;
                        break;
                    case 'were':
                        return false;
                        break;
                    case 'a':
                        return false;
                        break;
                    case 'have':
                        return false;
                        break;
                    case 'is':
                        return false;
                        break;
                    case 'the':
                        return false;
                        break;
                    default:
                        return true;
                        break;
                }
                break;
        }
    }
    
}

?>
