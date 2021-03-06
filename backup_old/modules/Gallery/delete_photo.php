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
if (!$gallery->user->canDeleteFromAlbum($gallery->album)) {
	exit;
}

if (isset($id)) {
        $index = $gallery->album->getPhotoIndex($id);
}

if ($confirm && isset($id)) {
	if ($albumDelete) {
		/* Track down the corresponding photo index and remove it */
		$index = 0;
		for ($i = 1; $i <= sizeof($gallery->album->photos); $i++) {
		    $photo = $gallery->album->getPhoto($i);
		    if (isset($photo->isAlbumName) && !strcmp($photo->isAlbumName, $id)) {
			/* Found it */
			$index = $i;
			break;
		    }
		}
	}

	$gallery->album->deletePhoto($index);
	$gallery->album->save();
	dismissAndReload();
	return;
}
?>

<html>
<head>
  <title>Delete Photo</title>
  <?= getStyleSheetLink() ?>
</head>
<body>


<?
if ($gallery->album && isset($id)) {
	if (isset($albumDelete)) {
?>

<center>
<span class="popuphead">Delete Album</span>
<br>
<br>
Do you really want to delete this Album?
<br>
<br>
<?
$myAlbum = new Album();
$myAlbum->load($id);
?>
<?= $myAlbum->getHighlightTag() ?>
<br>
<br>
<b>
<?= $myAlbum->fields[title] ?>
</b>
<br>
<br>
<?= $myAlbum->fields[description] ?>
<br>
<?= makeFormIntro("delete_photo.php"); ?>
<input type=hidden name=id value=<?= $id?>>
<input type=hidden name=albumDelete value=<?= $albumDelete?>>
<input type=submit name=confirm value="Delete">
<input type=submit value="Cancel" onclick='parent.close()'>
</form>
<br>

<?
	} else {
?>

<center>
Do you really want to delete this photo?
<br>
<br>
<?= $gallery->album->getThumbnailTag($index) ?>
<br>
<?= $gallery->album->getCaption($index) ?>
<br>
<?= makeFormIntro("delete_photo.php"); ?>
<input type=hidden name=id value=<?= $id?>>
<input type=submit name=confirm value="Delete">
<input type=submit value="Cancel" onclick='parent.close()'>
</form>
<br>

<?
	}
} else {
	error("no album / index specified");
}
?>

</body>
</html>
