<?php

/**
 * PHP class phrase
 * phrase.php
 *
 * @author Mojiferous
 * Mar 22, 2012
 */

require_once 'rest.php';
require_once 'sentence.php';
require_once 'word.php';

class phrase {
    public $allSentences = array(); /**< all sentences in the database, as sentence objects */
    
    private $dbConnection; /**< the database object, passed to the sentence object */
    private $wordnik; /**< the global wordnik object, to pass to the sentence object */

    public function __construct($connection, $wordnik) {
        /**
         * instantiate a new phrase object
         * @param $connection dbConnection object
         * @param $wordnik wordnik object 
         */
        $this->dbConnection = $connection;
        $this->wordnik = $wordnik;
        $this->getAllSentences();
    }
    
    private function getAllSentences() {
        /**
         * load all the sentences in the database into the allSentences array; 
         */
        
        $initSentence = new sentence($this->dbConnection, $this->wordnik);
        $vals = $initSentence->returnAllSentenceIds();
        
        while($row = mysql_fetch_array($vals)) {
            $id = $row['id'];
            $this->allSentences[] = $id;
        }
        
    }
    
    private function returnSingleSentence($search = '') {
        /**
         * return a single random sentence with proper nouns, conjunctions, etc. from the database 
         * @return string of sentence from database
         */
        $newSentence = new sentence($this->dbConnection, $this->wordnik);
        
        if ($search == '') {
            shuffle($this->allSentences);
            $newSentence->loadSentenceFromId($this->allSentences[0]);
        } else {
            $newSentence->loadSentenceFromId($search);
        }
        
        return $newSentence->with_proper;
    }
    
    public function returnNewSentence($search = '') {
        /**
         * return a sentence with the pieces and parts replaced 
         * @return string
         */
        $re1 = '(\\{.*?\\})';
        $retSentence = '';
        
        $singleSentence = $this->returnSingleSentence($search);
        $allParts = explode(' ', $singleSentence);
        
        foreach ($allParts as $txt) {
            if ($has = preg_match_all ("/".$re1."/is", $txt, $matches)){
                $wordType = str_replace('}', '', str_replace('{', '', $matches[1][0]));
  
                $newWord = new word($this->dbConnection, $this->wordnik);
                $newWord->randomWordOfType($wordType);
                
                $retSentence .= $newWord->word;
            } else {
                $retSentence .= $txt;
            }
            $retSentence .= ' ';
        }

        return $retSentence;
        
        
    }
}

?>
