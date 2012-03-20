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
require_once 'classes/preferences.php';
require_once 'classes/wordnik/wordnikClass.php';

require_once 'classes/word.php';
require_once 'classes/sentence.php';
require_once 'classes/docParser.php';

//load defaults
$defaults = new defaults();

$connection = new dbObject($defaults->settings['database']);
$prefs = new preferences($connection);

$globalWordnik = new wordnikClass($defaults->settings['wordnik']);

$passedWord = parseGet('word', '');
if ($passedWord != '') {
    $thisWord = new word($connection, $globalWordnik);
    echo $thisWord->returnWord($passedWord)->word;
}

if (isset ($_FILES["file"])) {
    if ($_FILES["file"]["error"] > 0) {
        //there was an error uploading the file
        echo "File upload error.";
    } else {
        $file_name = "upload/" . $_FILES["file"]["name"];
        if (file_exists("upload/" . $_FILES["file"]["name"])) {
            echo $_FILES["file"]["name"] . " already exists. ";
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"],
                    $file_name); 
        }
        
        $newDoc = new docParser($connection, $globalWordnik);
        
        $retVal = array();
        $retVal = $newDoc->parseDoc($file_name);
        
        print_r($retVal);
    }
} else {
?>

<form action="index.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>

<?php } ?>
