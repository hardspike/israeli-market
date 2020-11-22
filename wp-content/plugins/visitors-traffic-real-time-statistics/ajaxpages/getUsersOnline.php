<?php ob_start(); session_start();
header ('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate"); 

require_once('../../../../wp-load.php');
$ahcfree_countOnlineusers = ahcfree_countOnlineusers();
if(strlen($ahcfree_countOnlineusers) > 10)
{
echo 'x';	
}else{
echo $ahcfree_countOnlineusers;	
}
?>