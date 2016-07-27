<?php

$soapUrl = "https://prov.cellc.co.za/WS_4403_EPIC-4.0/RewardService_Callback";
$request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:epic="http://epic.ws.soa.cellc.co.za/">
<soapenv:Header/>
<soapenv:Body>
<epic:rewardSubscriber_Callback>
<!--Optional:-->
<RewardSubscriberInput>
<!--Optional:-->
<process>
<sessionId>NnAgaWvTJj9VOcs69EzFO7rYwdtMKVEI</sessionId>
<asCode>EPIC</asCode>
<aspCode>RewardService</aspCode>
<!--Optional:-->
<consumerRef>testing123</consumerRef>
</process>
<!--Optional:-->
<reward>
<!--Optional:-->
<callbackReference>test123</callbackReference>
<!--Optional:-->
<channel>GetMore</channel>
<errorCode/>
<!--Optional:-->
<errorDescripion/>
<!--Optional:-->
<errorMessage/>
<!--Optional:-->
<msisdn>0618194487</msisdn>
<!--Optional:-->
<operation>VPPPRCGM_DEL</operation>
<!--Optional:-->
<result/>
<!--Optional:-->
<rewardType/>
<status/>
</reward>
</RewardSubscriberInput>
</epic:rewardSubscriber_Callback>
</soapenv:Body>
</soapenv:Envelope>';

$response = getResponse($soapUrl, $request);



$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($response); //=====$data has the raw xml data...you want to format

$dom2 = new DOMDocument;
$dom2->preserveWhiteSpace = false;
$dom2->formatOutput = true;
$dom2->loadXML($request); //=====$data has the raw xml data...you want to format

echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';

echo "<h2>Request to CellC</h2>";

echo "<br/> <pre class=\"prettyprint\" >". htmlentities($dom2->saveXML())."</pre>";

echo "<h2>Response from CellC</h2>";

echo "<br/> <pre class=\"prettyprint\" >". htmlentities($dom->saveXML())."</pre>";

function getResponse($soapUrl, $request) {             
    // xml post structure
    $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>' . $request;

 $username='soatest';
 $password='soatest';

$auth='c29hdGVzdDpzb2F0ZXN0';

  // $auth = base64_encode($username . ':' . $password);
//echo 'Auth'.$auth;
//$headers[] = 'Authorization: Basic ' .$auth;//SOAPAction: your op URL  

 $headers = array("Authorization: Basic ". $auth,
                        "Content-Type: text/xml");   

    // PHP cURL  for https connection with auth
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, $soapUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$xml_post_string); // the SOAP request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // converting
    $response = curl_exec($ch); 
    #curl_close($ch);

    return $response;
}

//--------------------------------------------------- Call back ------------------------------------------------------------------------








