<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_NewPA = "192.168.0.4";
$database_NewPA = "ABSA";
$username_NewPA = "MyPAWeb";
$password_NewPA = "c43R79:9d5!4z,T";
$NewPA = mysql_pconnect($hostname_NewPA, $username_NewPA, $password_NewPA) or trigger_error(mysql_error(),E_USER_ERROR); 
?>