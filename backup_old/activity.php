<?php
require_once('php-bin/mysql.php');

include_once('header.php');

$activity = getActivity((int) $_GET['id']);
echo $activity->toHTML();

include_once('footer.php');

?>
