<?php
require_once 'core/Admin.php';
$admin = new Admin();
function getCost()
{
$targetTime = 0.05;
$cost = 8;
do {
	$cost++;
	$start = microtime(true);
	password_hash("testslakdf9034usdf",PASSWORD_BCRYPT,["cost" => $cost]);
	$end = microtime(true);
}
while(($end - $start) < $targetTime);

return $cost;
}

function generateKey($cost)
{
	$options = ['cost' => $cost,'salt' => sha1(md5(time()."lskdjf3949823\date(f)")),];
	$rawstring = (md5("jkyugjhggytyrfyrcv").sha1("skdf9wn,snf,msiie"));
	$key = password_hash($rawstring,PASSWORD_BCRYPT,$options);
	return $key;
}
$key = substr(generateKey(getCost()),7);

if(isset($_GET['cd']) && $_GET['cd'] == "add")
{
print_r($admin->addAuthKey($key));
}
elseif(isset($_GET['cd']) && $_GET['cd'] == 'get' && isset($_GET['pd']) && $_GET['pd'] == "berko")
{
echo ($admin->getAuthKey() !== false)?$admin->getAuthKey()->auth_key:"NULL";
}
else
{
	exit("Unauthorised Access");
}

?>