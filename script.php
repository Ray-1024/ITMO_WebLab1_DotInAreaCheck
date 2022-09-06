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

        $line = new DOMElement("tr");
        $table->appendChild($line);
        for ($j = 0; $j < count($resultsArray[$i]); ++$j) {
            $cell = new DOMElement("td");
            $cell->textContent = $resultsArray[$i][$j];
            $line->appendChild($cell);
        }
    }
}

function func()
{
    $currTime = microtime();
    date_default_timezone_set('Europe/Moscow');
    $pageFilename = __DIR__ . '/page.html';
    $resultsFilename = __DIR__ . '/results.txt';
    $document = new DOMDocument();
    $document->loadHTMLFile($pageFilename);


    if (isset($_POST["xRadio"]) && isset($_POST["yText"]) && isset($_POST["rCheckbox"]) && count($_POST["rCheckbox"]) == 1) {

        $xRadio = floatval($_POST["xRadio"]);
        $yText = floatval($_POST["yText"]);
        $rCheckbox = null;

        foreach ($_POST["rCheckbox"] as $i) {
            $rCheckbox = floatval($i);
            break;
        }

        echo '( ' . $xRadio . ' , ' . $yText . ' ) Radius: ' . $rCheckbox . PHP_EOL;
        $resultOfTest = check($xRadio, $yText, $rCheckbox);
        $resultStr = "Точка попала в область";
        if ($resultOfTest) {
            $resultStr = "Точка не попала в область";
        }

        $arr = loadResultsArray($resultsFilename);
        array_push($arr, array("" . (count($arr) + 1), date('m/d/Y h:i:s a', time()), "" . , "" . $xRadio, "" . $yText, "" . $rCheckbox, $resultStr));
        fillResultsTable($document, $arr);
        saveResultsArray($arr, $resultsFilename);
    }

    echo $document->saveHTML();
}

func();

?>