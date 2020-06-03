<?php

$host = 'localhost';
$user = 'root';
$pass = '111111';
$dbname = 'homework';

$mysqli = new mysqli($host, $user, $pass, $dbname);
$mysqli->query("SET NAMES utf8");

if ($mysqli->connect_errno)
{
    echo ' Could not connect: ' . mysql_error();
}

?>