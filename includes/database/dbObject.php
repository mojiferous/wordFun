<?php

/**
 * PHP class dbObject
 * dbObject.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */
class dbObject {
    private $connection;

    public function __construct($settings) {
        //called when the dbObject is instantiated
        $this->connect($settings);
    }

    private function connect($settings) {
        //called from instantiation to connect to the database set in defaults.ini
        //the system looks to the defaults set in defaults.ini to gather these variables
        $this->connection = mysql_connect($settings['dbLocation'],$settings['dbUser'],$settings['dbPass']);

        if (!$this->connection) {
            die("Could not connect to database ".mysql_error());
        }

        mysql_select_db($settings['dbName'],  $this->connection);
    }

    private function runQuery($query) {
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);

        return $row;
    }

    public function selectQuery($rowClause,$tableName,$whereClause) {
        //run a select query
        $query = "SELECT ".$rowClause." FROM ".$tableName." WHERE ".$whereClause;

        return $this->runQuery($query);
    }

    public function updateQuery($tableName, $columnClause, $whereClause) {
        //run an update query
        $query = "UPDATE ".$tableName." SET ".$columnClause." WHERE ".$whereClause;

        return $this->runQuery($query);
    }

    public function insertQuery($tableName,$fields,$values){
        $query = "INSERT INTO ".$tableName." (".$fields.") ";
        $query .= "VALUES (".$values.")";

        mysql_query($query);
    }
       
}

?>
