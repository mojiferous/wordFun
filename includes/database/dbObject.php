<?php

/**
 * PHP class dbObject
 * dbObject.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */
class dbObject {
    private $connection; /**< the mysql database connection */

    public function __construct($settings) {
        /**
         * called when the dbObject is instantiated
         * @param $settings array of settings, see defaults.php
         */
        $this->connect($settings);
    }

    private function connect($settings) {
        /**
         * called from instantiation to connect to the database set in defaults.ini
         * the system looks to the defaults set in defaults.ini to gather these variables
         * @param $settings array from defaults.ini instantitated in defaults.php
         */
        $this->connection = mysql_connect($settings['dbLocation'],$settings['dbUser'],$settings['dbPass']);

        if (!$this->connection) {
            die("Could not connect to database ".mysql_error());
        }

        mysql_select_db($settings['dbName'],  $this->connection);
    }

    public function runRawQuery($query) {
        /** 
        * runs a raw query to the database, allowing us to run things like 
        * DELETE and SELECT DISTINCT
        * @param $query string of the query
        * @return array
        */
        return mysql_query($query);
    }

    private function runQuery($query) {
        /** 
        * run a mySQL query and return the result
        * @param $query string of the query
        * @return array
        */
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_array($result);
        }

        return $row;
    }

    public function selectQuery($rowClause,$tableName,$whereClause, $runRaw = 0) {
        /**
         * run a select query
         * @param $rowClause string the row(s) to select
         * @param $tableName string the table to select
         * @param $whereClause string the where logic
         * @param $runRaw int run a raw query and return the query raw
         * @return array
         */
        $query = "SELECT ".$rowClause." FROM ".$tableName." WHERE ".$whereClause;
        
        if ($runRaw == 1) {
            return $this->runRawQuery($query);
        }
        return $this->runQuery($query);
    }

    public function updateQuery($tableName, $columnClause, $whereClause) {
        /**
         * run an update query
         * @param $tableName string the table to update
         * @param $columnClause string the columns to update, comma separated
         * @param $whereClause string the where logic
         * @return array
         */
        $query = "UPDATE ".$tableName." SET ".$columnClause;
        if($whereClause != '') {
            //if you want to delete all the content, simply pass a blank to $whereClause
            $query .= " WHERE ".$whereClause;
        }

        return $this->runQuery($query);
    }

    public function insertQuery($tableName,$fields,$values){
        /**
         * run an insert into the database 
         */
        $query = "INSERT INTO ".$tableName." (".$fields.") ";
        $query .= "VALUES (".$values.")";

        mysql_query($query);
    }
       
}

?>
