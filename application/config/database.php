<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

/*  if ($_SERVER["HTTP_HOST"] == "localhost") {
    $hostname = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'xr3';
} elseif ($_SERVER["HTTP_HOST"] == "qa.xr3t.cl") {
    $hostname = 'localhost';
    $username = 'ceningen_qa_xr3';
    $password = 'nLQC;Sz48=4$';
    $database = 'ceningen_qa_xr3'; 
} else {
	$hostname = 'localhost';
	$username = 'ceningen_xr3';
	$password = 'sW0}~%HKSiUn';
	$database = 'ceningen_xr3'; 
}  
 */
$hostname = 'localhost';
$username = 'ceningen_xr3';
$password = 'sW0}~%HKSiUn';
$database = 'ceningen_xr3'; 

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $hostname,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);