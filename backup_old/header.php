<?php
require_once('pages.php');
require_once('sidebar.php');

$pages = pages();
foreach($pages as $page)
	if($page['href'] == $_SERVER['PHP_SELF']) $title = $page['name'];

?>

<html>

<head>
	<title>MUN IEEE Student Branch</title>
	<link rel="stylesheet" type="text/css" href="/style/default.css"/>
</head>

<body>
	<div id="header">
		<a href="http://www.ieee.org/" target="_blank"
		   title="The Institute of Electrical and Electronic Engineers">
			<img src="/images/masterBrandSmall.gif" alt="IEEE"/>
		</a>
	</div>


	<table id="body">		<!-- gross -->
		<tr>
			<td><?php echo sidebar($_SERVER['PHP_SELF']) ?></td>
			<td id="content">
