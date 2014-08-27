<?php

require_once 'wemoconfig.inc.php';
require_once 'wemocommon.inc.php';

function nmapAddresses($ip)
{
	$Parts = explode('.', $ip);
	$Parts[3] = 0;
	$result = array();
	exec("nmap -sn " . implode('.', $Parts) . "/24", $result);
	$addresses = array();

	foreach($result as $line)
	{
		$matches = array();
		if (preg_match("/\d+\.\d+\.\d+\.\d+/", $line, $matches) && count($matches) === 1)
		{
			array_push($addresses, $matches[0]);
		}
	}

	return $addresses;
}

function ScanAddresses($MyAddress)
{
	// build the list of possible addresses
	$addresses = nmapAddresses($MyAddress);

	$Port = 49153;
	$Devices = array();

	$opts = array('http' =>
		array(
			'method'  => 'GET',
		    'header'  => "Content-Type: text/xml\r\n",
		    'timeout' => 1
		)
	);
	$context  = stream_context_create($opts);

	// scan each of them to see if they are a wemo device
	foreach ($addresses as $address)
	{
		// get the file content and suppress errors, we will check $contents for errors instead
		$contents = @file_get_contents("http://" . $address . ":" . $Port . "/setup.xml", false, $context);

		if ($contents !== false)
		{
			array_push($Devices, array('ipaddress'=>$address, 'info'=>$contents));
		}
	}

	// return the list of wemo devices
	echo json_encode(array('result'=>'success', 'devices'=>$Devices));
}

ScanAddresses($_SERVER['SERVER_ADDR']);


