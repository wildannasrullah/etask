<?php
try
{
	$bdd = new PDO('mysql:host=192.168.88.4:3306;dbname=db_emtc;charset=utf8', 'root', '19K23O15P');
}
catch(Exception $e)
{
        die('Error : '.$e->getMessage());
}
