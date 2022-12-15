<?php


/**
$rawData = file_get_contents("php://input");
$json_string = $rawData. json_encode(apache_request_headers()).json_encode($_REQUEST);
$file_handle = fopen(time().'my_filename.json', 'w');
fwrite($file_handle, $json_string);
fclose($file_handle);
**/

// Add custom Theme Functions here