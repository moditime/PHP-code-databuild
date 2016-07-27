<?php

$soapUrl = "https://prov.cellc.co.za/WS_4403_EPIC-4.0/SubscriberService";
$request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:epic="http://epic.ws.soa.cellc.co.za/">
   <soapenv:Header/>
   <soapenv:Body>
      <epic:getSubscriberProfile>
         <GetSubscriberProfileInput>
            <process>
               <ssUID></ssUID>
               <asqUID></asqUID>
               <sessionId>NnAgaWvTJj9VOcs69EzFO7rYwdtMKVEI</sessionId>
               <asCode>EPIC</asCode>
               <aspCode>RewardService</aspCode>
               <consumerRef>dc8a6cdf-9b70-4d05-a274-0b4aff81ac62</consumerRef>
               <data></data>
               <endTime></endTime>
               <entityCode></entityCode>
               <note></note>
               <request></request>
               <response></response>
               <severity></severity>
               <startTime></startTime>
               <providerId></providerId>
               <state></state>
            </process>
            <subscriberInput>
               <ICCID></ICCID>
               <IMSI></IMSI>
               <MSISDN>0845874787</MSISDN>
               <SIM></SIM>
            </subscriberInput>
         </GetSubscriberProfileInput>
      </epic:getSubscriberProfile>
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

echo "<h2>Request to Cell C</h2>";

echo "<br/> <pre class=\"prettyprint\" >". htmlentities($dom2->saveXML())."</pre>";

echo "<h2>Response from Cell C</h2>";

echo "<br/> <pre class=\"prettyprint\" >". htmlentities($dom->saveXML())."</pre>";

function getResponse($soapUrl, $request) {             
    // xml post structure
    $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>' . $request;



    $username='soatest';
    $password='soatest';

    $auth = c29hdGVzdDpzb2F0ZXN0;

//base64_encode($username . ':' .$password);


//$headers[] = 'Authorization: Basic ' . $auth; 


     $headers = array(
                        "Authorization: Basic ". $auth,
                        "Content-Type: text/xml",   
                    );  
                

    // PHP cURL  for https connection with auth
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $soapUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    

    // added by Cobus
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    // converting
    $response = curl_exec($ch); 
    $err = curl_error($ch);

    #curl_close($ch);

    return $response; 
}




?>