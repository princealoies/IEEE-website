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
 
if ($gallery->session->albumName == "") {
        header("Location: albums.php");
        return;
}

if (!$gallery->user->canReadAlbum($gallery->album)) {
	header("Location: " . makeAlbumUrl());
	return;
}

if (!$gallery->album->isLoaded()) {
	header("Location: " . makeAlbumUrl());
	return;
}

// default settings ---
$defaultLoop = 0;
$defaultPause = 3;
$defaultFull = 0;
$defaultDir = 1;

if (!$slide_index) {
    $slide_index = 1;
}
if (!$slide_pause) {
    $slide_pause = $defaultPause;
}
if (!$slide_loop) {
    $slide_loop = $defaultLoop;
}
if (!$slide_full) {
    $slide_full = $defaultFull;
}
if (!$slide_dir) {
    $slide_dir = $defaultDir;
}

function makeSlideLowUrl($index, $loop, $pause, $full, $dir) {

    return makeGalleryUrl('slideshow_low.php',
	array('set_albumName' => $gallery->session->albumName,
	      'slide_index' => $index,
	      'slide_loop' => $loop,
	      'slide_pause' => $pause,
	      'slide_full' => $full,
	      'slide_dir' => $dir));
}

$borderColor = $gallery->album->fields["bordercolor"];
$borderwidth = $gallery->album->fields["border"];
if (!strcmp($borderwidth, "off")) {
        $borderwidth = 1;
}
$bgcolor = $gallery->album->fields['bgcolor'];
$title = $gallery->album->fields["title"];
?>
<? if (!$GALLERY_EMBEDDED_INSIDE) { ?>
<head>
  <title>Slide Show for album :: <?= $gallery->album->fields["title"] ?></title>
  <?= getStyleSheetLink() ?>
  <style type="text/css">
<?
// the link colors have to be done here to override the style sheet
if ($gallery->album->fields["linkcolor"]) {
?>
    A:link, A:visited, A:active
      { color: <?= $gallery->album->fields[linkcolor] ?>; }
    A:hover
      { color: #ff6600; }
<?
}
if ($gallery->album->fields["bgcolor"]) {
        echo "BODY { background-color:".$gallery->album->fields[bgcolor]."; }";
}
if ($gallery->album->fields["background"]) {
        echo "BODY { background-image:url(".$gallery->album->fields[background]."); } ";
}
if ($gallery->album->fields["textcolor"]) {
        echo "BODY, TD {color:".$gallery->album->fields[textcolor]."; }";
        echo ".head {color:".$gallery->album->fields[textcolor]."; }";
        echo ".headbox {background-color:".$gallery->album->fields[bgcolor]."; }";
}
?>
  </style>
</head>

<body>
<? } ?>


<script language="JavaScript">
var timer; 
var current_location = <?= $slide_index ?>;
var next_location = <?= $slide_index ?>; 
var pics_loaded = 0;
var onoff = 0;
var timeout_value;
var images = new Array;
var photo_urls = new Array;
var photo_captions = new Array;
var loop = <?= $slide_loop ?>;
var full = <?= $slide_full ?>;
var direction = <?= $slide_dir ?>;
<?php

$numPhotos = $gallery->album->numPhotos($gallery->user->canWriteToAlbum($gallery->album));
$numDisplayed = 0; 

// Find the correct starting point, accounting for hidden photos
$index = getNextPhoto(0);
$photo_count = 0;
while ($numDisplayed < $numPhotos) { 
    $photo = $gallery->album->getPhoto($index);
    $numDisplayed++;

    // Skip movies and nested albums
    if ($photo->isMovie() || $photo->isAlbumName) {
	$index = getNextPhoto($index);
	continue;
    }

    $photo_count++;

    $image = $photo->image;
    if ($photo->image->resizedName) {
        $thumbImage = $photo->image->resizedName;
    } else {
        $thumbImage = $photo->image->name;
    }
    $photoURL = $gallery->album->getPhotoPath($index, $slide_full);

    // Now lets get the captions
    $caption = $gallery->album->getCaption($index);

    /*
     * Remove unwanted Characters from the comments,
     * We don't use the array based form of str_replace
     * because it's not supported on older versions of PHP
     */
    $caption = str_replace(";", " ", $caption);
    $caption = str_replace("\"", " ", $caption);
    $caption = str_replace("\n", " ", $caption);
    $caption = str_replace("\r", " ", $caption);

    // strip_tags takes out the html tags
    $caption = strip_tags($caption);

    // Print out the entry for this image as Javascript
    print "photo_urls[$photo_count] = \"$photoURL\";\n";
    print "photo_captions[$photo_count] = \"$caption\";\n";

    // Go to the next photo
    $index = getNextPhoto($index);
    $photosLeft--;
}
?>
var photo_count = <?=$photo_count?>; 

function stop() {
    onoff = 0;
    status = "The slide show is stopped, Click [play] to resume.";
    clearTimeout(timer);
}

function play() {
    onoff = 1;
    status = "Slide show is running...";
    go_to_next_photo();
}

function toggleLoop() {
    if (loop) {
        loop = 0;
    } else {
        loop = 1;
    }
}

function preload_complete() {
}

function reset_timer() {
    clearTimeout(timer);
    if (onoff) {
	timeout_value = document.TopForm.time.options[document.TopForm.time.selectedIndex].value * 1000;
	timer = setTimeout('go_to_next_page()', timeout_value);
    }
}

function wait_for_current_photo() {

    /* Show the current photo */
    if (!show_current_photo()) {

	/*
	 * The current photo isn't loaded yet.  Set a short timer just to wait
	 * until the current photo is loaded.
	 */
	status = "Picture is loading...(" + current_location + " of " + photo_count +
		").  Please Wait..." ;
	clearTimeout(timer);
	timer = setTimeout('wait_for_current_photo()', 500);
	return 0;
    } else {
	preload_next_photo();
	reset_timer();
    }
}

function go_to_next_page() {

    var slideShowUrl = "<?= makeGalleryUrl('slideshow_low.php',
				array('set_albumName' => $gallery->session->albumName)); ?>";

    document.location = slideShowUrl + "&slide_index=" + next_location + "&slide_full=" + full
	+ "&slide_loop=" + loop + "&slide_pause=" + (timeout_value / 1000) 
	+ "&slide_dir=" + direction;
    return 0;
}

function go_to_next_photo() {
    /* Go to the next location */
    current_location = next_location;

    /* Show the current photo */
    if (!show_current_photo()) {
	wait_for_current_photo();
	return 0;
    }

    preload_next_photo();
    reset_timer();

}

function preload_next_photo() {
    
    /* Calculate the new next location */
    next_location = (parseInt(current_location) + parseInt(direction));
    if (next_location > photo_count) {
	next_location = 1;
	if (!loop) {
	    stop();
	}
    }
    
    /* Preload the next photo */
    preload_photo(next_location);
}

function show_current_photo() {

    /*
     * If the current photo is not completely loaded don't display it.
     */
    if (!images[current_location] || !images[current_location].complete) {
	preload_photo(current_location);
	return 0;
    }
    
    return 1;
}

function preload_photo(index) {

    /* Load the next picture */
    if (pics_loaded < photo_count) {

	/* not all the pics are loaded.  Is the next one loaded? */
	if (!images[index]) {
	    images[index] = new Image;
	    images[index].onLoad = preload_complete();
	    images[index].src = photo_urls[index];
	    pics_loaded++;
	}
    } 
}

</Script>


<? includeHtmlWrap("slideshow.header"); ?>
<?
$imageDir = $gallery->app->photoAlbumURL."/images"; 
$pixelImage = "<img src=\"$imageDir/pixel_trans.gif\" width=\"1\" height=\"1\">";
?>

<form name="TopForm">

<?

#-- breadcrumb text ---
$breadCount = 0;
$breadtext[$breadCount] = "Album: <a href=\"" . makeAlbumUrl($gallery->session->albumName) .
      "\">" . $gallery->album->fields['title'] . "</a>";
$breadCount++;
$pAlbum = $gallery->album;
do {
  if (!strcmp($pAlbum->fields["returnto"], "no")) {
    break;
  }
  $pAlbumName = $pAlbum->fields['parentAlbumName'];
  if ($pAlbumName) {
    $pAlbum = new Album();
    $pAlbum->load($pAlbumName);
    $breadtext[$breadCount] = "Album: <a href=\"" . makeAlbumUrl($pAlbumName) .
      "\">" . $pAlbum->fields['title'] . "</a>";
  } else {
    //-- we're at the top! ---
    $breadtext[$breadCount] = "Gallery: <a href=\"" . makeGalleryUrl("albums.php") .
      "\">" . $gallery->app->galleryTitle . "</a>";
  }
  $breadCount++;
} while ($pAlbumName);

//-- we built the array backwards, so reverse it now ---
for ($i = count($breadtext) - 1; $i >= 0; $i--) {
    $breadcrumb["text"][] = $breadtext[$i];
}
$breadcrumb["bordercolor"] = $borderColor;
$breadcrumb["top"] = true;

include($GALLERY_BASEDIR . "layout/breadcrumb.inc");


$adminbox["commands"] = "";
$adminbox["text"] = "Slide Show";
$adminbox["bordercolor"] = $borderColor;
$adminbox["top"] = true;
include ($GALLERY_BASEDIR . "layout/adminbox.inc");

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3" bgcolor="<?= $borderColor ?>"><?= $pixelImage ?></td>
  </tr>
  <tr>
    <td height="25" width="1" bgcolor="<?= $borderColor ?>"><?= $pixelImage ?></td>
    <td width="5000" align="left" valign="middle">
    <span class=admin>
    &nbsp;<a href="#" onClick='stop(); return false;'>[stop]</a>
    <a href="#" onClick='play(); return false;'>[play]</a>
<?
if ($slide_full) {
    echo "<a href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, 0, $slide_dir) 
	. "\">[normal size]</a>";
} else {
    echo "<a href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, 1, $slide_dir)
        . "\">[full size]</a>";
}
?>
<?
if ($slide_dir == 1) {
    echo "&nbsp;<a href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, $slide_full, -1) 
	. "\">[reverse direction]</a>";
} else {
    echo "&nbsp;<a href=\"" . makeSlideLowUrl($slide_index, $slide_loop, $slide_pause, $slide_full, 1)
        . "\">[forward direction]</a>";
}
?>
    &nbsp;&nbsp;||
    &nbsp;Delay:
<?=
drawSelect("time", array(1 => "1 second",
                         2 => "2 second",
                         3 => "3 second",
                         4 => "4 second",
                         5 => "5 second",
                         10 => "10 second",
                         15 => "15 second",
                         30 => "30 second",
                         45 => "45 second",
                         60 => "60 second"),
           $slide_pause, // default value
           1, // select size
           array('onchange' => 'reset_timer()', 'style' => 'font-size=10px;' ));
?>
    &nbsp;Loop:<input type="checkbox" name="loopCheck" <?= ($slide_loop) ? "checked" : "" ?> onclick='toggleLoop();'>
    </span>
    </td>
    <td width="1" bgcolor="<?= $borderColor ?>"><?= $pixelImage ?></td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="<?= $borderColor ?>"><?= $pixelImage ?></td>
  </tr>
</table>

<br>
<div align="center">

<?
if ($photo_count > 0) {
?>

<table width=1% border=0 cellspacing=0 cellpadding=0>
  <tr bgcolor="<?=$borderColor?>">
    <td colspan=3 height=<?=$borderwidth?>><?=$pixelImage?></td>
  </tr>
  <tr>
    <td bgcolor="<?=$borderColor?>" width=<?=$borderwidth?>><?=$pixelImage?></td>
    <script language="JavaScript">
    document.write("<td><img border=0 src="+photo_urls[<?= $slide_index ?>]+" name=slide></td>");
    </script>
    <td bgcolor="<?=$borderColor?>" width=<?=$borderwidth?>><?=$pixelImage?></td>
  </tr>
  <tr bgcolor="<?=$borderColor?>">
    <td colspan=3 height=<?=$borderwidth?>><?=$pixelImage?></td>
  </tr>
</table>
<br>

<script language="Javascript">
/* show the caption either in a nice div or an ugly form textarea */
document.write("<div class='desc'>" + "[" + current_location + " of " + photo_count + "] " + photo_captions[<?= $slide_index ?>] + "</div>");

/* Load the first picture */
preload_photo(<?= $slide_index ?>);

/* Start the show. */
play();

</script>

<?
} else {
?>

<br><b>This album has no photos to show in a slide show.</b>
<br><br>
<span class="admin">
<a href="<?=makeGalleryUrl("view_album.php",
               array("set_albumName" => $gallery->session->albumName))?>">[back to album]</a>
</span>

<?
}
?> 

</div>
</form>


<? includeHtmlWrap("slideshow.footer"); ?>

<? if (!$GALLERY_EMBEDDED_INSIDE) { ?>
</body>
</html>
<? } ?>
