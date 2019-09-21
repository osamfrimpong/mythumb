<?php
session_start();
date_default_timezone_set("Africa/Accra");
$root = dirname($_SERVER["SCRIPT_NAME"])."/";
if(!empty($_SESSION) && array_key_exists('user_data', $_SESSION) && array_key_exists('thumb_my_berko', $_SESSION) && $_SESSION['thumb_my_berko'] === md5("okreb_ym_bmuht"))
{

require_once '../core/Controller.php';
require_once '../core/Aspirants.php';
require_once '../core/Elections.php';
require_once '../core/Admin.php';
require_once '../core/Config.php';
require_once '../core/Results.php';

$electionClass = new Elections();
$controller = new Controller();
$adminClass = new Admin();
$aspirantClass = new Aspirants();
$resultsClass = new Results();

$link = $adminClass->getLink();
$user_data = $_SESSION['user_data'];
$elections = ($electionClass->getElections($user_data->account_id) != false)?$electionClass->getElections($user_data->account_id):"";
$all_voters = ($controller->getAllVoters($user_data->account_id) != false)?$controller->getAllVoters($user_data->account_id):"";
$election_officers = ($adminClass->getElectionOfficers($user_data->account_id) != false)?$adminClass->getElectionOfficers($user_data->account_id):"";


function prepare($text)
{
  return addslashes(trim($text));
}

function isActive($pageUrl)
{
$arr = explode("/", $_SERVER['SCRIPT_NAME']);
$num = count($arr) - 1;
if($arr[$num] == $pageUrl)
{
  return "active";
}
}

function getNumbers($input)
{
  return (is_array($input))?count($input):1;
}

   function showAdmin($input,$userRole)
   {return ($userRole == 0)?$input:"";}

   function doRedirect($location)
   {
    header("Location: ".$location);
   }
}
else
{
  exit("Unauthorised Access!");
}
?>

