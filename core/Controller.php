<?php
require_once 'DbConnector.php';

class Controller extends DbConnector
{
	function addPortfolio($accountId,$portfolioname,$electionId)
	{
		//add portfolio
		
		$sql = "INSERT INTO `portfolios` (`name`, `election_id`,`account_id`) VALUES ('$portfolioname', '$electionId', '$accountId');";
		return $this->doquery($sql);
	}


	function getMyPortfolios($account_id,$election_id)
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `portfolios` WHERE `account_id` = '$account_id' AND `election_id` ='$election_id';";
		return $this->readquery($sql);
	}


function updatePortfolio($name,$portfolioId)
{
	
	$sql = "UPDATE `portfolios` SET `name` = '$name' WHERE `id` = '$portfolioId';";
return $this->doquery($sql);

}


	function deletePortfolio($portfolioId)
	{
		
		$sql = "DELETE FROM `portfolios` WHERE `id` = '$portfolioId' LIMIT 1;";
		return $this->doquery($sql);
	}

	function getPortfolio($portfolioId)
	{
		
		$sql = "SELECT * FROM `portfolios` WHERE `id` = '$portfolioId' LIMIT 1;";
		return $this->readquery($sql);
	}

function checkPortfolio($title,$accountId,$electionId)
	{
		$sql = "SELECT * FROM `portfolios` WHERE `name` = '$title' AND `account_id` = '$accountId' AND `election_id` = '$electionId';";

		return $this->readquery($sql);
	}

	function addVoter($account_id,$election_id,$voter_id,$voter_name,$level,$portfolios)
	{
		$sql = "INSERT INTO `voters` (`voter_id`, `voter_name`, `level`,`account_id`,`election_id`,`portfolios`) VALUES ('$voter_id', '$voter_name', '$level', '$account_id', '$election_id','$portfolios');";
		return $this->doquery($sql);
	}

	function deleteVoter($VoterId)
	{
		
		$sql = "DELETE FROM `voters` WHERE `id` = '$VoterId' LIMIT 1;";
		return $this->doquery($sql);
	}
	function addVoterPortfolio($user_id,$account_id,$election_id,$portfolio_id)
	{
			$sql = "";
			return $this->doquery($sql);

	}

function setCode($code,$voterId)
{
	
	$sql = "UPDATE `voters` SET `voter_pin` = '$code' WHERE `voter_id` = '$voterId';";
	return $this->doquery($sql);
}

function updateVoter($voter_id,$voter_name,$level,$portfolios,$recordId)
{
	$sql = "UPDATE `voters` SET `voter_id` = '$voter_id', `voter_name` = '$voter_name', `level` = '$level', `portfolios` = '$portfolios' WHERE `id` = '$recordId' ;";
	return $this->doquery($sql);
}


	function getVoters($accountId,$electionId)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `account_id` = '$accountId' AND `election_id` = '$electionId' ORDER BY level,voter_id";
		return $this->readquery($sql);
	}
function getVoter($VoterId)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `id` = '$VoterId';";
		return $this->readquery($sql);
	}

function checkVoter($VoterId,$voterName,$electionId,$accountId)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `voter_id` = '$VoterId' AND `voter_name` = '$voterName' AND `election_id` = '$electionId' AND `account_id` = '$accountId';";
		return $this->readquery($sql);
	}

	function getAllVoters($accountId)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `account_id` = '$accountId'  ORDER BY level,voter_id";
		return $this->readquery($sql);
	}

	function generateCode($voter_id)
	{
		//check if user is in database
		if($this->checkUser($voter_id))
		{
			//user is in data base
			//generate code
			return $this->codeGenerator(8);

		}
	else
	{
		//user is not in database
		return false;
	}

	}

function codeGenerator($length)
{
return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length); 
}

	function checkUser($voter_id)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `voter_id` = '$voter_id' LIMIT 1;";
		return $this->readquery($sql);

	}

function getUser($voter_id)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `voter_id` = '$voter_id' LIMIT 1;";

		return $this->readquery($sql);

	}

	//login to vote
	function loginToVote($voter_id,$pinCode)
	{
		
		$sql = "SELECT * FROM `voters` WHERE `voter_id` = '$voter_id' AND `voter_pin` = '$pinCode' LIMIT 1;";
		return $this->readquery($sql);

	}

	function alreadyVoted($voterId,$electionId,$accountId)
	{
		
		$sql = "SELECT * FROM `voted` WHERE `voter_id` = '$voterId' AND `election_id` = '$electionId' AND `account_id` = '$accountId' LIMIT 1;";
		return $this->readquery($sql);
	}

	function isVotingOngoing($voteindingin)
	{
$now = time();
//$votingStart = strtotime($votingStartIn);
$future_date = strtotime($voteindingin);
//$intervaltostart = $votingStart - $now();
$intervaltoend = ($future_date - $now);


if($intervaltoend > 0)
{
$days_remaining = floor($intervaltoend / 86400);
$hours_remaining = floor(($intervaltoend % 86400) / 3600);
$minutes_remaning = floor(($intervaltoend % 3600) / 60);
$seconds_left = $intervaltoend % 60;
return $hours_remaining."h:".$minutes_remaning."m:".$seconds_left."s";
}
else
{
return false;
}
	}


	function hasVotingStarted($votingStartIn)
	{
$now = time();
$votingStart = strtotime($votingStartIn);
//$future_date = strtotime($voteindingin);
$intervaltostart = $votingStart - $now;
//$intervaltoend = ($future_date - $now);


if($intervaltostart > 0)
{
	$days_remaining = floor($intervaltostart / 86400);
$hours_remaining = floor(($intervaltostart % 86400) / 3600);
$minutes_remaning = floor(($intervaltostart % 3600) / 60);
$seconds_left = $intervaltostart % 60;
return $hours_remaining."h:".$minutes_remaning."m:".$seconds_left."s";
}
else
{
return false;
}
}

}


?>