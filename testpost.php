<?php
$soapstring = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.callback.vasx.com/">
   <soapenv:Header/>
   <soapenv:Body>
      <ws:Notification>
         <processParameters>
            <aspCode>EventNotification</aspCode>
            <ospeqUID>376270</ospeqUID>
            <consumerRef>741224150171120151513432</consumerRef>
            <note/>
            <response>Success</response>
            <ssUID>202</ssUID>
         </processParameters>
         <processDetails>
            <msisdn>0744515202</msisdn>
            <newMSISDN>0744515202</newMSISDN>
            <subscriberID>2001231231</subscriberID>
            <subcriberType>Postpaid</subcriberType>
            <subscriberProductID>AllWeek</subscriberProductID>
            <subscriberPlanID>AllWeek100</subscriberPlanID>
            <channel>Partner X</channel>
            <transactionType>Activation</transactionType>
            <channel>Partner X</channel>
            <requestTime>2015/11/17 05:03:30 PM</requestTime>
            <!--Optional:-->
            <eventNote>Lost / Stolen</eventNote>
            <eventNote>non-payment</eventNote>
            <externalEventCode>SignUp001</externalEventCode>
            <!--Optional:-->
         </processDetails>
      </ws:Notification>
   </soapenv:Body>
</soapenv:Envelope>';

$dom2 = new DOMDocument;
$dom2->preserveWhiteSpace = false;
$dom2->formatOutput = true;
$dom2->loadXML($soapstring); 

$soapUrl = "http://192.168.0.90/reward_service/return.php";
$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>' . $soapstring;


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, $soapUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // converting
    $response = curl_exec($ch); 
    #curl_close($ch);

    echo $response;


?>