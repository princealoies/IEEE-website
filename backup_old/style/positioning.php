<?php
require_once('browserDetection.php');

header("Content-Type: text/css");

$masterBrandHeight = 64;

$cellPadding = ".25em";

$sidebarOffset = ".25em";
$sidebarWidth = "8em";
$bodyOffset = msie() ? "12em" : "10em";

?>

#header	{ position: absolute; top: 10px; right: 1em; }
#body	{ position: absolute; top: <?php echo ($masterBrandHeight + 20) ?>px; }
#content{ width: 100%; }

h1	{ margin-top: 0; }
p	{ margin: .5em; }

h1.minutes, h2.minutes { text-align: center; }
.date	{ text-align: center; }

td
{
	padding: <?php echo $cellPadding; ?>;
	vertical-align: top;
}

li.sidebar
{
	list-style-type: none;
	position: relative; left: -2em;
}
