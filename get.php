<?php
header('Content-type: text/plain');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    echo "POST\n";
//    [name] => zeppelin.gif
//    [type] => image/gif
//    [tmp_name] => /Applications/XAMPP/xamppfiles/temp/phpgU27gA
//    [error] => 0
//    [size] => 4944

    $request = $_REQUEST;
    $logArr = $request;
    $attach = NULL;
    if (isset($_FILES['file'])){
        $check = getimagesize($_FILES['file']['tmp_name']);
        if($check !== false) {
              $attach = $_FILES["file"];
                array_push($logArr, $attach);
        }
    }
    $size = count($request) + $attach['size'];
    if ($size < 100000) {
        $file_path = printArr($logArr);
        if($file_path != NULL)
            $logArr[0]['tmp_name'] = $file_path;
        messageLogger($logArr);
    } else {
        echo "Too much info.";
    }
} else {
        printArr($_GET);
}

function printArr($arr) {
    $target_file = NULL;
    $firstname = NULL;
    foreach ($arr as $key => $value) {
        if($key == 'firstname'){
            $firstname = $arr['firstname'];
        }
        if (is_array($value)) {
//                echo "printed ".$value;
                $target_file = saveFile($value, $firstname);
            foreach ($value as $key => $value) {
                if ($key == 'name') {
                    echo "$key = $value\n";
                } elseif ($key == 'type') {
                    echo "$key = $value\n";
                } elseif ($key == 'size') {
                    echo "$key = $value\n";
                }
            }
            //sätt nytt namn
        } else {
            echo "$key = $value\n";
        }
    }
        return $target_file;
}

function saveFile($file, $firstname){
    $time = $_SERVER['REQUEST_TIME'];
    $date = date('Ymd_His_', $time);
    $prepname = preg_replace("/[^a-zA-Z]/", "", $firstname);
    $file['name'] = $date .$prepname."_" . $file['name'];
    
    $target_dir = "messages/attachments/";
    $target_file = $target_dir . basename($file['name']);
    move_uploaded_file($file['tmp_name'], $target_file);
    $file['tmp_name'] = $target_file;
    return $target_file;
}

function messageLogger($arr) {
    $time = $_SERVER['REQUEST_TIME'];
    $logObj = new stdClass();
    $logObj->date = date('Y:m:d H:i:s', $time);
    $logObj->browser = getenv('HTTP_USER_AGENT');
    $logObj->ip = getenv('REMOTE_ADDR');
    $logObj->message = $arr;
    $logJObj = json_encode($logObj);
    $log = fopen("messages/messages.log", "a+") or die("Unable access logfile!");
    if (flock($log, LOCK_EX)) {
        fwrite($log, $logJObj . "\n");
        fflush($log);
        flock($log, LOCK_UN);
    } else {
        echo "File busy. Please report to admin.";
    }
    fclose($log);
}
