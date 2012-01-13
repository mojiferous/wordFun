<?php

/**
 * functions.php
 *
 * @author Mojiferous
 * Jan 10, 2012
 */

//set up defaults and dbfunctions
require_once 'classes/rest.php';
require_once 'defaults/defaults.php';
require_once 'database/dbObject.php';
require_once 'classes/wordnik/wordnikClass.php';

require_once 'classes/word.php';

//load defaults
$defaults = new defaults();

$connection = new dbObject($defaults->settings['database']);
$globalWordnik = new wordnikClass($defaults->settings['wordnik']);

$passedWord = parseGet('word', '');
if ($passedWord != '') {
    $thisWord = new word($connection, $globalWordnik);
    echo $thisWord->returnWord($passedWord);
}

?>
