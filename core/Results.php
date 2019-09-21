<?php
require_once 'DbConnector.php';

class Results extends DbConnector
{
	

	function getAspirants()
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `aspirants` ORDER BY `portfolio_id`;";
		return $this->readquery($sql);
	}

	function getAspirantsByPortfolio($portfolioId)
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `aspirants` WHERE  `portfolio_id`='$portfolioId';";
		return $this->readquery($sql);
	}

function getAspiredPortfolios()
{
	// return all portfolios
		
		$sql = "SELECT portfolio_id,count(portfolio_id) as aspirants FROM `aspirants` GROUP BY `portfolio_id` ORDER BY `portfolio_id`;";
		return $this->readquery($sql);
}

function aspirantVotes($aspirantId,$portfolioId)
{
	
		$sql = "SELECT count(`voted_for`) as `aspirant_votes` FROM `votes` WHERE `portfolio_id` = '$portfolioId' AND `voted_for` = '$aspirantId';";
		return $this->readquery($sql);
}

function votesByPortfolio($portfolioId)
{

		$sql = "SELECT count(`portfolio_id`) as `portfolio_votes` FROM `votes` WHERE `portfolio_id` = '$portfolioId';";
		return $this->readquery($sql);	
}

function getVotesCast($accountId,$electionId)
{

		$sql = "SELECT count(*) as `votes_cast` FROM `voted` WHERE `election_id` = '$electionId' AND `account_id` = '$accountId';";
		return $this->readquery($sql);	
}

	function getAspirant($aspirantId)
	{
		
		$sql = "SELECT * FROM `aspirants` WHERE `id` = '$aspirantId' LIMIT 1;";
		return $this->readquery($sql);
	}
}
?>