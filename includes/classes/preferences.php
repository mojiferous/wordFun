<?php

/**
 * PHP class preferences
 * preferences.php
 *
 * @author Mojiferous
 * Jan 22, 2012
 */
class preferences {
    private $dbConnection;

    //predefined values for the database//
    private $tableName  = "preference";
    private $allRows    = "*";

    public function __construct($connection) {
        $this->dbConnection = $connection;
    }

    private function keyExists($key) {
        $retVal = $this->loadKey($key);

        retVal($retVal, 'keyValues') != '' ? true : false;
    }

    private function updateKey($key,$value) {
        //updates the value of a key

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
        //insert a new key into the database

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
        //return the value of a key
        $key = cleanInput($key);

        $retVal = $this->dbConnection->selectQuery(
            $this->allRows,
            $this->tableName,
            "keyValues = '".$key."'");

        return $retVal;
    }

    public function setValue($key, $value) {
        if ($this->keyExists($key)) {
            $this->updateKey($key, $value);
        } else {
            $this->insertKey($key, $value);
        }
    }

    public function getValue($key) {
        return retVal($this->loadKey($key), 'valueValues');
    }
  
}

?>

