<?php
include_once "hquery.php";
include_once "kint.php";

ini_set('memory_limit', -1);
set_time_limit(0);

hQuery::$cache_path = "/hquery_cache";
hQuery::$cache_expires = 3600;

$regex = "/\/allies\/merchants\/ship\/.+\.html/";
$doc = hQuery::fromURL("https://uboat.net/allies/merchants/losses_year.html");
$links = $doc->find('div#content a');
$url = Array();

foreach ($links as $index => $a) {
   array_push($url, $a->attr('href'));
}

array_shift($url);
array_pop($url);

$main = Array();

foreach ($url as $index => $link) {

    $document = hQuery::fromURL($link);
    $tabledata = $document->find('div#content td');

    $element = Array();
    $counter = 1;
    $date = 1;
    $uboat = 2;
    $commander = 3;
    $picture = 4;
    $ship = 5;
    $nationality = 8;
    $convoy = 9;

    foreach ($tabledata as $index => $td) {
        if($counter == $date){
            if(trim($td->text(),"\xC2\xA0") == ""){
                break;
            }
            else{
            $string = trim($td->text());
            $value = (new DateTime($string))->format('Y-m-d');
            array_push($element, $value);
            $date += 9;
            }
        }
        if ($counter == $uboat) {
            array_push($element, trim($td->text()));
            $uboat += 9;
        }
        if ($counter == $commander) {
            array_push($element, trim($td->text()));
            $commander += 9;
        }
        if ($counter == $ship) {
            array_push($element, trim($td->text()));
            $temp = preg_match($regex, $td->html(), $matches);
            array_push($element, shipName("uboat.net" . $matches[0]));
            $ship += 9;
        }
        if ($counter == $nationality) {
            array_push($element, strtoupper(trim($td->text())));
            $nationality += 9;
        }
        if ($counter == $convoy){
            if(!empty(trim($td->text()))){
                array_push($element, trim($td->text()));
            }
            else{
                array_push($element, "NULL");
            } 
            $convoy += 9;
            array_push($main, $element);
            $element = Array();
        }
        $counter++; 
    }
}

function shipName($parametar){

    $content = hQuery::fromURL($parametar);
    $dataset = $content->find('div#content td');
    $shipname = "";
    $counter = 1;

    foreach ($dataset as $position => $name) {
        if($counter == 2){
            $shipname = trim($name->text());
            break;
        }
        $counter++;
    }
    return $shipname;
}

//---------- SQL FILE ------------
$file = fopen("main.sql", "w");

foreach($main as $key => $value) {
    
    if(strpos($value[3],"(d.)") == false){
        $string = "INSERT INTO dbo.potopljeno VALUES(";
        $string .= "(SELECT id_datum FROM dbo.datum WHERE datum='" . $value[0] . "'),";
        $string .= "(SELECT id_podmornica FROM dbo.podmornica WHERE naziv='" . $value[1] . "'),";
        $string .= "(SELECT id_tip FROM dbo.tip WHERE naziv=(SELECT tip FROM dbo.podmornica WHERE naziv='" . $value[1] . "')),";
        $string .= "(SELECT id_zapovjednik FROM dbo.zapovjednik WHERE naziv='" . $value[2]  . "'),";
        $string .= "(SELECT id_brod FROM dbo.brod WHERE naziv='" . $value[4] . "'),";
        $string .= "(SELECT id_drzava FROM dbo.drzava WHERE kratica='" . $value[5] . "'),";
        $string .= "(SELECT id_konvoj FROM dbo.konvoj WHERE naziv='" . $value[6] . "'));\n";

        fwrite($file, $string);
    }
}

fclose($file);
?>