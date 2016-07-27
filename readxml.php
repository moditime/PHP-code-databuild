
<!DOCTYPE html>
<html>
<head>

   <script type='text/javascript' src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.min.js'></script>
  <script type='text/javascript' src='js/jquery.msgBox.js'></script>
  <link rel="stylesheet" href="css/msgBoxLight.css" />

<style>

fieldset { border:200px solid #4682B4 }




</style>

</head>

<body>


<?php

include('config.php');


$response='<?xml version="1.0" encoding="UTF-8"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
   <S:Body>
      <ns2:rewardSubscriber_CallbackResponse xmlns:ns2="http://epic.ws.soa.cellc.co.za/">
         <return>
            <process>
               <ssUID>204</ssUID>
               <asqUID>1320782</asqUID>
               <sessionId>sessiongoeshere</sessionId>
               <username>yy</username>
               <password>yy</password>
               <asCode>Reward</asCode>
               <aspCode>RewardServiceCallback</aspCode>
               <consumerRef>123456789</consumerRef>
               <data>yy</data>
               <endTime>yy</endTime>
               <entityCode>AS-Success</entityCode>
               <note>yy</note>
               <request>yy</request>
               <response>yy</response>
               <severity>yy</severity>
               <startTime>yy</startTime>
               <providerId>0</providerId>
               <state>0</state>
            </process>
            <reward>
               <channel>CLIENT</channel>
               <errorCode>8</errorCode>
               <errorDescripion>NONE</errorDescripion>
               <errorMessage>NONE</errorMessage>
               <msisdn>0740039210</msisdn>
               <operation>POS.VAS.XX.50MB</operation>
               <orderId>0</orderId>
               <result>Your request has been received and will be processed shortly.</result>
               <rewardType>ADJUST</rewardType>
               <status>200</status>
            </reward>
         </return>
      </ns2:rewardSubscriber_CallbackResponse>
   </S:Body>
</S:Envelope>';

$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($response);


//-------------------------------------------- Extracting The Process note values ---------------------------------------

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

//

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
$endTimevalue = $dom->getElementsByTagName('endTime');
foreach ($endTimevalue as $endTime) {
    $endTime_v= $endTime->nodeValue;

}

$ssUID_v='205';
if($ssUID_v=='204')
{
	echo '<p align="right"><fieldset align="center" style="font-family:verdana;width:900px"><h3>Agent Report Notification <br><br><img src="images/failed.png" alt="Smiley face" width="42" height="42"><br><br>Status : Business Error</h3><big><big>This process has failed due to a business rule being broken (Re-try)<br><br></big></big></fieldset></p>';

}else if($ssUID_v=='200'){

echo '<p><fieldset align="center"; style="font-family:verdana;width:900px"><h3>Agent Report Notification <br><br><img src="images/pass.jpg" alt="Smiley face" width="42" height="42"><br><br>Status : Queued</h3><big><big>Process to be executed <br><br></big></big></fieldset></p>';

}else if($ssUID_v=='203'){

echo '<p><fieldset align="center"; style="font-family:verdana;width:900px"><h3>Agent Report Notification <br><br><img src="images/failed.png" alt="Smiley face" width="42" height="42"><br><br>Status : Errored</h3><big><big>System errored process has failed due to a systemic problem <br><br></big></big></fieldset></p>';

}
else if($ssUID_v=='202'){

echo '<p><fieldset align="center"; style="font-family:verdana;width:900px"><h3>Agent Report Notification <br><br><img src="images/pass.jpg" alt="Smiley face" width="42" height="42"><br><br>Status :Done</h3><big><big>Completed<br><br></big></big></fieldset></p>';

}
else if($ssUID_v=='207'){

echo '<p><fieldset align="center"; style="font-family:verdana;width:900px"><h3>Agent Report Notification <br><br><img src="images/timeout.png" alt="Smiley face" width="42" height="42"><br><br>Status :Authentication Timeout</h3><big><big>The process has run beyond allowable threshold of 120 seconds<br><br></big></big></fieldset></p>';

}else{


echo '<p><fieldset align="center"; style="font-family:verdana;width:900px"><h3>Agent Report Notification <br><br><img src="images/timeout.png" alt="Smiley face" width="42" height="42"><br><br>Status : Timeout</h3><big><big>The process has run beyond allowable threshold of 120 seconds<br><br></big></big></fieldset></p>';


}




/*

//-----------------------------------------  pushing data to Database   -----------------------
	
	
	try
	{
		$conn= new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	
	
	$sql2 = "INSERT INTO asterisk.cellcws_Results(ID,sessionId,asCode,aspCode,consumerRef,msisdn,operation,errorCode,errorDescripion,errorMessage,orderId,result,status,startTime,endTime)
    VALUES ('','$sessionId_v','$ssUID_v','$asqUID_v','$consumerRef_v','$msisdn_v','$operation_v','$errorCode_v','$errorDescripion_v','$errorMessage_v','$orderId_v','$result_v','$status_v','$startTime_v','$endTime_v')";	
		
	
	$conn->exec($sql2);
	echo 'Submitted';	
		
    }
catch(PDOException $e)
    {
		
		echo $sql2 . "<br>" . $e->getMessage();
		
	}
	*/
//----------------------------------------------------------------------------------------------	














?>



<script>

		
		msgBoxImagePath = "images/";
		function showMsgBox() {
			$.msgBox({
				title: "Request Information",
				content: "This process has failed due to a business rule being broken",
				type: "info"
			});
		}
		




</script>
</html>
</body>