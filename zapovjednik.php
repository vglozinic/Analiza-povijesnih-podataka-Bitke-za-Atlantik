<?php
include_once "hquery.php";
include_once "kint.php";

ini_set('memory_limit', -1);
set_time_limit(0);

hQuery::$cache_path = "/hquery_cache";
hQuery::$cache_expires = 3600;

$regex = '/uboat\.net\/men\/(?:(?:commanders\/\d+)|\w+)\.html?/';

$alphabet = Array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","z");
$links = Array();

foreach ($alphabet as $index => $letter) {
   array_push($links, "uboat.net/men/commanders/" . $letter . ".htm");
}

$url = Array();

foreach ($links as $index => $link) {

    $doc = hQuery::fromURL($link);
    $linkdata = $doc->find('div#content h3 > a');

    foreach ($linkdata as $pos => $a) {
        if(preg_match($regex, $a->attr('href'), $match)){
            array_push($url, $match[0]);
        }
    }
}

array_push($url,"uboat.net/men/schultze_heinz-otto.htm");
$commanders = Array();

foreach ($url as $pos => $commander) {
    
    $content = hQuery::fromURL($commander);
    $element = Array();

    $tabledata = $content->find('div#content td');
    $titledata = $content->find('div#content h1');
    $rankdata = $content->find('div#content h3');

    foreach ($titledata as $index => $title) {
        array_push($element, trim($title->text()));
    }

    $counter = 1;

    foreach ($rankdata as $number => $entry) {
        if($counter == 1){
            $data = trim($entry->text());
            $rank = explode("(", $data);
            array_push($element, trim($rank[0]," "));
            break;
        }
    }

    foreach ($tabledata as $position => $td) {
        if($counter == 3){
            $value = trim($td->text());
            $date = (new DateTime($value))->format('Y-m-d');
            array_push($element, $date);
        }
        if($counter == 5){
            array_push($element, trim($td->text()));
            break;
        }
        $counter++;
    }
    array_push($commanders, $element);
}

//---------- SQL FILE ------------

$file = fopen("commanders.sql", "w");

foreach($commanders as $key => $value) {
    $string = "INSERT INTO dbo.zapovjednik VALUES('" . $value[0] . "','" . $value[1] . "','" . $value[2] . "','" . $value[3] . "');\n";
    fwrite($file, $string);
}

fclose($file);
?>