<?php

/**
 * PHP class preferences
 * preferences.php
 *
 * @author Mojiferous
 * Jan 22, 2012
 */
require_once 'rest.php';
class preferences {
    private $dbConnection; /**< database class */
    private $tableName  = "preference"; /**< this class' table in the database */
    private $allRows    = "*"; /**< the hook for all rows */

    public function __construct($connection) {
        /**
         * instantiate the preference class
         * @param $connection dbConnection 
         */
        $this->dbConnection = $connection;
    }

    private function keyExists($key) {
        /**
         * does the key exist in the database? 
         * @param $key string of key to match to value
         * @return boolean if key exists
         */
        $retVal = $this->loadKey($key);

        return retVal($retVal, 'keyValues') != '' ? true : false;
    }

    private function updateKey($key,$value) {
        /**
         * updates the value of a key
         * @param $key string of key to update
         * @param $value string of value to update for key
         */

        //clean the query
        $key = cleanInput($key);
        $value = cleanInput($value);

        //update the key
        $this->dbConnection->updateQuery(
            $this->tableName,
            "valueValues = '".$value."'",
            "keyValues = '".$key."'");
    }

    private function insertKey($key, $value) {
        /**
         * insert a new key into the database
         * @param $key string of name of preference to update
         * @param $value string value of preference
         */

        //clean the queries
        $key = cleanInput($key);
        $value = cleanInput($value);

        //insert the key/value pair into the database
        $this->dbConnection->insertQuery(
            $this->tableName,
            "keyValues, valueValues",
            "'".$key."', '".$value."'");
    }

    private function loadKey($key) {
        /**
         * return the value of a key
         * @param $key string name of key to return value for
         * @return a mysql query, function calling must run retVal() on value
         */
        $key = cleanInput($key);

        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "keyValues = '".$key."'");

        return $retVal;
    }

    public function setValue($key, $value) {
        /**
         * set a preference value in the database
         * @param $key string name of the preference
         * @param $value string value of the preference $key
         */
        
        //check to see if the key exists
        if ($this->keyExists($key)) {
            $this->updateKey($key, $value);
        } else {
            $this->insertKey($key, $value);
        }
    }

    public function getValue($key) {
        /**
         * get a value from the database
         * @param $key string matched to a value in the database 
         * @return string of the value matching $key
         */
        return retVal($this->loadKey($key), 'valueValues');
    }
  
}

?>

