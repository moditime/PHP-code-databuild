<?php
header('Access-Control-Allow-Origin: *');
include('config.php');

function getXmlValueByTag($inXmlset,$needle){ 
        $resource    =    xml_parser_create();//Create an XML parser 
        xml_parse_into_struct($resource, $inXmlset, $outArray);// Parse XML data into an array structure 
        xml_parser_free($resource);//Free an XML parser 
        
        for($i=0;$i<count($outArray);$i++){ 
            if($outArray[$i]['tag']==strtoupper($needle)){ 
                $tagValue    =    $outArray[$i]['value']; 
            } 
        } 
        return $tagValue; 
    } 

// Receive soap here
$callback_response = file_get_contents('php://input');
$dom3 = new DOMDocument;
$dom3->preserveWhiteSpace = false;
$dom3->formatOutput = true;
$dom3->loadXML($callback_response);
// end receive

// Logging to a file starts here..
$dom3->formatOutput = true;
$filename = "/tmp/" . date("Ymd_His");
$dom3->save($filename. ".xml");
// Logging ends here

// Capture to database
// First the raw XML for failsafe
$textstring = $dom3->saveXML();
try
{
	$conn= new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT INTO asterisk.receivedcellc(XML)
               VALUES ('$textstring')";	
	$conn->exec($sql);	
}
catch(PDOException $e)
{
	echo $sql . "<br>" . $e->getMessage();
}

// Then lets put the XML elements into the DB individually
$aspCode = getXmlValueByTag($callback_response,'aspCode');
$ospeqUID= getXmlValueByTag($callback_response,'ospeqUID');
$consumerRef = getXmlValueByTag($callback_response,'consumerRef');
$response = getXmlValueByTag($callback_response,'response');
$ssUID = getXmlValueByTag($callback_response,'ssUID'); 
$msisdn = getXmlValueByTag($callback_response,'msisdn'); 
$newMSISDN = getXmlValueByTag($callback_response,'newMSISDN');
$subscriberID = getXmlValueByTag($callback_response,'subscriberID');
$subcriberType = getXmlValueByTag($callback_response,'subcriberType');
$subscriberProductID = getXmlValueByTag($callback_response,'subscriberProductID'); 
$subscriberPlanID = getXmlValueByTag($callback_response,'subscriberPlanID'); 
$channel= getXmlValueByTag($callback_response,'channel'); 
$transactionType = getXmlValueByTag($callback_response,'transactionType'); 
$requestTime = getXmlValueByTag($callback_response,'requestTime'); 
$eventNote = getXmlValueByTag($callback_response,'eventNote'); 
$externalEventCode = getXmlValueByTag($callback_response,'externalEventCode');
try
{
	// Put the XML elements into the Callbacks table
	$connws= new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$connws->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT INTO 
                	asterisk.cellcws_Callbacks (aspCode, ospeqUID, consumerRef, response, ssUID, msisdn, newMSISDN, subscriberID, subcriberType, subscriberProductID, subscriberPlanID, channel, transactionType, requestTime, eventNote, externalEventCode) 
		 VALUES 
              	('$aspCode', '$ospeqUID', '$consumerRef', '$response', $ssUID, '$msisdn', '$newMSISDN', '$subscriberID', '$subcriberType', '$subscriberProductID', '$subscriberPlanID', '$channel', '$transactionType', '$requestTime', '$eventNote', '$externalEventCode')";	
	$connws->exec($sql);
 	$lastId = $connws->lastInsertId();
}
catch(PDOException $e)
{
	echo $sql . "<br>" . $e->getMessage();
}

// Now lets do some actual member updates
include('configplp.php');

// We only change telesave if the request is a success from CellC's side
// if ($externalEventCode=='VPPPRCGM_DEL'||$externalEventCode=='VPPPRCGM') {
  if ($ssUID=='202') {
    switch ($transactionType){
	case 'ProductUpgrade':
	case 'ADD':
		try
		{
			// Update or insert new member in Telesave DB
			$connts= new PDO("mysql:host=$tsdbhost;dbname=$tsdbname", $tsdbuser, $tsdbpass);
			$connts->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO 
                			Telesave.Members(Club, Member_No, Scheme, `Status`, Load_By, Load_Date, Start_Date) 
				 VALUES 
                			(145, '$msisdn', 0, 'LIVE', '$channel', DATE_ADD(NOW(), INTERVAL 2 HOUR), DATE_ADD(NOW(), INTERVAL 2 HOUR))
				 ON DUPLICATE KEY UPDATE Cancel_Date=NULL, `Status`='LIVE', Scheme=0;";	
			$connts->exec($sql);
 			$lastId = $connts->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
		break;
	case 'DEL':
		try
		{
			// Update or insert new member in Telesave DB
			$connts= new PDO("mysql:host=$tsdbhost;dbname=$tsdbname", $tsdbuser, $tsdbpass);
			$connts->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE  
                			Telesave.Members SET Members.`Status`='CANC', Members.`Cancel_Date`=DATE_ADD(NOW(), INTERVAL 2 HOUR)
				 WHERE Club=145 AND Member_No='$msisdn' AND Scheme=0;
                			";	
			$connts->exec($sql);
 			$lastId = $connts->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
		break;
	case 'ChangeTel':
		try
		{
			// Update or insert new member in Telesave DB
			$connts= new PDO("mysql:host=$tsdbhost;dbname=$tsdbname", $tsdbuser, $tsdbpass);
			$connts->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE 
                			Telesave.Members SET Member_No='$newMSISDN' 
				 WHERE Club=145 AND Member_No='$msisdn';";	
			$connts->exec($sql);
 			$lastId = $connts->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
		break;
	case 'StopSubsBT':
		try
		{
			// Update or insert new member in Telesave DB
			$connts= new PDO("mysql:host=$tsdbhost;dbname=$tsdbname", $tsdbuser, $tsdbpass);
			$connts->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE 
                			Telesave.Members SET `Status`='SUSP' 
				 WHERE Club=145 AND Member_No='$msisdn' AND `Status`='LIVE';";	
			$connts->exec($sql);
 			$lastId = $connts->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
		break;
	case 'OpenSubsBK':
		try
		{
			// Update or insert new member in Telesave DB
			$connts= new PDO("mysql:host=$tsdbhost;dbname=$tsdbname", $tsdbuser, $tsdbpass);
			$connts->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "UPDATE 
                			Telesave.Members SET `Status`='LIVE' 
				 WHERE Club=145 AND Member_No='$msisdn' AND `Status`='SUSP';";	
			$connts->exec($sql);
 			$lastId = $connts->lastInsertId();
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
		break;

	default:
		// need to put something in here if we pick up transactiontypes that don't match anything we know. 
    }
  }
//}
// End of db work

// format a static response
$return = new DOMDocument();
$return->loadXML('<?xml version="1.0" encoding="utf-8"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
   <S:Body>
      <ns2:NotificationResponse xmlns:ns2="http://ws.callback.vasx.com/">
         <return>202</return>
      </ns2:NotificationResponse>
   </S:Body>
</S:Envelope>');
echo $return->saveXML();
// end static response
?>
