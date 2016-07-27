<?php
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>aspCode</th><th>ospeqUID</th><th>consumerRef</th><th>response</th><th>ssUID</th><th>msisdn</th><th>newMSISDN</th><th>subscriberID</th><th>subcriberType</th><th>subscriberProductID</th><th>subscriberPlanID</th><th>channel</th><th>transactionType</th><th>requestTime</th><th>eventNote</th><th>externalEventCode</th><th>log_date</th></tr>";

class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() { 
        echo "<tr>"; 
    } 

    function endChildren() { 
        echo "</tr>" . "\n";
    } 
} 

$servername = "192.168.0.246";
$username = "cron";
$password = "1234";
$dbname = "asterisk";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM cellcws_Callbacks ORDER BY ID DESC"); 
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
        echo $v;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
?>