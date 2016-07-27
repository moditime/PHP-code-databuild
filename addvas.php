<?php

//------------------------------------------------Time Stamp-------------------------------------
$today=date("Y-m-d\TH:m");

//------------------------------------- Connection script---------------------------------------
include('config.php');

$mobile_no=$_POST["postmsisdn"];
$leadid=$_POST["postref"];
$email=$_POST["postemail"];
$idnumber=$_POST["postidnumber"];
$agentid=$_POST["postagentid"];

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
<consumerRef>'.$leadid.'</consumerRef>
</process>
<!--Optional:-->
<reward>
<!--Optional:-->
<callbackReference>'.$leadid.'</callbackReference>
<!--Optional:-->
<channel>GetMore</channel>
<errorCode/>
<!--Optional:-->
<errorDescripion/>
<!--Optional:-->
<errorMessage/>
<!--Optional:-->
<msisdn>'.$mobile_no.'</msisdn>
<!--Optional:-->
<operation>VPPPRCGM</operation>
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

//--------------------------------------- Get the note values of the XML returned for adding a vas --------------
// ssUID
$ssUIDvalue = $dom->getElementsByTagName('ssUID');
foreach ($ssUIDvalue as $ssUID) {
    $ssUID_v= $ssUID->nodeValue;
}

//asqUID
$asqUIDvalue = $dom->getElementsByTagName('asqUID');
foreach ($asqUIDvalue as $asqUID) {
    $asqUID_v= $asqUID->nodeValue;
}

//sessionId
$sessionIdvalue = $dom->getElementsByTagName('sessionId');
foreach ($sessionIdvalue as $sessionId) {
    $sessionId_v= $sessionId->nodeValue;
}

//consumerRef
$consumerRefvalue = $dom->getElementsByTagName('consumerRef');
foreach ($consumerRefvalue as $consumerRef) {
    $consumerRef_v= $consumerRef->nodeValue;
}

//msisdn
$msisdnvalue = $dom->getElementsByTagName('msisdn');
foreach ($msisdnvalue as $msisdn) {
    $msisdn_v= $msisdn->nodeValue;
}

//operation
$operationvalue = $dom->getElementsByTagName('operation');
foreach ($operationvalue as $operation) {
    $operation_v= $operation->nodeValue;
}

//errorCode
$errorCodevalue = $dom->getElementsByTagName('errorCode');
foreach ($errorCodevalue as $errorCode) {
    $errorCode_v= $errorCode->nodeValue;
}

//errorDescripion
$errorDescripionvalue = $dom->getElementsByTagName('errorDescripion');
foreach ($errorDescripionvalue as $errorDescripion) {
    $errorDescripion_v= $errorDescripion->nodeValue;
}

//errorMessage
$errorMessagevalue = $dom->getElementsByTagName('errorMessage');
foreach ($errorMessagevalue as $errorMessage) {
    $errorMessage_v= $errorMessage->nodeValue;
}

//orderId
$orderIdvalue = $dom->getElementsByTagName('orderId');
foreach ($orderIdvalue as $orderId) {
    $orderId_v= $orderId->nodeValue;
}

//result
$resultvalue = $dom->getElementsByTagName('result');
foreach ($resultvalue as $result) {
    $result_v= $result->nodeValue;
}

//status
$statusvalue = $dom->getElementsByTagName('status');
foreach ($statusvalue as $status) {
    $status_v= $status->nodeValue;
}

//startTime
$startTimevalue = $dom->getElementsByTagName('startTime');
foreach ($startTimevalue as $startTime) {
    $startTime_v= $startTime->nodeValue;
}

//endTime
$startTimevalue = $dom->getElementsByTagName('endTime');
foreach ($endTimevalue as $endTime) {
    $endTime_v= $endTime->nodeValue;
}

//entityCode
$entityCodevalue = $dom->getElementsByTagName('entityCode');
foreach ($entityCodevalue as $entityCode) {
    $entityCode_v= $entityCode->nodeValue;
}


//-----------------------------------------  pushing data to Database for adding a VAS  --------------------------------------------------------------------------
	
	try
	{
		$conn= new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql2 = "INSERT INTO asterisk.cellcws_Results(ID,sessionId,asCode,aspCode,consumerRef,msisdn,operation,errorCode,errorDescripion,errorMessage,orderId,result,status,startTime,endTime,agentID,email,IDNumber)
						VALUES ('','$sessionId_v','$ssUID_v','$asqUID_v','$consumerRef_v','$msisdn_v','$operation_v','$errorCode_v','$errorDescripion_v','$errorMessage_v','$orderId_v','$result_v','$status_v','$startTime_v','$endTime_v','$agentid','$email','$idnumber')";	
		
	$conn->exec($sql2);	
    }
catch(PDOException $e)
    {
		echo $sql2 . "<br>" . $e->getMessage();
	}

if (empty($ssUID_v)){
	$errorDescripion_v='CellC Authentication service not running - your action will not take place. Capture offline and retry later.';
}


echo $entityCode_v . " : " . $result_v . " : Errors: " . $errorDescripion_v;

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


?>