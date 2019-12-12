<?php

class Functions {

    public function getLog($html_piece) { //TODO KOLLA Varför inte json fungerar.
        $handle = fopen("log.log", "r");
        $array = array();
//        $array = "";
        $tmp = array();
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $tmp = trim($line, "\n");
//            $array .= $line;
                array_push($array, $line);
            }
//            $array = "[" . $array . "]";
//            echo print_r($array);
            fclose($handle);
        } else {
            echo "Error reading log";
        }
        $returnstr = "";
        foreach ($array as $jsons) {
        $html_out = $html_piece;
            $jsons = json_decode($jsons);
            
            foreach ($jsons as $key => $value) {
                if ($key == 'date') {
                    $html_out = str_replace('---date---', $value, $html_out);
//                    echo $html1;
                } elseif ($key == 'browser') {
//                    $value = explode(" ", $value);
//                    echo $value[0];
//                    $html_out = str_replace('---browser---', $value[0]."->".$value[count($value)-1], $html_out);
                    $html_out = str_replace('---browser---', $value, $html_out);
                } else {
                    $html_out = str_replace('---ip---', $value, $html_out);
                }
                $html_out .= $html_out;
            }
            $returnstr .= $html_out;
        }
        return $returnstr;
    }

    public function countHit() {
        $total = fopen("count.txt", "r+");
        if (flock($total, LOCK_EX)) {
            $count = file_get_contents("count.txt");
            ftruncate($total, 0);
            fwrite($total, ++$count);
            fflush($total);
            flock($total, LOCK_UN);
            return ($count);
        } else {
            if ($count = file_get_contents("count.txt")) {
                return ($count);
            }
        }
        fclose($total);
    }

    public function getDate() {
        $time = $_SERVER['REQUEST_TIME'];
        $str = date('Y:m:d, H:i:s', $time);
        return $str;
    }

    public function logger() {

        $time = $_SERVER['REQUEST_TIME'];

        $logObj = new stdClass();
        $logObj->date = date('Y:m:d H:i:s', $time);
        $logObj->browser = getenv('HTTP_USER_AGENT');
        $logObj->ip = getenv('REMOTE_ADDR');
        $logJObj = json_encode($logObj);
//        echo $logJObj;
        $log = fopen("log.log", "a");
        if (flock($log, LOCK_EX)) {
            fwrite($log, $logJObj . "\n");
            fflush($log);
            flock($log, LOCK_UN);
//    print("Logged.");
        } else {
            echo "File busy. Please report to admin.";
        }
        fclose($log);
    }

    function generateFooter() {
        return ("Copyright 2019 Monster Inc.");
    }

}
