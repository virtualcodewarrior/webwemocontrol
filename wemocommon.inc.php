<?php

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, content-type, accept, authorization, authentication');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if (isset($_SERVER['HTTP_ORIGIN']) && preg_match("/http:\/\/[^.]*\.vicowa\.com/", $_SERVER['HTTP_ORIGIN']))
{
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
    header( "HTTP/1.1 200 OK" );
    exit();
}

#require_once "/var/www/authorization/authorize.inc.php"



