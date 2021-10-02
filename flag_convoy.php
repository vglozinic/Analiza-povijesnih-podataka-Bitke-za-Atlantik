<?php
include_once "hquery.php";
include_once "kint.php";

ini_set('memory_limit', -1);
set_time_limit(0);

hQuery::$cache_path = "/hquery_cache";
hQuery::$cache_expires = 3600;

$regex = '/uboat.net\/allies\/merchants\/losses_year.html.+/';

$doc = hQuery::fromURL("https://uboat.net/allies/merchants/losses_year.html");
$links = $doc->find('div#content a');
$url = Array();

foreach($links as $pos => $a) {
    $temp = preg_match($regex, $a->attr('href'), $matches);
    array_push($url, $matches[0]);
}

array_shift($url);
array_pop($url);

$flags = Array();
$convoys = Array();

foreach($url as $index => $link) {

    $document = hQuery::fromURL($link);
    $tabledata = $document->find('div#content td');

    $counter = 1;
    $flag = 8;
    $convoy = 9;

    foreach ($tabledata as $pos => $td) {
        if(trim($td->text()) != "unknown"){
            $text = trim($td->text());
            if($counter == $flag){
                putIntoArray($flags, strtoupper($text));
                $flag += 9;
            }
            if($counter == $convoy){
                if(!empty($text)){
                    putIntoArray($convoys, $text);
                }
                $convoy += 9;
            }
        }
        else{
            $flag += 9;
            $convoy += 9; 
        }
        $counter++;
    }
}

//-----------FUNCKIJA ------------

function putIntoArray(&$array, $string){

    $found = false;
    if (in_array($string, $array)){
        $found = true;
    }

    if($found == false){
        array_push($array, $string);
    }
}

//---------- SQL FILE ------------

$file = fopen("flags_convoys.sql", "w");

foreach($flags as $key => $value) {
    $string = "INSERT INTO dbo.drzava VALUES('" . "' ,'" . "', '" . $value . "');\n";
    fwrite($file, $string);
}

fwrite($file,"\n");

foreach($convoys as $key => $value) {
    $string = "INSERT INTO dbo.konvoj VALUES('" . $value . "');\n";
    fwrite($file, $string);
}

fclose($file);
?>