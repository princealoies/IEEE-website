<?
/*
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2002 Bharat Mediratta
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
?>
<?
// Hack prevention.
if (!empty($HTTP_GET_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_POST_VARS["GALLERY_BASEDIR"]) ||
		!empty($HTTP_COOKIE_VARS["GALLERY_BASEDIR"])) {
	print "Security violation\n";
	exit;
}
?>
<? require($GALLERY_BASEDIR . "init.php"); ?>
<?
// Hack check
if (!$gallery->user->canAddToAlbum($gallery->album)) {
	exit;
}
	
if (!$boxes) {
	$boxes = 5;
}

?>

<html>
<head>
  <title>Add Photos</title>
  <?= getStyleSheetLink() ?>

<script language="Javascript">
<!--
	function reloadPage() {
		document.count_form.submit();
		return false;
	}
// -->
</script>
</head>
<body>

<span class="popuphead">Add Photos</span>
<br>
<span class="popup">
Click the <b>Browse</b> button to locate a photo to upload.
<span class="admin">
<? if ($gallery->app->feature["zip"]) { ?>
<br>
&nbsp;&nbsp;Tip:  Upload a ZIP file full of photos and movies!
<? } ?>
<br>
&nbsp;&nbsp;(Supported file types: <?= join(", ", acceptableFormatList()) ?>)
</span>

<br><br>
<?= makeFormIntro("add_photos.php",
			array("name" => "count_form",
				"method" => "POST")); ?>
1. Select the number of files you want to upload:
<select name="boxes" onChange='reloadPage()'>
<? for ($i = 1; $i <= 10;  $i++) {
	echo "<option ";
        if ($i == $boxes) {
		echo "selected ";
	}
	echo "value=\"$i\">$i\n";

} ?>
</select>
<br>
</form>

<?= makeFormIntro("save_photos.php",
			array("name" => "upload_form",
				"enctype" => "multipart/form-data",
				"method" => "POST")); ?>
2. Use the Browse button to find the photos on your computer
<input type="hidden" name="max_file_size" value="10000000">
<? for ($i = 0; $i < $boxes;  $i++) { ?>
<br> <input name="userfile[]" type="file" size=40>
<? } ?>
<br>
<input type=checkbox name=setCaption checked value="1">Set photo captions with original filenames.
<br>
<center>
<input type="button" value="Upload Now" onClick='opener.showProgress(); document.upload_form.submit()'>
<input type=submit value="Cancel" onclick='parent.close()'>
</center>
</form>

<?= makeFormIntro("save_photos.php",
			array("name" => "uploadurl_form",
				"method" => "POST")); ?>
Or, upload any images found at this location.  The location
can either be a URL or a directory on the server.
<br>
<span class="admin">
&nbsp;&nbsp;Tip: FTP images to a directory on your server then provide that path here!
</span>
<br>

<input type="text" name="urls[]" size=40>
<br>
<input type=checkbox name=setCaption checked value="1">Set photo captions with original filenames.
<br>
<center>
<input type="button" value="Submit URL or directory" onClick='opener.showProgress(); document.uploadurl_form.submit()'>
<input type=submit value="Cancel" onclick='parent.close()'>
</center>
</form>
Download <b><a href="#" onClick="opener.location = 'http://gallery.sourceforge.net/gallery_remote.php?protocol_version=<?= $gallery->remote_protocol_version ?>'; parent.close();">Gallery Remote</a></b>. Upload photos using drag-and-drop from your desktop.
</span>

<head>
</body>
</html>
