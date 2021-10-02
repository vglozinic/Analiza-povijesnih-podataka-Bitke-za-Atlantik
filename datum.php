<?php
include_once "hquery.php";
include_once "kint.php";

$dates = Array();

$period = new DatePeriod(
    new DateTime('1939-09-01'),
    new DateInterval('P1D'),
    new DateTime('1945-05-08 +1 day')
);

foreach ($period as $key => $value) {

    $date = $value->format('Y-m-d');
    $element = Array();

    array_push($element, $date);
    array_push($element, date("d", strtotime($date)));
    array_push($element, date("l", strtotime($date)));
    array_push($element, date("m", strtotime($date)));
    array_push($element, date("F", strtotime($date)));
    array_push($element, date("Y", strtotime($date)));
    
    array_push($dates, $element);  
}

d($dates);

$file = fopen("datum.sql", "w");

foreach($dates as $key => $value) {
    $string = "INSERT INTO dbo.datum VALUES('" . $value[0] . "', " . $value[1] . ", '" . $value[2] . "', " . $value[3] . ", '" . $value[4] . "', " . $value[5] . ");\n";
    fwrite($file, $string);
}

fclose($file);
?>