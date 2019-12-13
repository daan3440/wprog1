<?php
header('Content-type: text/plain');
$arr = $_GET;
foreach($arr as $key => $value){
    echo "$key = $value\n";
}
