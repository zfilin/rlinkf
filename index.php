<?php
	interface IDBLinks
	{
		function Connect();
		function Disconnect();
		function GetLink($linkname);
		function PutLink($linkname, $link, $overwrite=false);
		function DelLink($linkname);
		function GetAllLinks();
	}

	require_once('main.class.php');
	$application = new main();
?>