<?php

require_once 'functions.php';
require_once 'config.php';

// read incoming data
$content = file_get_contents('php://input');
$jsondata = json_decode($content, true);
// get user data such as first name, text message etc.
$userData = getUserData($jsondata);
check($userData['first_name'], $jsondata);

//process
$reply = urlencode(query($userData));

// send reply
sendMessage($userData['chat_id'], $reply);

function check($chatID, $update){
  $file = 'log.txt';
  $updateArray = print_r($update, true);
  $fh = fopen($file, 'a') or die ('cant open log file');
  fwrite($fh, $chatID."\n\n");
  fwrite($fh, $updateArray."\n\n\n\n");
  fclose($fh);
}

?>
