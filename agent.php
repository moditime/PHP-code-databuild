<?php 
//-------------------------------------------------- Get Values from Vicidial -------------------
$mobile_no=$_GET["MSISDN"];
$agentName=$_GET["agentname"];
$agentid=$_GET["agentid"];
$agentTeam=$_GET["agentteam"];
$leadid=$_GET["leadid"];
$idnumber=$_GET["idnumber"];
$email=$_GET["email"];

$idtest=$_GET["email"];
$idtest2=$_GET["idnumber"];

// This is for the redirect to GMDirect if we have issues
$phone_number=$_GET['phone_number'];
$title=$_GET['title'];
$first_name=$_GET['first_name'];
$middle_initial=$_GET['middle_initial'];
$last_name=$_GET['last_name'];
$address1=$_GET['address1'];
$address2=$_GET['address2'];
$address3=$_GET['address3'];
$city=$_GET['city'];
$postal_code=$_GET['postal_code'];
$gender=$_GET['gender'];
$date_of_birth=$_GET['date_of_birth'];
$alt_phone=$_GET['alt_phone'];
$email=$_GET['email'];
$vendor_lead_code=$_GET['vendor_lead_code'];
$recording_filename=$_GET['recording_filename'];
$phone_login=$_GET['phone_login'];
$campaign=$_GET['campaign'];
$owner=$_GET['owner'];

?>

<script type="text/javascript">
<!--------------------------------------------------------------    Calling add Vas method -------------------------------------------------------------------------------------------->
function addVas(){

	var msisdn = $('#msisdn').val();
	var ref = $('#leadid').val();
	var email = $('#email').val();
	var idnumber = $('#id_field').val()
	var agentid = $('#agentid').val()

	$.post('addvas.php', {postmsisdn:msisdn,postref:ref,postemail:email,postidnumber:idnumber,postagentid:agentid},
	function(data)
		{
		$('#resultsfromws').html=(data);
		document.getElementById("resultsfromws").innerHTML=data;
		});
}

<!--------------------------------------------------------------    Calling Remove Vas method -------------------------------------------------------------------------------------------->
function removeVas(){
	var msisdn = $('#msisdn').val();
	var ref = $('#leadid').val();
	var agentid = $('#agentid').val()

	$.post('removevas.php', {postmsisdn:msisdn,postref:ref,postagentid:agentid},
	function(data)
		{
		$('#resultsfromws').html=(data);
		document.getElementById("resultsfromws").innerHTML=data;
		});
}

<!--------------------------------------------------------------    Check if subscriber exists -------------------------------------------------------------------------------------------->
function start(){
	var msisdn = $('#msisdn').val();

	$.post('checksubscriber.php', {postmsisdn:msisdn},
	function(data)
		{
		$('#resultsfromws').html=(data);
		document.getElementById("resultsfromws").innerHTML=data;
		});
}
</script>


<!-----------------------------------------------------------------------------User interface  begining------------------------------------------------------------------------------------------------------>
<html>
<head>
	<title>Cell C GetMore</title>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="css/msgBoxLight.css" />
</head>

<body onload="start();">
<fieldset  style="background-color:#FFFFFF;" >
<div class="form-style-5" align='center'>

<p align="center"><img src="images/getmore.png" alt="Smiley face" align="middle" ><br><br>
<legend>
<input type="text" id="msisdn" name="field1" value="<?php echo $mobile_no; ?>" style="width:650px;height:50px;font-size:30px;text-align:center"  x placeholder="                     ">
<input type="hidden" id="leadid" name="leadid" value="<?php echo $leadid; ?>">
<input type="hidden" id="agentid" name="agentid" value="<?php echo $agentid; ?>">
</legend>
</p>


<form style="width: 700px">
	<legend><span class="number">1</span>&nbsp;&nbsp;&nbsp;<input type="text" name="email" id="email" value="<?php echo $idtest; ?>"  autofocus style="width: 350px;"  placeholder="                             Email "> &nbsp;&nbsp* &nbsp;&nbsp<img src="images/correct.png" id="correct" visible="false" style="visibility:hidden" alt="Smiley face" height="32" width="32"><img src="images/wrong2.png" id="wrong" visible="false" style="visibility:hidden" alt="Smiley face" height="32" width="32"><img src="images/lock.png" id="emailloc" visible="false" style="visibility:hidden" alt="Smiley face" height="32" width="32"></legend>
	<legend><span class="number">2</span>&nbsp;&nbsp;&nbsp;<input type="text" name="id_field" id="id_field" value="<?php echo $idtest2; ?>" style="width: 350px;"  placeholder="                             ID Number ">&nbsp;&nbsp* &nbsp;&nbsp<img src="images/correct.png" id="correctID" visible="false" style="visibility:hidden" alt="Smiley face" height="32" width="32"><img src="images/wrong2.png" id="wrongID" visible="false" style="visibility:hidden" alt="Smiley face" height="32" width="32"><img src="images/lock.png" id="idloc" visible="false" style="visibility:hidden" alt="Smiley face" height="32" width="32"></legend> 
	<div id="results">
		<font style="width:650px;height:50px;font-size:30px;text-align:center"  x placeholder="                     " id="resultsfromws"> Waiting for command </font>
	</div>
	<input type="button" value="Add Getmore" onclick="addVas();"/>
	<input type="button" value="Remove Getmore" onclick="removeVas();"/>
	<input type="button" value="GetMore Direct Sale" onclick="location.href='http://192.168.0.90/gmdirect/appform.php?<?php echo 'phone_number='.$phone_number.'&title='.$title.'&first_name='.$first_name.'&middle_initial='.$middle_initial.'&last_name='.$last_name.'&address1='.$address1.'&address2='.$address2.'&address3='.$address3.'&city='.$city.'&postal_code='.$postal_code.'&gender='.$gender.'&date_of_birth='.$date_of_birth.'&alt_phone='.$alt_phone.'&email='.$email.'&vendor_lead_code='.$vendor_lead_code.'&recording_filename='.$recording_filename.'&phone_login='.$phone_login.'&campaign=--'.$campaign.'&owner='.$owner ?>';"/>
</form>
</div>

</fieldset>


</body>
</html>