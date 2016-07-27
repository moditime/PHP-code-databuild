<?php

//------------------------------------------------Time Stamp-------------------------------------
$today=date("Y-m-d\TH:m");

//------------------------------------- Connection script---------------------------------------
include('config.php');

$mobile_no=$_POST["postmsisdn"];
$leadid=$_POST["postref"];
$agentid=$_POST["postagentid"];

//------------------------------------------------ CallBack Service for Removing a VAS----------------------------
$soapUrl_remvas = "https://prov.cellc.co.za/WS_4403_EPIC-4.0/RewardService_Callback";
$request_remvas = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:epic="http://epic.ws.soa.cellc.co.za/">
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

$response_remvas =  getResponse_remvas($soapUrl_remvas,$request_remvas);

//echo 'Done Removing a Vas';


$dom5 = new DOMDocument;
$dom5->preserveWhiteSpace = false;
$dom5->formatOutput = true;
$dom5->loadXML($response_remvas); //=====$data has the raw xml data...you want to format

$dom6 = new DOMDocument;
$dom6->preserveWhiteSpace = false;
$dom6->formatOutput = true;
$dom6->loadXML($request_remvas); //=====$data has the raw xml data...you want to format


//--------------------------------------- Get the note values of the XML returned for Removing  a vas --------------
// ssUID
$ssUIDvalue = $dom5->getElementsByTagName('ssUID');
foreach ($ssUIDvalue as $ssUID) {
    $ssUID_v= $ssUID->nodeValue;
}

//asqUID
$asqUIDvalue = $dom5->getElementsByTagName('asqUID');
foreach ($asqUIDvalue as $asqUID) {
    $asqUID_rv= $asqUID->nodeValue;
}

//sessionId
$sessionIdvalue = $dom5->getElementsByTagName('sessionId');
foreach ($sessionIdvalue as $sessionId) {
    $sessionId_rv= $sessionId->nodeValue;
}

//consumerRef
$consumerRefvalue = $dom5->getElementsByTagName('consumerRef');
foreach ($consumerRefvalue as $consumerRef) {
    $consumerRef_rv= $consumerRef->nodeValue;
}

//msisdn
$msisdnvalue = $dom5->getElementsByTagName('msisdn');
foreach ($msisdnvalue as $msisdn) {
    $msisdn_rv= $msisdn->nodeValue;
}

//operation
$operationvalue = $dom5->getElementsByTagName('operation');
foreach ($operationvalue as $operation) {
    $operation_rv= $operation->nodeValue;
}

//errorCode
$errorCodevalue = $dom5->getElementsByTagName('errorCode');
foreach ($errorCodevalue as $errorCode) {
    $errorCode_rv= $errorCode->nodeValue;
}

//errorDescripion
$errorDescripionvalue = $dom5->getElementsByTagName('errorDescripion');
foreach ($errorDescripionvalue as $errorDescripion) {
    $errorDescripion_rv= $errorDescripion->nodeValue;
}

//errorMessage
$errorMessagevalue = $dom5->getElementsByTagName('errorMessage');
foreach ($errorMessagevalue as $errorMessage) {
    $errorMessage_rv= $errorMessage->nodeValue;
}

//orderId
$orderIdvalue = $dom5->getElementsByTagName('orderId');
foreach ($orderIdvalue as $orderId) {
    $orderId_rv= $orderId->nodeValue;
}

//result
$resultvalue = $dom5->getElementsByTagName('result');
foreach ($resultvalue as $result) {
    $result_rv= $result->nodeValue;
}

//status
$statusvalue = $dom5->getElementsByTagName('status');
foreach ($statusvalue as $status) {
    $status_rv= $status->nodeValue;
}

//startTime
$startTimevalue = $dom5->getElementsByTagName('startTime');
foreach ($startTimevalue as $startTime) {
    $startTime_rv= $startTime->nodeValue;
}

//endTime
$endTimevalue = $dom5->getElementsByTagName('endTime');
foreach ($endTimevalue as $endTime) {
    $endTime_rv= $endTime->nodeValue;
}

//entityCode
$entityCodevalue = $dom5->getElementsByTagName('entityCode');
foreach ($entityCodevalue as $entityCode) {
    $entityCode_rv= $entityCode->nodeValue;
}



//-----------------------------------------  pushing data to Database for removing  a VAS  --------------------------------------------------------------------------
	
	try
	{
		$conn= new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	
	$sql2 = "INSERT INTO asterisk.cellcws_Results(ID,sessionId,asCode,aspCode,consumerRef,msisdn,operation,errorCode,errorDescripion,errorMessage,orderId,result,status,startTime,endTime,agentID)
    VALUES ('','$sessionId_rv','$ssUID_v','$asqUID_rv','$consumerRef_rv','$msisdn_rv','$operation_rv','$errorCode_rv','$errorDescripion_rv','$errorMessage_rv','$orderId_rv','$result_rv','$status_rv','$startTime_rv','$endTime_rv','$agentid')";	
		
		
	$conn->exec($sql2);	
		
    }
catch(PDOException $e)
    {
		
		echo $sql2 . "<br>" . $e->getMessage();
		
	}

if (empty($ssUID_v)){
	$errorDescripion_rv='CellC Authentication service not running - your action will not take place. Capture offline and retry later.';
}

echo $entityCode_rv . " : " . $result_rv . " : Errors: " . $errorDescripion_rv;


function getResponse_remvas($soapUrl_remvas,$request_remvas) {             
    // xml post structure
$xml_post_string_rem = '<?xml version="1.0" encoding="utf-8"?>' . $request_remvas;

$username='soatest';
$password='soatest';

$auth='c29hdGVzdDpzb2F0ZXN0';

$auth = base64_encode($username . ':' . $password);
//echo 'Auth'.$auth;
//$headers[] = 'Authorization: Basic ' .$auth;//SOAPAction: your op URL  

 $headers = array("Authorization: Basic ". $auth,
                        "Content-Type: text/xml");   

    // PHP cURL  for https connection with auth
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL,$soapUrl_remvas);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$xml_post_string_rem); // the SOAP request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // converting
    $response_remvas = curl_exec($ch); 
    #curl_close($ch);

    return $response_remvas;


} //End of the removing vas function



?>