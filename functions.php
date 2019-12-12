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
          
//            $someArray = json_decode($array, true);
//            print_r($someArray);
//            echo $someArray[0]["browser"];
//        $jsonlog = "log.log";

//        $json = file_get_contents($jsonlog);
//        $json = str_replace('KHTML,', '', $json);
//        $json = "(" . $json . ")";
//        $json_data = json_decode(trim($json,'"'));
//        echo $json;
//        echo gettype($json_data);
//        echo gettype($json);
//        print_r(explode(",",$json));
//        var_dump(json_decode($json, true));
//        $json_exp = explode(",",$json);
        $html1 = NULL;
        $returnstr = NULL;
//        echo gettype($log);
//        echo gettype($json);
//        echo " ";
//        echo gettype($json_exp);
//        echo sizeof($json_exp);
//        echo $json_exp[0];
     
//        if(is_array($log)){
//            echo "isArray";
//        }
//        echo sizeof($array);
        foreach ($array as $jsons) {

            $jsons = json_decode($jsons);
            foreach ($jsons as $key => $value) {
                if ($key == 'date') {
                    $html_piece = str_replace('---date---', $value, $html_piece);
//                    echo $html1;
                } elseif ($key == 'browser') {
                    $html_piece = str_replace('---browser---', $value, $html_piece);
                }else{
                    $html_piece = str_replace('---ip---', $value, $html_piece);
                }
            }
                $returnstr .= $html_piece;
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

}
