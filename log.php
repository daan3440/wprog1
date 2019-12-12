<?php
include "functions.php";

$str = file_get_contents("log.html");
$html_pieces = explode("<!--===xxx===-->", $str);

$ch = new Functions();
$html0 = str_replace('---curdate---', $ch->getDate(), $html_pieces[0]);
echo $html0;

//LOOP
//$ch->getLog();

$html1 = $ch->getLog($html_pieces[1]);
echo $html1;

//END LOOP
echo $html_pieces[2];
