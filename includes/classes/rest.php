<?php

/**
 * rest.php
 * 
 * Mojiferous
 * Jan 10, 2012
 */

//function to return values from url variables
function parseGet($varName,$default){
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
    //let's clean up the input, so illegal characters can't be input, preventing sql insertion
    $thisString = htmlspecialchars($thisString, ENT_QUOTES);
    $thisString = strip_tags($thisString);
    return $thisString;
 }

function retVal($var, $val, $default = '') {
    return (isset($var[$val])) ? $var[$val] : $default;
}
?>