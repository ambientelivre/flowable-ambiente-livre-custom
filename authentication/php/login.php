<?php

// debug PHP 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// variables to integration - Alter for your server and users or custom code.
$urlFlowableIDM = 'http://localhost:8080/flowable-idm/app/authentication';
$flowableRedirect = 'http://localhost:8080/flowable-task/workflow/#/tasks';  // Url open last login
$flowableDomain = 'localhost'; // Domain your Flowable
$flowableUserName = 'admin';   // Your user in Flowable
$flowablePassword = 'test';    // Your password in flowable.

// parameters for POST Login
$params= array();
$params["j_username"] = $flowableUserName;
$params["j_password"] = $flowablePassword;
$params["_spring_security_remember_me"] = true;
$params["submit"] = 'Login';

$ch = curl_init($urlFlowableIDM);
curl_setopt ($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_POSTFIELDS,$params);
$result = curl_exec ($ch);

// Set Cookies for other URL (Flowable)
list($header, $body) = explode("\r\n\r\n", $result, 2);
preg_match_all('/Set-Cookie: (.*?);/is', $header, $Cookies);
foreach ($Cookies[1] as $chave => $valor){
    $novoCookie = explode('=', $valor);
	setcookie($novoCookie[0], $novoCookie[1], 0, '/', $flowableDomain, true);
}

// Debug cookies Variables  ans result of POST for flowable
//var_dump($Cookies);
//var_dump($result);
curl_close ($ch);

header('Location: ' . $flowableRedirect); 

?>
