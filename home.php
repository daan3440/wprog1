<?php

include 'functions.php';
$ch = new Functions;
$ch->logger();

$str = file_get_contents("home.html");
$html = str_replace('--salkaslk1234lk3kioa--', $ch->countHit(), $str);
echo $html;


