<?
$email=base64_decode($_GET[email]);
if($email) echo "<meta http-equiv=refresh content='0;url=mailto:$email'> ";
?>