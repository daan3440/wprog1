<?php

include "functions.php";

$str = file_get_contents("log.html");
$html_pieces = explode("<!--===xxx===-->", $str);

$ch = new Functions();
$date = $ch->getDate();
$html0 = str_replace('---curdate---', $date, $html_pieces[0]);
echo $html0;

$html_table = $ch->getLog($html_pieces[1]);
echo $html_table;

//END LOOP
$html_end = str_replace('---curdate---', $date, $html_pieces[2]);
echo $html_end;
