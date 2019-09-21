<?php

class DbConnector
{
	private $con;
	function __construct()
	{
		$this->con = new mysqli("localhost","root","kofiessuman","evote") or die(mysql_errno());
	}

	public function getLink()
	{
		return $this->con;
	}

	public function readquery($sql)
	{
		
		$query = $this->con->query($sql);
		$num=mysqli_num_rows($query);
		if($num > 0)
		{
			if($num > 1)
			{
				while($row = $query->fetch_object())
				{
					$results[] = $row;
				}

				return $results;
			}
			else
			{
				return $query->fetch_object();
			}

			
		}
		else
		{
			return false;
		}
	}


	public function doquery($sql)
	{

		$query = $this->con->query($sql);
		
		if($query)
		{
			return true;	
		}
		else
		{
			return mysqli_error($this->con);
		}
	}

	
}


?>