<?php
header('Content-type: text/plain');
$maxSize = 1500000;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $request = $_REQUEST;
    $logArr = $request;
    $attach = NULL;
    if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
        $disallow = array('text/x-php', 'text/html');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        if (in_array($mime, $disallow)) {
            echo "Kan inte bifoga filer av filtyp '" . $mime ."'.\n";
        } else {
            $checkSize = filesize($_FILES['file']['tmp_name']);
            if ($checkSize !== false) {
                $attach = $_FILES["file"];
                array_push($logArr, $attach);
            }
            finfo_close($finfo);
        }
    }
    $size = count($request) + $attach['size'];
    if ($size < $maxSize) {
        $file_path = printArr($logArr);
        if($file_path != NULL)
            $logArr[0]['tmp_name'] = $file_path;
        messageLogger($logArr);
    } else {
        echo "Meddelandet för stort. Max i storlek är " . $maxSize;
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
