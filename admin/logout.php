<?php
session_start();
$role = $_SESSION['user_data']->role;
session_unset();
session_destroy();
if($role == 0){
header("Location: ../admin");}
else
{
	header("Location: ../officer");
}

?>