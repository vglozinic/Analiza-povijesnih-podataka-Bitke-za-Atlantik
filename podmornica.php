<?php
include_once "hquery.php";
include_once "kint.php";

ini_set('memory_limit', -1);
set_time_limit(0);

hQuery::$cache_path = "/hquery_cache";
hQuery::$cache_expires = 3600;

$regex = '/uboat.net\/boats\/u\d+\.htm/';
$number = '/\d+/';
$shipyard = '/(.+), (.+) \(.+\)/';

$doc = hQuery::fromURL("uboat.net/boats/listing.html");
$links = $doc->find('div#content a');
$url = Array();

foreach($links as $pos => $a) {
    $links[$pos] = $a->attr('href'); 

    if(preg_match($regex, $a->attr('href'), $match)){
        array_push($url, $match[0]);
    }
}

array_pop($url);
$subs = Array();

foreach($url as $index => $link) {

    $document = hQuery::fromURL($link);
    $tabledata = $document->find('div#content td');

    $counter = 1;
    $element = Array();

    if(preg_match($number, $link, $u)){
        array_push($element, "U-" . $u[0]);
    }

    foreach($tabledata as $pos => $td) {

        if($counter == 2 || $counter == 5 || $counter == 9 || $counter == 14){
            $value = trim($td->text());
            if($value[0] == "X" || $value[0] == "I" || $value[0] == "V"){
                array_push($element, $value);
            }
            else{
                if($value[0] == "0" || $value[0] == "1" || $value[0] == "2" || $value[0] == "3" || $value[0] == "4" || $value[0] == "5" || $value[0] == "6" || $value[0] == "7" || $value[0] == "8" || $value[0] == "9"){
                    $date = (new DateTime($value))->format('Y-m-d');
                    array_push($element, $date);
                }
                else{
                    $data = preg_match($shipyard, $value, $matches);
                    array_push($element, $matches[1]);
                    array_push($element, $matches[2]);
                }
            }
        }
        $counter++;
    }
    array_push($subs, $element);
}

//---------- SQL FILE ------------

$file = fopen("subs.sql", "w");

foreach($subs as $key => $value) {
    $string = "INSERT INTO dbo.podmornica VALUES('" . $value[0] . "', '" . $value[2] . "', '" . $value[3] . "', '" . $value[4] . "', '" . $value[5] . "', '" . $value[1] . "');\n";
    fwrite($file, $string);
}

fclose($file);
?>