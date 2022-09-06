<?php


function check($x, $y, $r)
{
    if (($x >= 0.0) && ($x <= $r / 2.0) && ($y <= 0.0) && ($y >= $x - $r / 2.0)) {
        return true;
    }
    if (($x >= -$r / 2.0) && ($x <= 0.0) && ($y >= 0.0) && ($y <= $r)) {
        return true;
    }
    if (($x >= -$r / 2.0) && ($x <= 0.0) && ($y >= -$r / 2.0) && ($y <= 0.0) && (($x * $x + $y * $y) <= ($r * $r / 4.0)))
        return true;
    return false;
}


function loadResultsArray($filename)
{
    /*$filename = __DIR__ . '/results.txt';
    $text = file_get_contents($filename);
    if ($text == false) {
        return array();
    } else {
        $lines = preg_split("\n", $text);

        if ($lines == null) return array();
        for ($i = 0; $i < count($lines); $i++) {
            $lines[i] = preg_split(" ", $lines[i]);
            if ($lines[i] == false) return array();
        }
        return $lines;
    }*/
    $text = file_get_contents($filename);
    if ($text == false) return array();
    $array = unserialize(base64_decode($text));
    if ($array == false) return array();
    return $array;
}


function saveResultsArray($array, $filename)
{
    $text = base64_encode(serialize($array));
    file_put_contents($filename, $text);
}


function fillResultsTable($document, $resultsArray)
{
    if ($document == null) return;
    $table = $document->getElementById("resultTable");
    if ($table == null) return;

    for ($i = 0; $i < count($resultsArray); ++$i) {
        ///////////////////////////////////////////////////////////////////////////////////////
        $newLine = new DOMElement("tr");
        $cellNumber = new DOMElement("td");
        $cellDateTime = new DOMElement("td");
        $cellX = new DOMElement("td");
        $cellY = new DOMElement("td");
        $cellR = new DOMElement("td");
        $cellResponse = new DOMElement("td");
        ///////////////////////////////////////////////////////////////////////////////////////
        $table->appendChild($newLine);
        $newLine->appendChild($cellNumber);
        $newLine->appendChild($cellDateTime);
        $newLine->appendChild($cellX);
        $newLine->appendChild($cellY);
        $newLine->appendChild($cellR);
        $newLine->appendChild($cellResponse);
        //////////////////////////////////////////////////////////////////////////////////////
        $cellNumber->textContent = "6";
        $cellDateTime->textContent = "5";
        $cellX->textContent = "4";
        $cellY->textContent = "3";
        $cellR->textContent = "2";
        $cellResponse->textContent = "1";
    }
}

function func()
{
    $filename = __DIR__ . '/page.html';
    $document = new DOMDocument();
    $document->loadHTMLFile($filename);


    if (isset($_POST["xRadio"]) && isset($_POST["yText"]) && isset($_POST["rCheckbox"]) && count($_POST["rCheckbox"]) == 1) {

        $xRadio = floatval($_POST["xRadio"]);
        $yText = floatval($_POST["yText"]);
        $rCheckbox = null;

        foreach ($_POST["rCheckbox"] as $i) {
            $rCheckbox = floatval($i);
        }

        /*echo '( ' . $xRadio . ' , ' . $yText . ' ) Radius: ' . $rCheckbox . PHP_EOL;
        if (check($xRadio, $yText, $rCheckbox)) {
            echo 'YES';
        } else echo 'NO';*/

        $arr = loadResultsArray($filename);
        fillResultsTable($document, $arr);
        saveResultsArray($arr, $filename);
    }

    echo $document->saveHTML();
}

func();

?>