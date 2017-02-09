<?php
require_once('browserDetection.php');
header('Content-type: text/css');
?>

body
{
	font: verdana, arial, helvetica, sans-serif;
<?php if(msie()) { ?>	font-size: smaller; <?php } ?>
}


h1		{ font-size: 200%; }
h2		{ font-size: 150%; }
h3		{ font-size: 110%; }

body		{ font-family: "Helvetica", "Arial", sans-serif; }

h1, h2		{ font-weight: bold; }

a
{
	font-weight: bold;
	text-decoration: none;
}

.date
{
	font-size: smaller;
	font-style: italic;
	text-align: center;
}
