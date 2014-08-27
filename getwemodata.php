<?php

require_once 'wemocommon.inc.php';

if (isset($_GET['ipaddress']) && isset($_GET['url']))
{
    $Port = 49153;

    $opts = array('http' =>
        array(
            'method'  => 'GET',
            'timeout' => 1
        )
    );
    $context  = stream_context_create($opts);

	$Path = "http://" . $_GET['ipaddress'] . ":" . $Port . $_GET['url'];

	$ch = curl_init($Path);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_exec($ch);

  	# get the content type
  	$MimeType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    // get the file content and suppress errors, we will check $contents for errors instead
    $contents = @file_get_contents($Path, false, $context);

	if ($contents !== false)
	{
		if (!preg_match("/^text/", $MimeType))
		{
			echo json_encode(array("result" => "success", "data" => base64_encode($contents), "mimetype" => $MimeType));
		}
		else
		{
			echo json_encode(array("result" => "success", "data" => $contents, "mimetype" => $MimeType));
		}
	}
	else
	{
		 echo json_encode(array("result" => "error", "description" => "Data could not be retrieved"));
	}
}
else
{
	echo json_encode(array("result" => "error", "description" => "No ip address or url given"));
}


