<?php
$mobile_no=$_POST["postmsisdn"];
//------------------------------------------------ SubscriberService-------------------------------------------------------------------------------------------------
$requestUrl = "https://prov.cellc.co.za/WS_4403_EPIC-4.0/SubscriberService";
$requestSub = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:epic="http://epic.ws.soa.cellc.co.za/">
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
               <MSISDN>'.$mobile_no.'</MSISDN>
               <SIM></SIM>
            </subscriberInput>
         </GetSubscriberProfileInput>
      </epic:getSubscriberProfile>
   </soapenv:Body>
</soapenv:Envelope>';

$response_sub = getSubscriber($requestUrl, $requestSub);

$dom4 = new DOMDocument;
$dom4->preserveWhiteSpace = false;
$dom4->formatOutput = true;
$dom4->loadXML($response_sub); //=====$data has the raw xml data...you want to format

//echo '<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>';
//echo "<h2>Subsciber</h2>";
//echo "<br/> <pre class=\"prettyprint\" >". htmlentities($dom4->saveXML())."</pre>";
// ssUID for Subscriber

$ssUIDvalue = $dom4->getElementsByTagName('ssUID');
foreach ($ssUIDvalue as $ssUID) {
    $ssUID_v2=$ssUID->nodeValue;
}

$prettymessage='CellC Authentication service not running - your action will not take place. Capture offline and retry later.';

switch ($ssUID_v2){
	case '200':
		$prettymessage='Queued';
		break;
	case '201':
		$prettymessage='Running';
		break;
	case '202':
		$prettymessage='Completed';
		break;
	case '203':
		$prettymessage='Errored';
		break;
	case '204':
		$prettymessage='';
				//Business Error - This subscriber cannot sign up for the Getmore program
		break;
	case '205':
		$prettymessage='Timeout - CellC webservices offline currently - your action will not take place. Capture offline and retry later.';
		break;
	case '206':
		$prettymessage='Waiting ';
		break;
	case '207':
		$prettymessage='Authentication Failed';
		break;
	case '208':
		$prettymessage='Callback Waiting';
		break;
	case '209':
		$prettymessage='Callback Complete';
		break;
}

echo $ssUID_v2 . ' : ' . $prettymessage;

//--------------------------------------- Get the note values of the Subscriber  XML returned(ssUID)--------------
function getSubscriber($requestUrl, $requestSub) {             
    // xml post structure
 $xml_post_subcriber = '<?xml version="1.0" encoding="utf-8"?>' . $requestSub;

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
    curl_setopt($ch, CURLOPT_URL, $requestUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$xml_post_subcriber); // the SOAP request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // converting
    $response_sub = curl_exec($ch); 
    #curl_close($ch);

    return $response_sub;
}


?>