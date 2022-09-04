<?php

$filename = __DIR__ . '/page.html';
$document = new DOMDocument();
$document->loadHTMLFile($filename);

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

if (isset($_POST["xRadio"]) && isset($_POST["yText"]) && isset($_POST["rCheckbox"]) && count($_POST["rCheckbox"]) == 1) {
    $xRadio = floatval($_POST["xRadio"]);
    $yText = floatval($_POST["yText"]);
    $rCheckbox = null;
    foreach ($_POST["rCheckbox"] as $i) {
        $rCheckbox = floatval($i);
    }
    echo '( ' . $xRadio . ' , ' . $yText . ' ) Radius: ' . $rCheckbox . PHP_EOL;
    if (check($xRadio, $yText, $rCheckbox)) {
        echo 'YES';
    } else echo 'NO';
}


echo $document->saveHTML();
?>