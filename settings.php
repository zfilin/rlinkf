<?php

$settings=array(

	'db_type' => 'file',					//type of database ("file"/"mysql")

	//for "file" db_type
	'db_options' => array (
		'file' =>	'./data/linksbase'		//path and filename for links store
	),

	//for "mysql" db_type (not supported in current version)
	/*
	'db_options' => array (
		'host' =>			'localhost',
		'database' =>		'rlinkf',
		'user' =>			'username',
		'pass' =>			'password',
		'table_prefix' =>	'rlinkf_'
	),
	*/

	'locale' => 'ru', 						//locale

	'empty_redirect' => false, 				//URL for redirecting for emtpy or incorrect queries,
											//or false to show link list

	'admin_user' => 'root',					//user and password for administrative web access
	'admin_password' => 'root'
);
?>