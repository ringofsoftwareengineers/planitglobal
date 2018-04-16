<?php
$google_url="https://www.google.com/recaptcha/api/siteverify";
$secret="6LeSSkIUAAAAAF15m3lH3SH5FnZ5-tunWXp4B85m";

if(!isset($_SERVER["REMOTE_ADDR"]) || !isset($_POST["g-recaptcha-response"]))
{
    $resp = false;
}  else {
    
$remoteip=$_SERVER["REMOTE_ADDR"];
$response=$_POST["g-recaptcha-response"];

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here

    
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $google_url,
    CURLOPT_USERAGENT => 'Planit Verification Request',
    CURLOPT_POST => 1,    
    CURLOPT_POSTFIELDS => array(
        'secret' => $secret,
        'response' => $response,
        'remoteip'=>$remoteip
    )
));
// Send the request & save response to $resp
$resp = curl_exec($curl);



// Close request to clear up some resources
curl_close($curl);    
    
}
