<?php
require_once 'DbConnector.php';

class Config extends DbConnector
{
	function getConfigs($accountId)
	{
		$sql = "SELECT * FROM `config` WHERE `account_id` = '$accountId' LIMIT 1;";

		return $this->readquery($sql);
	}

	function addConfigs($accountId,$data)
	{
		$sql = "INSERT INTO `config` (`title`, `start_time`, `end_time`, `id`, `display_results`, `extend_voting`, `extend_duration`, `compulsory_vote`, `account_id`) VALUES ('Title', '2018-08-11 00:00:00', '2018-08-18 00:00:00', '1', '0', '0', '0', '1', '$accountId');";
		return $this->doquery($sql);

	}

	function updateConfigs($accountId,$data,$configId)
	{
		$sql = "UPDATE `config` SET `title` = '', `start_time` = '', `end_time` = '', `display_results` = '', `extend_voting` = '', `extend_duration` = '', `compulsory_vote` = '' WHERE `config`.`account_id` = '$accountId' AND `config`.`id` = '$configId'; ";
		return $this->doquery($sql);
	}
}