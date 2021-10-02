<?php
include_once "hquery.php";
include_once "kint.php";

ini_set('memory_limit', -1);
set_time_limit(0);

hQuery::$cache_path = "/hquery_cache";
hQuery::$cache_expires = 3600;

$regex = '/uboat.net\/allies\/merchants\/losses_year.html.+/';
$ship = '/uboat.net\/allies\/merchants\/ship\/.+/';

$doc = hQuery::fromURL("https://uboat.net/allies/merchants/losses_year.html");
$links = $doc->find('div#content a');
$url = Array();

foreach ($links as $pos => $a) {
    $temp = preg_match($regex, $a->attr('href'), $matches);
    array_push($url, $matches[0]);
}

array_shift($url);
array_pop($url);
$merchants = Array();
$ships = Array();

foreach ($url as $index => $link) {

    $document = hQuery::fromURL($link);
    $linkdata = $document->find('div#content a');

    foreach ($linkdata as $pos => $a) {
        if(preg_match($ship, $a->attr('href'), $match)){
            array_push($merchants, $match[0]);
        }
    }
}

$variable = "uboat.net/allies/merchants/ship/";
array_splice($merchants, array_search($variable . "2109.html", $merchants), 1);
array_splice($merchants, array_search($variable . "2321.html", $merchants), 1);
array_splice($merchants, array_search($variable . "3163.html", $merchants), 1);
array_splice($merchants, array_search($variable . "3271.html", $merchants), 1);
array_splice($merchants, array_search($variable . "3272.html", $merchants), 1);
array_splice($merchants, array_search($variable . "3273.html", $merchants), 1);

foreach ($merchants as $number => $merchant) {
    
    $content = hQuery::fromURL($merchant);
    $tabledata = $content->find('div#content td');

    $element = Array();
    $counter = 1;

    foreach($tabledata as $position => $td) {
    
        if($counter == 2){
            $data = trim($td->text());
            array_push($element, str_replace("  "," ",$data));
        }
        if($counter == 4 || $counter == 10 || $counter == 12 || $counter == 26){
            $data = trim($td->text(), "\xC2\xA0");

            if(empty($data)){
                array_push($element, "NULL");
            }
            else{
                array_push($element, $data);
            }
        }
        if($counter == 6){
            $data = trim($td->text());
            $tons = explode(" ", $data);
            array_push($element, str_replace(",","",$tons[0]));
        }
        if($counter === 22){
            $data = trim($td->text());
            $complement = explode(" ",$data);
            
            if($complement[0] == "?"){
                array_push($element, "NULL");
            }
            else{
                array_push($element, $complement[0]);
            } 
        }
        if($counter == 28){
            $data = trim($td->text(), "\xC2\xA0");

            if(empty($data)){
                array_push($element, "NULL");
            }
            else{
                array_push($element, $data);
            }
            break;
        }
        $counter++;
    }
    array_push($ships,$element);
}

//---------- SQL FILE ------------

$file = fopen("ships.sql", "w");

foreach($ships as $key => $value) {
    $string = "INSERT INTO dbo.brod VALUES('" . $value[0] . "','" . $value[1] . "'," . $value[2] . ",'" . $value[3] . "','" . $value[4] . "','" . $value[5] . "','" . $value[6] . "','" . $value[7] . "');\n";
    fwrite($file, $string);
}

fclose($file);
?>