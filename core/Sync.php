<?php
//require_once 'DbConnector.php';

class Sync
{

private $electionId;
private $accountId;
private $con;


function __construct($accountId,$electionId = NULL)
{
	$this->electionId = $electionId;
	$this->accountId = $accountId;
	$this->con = new mysqli("localhost","root","","evote") or die(mysqli_errno());
}

function updateLiveServer($url, array $post = NULL, array $options = array())
{
	$defaults = array( 
        CURLOPT_POST => 1, 
        CURLOPT_HEADER => 0, 
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 4, 
        CURLOPT_POSTFIELDS => http_build_query($post) 
    ); 
    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $result = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
}

function getBackupFile($source,$filename)
{

	header("Content-type: application/octet-stream");
 header("Content-Disposition: attachment; filename=mythumb_".$filename."_".$this->accountId."_".$this->electionId."_".time().".mt");
 header("Pragma: no-cache");
 header("Expires: 0");
 echo $source;
 exit;
}

function getFile($source,$filename)
{
	$fh = fopen('php://output','w');
	ob_start();
	fwrite($fh, $source);
	$string = ob_get_clean();

	header("Content-Type: application/mythumb");
 header("Content-Disposition: attachment; filename=mythumb_".$filename."_".$this->accountId."_".$this->electionId.".mt");
 header("Pragma: public");
 header("Expires: 0");
 header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
 header('Cache-Control: private',false);
 header('Content-Transfer-Encoding: binary');
 exit($string);

}

function exportTable($tablename)
{
	$finalString = "";

switch ($tablename) {
	case 'votes':
		$whatToSelect = "voter_id,portfolio_id,voted_for,account_id,election_id";
		$where = " WHERE `account_id` = '$this->accountId' AND `election_id` = '$this->electionId'";
		break;
	case 'voted':
		$whatToSelect = "voter_id,date_voted,election_id,account_id";
		$where = " WHERE `account_id` = '$this->accountId' AND  `election_id` = '$this->electionId'";
		break;
	case 'aspirants':
		$whatToSelect = "*";
		$where = " WHERE `account_id` = '$this->accountId' AND  `election_id` = '$this->electionId'";
		break;
	case 'portfolios':
		$whatToSelect = "*";
		$where = " WHERE `account_id` = '$this->accountId' AND  `election_id` = '$this->electionId'";
		break;
	case 'elections':
		$whatToSelect = "*";
		$where = " WHERE `account_id` = '$this->accountId' AND  `id` = '$this->electionId'";
		break;
	case 'election_officers':
		$whatToSelect = "*";
		$where = " WHERE `account_id` = '$this->accountId'";
		break;
	case 'users':
		$whatToSelect = "*";
		$where = " WHERE `account_id` = '$this->accountId'";
		break;
	default:
		$whatToSelect = "*";
		$where = "";
		break;
}
$sql = "SELECT $whatToSelect FROM `$tablename` $where;";
$result = $this->con->query($sql);
if($result)
{
	$numberOfFields = mysqli_num_fields($result);
	$numberOfRows = mysqli_num_rows($result);
	if($numberOfRows > 0)
	{
	//$finalString .=  $numberOfFields;
	$i = 0;
	$fields = mysqli_fetch_fields($result);
	$finalString .=  "INSERT IGNORE INTO `$tablename` (";
	foreach ($fields as $field)
	{
		$i++;
		$finalString .=  "`".$field->name."`";
		if($i < $numberOfFields)
		{
			$finalString .=  ",";
		}
		
	}

	$finalString .=  ") VALUES";
	$j = 0;
	
	while($rows = $result->fetch_row())
	{
		$j++;
		$finalString .=  "(";
		//print_r($rows);
		for ($ji = 0;$ji<$numberOfFields;$ji++) {
			
			
			$finalString .=  "'".$rows[$ji]."'";
			if($ji < $numberOfFields-1)
			{
				$finalString .=  ",";
			}
		}

		if($j < $numberOfRows)
		{
		$finalString .=  "),";
		}
		else
		{
		$finalString .=  ");";
		}


		//$finalString .=  "\n";

	}

}
}

return $finalString;
}

function importData($sql)
{
	if($this->con->query($sql))
	{
		return true;
	}
	else
	{
		return mysqli_error($this->con);
	}
}

function exportVoters($syncType=NULL)
{
$tablename = "voters";
$whatToSelect = "*";
$finalString = "";
if($syncType == "auto_sync")
{
$whatToSelect = "`voter_id`,`voter_pin`,`voter_name`,`level`,`portfolios`,`account_id`,`election_id`";
$where = " WHERE `account_id` = '$this->accountId' AND  `election_id` = '$this->electionId'";
}
else
{
	$where = " WHERE `account_id` = '$this->accountId' AND  `election_id` = '$this->electionId'";
}
$sql = "SELECT $whatToSelect FROM `$tablename` $where;";
$result = $this->con->query($sql);
if($result)
{
	$numberOfFields = mysqli_num_fields($result);
	$numberOfRows = mysqli_num_rows($result);
	if($numberOfRows > 0)
	{
	//$finalString .=  $numberOfFields;
	$i = 0;
	$fields = mysqli_fetch_fields($result);
	$queryString =  "INSERT INTO `$tablename` (";
	foreach ($fields as $field)
	{
		
		$i++;
		$queryString .=  "`".$field->name."`";
		if($i < $numberOfFields)
		{
			$queryString .=  ",";
		}
		
	}

	//$finalString .=  ") VALUES\n";
	$j = 0;
	
	while($rows = $result->fetch_row())
	{
		$j++;
		$finalString .= $queryString. ") VALUES (";
		//print_r($rows);
		for ($ji = 0;$ji<$numberOfFields;$ji++) {
			
			
			$finalString .=  "'".$rows[$ji]."'";
			if($ji < $numberOfFields-1)
			{
				$finalString .=  ",";
			}
		}

		// if($j < $numberOfRows)
		// {
		// $finalString .=  ")\n ".(($tablename == "voters")?"ON DUPLICATE KEY UPDATE voter_pin = '".$rows[1]."'":"").",";
		// }
		// else
		// {
		$finalString .=  ") ON DUPLICATE KEY UPDATE voter_pin = '".$rows[1]."';";
		//}


		$finalString .=  "\n";

	}

}
}

return $finalString;
}

}