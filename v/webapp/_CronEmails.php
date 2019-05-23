<?php
include("functions.php");

if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php")) {
    include($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php");
} else {
    $path = substr(__DIR__, 0, -9);
    include($path."/config/MailSettings.php");
}

$queues = pendingEmailQueues($connection);
foreach ($queues as $key => $queue) {
   $result = SendMail($queue['staff_email'], $queue['staff_name'], $queue['title'], $queue['message']);
    if($result) {
        $time = time();
        updateQueue($connection, $queue['id'], '1', date("Y-m-d h:i:s", $time));
    }else{
        updateFailedQueue($connection, $queue['id'], $result);
    }

}

?>