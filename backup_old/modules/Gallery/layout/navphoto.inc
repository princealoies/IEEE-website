<?
// Calculate the beginning and ending of the navigator range
$begin = 0;
$navpage = sizeof($navigator["allIds"]);
while ($navpage > 0) {
	if (!strcmp($navigator["allIds"][$navpage], $navigator["id"])) {
		break;
	}
	$navpage--;
}
$navcount = sizeof($navigator["allIds"]);

// If the border color is not passed in, we do a white one
if ($navigator["bordercolor"]) {
	$borderIn = $navigator["bordercolor"];
} else {
	$borderIn = "#FFFFFF";
}


$imageDir = $gallery->app->photoAlbumURL."/images";
$pixelImage = "<img src=\"$imageDir/pixel_trans.gif\" width=\"1\" height=\"1\">";
?>

<table width="<?=$navigator["fullWidth"] . $navigator["widthUnits"]?>" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="11" bgcolor="<?=$borderIn?>"><?=$pixelImage?></td>
  </tr>
  <tr> 
    <td bgcolor="<?=$borderIn?>" width="1" height="1"><?=$pixelImage?></td>
<?      
#-- 'first' button cell ---
if ($navpage == 0) {
?>
    <td align="center" width="27" height="18">&nbsp;</td> 
    <td width="1" height="1"><?=$pixelImage?></td>
<?
} else {
	$firstPage = $navigator["allIds"][0];
	$firstAlbumName = "";
	if ($gallery->album->isAlbumName(1)) { // first image is an album
		$firstAlbumName = $gallery->album->isAlbumName(1);
	}
?>
    <td align="center" width="27" height="18"> 
<?
	if ($firstAlbumName) {
?>
      <a href="<?=makeAlbumUrl($firstAlbumName)?>"><img src="<?=$imageDir?>/nav_first.gif" border="0" width="27" height="11" alt="First Photo"></a>
<?	} else {
?>
      <a href="<?=makeAlbumUrl($gallery->session->albumName, $firstPage)?>"><img src="<?=$imageDir?>/nav_first.gif" border="0" width="27" height="11" alt="First Photo"></a>
<?
	}
?>
      </td>
    <td bgcolor="<?=$borderIn?>" width="1" height="1"><?=$pixelImage?></td>
<?
} 
?>
<?
#-- 'previous' button cell ---
$prevPage = $navpage - 1;
if ($prevPage >= 0) {
	$prevPage = $navigator["allIds"][$prevPage];
	$prevAlbumName = "";
	if ($gallery->album->isAlbumName($navpage)) { // previous page is album
		$prevAlbumName = $gallery->album->isAlbumName($navpage);
	}
?>
    <td align="center" width="72" height="18">
<?
        if ($prevAlbumName) {
?>
      <a href="<?=makeAlbumUrl($prevAlbumName)?>"><img src="<?=$imageDir?>/nav_prev.gif" border="0" width="72" height="11" alt="Previous Photo">
</a>
<?
        } else {
?>
      <a href="<?=makeAlbumUrl($gallery->session->albumName, $prevPage)?>"><img src="<?=$imageDir?>/nav_prev.gif" border="0" width="72" height="11" alt="Previous Photo"></a>
<?
}
?>
      </td>
    <td bgcolor="<?=$borderIn?>" width="1" height="1"><?=$pixelImage?></td>
<?
} else {
?>
    <td align="center" width="72" height="18">&nbsp;</td>
    <td width="1" height="1"><?=$pixelImage?></td>
<?
}
?>
    <td align="center" width="3000" height="18">   
<?
#-- 'page numbers' cell ---                  
echo "<span class=\"nav\">";
echo $navpage+1 . " (of $navcount)";
echo "</span>";
?>
    </td>
<?
#-- 'next' button cell ---
if ($navpage+1 != $navcount) {
	$nextPage = $navigator["allIds"][$navpage+1];
	$nextAlbumName = "";
	if ($gallery->album->isAlbumName($navpage+2)) { //our next page is an album
		$nextAlbumName = $gallery->album->isAlbumName($navpage+2);
	} 
?>
    <td bgcolor="<?=$borderIn?>" width="1" height="1"><?=$pixelImage?></td>
    <td align="center" width="72" height="18">
<? 
	if ($nextAlbumName) {
?>
      <a href="<?=makeAlbumUrl($nextAlbumName)?>"><img src="<?=$imageDir?>/nav_next.gif" border="0" width="72" height="11" alt="Next Photo"></a>
<?
	} else {
?>
      <a href="<?=makeAlbumUrl($gallery->session->albumName, $nextPage)?>"><img src="<?=$imageDir?>/nav_next.gif" border="0" width="72" height="11" alt="Next Photo">
</a>
<?
}
?>
      </td>
<?
} else {   
?>
    <td align="center" width="72" height="18">&nbsp;</td>
    <td width="1" height="1"><?=$pixelImage?></td>
<?
} 
?>
<?
#-- 'last' button ---
if ($navpage+1 == $navcount) {
?>
    <td width="1" height="1"><?=$pixelImage?></td>
    <td align="center" width="27" height="18">&nbsp;</td>
<?
} else {        
	$lastPage = $navigator["allIds"][$navcount-1];
	$lastAlbumName = "";
	if ($gallery->album->isAlbumName($navcount)) { // out last page is an album
		$lastAlbumName = $gallery->album->isAlbumName($navcount);
	}
?>
    <td bgcolor="<?=$borderIn?>" width="1" height="1"><?=$pixelImage?></td>
    <td align="center" width="27" height="18">
<?
	if ($lastAlbumName) {
?>
      <a href="<?=makeAlbumUrl($lastAlbumName)?>"><img src="<?=$imageDir?>/nav_last.gif" border="0" width="27" height="11" alt="Last Photo"></a>
<? 	} else {
?>
      <a href="<?=makeAlbumUrl($gallery->session->albumName, $lastPage)?>"><img src="<?=$imageDir?>/nav_last.gif" border="0" width="27" height="11" alt="Last Photo"></a>
<?
}
?>
    </td>
<?
} 
?>
    <td bgcolor="<?=$borderIn?>" width="1" height="1"><?=$pixelImage?></td>
  </tr>
  <tr> 
    <td colspan="11" bgcolor="<?=$borderIn?>"><?=$pixelImage?></td>
  </tr>
</table>
    
