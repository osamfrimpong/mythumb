<?php
require_once 'DbConnector.php';

class Elections extends DbConnector
{
	function getElections($accountId)
	{
		$sql = "SELECT * FROM `elections` WHERE `account_id` = '$accountId' ORDER BY `start_time`;";
		
		return $this->readquery($sql);
	}

	function addElection($accountId,$title,$startTime,$endTime,$compulsoryVote)
	{
		$sql = "INSERT INTO `elections` (`title`, `start_time`, `end_time`, `display_results`, `compulsory_vote`, `account_id`) VALUES ('$title', '$startTime', '$endTime', '0', '$compulsoryVote', '$accountId');";
		return $this->doquery($sql);

	}

	function updateElection($accountId,$electionsId,$title,$startTime,$endTime,$compulsoryVote,$displayResults)
	{
		$sql = "UPDATE `elections` SET `title` = '$title', `start_time` = '$startTime', `end_time` = '$endTime', `display_results` = '$displayResults', `compulsory_vote` = '$compulsoryVote' WHERE `elections`.`account_id` = '$accountId' AND `elections`.`id` = '$electionsId'; ";
		return $this->doquery($sql);
	}

	function deleteElection($electionId)
	{
		$sql = "DELETE FROM `elections` WHERE `id` = '$electionId';";
		return $this->doquery($sql);
	}

	function declareElection($electionId)
	{
		$sql = "UPDATE `elections` SET `display_results` = '1' WHERE `id` = '$electionId';";
		return $this->doquery($sql);
	}

	function getElection($electionId)
	{
		$sql = "SELECT * FROM `elections` WHERE `id` = '$electionId';";

		return $this->readquery($sql);
	}

	function checkElection($title,$accountId)
	{
		$sql = "SELECT * FROM `elections` WHERE `title` = '$title' AND `account_id` = '$accountId';";

		return $this->readquery($sql);
	}


	function castVote($voter_id,$portfolio,$aspirant,$electionId,$accountId)
	{
		$sql = "INSERT INTO `votes` (`voter_id`, `portfolio_id`, `voted_for`, `election_id`,`account_id`) VALUES ('$voter_id', '$portfolio', '$aspirant', '$electionId','$accountId');";
		return $this->doquery($sql);

	}

	function addToVoted($voterId,$electionId,$accountId)
	{
		$dateTime = date("Y-m-d H:i:s");
		$sql = "INSERT INTO `voted` (`voter_id`, `date_voted`, `election_id`,`account_id`) VALUES ('$voterId', '$dateTime', '$electionId', '$accountId');";
		return $this->doquery($sql);
	}

	function getTimeToStart($millisecondsleft)
	{
$days_remaining = floor($millisecondsleft / 86400);
$hours_remaining = floor(($millisecondsleft % 86400) / 3600);
$minutes_remaning = floor(($millisecondsleft % 3600) / 60);
$seconds_left = $millisecondsleft % 60;
return $days_remaining."d:".$hours_remaining."h:".$minutes_remaning."m:".$seconds_left."s";
	}

	function resetElectionData($electionId,$accountId)
	{
		//delete from multiple tables at a goal
		$sql = "DELETE votes,voted FROM votes INNER JOIN voted ON voted.election_id = votes.election_id WHERE votes.election_id = '$electionId' AND votes.account_id = '$accountId';";
		return $this->doquery($sql);
	}
}