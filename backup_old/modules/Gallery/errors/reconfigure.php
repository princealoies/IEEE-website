<?
// Hack prevention.
if (!empty($HTTP_GET_VARS["GALLERY_BASEDIR"]) ||
                    !empty($HTTP_POST_VARS["GALLERY_BASEDIR"]) ||
                    !empty($HTTP_COOKIE_VARS["GALLERY_BASEDIR"])) {
            print "Security violation\n";
	            exit;
		    }
?>
<? require($GALLERY_BASEDIR . "errors/configure_instructions.php") ?>
<html>
<head>
  <title>Gallery needs Reconfiguration</title>
  <?= getStyleSheetLink() ?>
</head>
<body>	
<center>
<span class="title"> Gallery needs Reconfiguration </span>
<p>
<center>
<table width=80%><tr><td>
Your Gallery configuration was created using the config wizard
from an older version of Gallery.  It is out of date.  Please
re-run the configuration wizard!  In a shell do this:
<p><center>
<? configure("configure"); ?>
<p>
Then launch the <a href="<?=$GALLERY_BASEDIR?>setup/index.php">configuration wizard</a>.

<? include($GALLERY_BASEDIR . "errors/configure_help.php"); ?>

</table>
</body>
</html>