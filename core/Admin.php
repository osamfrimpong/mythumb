<?php
require_once 'DbConnector.php';

class Admin extends DbConnector
{
	

	function doLogin($username,$password)
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' LIMIT 1;";
		return $this->readquery($sql);
	}

function doOfficerLogin($username,$password)
	{
		// return all portfolios
		
		$sql = "SELECT * FROM `election_officers` WHERE `username` = '$username' AND `password` = '$password' LIMIT 1;";
		return $this->readquery($sql);
	}

	function getElectionOfficers($accountId)
	{
		$sql = "SELECT * FROM `election_officers` WHERE `account_id` = '$accountId';";
		return $this->readquery($sql);
	}


	function getElectionOfficer($userId)
	{
		$sql = "SELECT * FROM `election_officers` WHERE `id` = '$userId' LIMIT 1;";
		return $this->readquery($sql);
	}

	function addElectionOfficer($accountId,$username,$password,$role)
	{
		$sql = "INSERT INTO `election_officers` (`username`,`password`,`role`,`account_id`) VALUES ('$username','$password','$role','$accountId');";
		return $this->doquery($sql);
	}

	function updateElectionOfficer($username,$password,$role,$userId)
	{
		$sql = "UPDATE `election_officers` 	SET `username` = '$username', `password` = '$password',`role` = '$role' WHERE `id` = '$userId';";
		return $this->doquery($sql);
	}

	function deleteElectionOfficer($userId)
	{
		$sql = "DELETE FROM `election_officers` WHERE `election_officers`.`id` = '$userId';";
		return $this->doquery($sql);
	}

	function createAccount($username,$password,$email)
	{
		$sql = "INSERT INTO `users` (`username`, `password`, `email`, `role`, `account_id`) VALUES ('$username', '$password', '$email', '0', '0');";
		return $this->doquery($sql);
	}

	function getLastId()
	{
		$sql = "SELECT `id` FROM `users` ORDER BY `id` DESC LIMIT 1";
		return $this->readquery($sql);
	}

	function setAccountId($userId)
	{
		$sql = "UPDATE `users` SET `account_id` = '$userId' WHERE `id` = '$userId';";
		return $this->doquery($sql);
	}

	function verifyAuthKey($authKey)
	{
		$sql = "SELECT * FROM `auth_keys` WHERE `auth_key` = '{$authKey}';";
		return $this->readquery($sql);
		//return $sql;
	}

	function issueAuthKey($authKey,$email)
	{
		$sql = "UPDATE `auth_keys` SET `used` = '1', `user_email` = '$email' WHERE `auth_keys`.`auth_key` = '{$authKey}';";
		return $this->doquery($sql);
	}

	function addAuthKey($authKey)
	{

		$sql = "INSERT INTO `auth_keys` (`auth_key`,  `date_generated`) VALUES ('$authKey', CURRENT_TIMESTAMP);";
		return $this->doquery($sql);
	}

	function getAuthKey()
	{
		$sql = "SELECT `auth_key` FROM `auth_keys` WHERE `used` != 1 ORDER BY `id` ASC LIMIT 1;";
		return $this->readquery($sql);
	}
	
}

?>