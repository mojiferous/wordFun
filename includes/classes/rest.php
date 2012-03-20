<?php

/**
 * rest.php
 * 
 * Mojiferous
 * Jan 10, 2012
 */

function parseGet($varName,$default){
    /**
     * return values from a url variable 
     * @param $varName string of $_REQUEST variable
     * @param $default value to return as a default if $varName does not exist
     * @return value of the $varName in the request
     */
    if (isset($_REQUEST[$varName])){
        $retVal = $_REQUEST[$varName];
        $retVal = cleanInput($retVal);
        return $retVal;
    }
    else {
        //if the variable does not exist in the GET variable, return the default
        return $default;
    }
}

function cleanInput($thisString) {
    /**
     * let's clean up the input, so illegal characters can't be input, preventing sql insertion
     * @param $thisString string to clean
     * @return string cleaned of html characters
     */
    $thisString = htmlspecialchars($thisString, ENT_QUOTES);
    $thisString = strip_tags($thisString);
    return $thisString;
 }

function retVal($var, $val, $default = '') {
    /**
     * return a value, usually from a database query
     * @param $var array to search for key $val
     * @param $val string of key to search for
     * @param $default string to return if $val is not found in $var, defaults to ''
     * @return value of $val in $var, or $default if not found
     */
    return (isset($var[$val])) ? $var[$val] : $default;
}
?>