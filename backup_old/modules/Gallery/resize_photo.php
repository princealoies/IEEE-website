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
if (!$gallery->user->canWriteToAlbum($gallery->album)) {
	exit;
}
?>

<html>
<head>
  <title>Resize Photo</title>
  <?= getStyleSheetLink() ?>
</head>
<body>

<?
$all = !strcmp($index, "all");
if ($gallery->session->albumName && isset($index)) {
	if ($resize) {
		if (!strcmp($index, "all")) {
			$np = $gallery->album->numPhotos();
			echo("<br> Resizing $np photos...");
			my_flush();
			for ($i = 1; $i <= $np; $i++) {
				echo("<br> Processing image $i...");
				my_flush();
				set_time_limit($gallery->app->timeLimit);
				$gallery->album->resizePhoto($i, $resize);
			}
		} else {
			echo("<br> Resizing 1 photo...");
			my_flush();
			set_time_limit($gallery->app->timeLimit);
			$gallery->album->resizePhoto($index, $resize);
		}
		$gallery->album->save();
		dismissAndReload();
		return;
	} else {
?>

<center>
<font size=+1>Resizing photos</a>
<br>
This will resize your photos so that the longest side of the 
photo is equal to the target size below.  

<p>

What is the target size for <?= $all ? "all the photos in this album" : "this photo" ?>?
<br>
<?= makeFormIntro("resize_photo.php"); ?>
<input type=hidden name=index value=<?=$index?>>
<input type=submit name=resize value="Original Size">
<input type=submit name=resize value="1024">
<input type=submit name=resize value="800">
<input type=submit name=resize value="700">
<input type=submit name=resize value="640">
<input type=submit name=resize value="600">
<input type=submit name=resize value="500">
<input type=submit name=resize value="400">
<input type=submit value="Cancel" onclick='parent.close()'>
</form>

<br><br>
<?
if (!$all) {
	echo $gallery->album->getThumbnailTag($index);
} 
?>

<?
	}
} else {
	error("no album / index specified");
}
?>

</body>
</html>





