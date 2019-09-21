<?php
require_once 'DbConnector.php';

class Aspirants extends DbConnector
{
	function addAspirant($aspirantname,$portfolioId,$gender,$pictureUrl,$accountId,$electionId)
	{
		//add portfolio
		
		$sql = "INSERT INTO `aspirants` (`name`, `gender`, `portfolio_id`, `picture`, `account_id`,`election_id`) VALUES ('$aspirantname', '$gender', '$portfolioId', '$pictureUrl', '$accountId','$electionId');";
		return $this->doquery($sql);
	}

	function getAspirants($electionId,$accountId)
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `aspirants` WHERE `account_id` = '$accountId' AND `election_id` = '$electionId' ORDER BY `portfolio_id`;";
		return $this->readquery($sql);
	}

	function getAspirantPictures($electionId,$accountId)
	{
		// return all portfolios
		
		$sql = "SELECT `picture` FROM `aspirants` WHERE `account_id` = '$accountId' AND `election_id` = '$electionId'";
		return $this->readquery($sql);
	}

	function getAspirantsByPortfolio($portfolioId)
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `aspirants` WHERE  `portfolio_id`='$portfolioId' ORDER BY `name`;";
		return $this->readquery($sql);
	}

function getAspiredPortfolios($electionId)
{
	// return all portfolios
		
		$sql = "SELECT portfolio_id,count(portfolio_id) as aspirants FROM `aspirants` WHERE `election_id` = '$electionId' GROUP BY `portfolio_id` ORDER BY `portfolio_id`;";
		return $this->readquery($sql);
}

function getAspiredPortfoliosId($electionId)
{
	// return all portfolios
		
		$sql = "SELECT portfolio_id FROM `aspirants` WHERE `election_id` = '$electionId' GROUP BY `portfolio_id` ORDER BY `portfolio_id`;";
		return $this->readquery($sql);
}

function updateAspirant($aspirantname,$portfolioId,$gender,$pictureUrl,$aspirantId)
{
	
	$sql = "UPDATE `aspirants` SET `name` = '$aspirantname', `gender` = '$gender', `portfolio_id` = '$portfolioId', `picture` = '$pictureUrl' WHERE `aspirants`.`id` = '$aspirantId';";
	return $this->doquery($sql);

}


	function deleteAspirant($aspirantId)
	{
		
		$sql = "DELETE FROM `aspirants` WHERE `id` = '$aspirantId' LIMIT 1;";
		return $this->doquery($sql);
	}

	function getAspirant($aspirantId)
	{
		
		$sql = "SELECT * FROM `aspirants` WHERE `id` = '$aspirantId' LIMIT 1;";
		return $this->readquery($sql);
    }
    function checkAspirant($aspirantname,$portfolioId,$accountId,$electionId)
	{
		
		$sql = "SELECT * FROM `aspirants` WHERE `name` = '$aspirantname' AND `portfolio_id` = '$portfolioId' AND `account_id` = '$accountId' AND `election_id` = '$electionId' LIMIT 1;";
		return $this->readquery($sql);
    }
}
?>